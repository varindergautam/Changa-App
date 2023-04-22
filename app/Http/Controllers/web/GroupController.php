<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index()
    {
        try{
            $mediate_tags = Group::get();
            return view('admin.group.list')->with('mediate_tags',$mediate_tags);
        }catch(\Throwable $e){
            return $e->getMessage();
        }
    }

    public function create()
    {
        return view('admin.group.edit');
    }

    public function show($id)
    {
        $mediate_tag = Group::find($id);
        return view('admin.group.view', compact('mediate_tag'));
    }

    public function edit($id)
    {
        try{
            $mediate_tag = Group::where('id',$id)->first();
            return view('admin.group.edit')->with('mediate_tag',$mediate_tag);
        } catch(\Throwable $e){
            return view('admin.group.edit')->with('error',$e);
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

            $users = User::whereIn('user_type', [config('userTypes.mediator'), config('userTypes.user')])->get()->pluck('id')->toArray();
            
            $group = Group::updateOrCreate(['id' => $request->id],
                [
                    'group_name' => $request->group_name
                ]
            );

            GroupUser::updateOrCreate(['id' => $request->id],
                [
                    'group_id' => $group->id,
                    'user_id' => implode(',', $users)
                ]
            );

            $valid = true;
            $message = "Created successfully";
            if($request->id) {
                $message = "Updated successfully";
            }
            $redirect = route('group');

        } catch (\Exception $ex) {
            $message = $ex;
            $redirect = '';
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    public function validationForStore($request)
    {
        return [
            'group_name' => 'required|unique:groups,group_name,'.$request->id,
        ];
    }
}
