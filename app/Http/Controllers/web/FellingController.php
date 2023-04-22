<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Felling;
use Session;
use Validator;
use Illuminate\Http\Request;

class FellingController extends Controller
{
    public function index()
    {
        try{
            $mediate_tags = Felling::get();
            return view('admin.felling.list')->with('mediate_tags',$mediate_tags);
        }catch(\Throwable $e){
            return $e->getMessage();
        }
    }

    public function create()
    {
        return view('admin.felling.edit');
    }

    public function show($id)
    {
        $mediate_tag = Felling::find($id);
        return view('admin.felling.view', compact('mediate_tag'));
    }

    public function edit($id)
    {
        try{
            $mediate_tag = Felling::where('id',$id)->first();
            return view('admin.felling.edit')->with('mediate_tag',$mediate_tag);
        } catch(\Throwable $e){
            return view('admin.felling.edit')->with('error',$e);
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
            
            $user = Felling::updateOrCreate(['id' => $request->id],
                [
                    'name' => $request->name
                ]
            );

            $valid = true;
            $message = "Created successfully";
            if($request->id) {
                $message = "Updated successfully";
            }
            $redirect = route('fellings');

        } catch (\Exception $ex) {
            $message = $ex;
            $redirect = '';
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    public function validationForStore($request)
    {
        return [
            'name' => 'required|unique:fellings,name,'.$request->id,
        ];
    }
}
