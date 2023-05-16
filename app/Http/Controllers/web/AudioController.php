<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\AudioTag;
use App\Models\AudioTagMulti;
use App\Models\AudioTagMutli;
use Illuminate\Http\Request;
use Validator;
use Session;
use Exception;

class AudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $users = Audio::with('audioTag', 'audioTagMulti')->paginate(env('PAGINATE'));

            if(count($users) > 0) {
                foreach($users as $key => $user) {
                    $arr = [];
                    foreach($user->audioTagMulti as $tag) {
                        $audioTags = AudioTag::where('id', $tag->audio_tag_id)->get()->pluck('tag')->toArray();
                        $arr[] = implode(', ', $audioTags);
                    }
                    $users[$key]['audio_tag_id'] = implode(', ', $arr);
                }
            }
         
            return view('admin.audio.list')->with('users',$users);
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
        $tags = AudioTag::get();
        return view('admin.audio.edit', compact('tags'));
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
        $user = Audio::with('audioTag', 'audioTagMulti')->find($id);
        $tags = AudioTagMulti::where('audio_id',$id)->get()->pluck('audio_tag_id')->toArray();
        $audioTags = AudioTag::whereIn('id', $tags)->get()->pluck('tag')->toArray();
        $user->audio_tag_id = implode(', ', $audioTags);
        return view('admin.audio.view', compact('user'));
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
            $tags = AudioTag::get();
            $user = Audio::where('id',$id)->first();
            $multi = AudioTagMulti::where('audio_id',$id)->get()->pluck('audio_tag_id')->toArray();
            return view('admin.audio.edit', compact('user', 'tags', 'multi'));
        } catch(\Throwable $e){
            return $e;
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

            $audio = Audio::updateOrCreate(['id' => $request->id],
                [
                    // 'audio_tag_id' => implode(',', $request->tag),
                    'user_id' => auth()->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'file' => $fileName,
                    'file_type' => $fileType,
                    'background_image' => $background_image,
                ]
            );

            if(isset($request->tag)) {
                AudioTagMulti::where('audio_id', $audio->id)->delete();
                foreach($request->tag as $tag) {
                    $mutli  = new AudioTagMulti();
                    $mutli->audio_tag_id = $tag;
                    $mutli->audio_id = $audio->id;
              
                    $mutli->save();
                }
            }

            $valid = true;
            $message = "audio created successfully";
            if($request->id) {
                $message = "audio updated successfully";
            }
            $redirect = route('audio');

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
            Audio::where('id', $id)->delete();
            AudioTagMulti::where('audio_id', $id)->delete();
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
        $userstatus = Audio::find($id);

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
        $file = !$request->check_file ? 'required|mimetypes:audio/mpeg,mpga,mp3,wav|max:100000' :"mimetypes:audio/mpeg,mpga,mp3,wav|max:100000" ;
        return [
            'tag' => 'required',
            'title' => 'required|unique:audios,title,'. $request->id,
            'description' => 'required',
            'file' => $file,
        ];
    }
}
