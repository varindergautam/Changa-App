<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Intention;
use Illuminate\Http\Request;
use Session;
use Validator;

class IntentionController extends Controller
{
    public function index()
    {
        try{
            $mediate_tags = Intention::paginate(env('PAGINATE'));
            return view('admin.intention.list')->with('mediate_tags',$mediate_tags);
        }catch(\Throwable $e){
            return $e->getMessage();
        }
    }

    public function create()
    {
        return view('admin.intention.edit');
    }

    public function show($id)
    {
        $mediate_tag = Intention::find($id);
        return view('admin.intention.view', compact('mediate_tag'));
    }

    public function edit($id)
    {
        try{
            $mediate_tag = Intention::where('id',$id)->first();
            return view('admin.intention.edit')->with('mediate_tag',$mediate_tag);
        } catch(\Throwable $e){
            return view('admin.intention.edit')->with('error',$e);
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
            
            $user = Intention::updateOrCreate(['id' => $request->id],
                [
                    'name' => $request->name
                ]
            );

            $valid = true;
            $message = "Created successfully";
            if($request->id) {
                $message = "Updated successfully";
            }
            $redirect = route('intentions');

        } catch (\Exception $ex) {
            $message = $ex;
            $redirect = '';
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    public function validationForStore($request)
    {
        return [
            'name' => 'required|unique:intentions,name,'.$request->id,
        ];
    }
}

