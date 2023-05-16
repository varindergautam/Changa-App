<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\TherapyTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TherapyTagsController extends Controller
{
    public function index()
    {
        try{
            $mediate_tags = TherapyTag::paginate(env('PAGINATE'));
            return view('admin.therapy_tags.list')->with('mediate_tags',$mediate_tags);
        }catch(\Throwable $e){
            return $e->getMessage();
        }
    }

    public function create()
    {
        return view('admin.therapy_tags.edit');
    }

    public function show($id)
    {
        $mediate_tag = TherapyTag::find($id);
        return view('admin.therapy_tags.view', compact('mediate_tag'));
    }

    public function edit($id)
    {
        try{
            $mediate_tag = TherapyTag::where('id',$id)->first();
            return view('admin.therapy_tags.edit')->with('mediate_tag',$mediate_tag);
        } catch(\Throwable $e){
            return view('admin.therapy_tags.edit')->with('error',$e);
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
            
            TherapyTag::updateOrCreate(['id' => $request->id],
                [
                    'tag' => $request->tag
                ]
            );

            $valid = true;
            $message = "Created successfully";
            if($request->id) {
                $message = "Updated successfully";
            }
            $redirect = route('therapy_tags');

        } catch (\Exception $ex) {
            $message = $ex;
            $redirect = '';
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    public function validationForStore($request)
    {
        return [
            'tag' => 'required|unique:therapy_tags,tag,'.$request->id,
        ];
    }
}
