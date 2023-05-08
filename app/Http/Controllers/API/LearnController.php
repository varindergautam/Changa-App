<?php

namespace App\Http\Controllers\API;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Learn;
use App\Models\LearnTag;
use App\Models\LearnTagMulti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LearnController extends BaseController 
{
    public function learnTag() {
        $mediate_tags = LearnTag::get();
        if($mediate_tags->count() > 0) {
            return $this->sendResponse( $mediate_tags, 'Success' );
        } else {
            return $this->sendResponse( [], 'No Data found');
        }
    }

    public function learn(Request $request) {
        $users = Learn::with('learnTagMulti', 'user', 'favourite')->get();
        
        if($request->id) {
            $users = Learn::where('id', $request->id)->with('learnTagMulti', 'user', 'favourite')->get();
        }

        if($request->tag_id) {
            $multi = LearnTagMulti::where('learn_tag_id', $request->tag_id)->get()->pluck('learn_id')->toArray();
            $users = Learn::with('learnTagMulti','user', 'favourite')->whereIn('id', $multi)->get();
        }

        if($users->count() > 0) {
            foreach($users as $key => $learn) {
                
                // $users[$key]['description'] = stripslashes(html_entity_decode(strip_tags($learn->description)));
                $description = strip_tags(html_entity_decode($learn->description));
                $users[$key]['description'] = strip_tags(nl2br($description));
                $users[$key]['file'] = asset('/storage/file/'. $learn->file);
                $users[$key]['url'] = url('/api/learn/learn?id=' . $learn->id);
                $users[$key]['created_date'] = ChangaAppHelper::dateFormat($learn->created_at);
                $users[$key]['user']['profile_pic'] = !empty($learn->user->profile_pic) ? asset('/storage/profile_pic/'. $learn->user->profile_pic) : null;
                $users[$key]['user']['background_image'] = !empty($learn->user->background_image) ? asset('/storage/file/'. $learn->user->background_image) : null;

                $arr = [];
                foreach($learn->learnTagMulti as $tag) {
                    $arr[] = LearnTag::where('id', $tag->learn_tag_id)->get()->toArray();
                }
                $users[$key]['learn_tag'] = $arr;
            }
            return $this->sendResponse( $users, 'Success' );
        } else {
            return $this->sendResponse( [], 'No Data found');
        }
    }

    public function store(Request $request) {
        try {
            if($request->id && !Learn::find($request->id)) {
                return $this->sendError('not found', [] );
            }

            $validator = Validator::make($request->all(), self::validationForStore($request));
            if ($validator->fails()) {
                return $this->sendError( $validator->errors(), [] );
            }

            if($request->file('file')) {
                $profile = $request->file('file');
                $path = "file";
                $fileName = ChangaAppHelper::uploadfile($profile, $path);
                $fileType = ChangaAppHelper::checkFileExtension($fileName);
            } else {
                $fileName = $request->check_file;
                $fileType = $request->file_type;
            }

            if($request->file('background_image')) {
                $profile = $request->file('background_image');
                $path = "file";
                $background_image = ChangaAppHelper::uploadfile($profile, $path);
            } else {
                $background_image = $request->background_image;
            }

            $Learn = Learn::updateOrCreate(['id' => $request->id],
                [
                    // 'learn_tag_id' => $request->tag,
                    'user_id' => auth()->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'file' => $fileName,
                    'file_type' => $fileType,
                    'background_image' => $background_image,
                ]
            );

            if(isset($request->tag)) {
                LearnTagMulti::where('learn_id', $Learn->id)->delete();
                foreach($request->tag as $tag) {
                    $mutli  = new LearnTagMulti();
                    $mutli->learn_tag_id = $tag;
                    $mutli->learn_id = $Learn->id;
              
                    $mutli->save();
                }
            }

            $pushNotificationData['message'] = $Learn->title;
            $pushNotificationData['id'] = $Learn->id;
            $pushNotificationData['notification_type'] = 'therapy';
            $users = User::where('user_type', config('userTypes.user'))->get()->pluck('id');
            if(isset($users)) {
                foreach($users as $user) {
                    ChangaAppHelper::sendNotication($user, $pushNotificationData);
                }
            }

            $Learn->file = asset('/storage/file/'. $Learn->file);
            $Learn->background_image = !empty($Learn->background_image) ? asset('/storage/file/'. $Learn->background_image) : NULL;

            return $this->sendResponse( $Learn, 'Success' );

        } catch (\Exception $ex) {
            return $this->sendResponse( $ex->getMessage(), 'something went wrong');
        }
    }

    public function validationForStore($request)
    {
        $file = 'required|mimetypes:image/jpeg,image/png,image/gif,video/webm,video/mp4,audio/mpeg,mpga,mp3,wav';
        return [
            'tag' => 'required',
            'title' => 'required|unique:learns,title,'. $request->id,
            'description' => 'required',
            'file' => $file,
            // 'background_image' => 'required|mimetypes:image/jpeg,image/png,image/jpg',
        ];
    }

    public function destroy(Request $request) {
        try {
            if(!Learn::find($request->id)) {
                LearnTagMulti::where('learn_id',$request->id)->delete();
                return $this->sendError('not found', [] );
            }

            if(Learn::where('id', $request->id)->delete()) {
                return $this->sendResponse( [], 'Success' );
            }
        }
        catch (\Exception $ex) {
            return $this->sendResponse( $ex->getMessage(), 'something went wrong');
        }
    }
}
