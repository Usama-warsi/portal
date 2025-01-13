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
use Modules\ActivityLog\Http\Controllers\ActivityLogController;

// Route::prefix('activitylog')->group(function() {
//     Route::get('/', 'ActivityLogController@index');
// });
Route::group(['middleware' => 'PlanModuleCheck:ActivityLog'], function ()
{
    Route::resource('activitylog', ActivityLogController::class)->middleware(
        [
            'auth',
        ]
    );

});
