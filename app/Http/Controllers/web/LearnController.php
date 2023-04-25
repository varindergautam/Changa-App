<?php

namespace App\Http\Controllers\web;

use Exception;
use Session;
use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\LearnTag;
use App\Models\Learn;
use App\Models\LearnTagMulti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LearnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $users = Learn::with('learnTagMulti')->get();

            if(count($users) > 0) {
                foreach($users as $key => $user) {
                    $arr = [];
                    foreach($user->learnTagMulti as $tag) {
                        $audioTags = LearnTag::where('id', $tag->learn_tag_id)->get()->pluck('tag')->toArray();
                        $arr[] = implode(', ', $audioTags);
                    }
                    $users[$key]['learn_tag_id'] = implode(', ', $arr);
                }
            }
            return view('admin.learns.list')->with('users',$users);
            }catch(\Throwable $e){
            return $e;
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = LearnTag::get();
        return view('admin.learns.edit', compact('tags'));
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
        $user = Learn::with('learnTag', 'learnTagMulti')->find($id);
        $tags = LearnTagMulti::where('learn_id',$id)->get()->pluck('learn_tag_id')->toArray();
        $audioTags = LearnTag::whereIn('id', $tags)->get()->pluck('tag')->toArray();
        $user->learn_tag_id = implode(', ', $audioTags);
        return view('admin.learns.view', compact('user'));
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
            $tags = LearnTag::get();
            $user = Learn::where('id',$id)->first();
            $multi = LearnTagMulti::where('learn_id',$id)->get()->pluck('learn_tag_id')->toArray();
            // print_r($multi);die;
            return view('admin.learns.edit', compact('user', 'tags', 'multi'));
        } catch(\Throwable $e){
            return view('admin.learns.edit')->with('error',$e);
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

            $learn = Learn::updateOrCreate(['id' => $request->id],
                [
                    'user_id' => auth()->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'file' => $fileName,
                    'file_type' => $fileType,
                    'background_image' => $background_image,
                ]
            );
     
            if(isset($request->tag)) {
                LearnTagMulti::where('learn_id', $learn->id)->delete();
                foreach($request->tag as $tag) {
                    $mutli  = new LearnTagMulti();
                    $mutli->learn_tag_id = $tag;
                    $mutli->learn_id = $learn->id;
              
                    $mutli->save();
                }
            }

            $pushNotificationData['message'] = $learn->title;
            $pushNotificationData['id'] = $learn->id;
            $pushNotificationData['notification_type'] = 'therapy';
            $users = User::where('user_type', config('userTypes.user'))->get()->pluck('id');
            if(isset($users)) {
                foreach($users as $user) {
                    ChangaAppHelper::sendNotication($user, $pushNotificationData);
                }
            }

            $valid = true;
            $message = "Learn created successfully";
            if($request->id) {
                $message = "Learn updated successfully";
            }
            $redirect = route('learns');

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
            Learn::where('id', $id)->delete();
            LearnTagMulti::where('learn_id',$id)->delete();
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
        $userstatus = Learn::find($id);

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
            'title' => 'required|unique:learns,title,'. $request->id,
            'description' => 'required',
            'file' => $file,
        ];
    }
}
