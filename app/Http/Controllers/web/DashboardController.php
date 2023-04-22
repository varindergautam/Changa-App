<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Models\Guide;
use App\Models\Learn;
use App\Models\Listen;
use App\Models\Mediate;
use App\Models\Therapy;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


    function __construct(){
        $this->middleware('auth')->except('logout');
    }
    
    function index(){
        $data['total_customer'] = User::where('user_type', config('userTypes.user'))->count();
        $data['total_mediator'] = User::where('user_type', config('userTypes.mediator'))->count();
        $data['total_mediate'] = Mediate::count();
        $data['total_learn'] = Learn::count();
        $data['total_listen'] = Listen::count();
        $data['total_therapy'] = Therapy::count();
        $data['total_guide'] = Guide::count();
        return view('layouts.dashboard', $data);
    }
    // function users(){
    //     try{
    //         $users = User::where('user_type','2')->get();
    //         return view('layouts.users')->with('users',$users);
    //         }catch(\Throwable $e){
    //         return  view('layouts.users');
    //         }
       
    // }

    // function edit_user($id){
    //     try{
    //         $user = User::where('id',$id)->first();
    //         return view('layouts.users.edit')->with('user',$user);
    //     } catch(\Throwable $e){
    //         return view('layouts.users.edit')->with('error',$e);
    //     }

       
    // }

    // function view_user(){
       
    //     return view('layouts.users.view');
    // }

    // function add(Request $request) {
    //     return view('layouts.users.edit');
    // } 

    function update_user(Request $request) {
        try {
            $validations = [];
            $valid = false;
            // $error = $redirect = "";
            $validator = Validator::make($request->all(), self::validationForStore());
            if ($validator->fails()) {
                $validations = $validator->errors();
                throw new \Exception("Please correct all the validations.");
            }
            
            $user = User::updateOrCreate(['id' => $request->id],
                [
                    'phone' => $request->phone,
                    'first_name' => $request->phone,
                    'email' => $request->phone,
                    'username' => $request->phone,
                    'password' => $request->phone,
                ]
            );

            $valid = true;
            $message = " updated successfully";
            $redirect = route('users');

            // return ChangaAppHelper::sendAjaxResponse(true, $message, $validations, $response);
        } catch (\Exception $ex) {
            $valid = false;
            $message = $ex;
            // return ChangaAppHelper::sendAjaxResponse(false, $ex, '', $validations);
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
        // return (new Response(compact('valid', 'error', 'validations', 'redirect'), 200))->header('Content-Type', 'application/json');
    }

    public function validationForStore()
    {
        return [
            'phone' => 'required',
            // 'customer_id' => 'required',
        ];
    }

}
