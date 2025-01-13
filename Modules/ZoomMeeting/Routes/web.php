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
use Modules\ZoomMeeting\Http\Controllers\ZoomMeetingController;

Route::group(['middleware' => 'PlanModuleCheck:ZoomMeeting'], function ()
{
    Route::middleware(['auth','verified'])->group(function () {

        Route::prefix('zoom-meeting')->group(function() {
            Route::get('/', 'ZoomMeetingController@index');

        Route::get('/', [ZoomMeetingController::class ,'index'])->name('zoom-meeting.index');
        Route::get('/create', [ZoomMeetingController::class,'create'])->name('zoom-meeting.create');
        Route::post('/store', [ZoomMeetingController::class,'store'])->name('zoom-meeting.store');
        Route::get('/show/{id}', [ZoomMeetingController::class,'show'])->name('zoom-meeting.show');
        Route::delete('/destory/{id}', [ZoomMeetingController::class,'destroy'])->name('zoom-meeting.destory');
        Route::get('/calender', [ZoomMeetingController::class,'calender'])->name('zoom-meeting.calender');
        Route::post('/setting/store', [ZoomMeetingController::class,'setting'])->name('zoom-meeting.setting.store');

        });
    });
});
