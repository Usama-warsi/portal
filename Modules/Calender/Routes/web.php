<?php

use Illuminate\Support\Facades\Route;
use Modules\Calender\Http\Controllers\CalenderController;





Route::group(['middleware' => 'PlanModuleCheck:Calender'], function () {
    Route::prefix('calendar')->group(function () {
        Route::get('/calendars',[CalenderController::class,'index'])->name('calender.index');
        Route::post('/google-calendar','CalenderController@saveGoogleCalenderSettings')->name('google.calender.settings');
        Route::any('event/get_event_data', 'CalenderController@get_event_data')->name('event.get_event_data');
    });
});






