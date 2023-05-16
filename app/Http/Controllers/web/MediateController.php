<?php

namespace App\Http\Controllers\web;

use Exception;
use Session;
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

class MediateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $users = Mediate::with('mediateTagMulti')->paginate(env('PAGINATE'));
            if(count($users) > 0) {
                foreach($users as $key => $user) {
                    $arr = [];
                    foreach($user->mediateTagMulti as $tag) {
                        $audioTags = MediateTag::where('id', $tag->mediate_tag_id)->get()->pluck('tag')->toArray();
                        $arr[] = implode(', ', $audioTags);
                    }
                    $users[$key]['mediate_tag_id'] = implode(', ', $arr);
                }
            }
            return view('admin.mediates.list')->with('users',$users);
            }catch(\Throwable $e){
            return  $e;
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = MediateTag::get();
        return view('admin.mediates.edit', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Mediate::with('mediateTag')->find($id);
        $tags = MediateTagMulti::where('mediate_id',$id)->get()->pluck('mediate_tag_id')->toArray();
        $audioTags = MediateTag::whereIn('id', $tags)->get()->pluck('tag')->toArray();
        $user->mediate_tag_id = implode(', ', $audioTags);
        return view('admin.mediates.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $tags = MediateTag::get();
            $user = Mediate::where('id',$id)->first();
            $multi = MediateTagMulti::where('mediate_id',$id)->get()->pluck('mediate_tag_id')->toArray();
            return view('admin.mediates.edit', compact('user', 'tags', 'multi'));
        } catch(\Throwable $e){
            return view('admin.mediates.edit')->with('error',$e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $validations = [];
            $valid = false;
            // $error = $redirect = "";
            $validator = Validator::make($request->all(), self::validationForStore($request));
            if ($validator->fails()) {
                $validations = $validator->errors();
                throw new \Exception("Please correct all the validations.");
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
                $background_image = $request->background_image_hiden;
            }

            $mediate = Mediate::updateOrCreate(['id' => $request->id],
                [
                    // 'mediate_tag_id' => implode(',', $request->tag),
                    'user_id' => auth()->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'file' => $fileName,
                    'file_type' => $fileType,
                    'background_image' => @$background_image,
                ]
            );

            if(isset($request->tag)) {
                MediateTagMulti::where('mediate_id', $mediate->id)->delete();
                foreach($request->tag as $tag) {
                    $mutli  = new MediateTagMulti();
                    $mutli->mediate_tag_id = $tag;
                    $mutli->mediate_id = $mediate->id;
              
                    $mutli->save();
                }
            }

            $pushNotificationData['message'] = $mediate->title;
            $pushNotificationData['id'] = $mediate->id;
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

            $valid = true;
            $message = "Mediator created successfully";
            if($request->id) {
                $message = "Mediator updated successfully";
            }
            $redirect = route('mediates');

        } catch (\Exception $ex) {
            $valid = false;
            $message = $ex;
            $redirect = '';
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {

            if (!$id) {
                throw new Exception('not found', Config('HttpCodes.fail'));
            }

            $result['status'] = 1;
            Mediate::where('id', $id)->delete();
            MediateTagMulti::where('mediate_id',$id)->delete();
            Session::flash('success', 'Deleted Successfully.');
            return response()->json($result, 200);

        } catch (Exception $ex) {
            $result['status'] = 1;
            Session::flash('success', $ex->getMessage());

        }
        return response()->json($result, 200);
    }

    public function status($id, $status)
    {
        $userstatus = Mediate::find($id);

        if ($userstatus) {
            $userstatus->active = ($status == '0') ? '1' : '0';
            $userstatus->save();

            $result['status'] = '1';
            return response()->json($result, 200);
        } else {
            $result['status'] = '0';
            return response()->json($result, 200);
        }
    }

    public function validationForStore($request)
    {
        $file = !$request->check_file ? 'required|mimetypes:image/jpeg,image/png,image/gif,video/webm,video/mp4,audio/mpeg,mpga,mp3,wav|max:100000' :"mimetypes:image/jpeg,image/png,image/gif,video/webm,video/mp4,audio/mpeg,mpga,mp3,wav|max:100000" ;
        return [
            'tag' => 'required',
            'title' => 'required|unique:mediates,title,'. $request->id,
            'description' => 'required',
            'file' => $file,
        ];
    }
}
