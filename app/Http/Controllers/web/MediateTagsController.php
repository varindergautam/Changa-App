<?php

namespace App\Http\Controllers\web;

use App\Models\MediateTag;
use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediateTagsController extends Controller
{
    public function index()
    {
        try{
            $mediate_tags = MediateTag::get();
            return view('admin.mediate_tags.list')->with('mediate_tags',$mediate_tags);
        }catch(\Throwable $e){
            return $e->getMessage();
        }
    }

    public function create()
    {
        return view('admin.mediate_tags.edit');
    }

    public function show($id)
    {
        $mediate_tag = MediateTag::find($id);
        return view('admin.mediate_tags.view', compact('mediate_tag'));
    }

    public function edit($id)
    {
        try{
            $mediate_tag = MediateTag::where('id',$id)->first();
            return view('admin.mediate_tags.edit')->with('mediate_tag',$mediate_tag);
        } catch(\Throwable $e){
            return view('admin.mediate_tags.edit')->with('error',$e);
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
            
            $user = MediateTag::updateOrCreate(['id' => $request->id],
                [
                    'tag' => $request->tag
                ]
            );

            $valid = true;
            $message = "Created successfully";
            if($request->id) {
                $message = "Updated successfully";
            }
            $redirect = route('mediate_tags');

        } catch (\Exception $ex) {
            $message = $ex;
            $redirect = '';
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    public function validationForStore($request)
    {
        return [
            'tag' => 'required|unique:mediate_tags,tag,'.$request->id,
        ];
    }
}
