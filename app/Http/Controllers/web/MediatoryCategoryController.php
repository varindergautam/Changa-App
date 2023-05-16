<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\MediatorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediatoryCategoryController extends Controller
{
    public function index()
    {
        try{
            $mediator_categories = MediatorCategory::paginate(env('PAGINATE'));
            return view('admin.mediator_category.list')->with('mediator_categories',$mediator_categories);
        }catch(\Throwable $e){
            $message = $e;
            $redirect = '';
            return ChangaAppHelper::sendAjaxResponse(false, $message, $redirect, '');
        }
    }

    public function create()
    {
        return view('admin.mediator_category.edit');
    }

    public function show($id)
    {
        $mediator_category = MediatorCategory::find($id);
        return view('admin.mediator_category.view', compact('mediator_category'));
    }

    public function edit($id)
    {
        try{
            $mediator_category = MediatorCategory::where('id',$id)->first();
            return view('admin.mediator_category.edit')->with('mediator_category',$mediator_category);
        } catch(\Throwable $e){
            return view('admin.mediator_category.edit')->with('error',$e);
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
            
            MediatorCategory::updateOrCreate(['id' => $request->id],
                [
                    'category' => $request->category
                ]
            );

            $valid = true;
            $message = "Created successfully";
            if($request->id) {
                $message = "Updated successfully";
            }
            $redirect = route('mediator_category');

        } catch (\Exception $ex) {
            $message = $ex;
            $redirect = '';
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    public function validationForStore($request)
    {
        return [
            'category' => 'required|unique:mediator_categories,category,'.$request->id,
        ];
    }
}
