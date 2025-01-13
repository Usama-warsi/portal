<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\TrainerController;
use Modules\Training\Http\Controllers\TrainingController;
use Modules\Training\Http\Controllers\TrainingTypeController;

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


Route::group(['middleware' => 'PlanModuleCheck:Training'], function ()
 {
    Route::post('training/status', [TrainingController::class, 'updateStatus'])->name('training.status')->middleware(
        [
            'auth',

        ]
    );
    Route::resource('training', TrainingController::class)->middleware(
        [
            'auth',

        ]
    );

    //Trainer
    Route::resource('trainer', TrainerController::class)->middleware(
        [
            'auth',
        ]
    );


    //Trainingtype
    Route::resource('trainingtype', TrainingTypeController::class)->middleware(
        [
            'auth',

        ]
    );
});
