<?php

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
use Illuminate\Support\Facades\Route;
use Modules\GoogleMeet\Http\Controllers\GoogleMeetController;

Route::group(['middleware' => 'PlanModuleCheck:GoogleMeet'], function ()
 {

    Route::prefix('googlemeet')->group(function () {
        Route::get('/', [GoogleMeetController::class, 'index'])
            ->name('googlemeet.index')
            ->middleware('auth');

        Route::get('/create', [GoogleMeetController::class, 'create'])
            ->name('googlemeet.create')
            ->middleware('auth');

        Route::post('/store', [GoogleMeetController::class, 'store'])
            ->name('googlemeet.store')
            ->middleware('auth');

        Route::get('/show/{id}', [GoogleMeetController::class, 'show'])
            ->name('googlemeet.show')
            ->middleware('auth');

        Route::delete('/destory/{id}', [GoogleMeetController::class, 'destroy'])
            ->name('googlemeet.destory')
            ->middleware('auth');

        Route::get('/calender', [GoogleMeetController::class, 'calender'])
            ->name('googlemeet.calender')
            ->middleware('auth');

        // Route::get('/setting', [GoogleMeetController::class, 'setting'])
        //     ->name('googlemeet.setting.index')
        //     ->middleware('auth');

        Route::post('/setting/store', [GoogleMeetController::class, 'setting'])
            ->name('googlemeet.setting.store')
            ->middleware('auth');
    });

    Route::get('/auth/googlemeet', [GoogleMeetController::class, 'redirectToGoogle'])
        ->name('auth.googlemeet');

    Route::get('/oauth', [GoogleMeetController::class, 'handleGoogleCallback'])
        ->name('auth.googlemeet.callback');
        
});