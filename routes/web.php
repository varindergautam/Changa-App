<?php

use App\Http\Controllers\web\Auth\LoginController;
use App\Http\Controllers\web\Auth\RegistrationController;
use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\ProfileController;
use App\Http\Controllers\web\UsersController;
use App\Http\Controllers\web\CustomerController;
use App\Http\Controllers\web\MediatorController;
use App\Http\Controllers\web\MediatoryCategoryController;
use App\Http\Controllers\web\MediateTagsController;
use App\Http\Controllers\web\MediateController;
use App\Http\Controllers\web\LearnTagsController;
use App\Http\Controllers\web\LearnController;
use App\Http\Controllers\web\ListenTagsController;
use App\Http\Controllers\web\ListenController;
use App\Http\Controllers\web\TherapyTagsController;
use App\Http\Controllers\web\TherapyController;
use App\Http\Controllers\web\GuideCategoryController;
use App\Http\Controllers\web\GuideController;
use App\Http\Controllers\web\FellingController;
use App\Http\Controllers\web\IntentionController;
use App\Http\Controllers\web\VisualController;
use App\Http\Controllers\web\AudioTagController;
use App\Http\Controllers\web\AudioController;
use App\Http\Controllers\web\GroupController;
use App\Http\Controllers\web\TripJournalController;
use App\Models\Group;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $groups = Group::all();
    return view('chat.group', compact('groups'));
});


Route::get('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth'); 

/**
 * Dashboard Controller for all dashboard route 
 */
Route::controller(DashboardController::class)->group(function(){
    Route::get('dashboard','index')->name('dashboard');
    // Route::get('users','users')->name('users');
    
    // Route::get('users/edit/{id}','edit_user')->name('edit_user');
    // Route::post('users/update','update_user')->name('update_user');
    // Route::get('users/view','view_user')->name('view_user');
    // Route::get('users/add','add')->name('add');
   

})->middleware("web");

/**
 * User Controller for login registration and forgot password or reset password
 */

 Route::controller(UsersController::class)->group(function(){
    Route::get('login','login')->name('login');
    Route::post('check_login','check_login')->name('check_login');
    Route::get('registration','registration')->name('registration');
    Route::get('verifyAccount/{id}','verifyAccount')->name('verifyAccount');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/chat/{group_id}', [App\Http\Controllers\ChatTestController::class, 'chat'])->name('chat');
    
    Route::controller(CustomerController::class)
    ->prefix('users')
    ->group(function(){
        Route::get('/','index')->name('users');
        Route::get('create','create')->name('create.user');
        Route::post('update','update')->name('update.user');
        Route::get('edit/{id}','edit')->name('edit.user');
        Route::get('view/{id}','show')->name('show.user');
        Route::get('modal/{id}','modal')->name('modal.user');
        Route::get('delete/{id}','destroy')->name('delete.user');
        Route::get('status/{id}/{status}','status')->name('status.user');
        Route::get('/testEmail','testEmail')->name('testEmail');
        

    });

    Route::controller(MediatorController::class)
    ->prefix('mediators')
    ->group(function(){
        Route::get('/','index')->name('mediators');
        Route::get('create','create')->name('create.mediators');
        Route::post('update','update')->name('update.mediators');
        Route::get('edit/{id}','edit')->name('edit.mediators');
        Route::get('view/{id}','show')->name('show.mediators');
        Route::get('modal/{id}','modal')->name('modal.mediators');
        Route::get('delete/{id}','destroy')->name('delete.mediators');
        Route::get('status/{id}/{status}','status')->name('status.mediators');
    });

    Route::controller(MediateTagsController::class)
    ->prefix('mediate_tags')
    ->group(function(){
        Route::get('/','index')->name('mediate_tags');
        Route::get('create','create')->name('create.mediate_tags');
        Route::post('update','update')->name('update.mediate_tags');
        Route::get('edit/{id}','edit')->name('edit.mediate_tags');
        Route::get('view/{id}','show')->name('show.mediate_tags');
        Route::get('modal/{id}','modal')->name('modal.mediate_tags');
        Route::get('delete/{id}','destroy')->name('delete.mediate_tags');
    });

    Route::controller(MediateController::class)
    ->prefix('mediates')
    ->group(function(){
        Route::get('/','index')->name('mediates');
        Route::get('create','create')->name('create.mediates');
        Route::post('update','update')->name('update.mediates');
        Route::get('edit/{id}','edit')->name('edit.mediates');
        Route::get('view/{id}','show')->name('show.mediates');
        Route::get('modal/{id}','modal')->name('modal.mediates');
        Route::get('delete/{id}','destroy')->name('delete.mediates');
        Route::get('status/{id}/{status}','status')->name('status.mediates');
    });

    Route::controller(LearnTagsController::class)
    ->prefix('learn_tags')
    ->group(function(){
        Route::get('/','index')->name('learn_tags');
        Route::get('create','create')->name('create.learn_tags');
        Route::post('update','update')->name('update.learn_tags');
        Route::get('edit/{id}','edit')->name('edit.learn_tags');
        Route::get('view/{id}','show')->name('show.learn_tags');
        Route::get('modal/{id}','modal')->name('modal.learn_tags');
        Route::get('delete/{id}','destroy')->name('delete.learn_tags');
    });

    Route::controller(LearnController::class)
    ->prefix('learns')
    ->group(function(){
        Route::get('/','index')->name('learns');
        Route::get('create','create')->name('create.learns');
        Route::post('update','update')->name('update.learns');
        Route::get('edit/{id}','edit')->name('edit.learns');
        Route::get('view/{id}','show')->name('show.learns');
        Route::get('modal/{id}','modal')->name('modal.learns');
        Route::get('delete/{id}','destroy')->name('delete.learns');
        Route::get('status/{id}/{status}','status')->name('status.learns');
    });

    Route::controller(ListenTagsController::class)
    ->prefix('listen_tags')
    ->group(function(){
        Route::get('/','index')->name('listen_tags');
        Route::get('create','create')->name('create.listen_tags');
        Route::post('update','update')->name('update.listen_tags');
        Route::get('edit/{id}','edit')->name('edit.listen_tags');
        Route::get('view/{id}','show')->name('show.listen_tags');
        Route::get('modal/{id}','modal')->name('modal.listen_tags');
        Route::get('delete/{id}','destroy')->name('delete.listen_tags');
    });

    Route::controller(ListenController::class)
    ->prefix('listens')
    ->group(function(){
        Route::get('/','index')->name('listens');
        Route::get('create','create')->name('create.listens');
        Route::post('update','update')->name('update.listens');
        Route::get('edit/{id}','edit')->name('edit.listens');
        Route::get('view/{id}','show')->name('show.listens');
        Route::get('modal/{id}','modal')->name('modal.listens');
        Route::get('delete/{id}','destroy')->name('delete.listens');
        Route::get('status/{id}/{status}','status')->name('status.listens');
    });

    Route::controller(TherapyTagsController::class)
    ->prefix('therapy_tags')
    ->group(function(){
        Route::get('/','index')->name('therapy_tags');
        Route::get('create','create')->name('create.therapy_tags');
        Route::post('update','update')->name('update.therapy_tags');
        Route::get('edit/{id}','edit')->name('edit.therapy_tags');
        Route::get('view/{id}','show')->name('show.therapy_tags');
        Route::get('modal/{id}','modal')->name('modal.therapy_tags');
        Route::get('delete/{id}','destroy')->name('delete.therapy_tags');
    });

    Route::controller(TherapyController::class)
    ->prefix('therapy')
    ->group(function(){
        Route::get('/','index')->name('therapy');
        Route::get('create','create')->name('create.therapy');
        Route::post('update','update')->name('update.therapy');
        Route::get('edit/{id}','edit')->name('edit.therapy');
        Route::get('view/{id}','show')->name('show.therapy');
        Route::get('modal/{id}','modal')->name('modal.therapy');
        Route::get('delete/{id}','destroy')->name('delete.therapy');
        Route::get('status/{id}/{status}','status')->name('status.therapy');
    });

    Route::controller(MediatoryCategoryController::class)
    ->prefix('mediator_category')
    ->group(function(){
        Route::get('/','index')->name('mediator_category');
        Route::get('create','create')->name('create.mediator_category');
        Route::post('update','update')->name('update.mediator_category');
        Route::get('edit/{id}','edit')->name('edit.mediator_category');
        Route::get('view/{id}','show')->name('show.mediator_category');
        Route::get('modal/{id}','modal')->name('modal.mediator_category');
        Route::get('delete/{id}','destroy')->name('delete.mediator_category');
    });

    Route::controller(GuideCategoryController::class)
    ->prefix('guide_category')
    ->group(function(){
        Route::get('/','index')->name('guide_category');
        Route::get('create','create')->name('create.guide_category');
        Route::post('update','update')->name('update.guide_category');
        Route::get('edit/{id}','edit')->name('edit.guide_category');
        Route::get('view/{id}','show')->name('show.guide_category');
        Route::get('delete/{id}','destroy')->name('delete.guide_category');
    });

    Route::controller(GuideController::class)
    ->prefix('guide')
    ->group(function(){
        Route::get('/','index')->name('guide');
        Route::get('create','create')->name('create.guide');
        Route::post('update','update')->name('update.guide');
        Route::get('edit/{id}','edit')->name('edit.guide');
        Route::get('view/{id}','show')->name('show.guide');
        Route::get('delete/{id}','destroy')->name('delete.guide');
        Route::get('status/{id}/{status}','status')->name('status.guide');
    });

    Route::controller(FellingController::class)
    ->prefix('fellings')
    ->group(function(){
        Route::get('/','index')->name('fellings');
        Route::get('create','create')->name('create.fellings');
        Route::post('update','update')->name('update.fellings');
        Route::get('edit/{id}','edit')->name('edit.fellings');
        Route::get('view/{id}','show')->name('show.fellings');
        Route::get('delete/{id}','destroy')->name('delete.fellings');
        Route::get('status/{id}/{status}','status')->name('status.fellings');
    });

    Route::controller(IntentionController::class)
    ->prefix('intentions')
    ->group(function(){
        Route::get('/','index')->name('intentions');
        Route::get('create','create')->name('create.intentions');
        Route::post('update','update')->name('update.intentions');
        Route::get('edit/{id}','edit')->name('edit.intentions');
        Route::get('view/{id}','show')->name('show.intentions');
        Route::get('delete/{id}','destroy')->name('delete.intentions');
        Route::get('status/{id}/{status}','status')->name('status.intentions');
    });

    Route::controller(VisualController::class)
    ->prefix('visual')
    ->group(function(){
        Route::get('/','index')->name('visual');
        Route::get('create','create')->name('create.visual');
        Route::post('update','update')->name('update.visual');
        Route::get('edit/{id}','edit')->name('edit.visual');
        Route::get('view/{id}','show')->name('show.visual');
        Route::get('delete/{id}','destroy')->name('delete.visual');
        Route::get('status/{id}/{status}','status')->name('status.visual');
    });

    Route::controller(AudioTagController::class)
    ->prefix('audio_tags')
    ->group(function(){
        Route::get('/','index')->name('audio_tags');
        Route::get('create','create')->name('create.audio_tags');
        Route::post('update','update')->name('update.audio_tags');
        Route::get('edit/{id}','edit')->name('edit.audio_tags');
        Route::get('view/{id}','show')->name('show.audio_tags');
        Route::get('delete/{id}','destroy')->name('delete.audio_tags');
        Route::get('status/{id}/{status}','status')->name('status.audio_tags');
    });

    Route::controller(AudioController::class)
    ->prefix('audio')
    ->group(function(){
        Route::get('/','index')->name('audio');
        Route::get('create','create')->name('create.audio');
        Route::post('update','update')->name('update.audio');
        Route::get('edit/{id}','edit')->name('edit.audio');
        Route::get('view/{id}','show')->name('show.audio');
        Route::get('delete/{id}','destroy')->name('delete.audio');
        Route::get('status/{id}/{status}','status')->name('status.audio');
    });

    Route::controller(GroupController::class)
    ->prefix('group')
    ->group(function(){
        Route::get('/','index')->name('group');
        Route::get('create','create')->name('create.group');
        Route::post('update','update')->name('update.group');
        Route::get('edit/{id}','edit')->name('edit.group');
        Route::get('view/{id}','show')->name('show.group');
        Route::get('delete/{id}','destroy')->name('delete.group');
        Route::get('status/{id}/{status}','status')->name('status.group');
    });

    Route::controller(TripJournalController::class)
    ->prefix('trip_journal')
    ->group(function(){
        Route::get('/','index')->name('trip_journal');
        Route::get('create','create')->name('create.trip_journal');
        Route::post('update','update')->name('update.trip_journal');
        Route::get('edit/{id}','edit')->name('edit.trip_journal');
        Route::get('view/{id}','show')->name('show.trip_journal');
        Route::get('delete/{id}','destroy')->name('delete.trip_journal');
        Route::get('status/{id}/{status}','status')->name('status.trip_journal');
    });
});

// Route::get('registration', [RegistrationController::class, 'index'])->name('registration');
// Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile/update', [ProfileController::class, 'update'])->name('update.profile');


