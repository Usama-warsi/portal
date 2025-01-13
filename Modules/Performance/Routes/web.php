<?php
use Illuminate\Support\Facades\Route;
use Modules\Performance\Http\Controllers\GoalTypeController;
use Modules\Performance\Http\Controllers\GoalTrackingController;
use Modules\Performance\Http\Controllers\PerformanceTypeController;
use Modules\Performance\Http\Controllers\CompetenciesController;
use Modules\Performance\Http\Controllers\IndicatorController;
use Modules\Performance\Http\Controllers\EmployeeController;
use Modules\Performance\Http\Controllers\AppraisalController;

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
//Goal type
Route::group(['middleware' => 'PlanModuleCheck:Performance'], function ()
 {
    Route::resource('goaltype', GoalTypeController::class)->middleware(
        [
            'auth',

        ]
    );

    //GoalTracking
    Route::resource('goaltracking', GoalTrackingController::class)->middleware(
        [
            'auth',

        ]
    );
    Route::get('goaltracking-grid', [GoalTrackingController::class,'grid'])->name('goaltracking.grid')->middleware(
        [
            'auth',

        ]
    );

    //performanceType
    Route::resource('performanceType', PerformanceTypeController::class)->middleware(
        [
            'auth',
        ]
    );

    //competencies
    Route::resource('competencies', CompetenciesController::class)->middleware(
        [
            'auth',
        ]
    );

    //indicator
    Route::resource('indicator', IndicatorController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::post('employee/json', [EmployeeController::class,'json'])->name('employee.json')->middleware(
        [
            'auth',
        ]
    );

    //appraisal
    Route::resource('appraisal', AppraisalController::class)->middleware(
        [
            'auth',
        ]
    );
    Route::post('/appraisals', [AppraisalController::class,'empByStar'])->name('empByStar')->middleware(['auth']);
    Route::post('/appraisals1', [AppraisalController::class,'empByStar1'])->name('empByStar1')->middleware(['auth']);
    Route::post('/getemployee', [AppraisalController::class,'getemployee'])->name('getemployee');
});
