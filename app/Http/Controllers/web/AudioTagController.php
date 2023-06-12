<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\AudioTag;
use Illuminate\Http\Request;
use Validator;

class AudioTagController extends Controller
{
    public function index()
    {
        try{
            $mediate_tags = AudioTag::paginate(env('PAGINATE'));
            return view('admin.audio_tags.list')->with('mediate_tags',$mediate_tags);
        }catch(\Throwable $e){
            return $e->getMessage();
        }
    }

    public function create()
    {
        return view('admin.audio_tags.edit');
    }

    public function show($id)
    {
        $mediate_tag = AudioTag::find($id);
        return view('admin.audio_tags.view', compact('mediate_tag'));
    }

    public function edit($id)
    {
        try{
            $mediate_tag = AudioTag::where('id',$id)->first();
            return view('admin.audio_tags.edit')->with('mediate_tag',$mediate_tag);
        } catch(\Throwable $e){
            return view('admin.audio_tags.edit')->with('error',$e);
        }
    }

    public function update(Request $request)
    {
        try {
            $validations = [];
            $valid = false;
            $validator = Validator::make($request->all(), self::validationForStore($request));
            if ($validator->fails()) {
                $validations = $validator->errors();
                throw new \Exception("Please correct all the validations.");
            }
            
            $user = AudioTag::updateOrCreate(['id' => $request->id],
                [
                    'tag' => $request->tag
                ]
            );

            $valid = true;
            $message = "Created successfully";
            if($request->id) {
                $message = "Updated successfully";
            }
            $redirect = route('audio_tags');

        } catch (\Exception $ex) {
            $message = $ex;
            $redirect = '';
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    public function validationForStore($request)
    {
        return [
            'tag' => 'required|unique:audio_tags,tag,'.$request->id,
        ];
    }
}
