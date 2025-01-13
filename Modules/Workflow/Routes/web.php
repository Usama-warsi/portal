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
use Modules\Workflow\Http\Controllers\WorkflowController;

Route::group(['middleware' => 'PlanModuleCheck:Workflow'], function () {

    Route::middleware(['auth','verified'])->group(function () {

        Route::resource('workflow', WorkflowController::class);

        Route::post('/workflow/getfielddata', [WorkflowController::class,'getfielddata'])->name('workflow.getfielddata');
        Route::post('/workflow/getcondition', [WorkflowController::class ,'getcondition'])->name('workflow.getcondition');
        Route::post('/workflow/attribute', [WorkflowController::class ,'attribute'])->name('workflow.attribute');
        Route::post('/workflow/modules', [WorkflowController::class ,'module'])->name('workflow.modules');
        Route::post('/workflow/event', [WorkflowController::class ,'event'])->name('workflow.event');
    });
});
