<?php

namespace App\Http\Controllers\web;

use Exception;
use Session;
use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\TherapyTag;
use App\Models\Therapy;
use App\Models\TherapyTagMulti;
use App\Models\User;
use App\Models\UserNotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TherapyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $users = Therapy::with('therapyTag')->paginate(env('PAGINATE'));
            if(count($users) > 0) {
                foreach($users as $key => $user) {
                    $arr = [];
                    foreach($user->therapyTagMulti as $tag) {
                        $audioTags = TherapyTag::where('id', $tag->therapy_tag_id)->get()->pluck('tag')->toArray();
                        $arr[] = implode(', ', $audioTags);
                    }
                    $users[$key]['therapy_tag_id'] = implode(', ', $arr);
                }
            }
            return view('admin.therapy.list')->with('users',$users);
            }catch(\Throwable $e){
            return  $e->getMessage();
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = TherapyTag::get();
        return view('admin.therapy.edit', compact('tags'));
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
        $user = Therapy::with('therapyTag', 'therapyTagMulti')->find($id);
        $tags = TherapyTagMulti::where('therapy_id',$id)->get()->pluck('therapy_tag_id')->toArray();
        $audioTags = TherapyTag::whereIn('id', $tags)->get()->pluck('tag')->toArray();
        $user->therapy_tag_id = implode(', ', $audioTags);
        return view('admin.therapy.view', compact('user'));
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
            $data['tags'] = TherapyTag::get();
            $data['user'] = Therapy::where('id',$id)->first();
            $data['multi'] = TherapyTagMulti::where('therapy_id',$id)->get()->pluck('therapy_tag_id')->toArray();
            return view('admin.therapy.edit', $data);
        } catch(\Throwable $e){
            return view('admin.therapy.edit')->with('error',$e);
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

            $therapy = Therapy::updateOrCreate(['id' => $request->id],
                [
                    // 'therapy_tag_id' => $request->tag,
                    'user_id' => auth()->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'file' => $fileName,
                    'file_type' => $fileType,
                    'background_image' => $background_image,
                ]
            );

            if(isset($request->tag)) {
                TherapyTagMulti::where('therapy_id', $therapy->id)->delete();
                foreach($request->tag as $tag) {
                    $mutli  = new TherapyTagMulti();
                    $mutli->therapy_tag_id = $tag;
                    $mutli->therapy_id = $therapy->id;
                    $mutli->save();
                }
            }

            $pushNotificationData['message'] = $therapy->title;
            $pushNotificationData['id'] = $therapy->id;
            $pushNotificationData['notifiable_type'] = 'therapy_create';
            if($request->id) {
                $pushNotificationData['notifiable_type'] = 'therapy_update';
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
            $message = "Therapy created successfully";
            if($request->id) {
                $message = "Therapy updated successfully";
            }
            $redirect = route('therapy');

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
            Therapy::where('id', $id)->delete();
            TherapyTagMulti::where('therapy_id',$id)->delete();
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
        $userstatus = Therapy::find($id);

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
            'title' => 'required|unique:therapies,title,'. $request->id,
            'description' => 'required',
            'file' => $file,
        ];
    }
}
