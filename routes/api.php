<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LearnController;
use App\Http\Controllers\API\ListenController;
use App\Http\Controllers\API\TherapyController;
use App\Http\Controllers\API\MediateController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\AuthOtpController;
use App\Http\Controllers\API\FavouriteController;
use App\Http\Controllers\API\GuideController;
use App\Http\Controllers\API\BeginTripController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('socialLogin', [AuthController::class, 'socialLogin']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forgotPassword', [AuthController::class, 'forgotPassword']);

// Route::get('otp_verification', [AuthController::class, 'otpVerification']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('otp_verification', [AuthController::class, 'otpVerification']);
    Route::post('resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('updateProfile', [AuthController::class, 'updateProfile']);
    Route::get('accountDelete', [AuthController::class, 'accountDelete']);

    Route::controller(LearnController::class)
    ->prefix('learn')
    ->group(function(){
        Route::get('/','learnTag')->name('tag.learn');
        Route::get('learn','learn')->name('learn');
        Route::post('store','store')->name('learn.store');
        Route::post('destroy','destroy')->name('learn.destroy');
    });

    Route::controller(ListenController::class)
    ->prefix('listen')
    ->group(function(){
        Route::get('/','listenTag')->name('tag.listen');
        Route::get('listen','listen')->name('listen');
        Route::post('store','store')->name('listen.store');
        Route::post('destroy','destroy')->name('listen.destroy');
    });

    Route::controller(TherapyController::class)
    ->prefix('therapy')
    ->group(function(){
        Route::get('/','therapyTag')->name('tag.therapy');
        Route::get('therapy','therapy')->name('therapy');
        Route::post('store','store')->name('therapy.store');
        Route::post('destroy','destroy')->name('therapy.destroy');
    });

    Route::controller(MediateController::class)
    ->prefix('mediate')
    ->group(function(){
        Route::get('/','mediateTag')->name('tag.mediate');
        Route::get('mediate','mediate')->name('mediate');
        Route::post('store','store')->name('mediate.store');
        Route::post('destroy','destroy')->name('mediate.destroy');
    });

    Route::controller(ChatController::class)
    ->prefix('chat')
    ->group(function(){
        Route::post('/','sendMessage')->name('chat');
        Route::get('/groupList','groupList')->name('groupList');
        Route::post('/recieve','recieve')->name('recieve');
    });

    Route::controller(FavouriteController::class)
    ->prefix('favourite')
    ->group(function(){
        Route::post('/','markFavourite')->name('markFavourite');
    });

    Route::controller(GuideController::class)
    ->prefix('guide')
    ->group(function(){
        Route::get('/','index')->name('guide');
        Route::get('/category','category')->name('guide.category');
    });

    Route::controller(BeginTripController::class)
    ->prefix('begin_trip')
    ->group(function(){
        Route::get('/','index')->name('index');
        Route::post('/store','store')->name('store');
        Route::post('/delete','destroy')->name('destroy');
        Route::post('/felling_now','feelingNow')->name('feelingNow');
        Route::get('/fellings','fellings')->name('fellings');
        Route::get('/trip_journal','tripJournal')->name('tripJournal');
        Route::get('/intentions','intentions')->name('intentions');
        Route::get('/visual','visual')->name('visual');
        Route::get('/audio','audio')->name('audio');
        Route::get('/audio-tag','audioTag')->name('audioTag');
        Route::get('/history','history')->name('history');
    });
});


// Route::controller(AuthOtpController::class)->group(function(){
//     Route::get('/otp/login', 'login')->name('otp.login');
//     Route::post('/otp/generate', 'generate')->name('otp.generate');
//     Route::get('/otp/verification/{user_id}', 'verification')->name('otp.verification');
//     Route::post('/otp/login', 'loginWithOtp')->name('otp.getlogin');
// });

// Route::get('/auth/google/redirect', function () {
//     return Socialite::driver('google')->redirect();
// });
 
// Route::get('/auth/facebook/callback', function () {
//     $user = Socialite::driver('facebook')->user();
 
//     // $user->token
// });
