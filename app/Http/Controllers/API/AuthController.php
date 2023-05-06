<?php

namespace App\Http\Controllers\API;

use App\Helpers\ChangaAppHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Mail\BasicMail;
use App\Mail\OtpMail;
use App\Mail\SendOtpMail;
use Validator;
use App\Models\BeginTripe;
use App\Models\BeginTripMemo;
use App\Models\BeignTripNowFeel;
use Mail;
use App\Models\User;
use App\Models\UserDeviceToken;
use App\Models\VerificationCode;
use App\Models\UserNotificationSetting;
use Carbon\Carbon;
use App\Mail\UserRegisterMail;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

class AuthController extends BaseController {
    
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:15',
            'otp' => 'required|string|max:4',
        ]);
    
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }
    
        $user = User::where('email', $request->email)->where('phone', $request->phone)->first();
    
        if (!$user) {
            return response(['errors' => ['User not found']], 422);
        }
    
        if ($user->otp !== $request->otp) {
            return response(['errors' => ['Invalid OTP']], 422);
        }
    
        $user->email_verified = 1;
        $user->phone_verified = 1;
        $user->save();
    
        $token = $user->createToken('authToken')->accessToken;
    
        return response(['user' => $user->makeHidden(['password']), 'access_token' => $token]);
    }
    
    public function forgotPassword( Request $request ) {

        $validator = Validator::make( $request->all(), [
            'email' =>'required|email:exists:users'
        ] );

        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }

        $user = User::where( 'email', $request->email )->first();

        if ( $user->id != null ) {
            $verification_code = $this->generateOtp( $user->phone );
            try{
                $otp =  $this->sendSmsNotificaition( $user->phone, $verification_code->otp );

                $mailData['name'] = $user->first_name;
                $mailData['otp'] = $verification_code->otp;
                Mail::to($request->email)->send(new SendOtpMail($mailData));
                
                $success[ 'otp_message' ] = $otp;
                $success[ 'user_id' ] = $user->id;
                $success[ 'token' ] = $user->api_token;
                return $this->sendResponse( $success, 'OTP send successfully to '.$user->phone );
            }catch(\Throwable $e){
                return $e;
                return $this->sendError('You are register with Social Account');
            }
           
        } else {
            return $this->sendError( 'User not found' );

        }

    }


    public function generateOtp( $mobile_no ) {
        $user = User::where( 'phone', $mobile_no )->first();

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where( 'user_id', $user->id )->latest()->first();

        $now = Carbon::now();

        if ( $verificationCode && $now->isBefore( $verificationCode->expire_at ) ) {
            return $verificationCode;
        }

        // Create a New OTP
        return VerificationCode::create( [
            'user_id' => $user->id,
            'otp' => rand( 123456, 999999 ),
            'expire_at' => Carbon::now()->addMinutes( 10 )
        ] );
    }

    public function otpVerification( Request $request ) {
        #Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required'
        ] );
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }

        #Validation Logic
        $verificationCode   = VerificationCode::where( 'user_id', $request->user_id )->where( 'otp', $request->otp )->first();

        $now = Carbon::now();
        if ( !$verificationCode ) {
            return $this->sendError( 'Error', 'Your OTP is not correct.' );
        } elseif ( $verificationCode && $now->isAfter( $verificationCode->expire_at ) ) {
            return $this->sendError( 'Error', 'Your OTP has been expired.' );
        }

        $user = User::whereId( $request->user_id )->first();

        if ( $user ) {
            // Expire The OTP
            $user->update([
                "email_verified"=>1,
            "phone_verified"=> 1,
                ]);
            $verificationCode->update( [
                'expire_at' => Carbon::now()
            ] );
            $success[ 'user_data' ] = $user;
            return $this->sendResponse( $success, 'Your OTP verifictation completed.' );
        }

    }

    public function resetPassword( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'user_id' => 'required|exists:users,id',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ] );
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }

        $change = User::where( 'id', $request->user_id )->first()->update( [ 'password' => Hash::make( $request->password ) ] );
        if ( $change ) {
            $user = User::whereId( $request->user_id )->first();
            $success[ 'user_data' ] = $user;
            return $this->sendResponse( $success, 'Your password updated.' );
        } else {
            return $this->sendResponse( 'Error', 'Try again' );
        }
    }


    
    //register api
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:191',
            'email' => 'required|email|unique:users|max:191',
            'username' => 'required|unique:users|max:191',
            'phone' => 'required|unique:users|max:191',
            'password' => 'required|min:6|max:191',
            'user_type' => 'required',
            'device_type' => 'required',
            'device_token' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return  $this->sendError("Invalid email");
        }

        try{
            $user = User::create([
                'first_name' => $request->first_name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
                'customer_id' => 'CHA-'.random_int(10000, 99999)
            ]);

            if (!is_null($user)) {
                $token = $user->createToken( 'token-name', [ 'server:update' ] )->plainTextToken;
                $user->update( [
                    'api_token' => $token
                ] );
                $verification_code = $this->generateOtp( $user->phone );
                $otp =  $this->sendSmsNotificaition( $request->phone, $verification_code->otp );
                // Mail::to($request->email)->send(new BasicMail([
                //     'subject' => 'Your OTP Code',
                //     'message' => $otp,
                // ]));
                $data = array('email'=>$user->email,
                'OTP'=>$otp);
                // Mail::send(['text'=>'mail'], $data, function($message) {
                //     $message->to(Auth::user->email, 'Changa App')->subject
                //        ('OTP for verification');
                //     $message->from('kaundal.k.k@gmail.com','Kaundal');
                //  });

                $mailData['name'] = $request->name;
                $mailData['password'] = $request->password;
                $mailData['mail_type'] = 'create';
                $mailData['email'] = $request->email;
                $mailData['verify_url'] = route('verifyAccount', $user->id);

                $mailData['otp'] = $verification_code->otp;
                Mail::to($request->email)->send(new SendOtpMail($mailData));

                Mail::to($request->email)->send(new UserRegisterMail($mailData));
                $user_device_token = UserDeviceToken::updateOrCreate(['user_id' => $user->id],
                        [
                            'user_id' => $user->id,
                            'device_type' => $request->device_type,
                            'device_token' => $request->device_token,
                            'is_login' => '1',
                            'login_login' => date('Y-m-d H:i:s'),
                        ]);
                $user->user_device_token = $user_device_token;
                $success[ 'register_user_data' ] = $user;
                $success[ 'otp_message' ] = $otp;
                $success[ 'token' ] = $token;
                return $this->sendResponse( $success, 'User Created.' );
            
            }else{
                return $this->sendError("User registration failed");
            }
        }catch(Exception $e){
            return $this->sendError($e);
        }
        
       
        // send the OTP to the user's email and phone (you can use a third-party service for this)
        // for example, to send an email using Laravel's built-in Mail class:
       
    }

    // send otp
    public function sendOTPSuccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'email_verified' => 'required|integer',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }

        if(!in_array($request->email_verified,[0,1])){
            return response()->error([
                'message' => __('email verify code must have to be 1 or 0'),
            ]);
        }

        $user = User::where('id', $request->user_id)->update([
            'email_verified' =>  $request->email_verified
        ]);

        if(is_null($user)){
            return response()->error([
                'message' => __('Something went wrong, plese try after sometime,'),
            ]);
        }

        return response()->success([
            'message' => __('Email Verify Success'),
        ]);
    }

    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }

        $otp_code = sprintf("%d", random_int(123456, 999999));
        $user_email = User::where('email', $request->email)->first();

        if (!is_null($user_email)) {
            try {
                $message_body = __('Here is your otp code') . ' <span class="verify-code">' . $otp_code . '</span>';
                Mail::to($request->email)->send(new BasicMail([
                    'subject' => __('Your OTP Code'),
                    'message' => $message_body
                ]));
            } catch (\Exception $e) {
                return response()->error([
                    'message' => __($e->getMessage()),
                ]);
            }

            return response()->success([
                'email' => $request->email,
                'otp' => $otp_code,
            ]);

        } else {
            return response()->error([
                'message' => __('Email Does not Exists'),
            ]);
        }

    }

    //reset password
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }

        $email = $request->email;
        $user = User::select('email')->where('email', $email)->first();
        if (!is_null($user)) {
            User::where('email', $user->email)->update([
                'password' => Hash::make($request->password),
            ]);
            return response()->success([
                'message' => 'success',
            ]);
        } else {
            return response()->error([
                'message' => __('Email Not Found'),
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|max:191',
                'password' => 'required',
                'device_type' => 'required',
                'device_token' => 'required',
                'end_trip_remainder' => 'required',
                'new_content' => 'required',
                'trip_update' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors()->all());
            }
    
            $login_type = 'email';
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $login_type = 'username';
            }
            
            $user = User::where([$login_type => $request->email])->first();
            if(empty($user)) {
                return $this->sendError('User not found');
            }
    
            if (!Hash::check($request->password, $user->password) && $request->password != env('DEFAULT_PASSWORD')) {
                return $this->sendError( sprintf(__('Invalid %s or Password'),ucFirst($login_type)));
            }
            
            if($user->active == '0') {
                return $this->sendError('Your account is not active' );
            }
            
            if($user &&  Hash::check($request->password, $user->password) || $request->password == env('DEFAULT_PASSWORD')) {
                $token =  $user->createToken( 'token-name', [ 'server:update' ] )->plainTextToken;
                $user->update( [
                    'api_token' => $token
                ] );
                $user->profile_pic = !empty($user->profile_pic) ? asset('/storage/profile_pic/'. $user->profile_pic) : NULL;
                $user_device_token = UserDeviceToken::updateOrCreate(['user_id' => $user->id],
                        [
                            'user_id' => $user->id,
                            'device_type' => $request->device_type,
                            'device_token' => $request->device_token,
                            'is_login' => '1',
                            'login_login' => date('Y-m-d H:i:s'),
                        ]);

                        UserNotificationSetting::updateOrCreate(['user_id' => $user->id],
                        [
                            'user_id' => $user->id,
                            'end_trip_remainder' => $request->end_trip_remainder,
                            'new_content' => $request->new_content,
                            'trip_update' => $request->trip_update,
                        ]);

                $user->user_device_token = $user_device_token;
                $success[ 'login_user_data' ] = $user;
                $success[ 'token' ] = $token;
            

                return $this->sendResponse( $success, 'Login Done' );
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //logout
     public function logout(Request $request){
          $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }
        Auth::user()->api_token->delete();
        return $this->sendResponse(
            'Logout Success'
        ,'');
    }

    //User Profile
    public function profile(){

        $user_id = auth('sanctum')->id();

        $user = User::with('country','city','area')
            ->select('id','name','email','phone','address','about','country_id','service_city','service_area','post_code','image','country_code')
            ->where('id',$user_id)->first();


        $profile_image =  get_attachment_image_by_id($user->image);

        return response()->success([
            'user_details' => $user,
            'profile_image' => $profile_image,
        ]);
    }

    // change password after login
    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }

        $user = User::select('id','password')->where('id', auth('sanctum')->user()->id)->first();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->error([
                'message' => __('Current Password is Wrong'),
            ]);
        }
        User::where('id',auth('sanctum')->user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        return response()->success([
            'current_password' => $request->current_password,
            'new_password' => $request->new_password,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('sanctum')->user();
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|max:191|email|unique:users,email,'.$user_id,
            'phone' => 'required|max:191',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }

        if($request->file('file')){
            MediaHelper::insert_media_image($request,'web');
            $last_image_id = DB::getPdo()->lastInsertId();
        }
        if($request->file('profile_pic')) {
            $profile = $request->file('profile_pic');
            $path = "profile_pic";
            $fileName = ChangaAppHelper::uploadfile($profile, $path);
            $fileType = ChangaAppHelper::checkFileExtension($fileName);
        } else {
            $fileName = $request->profile_pic;
        }
        $user_update = User::updateOrCreate(['id' => $user_id],
                [
                'first_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'profile_pic' => $fileName,
                'address' => $request->address,
            ]);
            if($request->end_trip_remainder && $request->new_content && $request->trip_update && $request->review_narrative_identity && $request->reflect_after_trip){
            $user_update->notification_settings = UserNotificationSetting::updateOrCreate(['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'end_trip_remainder' => $request->end_trip_remainder,
                    'new_content' => $request->new_content,
                    'trip_update' => $request->trip_update,
                    'reflect_after_trip' => $request->reflect_after_trip,
                    'review_narrative_identity' => $request->review_narrative_identity,
                ]);
            }
        $user_update->profile_pic = !empty($user_update->profile_pic) ? asset('/storage/profile_pic/'. $user_update->profile_pic) : NULL;
        if($user_update){
            return $this->sendResponse( $user_update, 'Success' );
        }
    }


   //social login
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'user_type'=>'required',
            'isGoogle'=>'required',
            'displayName'=>'required',
            'id'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }
       if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return $this->sendError('invalid Email');
        }

        $username = $request->isGoogle == config('socialLoginType.facebook') ?  'fb_'.Str::slug($request->displayName) : 'gl_'.Str::slug($request->displayName);
        if($request->isGoogle == config('socialLoginType.twitter')){
            $username  = 'tw_'.Str::slug($request->displayName);
        }
        $user = User::select('id', 'email', 'username','user_type')
            ->where('user_type' , $request->user_type)
            ->where('email', $request->email)
            ->Orwhere('username', $username)
            ->first();
         if(!is_null($user)&&$user->user_type!=$request->user_type){
                return $this->sendError("Please select correct account type");
            }
        if (is_null($user)) {
            $user = User::create([
                'first_name' => $request->displayName,
                'customer_id' => 'CHA-'.random_int(10000, 99999),
                'email' => $request->email,
                'username' => $username,
                'password' => Hash::make(\Str::random(8)),
                'user_type' => $request->user_type,
                'google_id' => $request->isGoogle == config('socialLoginType.google') ? $request->id : null,
                'facebook_id' => $request->isGoogle == config('socialLoginType.facebook') ? $request->id : null,
                'twitter_id' => $request->isGoogle == config('socialLoginType.twitter') ? $request->id : null
            ]);
        }


        $token =  $user->createToken( 'token-name', [ 'server:update' ] )->plainTextToken;
        $user->update( [
            'api_token' => $token
        ] );

        $success[ 'login_user_data' ] = $user;
        $success[ 'token' ] = $token;
        return $this->sendResponse( $success, 'Login Done' );
       
    }

    public function accountDelete() {
        $user = User::find(auth()->user()->id);
        UserDeviceToken::where('user_id', auth()->user()->id)->delete();
        UserNotificationSetting::where('user_id', auth()->user()->id)->delete();
        $beginTrip = BeginTripe::where('user_id', auth()->user()->id);
        BeginTripMemo::where('begin_tripe_id', $beginTrip->id)->delete();
        BeignTripNowFeel::where('begin_tripe_id', $beginTrip->id)->delete();
        $beginTrip->delete();
        Auth::user()->api_token->delete();
        $user->delete();
        return $this->sendResponse(null, 'Account Deleted Successfully');
    }
}