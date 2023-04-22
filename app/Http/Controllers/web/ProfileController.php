<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    function index(){
        return view('layouts.profile');
    }

    function update(Request $request) {
        try {
            $validations = [];
            $valid = false;
            $validator = Validator::make($request->all(), self::validationForStore($request));
            if ($validator->fails()) {
                $validations = $validator->errors();
                throw new \Exception("Please correct all the validations.");
            }
            $user = User::find(auth()->user()->id);
            $user->first_name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $valid = true;
            $message = "Updated successfully";
            $redirect = route('profile');
        } catch (\Exception $ex) {
            $valid = false;
            $message = $ex;
            $redirect = '';
        }

        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    public function validationForStore($request)
    {
        
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. auth()->user()->id,
            'password' => 'nullable',
            'confirm_password' => 'required_with:password|same:password',
        ];
    }
}
