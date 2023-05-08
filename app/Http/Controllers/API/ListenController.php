<?php

namespace App\Http\Controllers\API;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Listen;
use App\Models\ListenTag;
use App\Models\ListenTagMulti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListenController extends BaseController
{
    public function listenTag() {
        $mediate_tags = ListenTag::get();
        if($mediate_tags->count() > 0) {
            return $this->sendResponse( $mediate_tags, 'Success' );
        } else {
            return $this->sendResponse( [], 'No Data found');
        }
    }

    public function listen(Request $request) {
        $users = Listen::with('user', 'listenTagMulti', 'favourite')->get();
        if($request->id) {
            $users = Listen::where('id', $request->id)->with('listenTagMulti', 'user', 'favourite')->get();
        }

        if($request->tag_id) {
            $multi = ListenTagMulti::where('listen_tag_id', $request->tag_id)->get()->pluck('listen_id')->toArray();
            $users = Listen::with('listenTagMulti','user', 'favourite')->whereIn('id', $multi)->get();
        }

        if($users->count() > 0) {
            foreach($users as $key => $listen) {
                $description = strip_tags(html_entity_decode($listen->description));
                $users[$key]['description'] = strip_tags(nl2br($description));
                $users[$key]['file'] = asset('/storage/file/'. $listen->file);
                $users[$key]['url'] = url('/api/listen/listen?id=' . $listen->id);
                $users[$key]['created_date'] = ChangaAppHelper::dateFormat($listen->created_at);
                $users[$key]['user']['profile_pic'] = !empty($listen->user->profile_pic) ? asset('/storage/profile_pic/'. $listen->user->profile_pic) : null;
                $users[$key]['user']['background_image'] = !empty($listen->user->background_image) ? asset('/storage/file/'. $listen->user->background_image) : null;;

                $arr = [];
                foreach($listen->listenTagMulti as $tag) {
                    $arr[] = ListenTag::where('id', $tag->listen_tag_id)->get()->toArray();
                }
                $users[$key]['listen_tag'] = $arr;
            }
            $success[ 'data' ] = $users;
            return $this->sendResponse( $users, 'Success' );
        } else {
            return $this->sendResponse( [], 'No Data found');
        }
    }

    public function store(Request $request) {
        try {
            if($request->id && !Listen::find($request->id)) {
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

            $Learn = Listen::updateOrCreate(['id' => $request->id],
                [
                    // 'listen_tag_id' => $request->tag,
                    'user_id' => auth()->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'file' => $fileName,
                    'file_type' => $fileType,
                    'background_image' => $background_image,
                ]
            );

            if(isset($request->tag)) {
                ListenTagMulti::where('listen_id', $Learn->id)->delete();
                foreach($request->tag as $tag) {
                    $mutli  = new ListenTagMulti();
                    $mutli->listen_tag_id = $tag;
                    $mutli->listen_id = $Learn->id;
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
            'title' => 'required|unique:listens,title,'. $request->id,
            'description' => 'required',
            'file' => $file,
            // 'background_image' => 'required|mimetypes:image/jpeg,image/png,image/jpg',
        ];
    }

    public function destroy(Request $request) {
        try {
            if(!Listen::find($request->id)) {
                return $this->sendError('not found', [] );
            }

            if(Listen::where('id', $request->id)->delete()) {
                ListenTagMulti::where('listen_id',$request->id)->delete();
                return $this->sendResponse( [], 'Success' );
            }
        }
        catch (\Exception $ex) {
            return $this->sendResponse( $ex->getMessage(), 'something went wrong');
        }
    }
}
