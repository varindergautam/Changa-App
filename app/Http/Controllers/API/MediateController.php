<?php

namespace App\Http\Controllers\API;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Mediate;
use App\Models\MediateTag;
use App\Models\MediateTagMulti;
use App\Models\Notifications;
use App\Models\User;
use App\Models\UserNotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediateController extends BaseController
{
    public function mediateTag() {
        $mediate_tags = MediateTag::get();
        if($mediate_tags->count() > 0) {
            return $this->sendResponse( $mediate_tags, 'Success' );
        } else {
            return $this->sendError( [], 'No Data found');
        }
    }

    public function mediate(Request $request){
        $users = Mediate::with('user', 'mediateTagMulti', 'favourite')->where('active', config('commonStatus.ACTIVE'))->get();
        if($request->id) {
            $users = Mediate::where('id', $request->id)->with('mediateTagMulti', 'user', 'favourite')->where('active', config('commonStatus.ACTIVE'))->get();
            $mediators_id = Mediate::get()->pluck('user_id')->toArray();
            $mediators = User::whereIn('id', $mediators_id)->get();
        }

        if($request->user_id) {
            $users = Mediate::where('user_id', $request->user_id)->with('mediateTagMulti', 'user', 'favourite')->where('active', config('commonStatus.ACTIVE'))->get();
            $mediators_id = Mediate::where('active', config('commonStatus.ACTIVE'))->get()->pluck('user_id')->toArray();
            $mediators = User::whereIn('id', $mediators_id)->get();
        }

        if($request->tag_id) {
            $multi = MediateTagMulti::where('mediate_tag_id', $request->tag_id)->get()->pluck('mediate_id')->toArray();
            $users = Mediate::with('mediateTagMulti','user', 'favourite')->where('active', config('commonStatus.ACTIVE'))->whereIn('id', $multi)->get();
        }

        if($users->count() > 0) {
            foreach($users as $key => $mediate) {
                $description = strip_tags(html_entity_decode($mediate->description));
                $users[$key]['description'] = strip_tags(nl2br($description));
                $users[$key]['file'] = asset('/storage/file/'. $mediate->file);
                $users[$key]['created_date'] = ChangaAppHelper::dateFormat($mediate->created_at);
                $users[$key]['url'] = url('/api/mediate/mediate?id=' . $mediate->id);
                $users[$key]['user']['profile_pic'] = isset($mediate->user) && !empty($mediate->user->profile_pic) ? asset('/storage/profile_pic/'. $mediate->user->profile_pic) : null;

                $users[$key]['user']['background_image'] = isset($mediate->user) && !empty($mediate->user->background_image) ? asset('/storage/file/'. $mediate->user->background_image) : null;

                $arr = [];
                foreach($mediate->mediateTagMulti as $tag) {
                    $arr[] = MediateTag::where('id', $tag->mediate_tag_id)->get()->toArray();
                }
                $users[$key]['mediate_tag'] = $arr;
                $users[$key]['mediators'] = isset($mediators) ? $mediators : NULL;
            }
            return $this->sendResponse( $users, 'Success' );
        } else {
            return $this->sendError( [], 'No Data found');
        }
    }

    public function store(Request $request) {
        try {
            if($request->id && !Mediate::find($request->id)) {
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
            }

            if($request->file('background_image')) {
                $profile = $request->file('background_image');
                $path = "file";
                $background_image = ChangaAppHelper::uploadfile($profile, $path);
            } else {
                $background_image = $request->background_image;
            }

            $Learn = Mediate::updateOrCreate(['id' => $request->id],
                [
                    // 'mediate_tag_id' => $request->tag,
                    'user_id' => auth()->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'file' => $fileName,
                    'file_type' => $fileType,
                    'background_image' => $background_image,
                    'active' => config('commonStatus.INACTIVE'),
                ]
            );

            if(isset($request->tag)) {
                MediateTagMulti::where('mediate_id', $Learn->id)->delete();
                foreach($request->tag as $tag) {
                    $mutli  = new MediateTagMulti();
                    $mutli->mediate_tag_id = $tag;
                    $mutli->mediate_id = $Learn->id;
              
                    $mutli->save();
                }
            }

            $Learn->file = asset('/storage/file/'. $Learn->file);
            $Learn->background_image = !empty($Learn->background_image) ? asset('/storage/file/'. $Learn->background_image) : NULL;
 
            $pushNotificationData['message'] = $Learn->title;
            $pushNotificationData['id'] = $Learn->id;
            $pushNotificationData['notifiable_type'] = 'mediate_create';
            if($request->id) {
                $pushNotificationData['notifiable_type'] = 'mediate_update';
            }
            $users = User::where('user_type', config('userTypes.user'))->get()->pluck('id');
            if(isset($users)) {
                foreach($users as $user) {
                    $data['notifiable_id'] = $user;
                    $data['notifiable_type'] = $pushNotificationData['notifiable_type'];
                    $data['type'] = $pushNotificationData['notifiable_type'];
                    $data['data'] = $pushNotificationData['message'];
                    Notifications::saveNotification($data);

                    $setting = UserNotificationSetting::where('user_id', $user)->first();
        
                    if(isset($setting) && $setting->new_content == '1') {
                        ChangaAppHelper::sendNotication($user, $pushNotificationData);
                    } else if(isset($setting) && $setting->trip_update == '1') {
                        ChangaAppHelper::sendNotication($user, $pushNotificationData);
                    }
                }
            }
            
            return $this->sendResponse( $Learn, 'Success' );

        } catch (\Exception $ex) {
            return $this->sendError( $ex->getMessage(), 'something went wrong');
        }
    }

    public function validationForStore($request)
    {
        $file = 'required|mimetypes:image/jpeg,image/png,image/gif,video/webm,video/mp4,audio/mpeg,mpga,mp3,wav';
        return [
            'tag' => 'required',
            // 'title' => 'required|unique:mediates,title,'. $request->id,
            'title' => 'required',
            'description' => 'required',
            'file' => $file,
            // 'background_image' => 'required|mimetypes:image/jpeg,image/png,image/jpg',
        ];
    }

    public function destroy(Request $request) {
        try {
            if(!Mediate::find($request->id)) {
                return $this->sendError('not found', [] );
            }

            if(Mediate::where('id', $request->id)->delete()) {
                MediateTagMulti::where('mediate_id',$request->id)->delete();
                return $this->sendResponse( [], 'Success' );
            }
        }
        catch (\Exception $ex) {
            return $this->sendError( $ex->getMessage(), 'something went wrong');
        }
    }

    public function favourite(Request $request){
        $users = Mediate::with('user', 'mediateTagMulti', 'favourite')->whereHas('favourite')->get();
        if($request->id) {
            $users = Mediate::where('id', $request->id)->with('mediateTagMulti', 'user', 'favourite')->whereHas('favourite')->get();
        }

        if($request->tag_id) {
            $multi = MediateTagMulti::where('mediate_tag_id', $request->tag_id)->get()->pluck('mediate_id')->toArray();
            $users = Mediate::with('mediateTagMulti','user', 'favourite')->whereHas('favourite')->whereIn('id', $multi)->get();
        }

        if($users->count() > 0) {
            foreach($users as $key => $mediate) {
                $description = strip_tags(html_entity_decode($mediate->description));
                $users[$key]['description'] = strip_tags(nl2br($description));
                $users[$key]['file'] = asset('/storage/file/'. $mediate->file);
                $users[$key]['created_date'] = ChangaAppHelper::dateFormat($mediate->created_at);
                $users[$key]['url'] = url('/api/mediate/mediate?id=' . $mediate->id);
                $users[$key]['user']['profile_pic'] = !empty($mediate->user->profile_pic) ? asset('/storage/profile_pic/'. $mediate->user->profile_pic) : null;

                $users[$key]['user']['background_image'] = !empty($mediate->user->background_image) ? asset('/storage/file/'. $mediate->user->background_image) : null;

                $arr = [];
                foreach($mediate->mediateTagMulti as $tag) {
                    $arr[] = MediateTag::where('id', $tag->mediate_tag_id)->get()->toArray();
                }
                $users[$key]['mediate_tag'] = $arr;
            }
            return $this->sendResponse( $users, 'Success' );
        } else {
            return $this->sendResponse( [], 'No Data found');
        }
    }

    public function mediateUser() {
        $mediators_id = Mediate::where('active', config('commonStatus.ACTIVE'))->get()->pluck('user_id')->toArray();
        $mediate_tags = User::whereIn('id', $mediators_id)->get();
        if($mediate_tags->count() > 0) {
            return $this->sendResponse( $mediate_tags, 'Success' );
        } else {
            return $this->sendError( [], 'No Data found');
        }
    }
}

