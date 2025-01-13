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
use Modules\Rotas\Http\Controllers\AvailabilityController;
use Modules\Rotas\Http\Controllers\BranchesController;
use Modules\Rotas\Http\Controllers\DashboardController;
use Modules\Rotas\Http\Controllers\DepartmentsController;
use Modules\Rotas\Http\Controllers\DesignationsController;
use Modules\Rotas\Http\Controllers\RotaController;
use Modules\Rotas\Http\Controllers\RotaemployeeController;
use Modules\Rotas\Http\Controllers\RotaleaveController;
use Modules\Rotas\Http\Controllers\RotaleaveTypeController;

Route::prefix('rotas')->group(function () {
    Route::get('/', 'RotasController@index');
});

Route::group(['middleware' => 'PlanModuleCheck:Rotas'], function () {
    Route::middleware(['auth', 'verified'])->group(function () {


        Route::get('dashboard/rotas', [DashboardController::class, 'index'])->name('rotas.dashboard');
        Route::get('dashboard/day', [DashboardController::class, 'dayView'])->name('rota.dashboard.day');
        Route::post('dashboard/dayview_filter', [DashboardController::class, 'dayview_filter'])->name('rota.dashboard.dayview_filter');
        Route::get('dashboard/user-view', [DashboardController::class, 'userView'])->name('rota.dashboard.user-view');
        Route::post('dashboard/userviewfilter', [DashboardController::class, 'userviewfilter'])->name('rota.dashboard.userviewfilter');

        Route::post('/dashboard/location_filter', [DashboardController::class, 'location_filter'])->name('rota.dashboard.location_filter');

        Route::post('rotas/setting/save', [RotaController::class,'companyworkschedule'])->name('rotas.setting.save');


        Route::post('/rota/week_sheet', [RotaController::class,'week_sheet'])->name('rotas.week_sheet');
        Route::post('hideavailability', [RotaController::class,'hideavailability'])->name('hideavailability');
        Route::post('hidedayoff', [RotaController::class,'hidedayoff'])->name('hidedayoff');
        Route::post('hideleave', [RotaController::class,'hideleave'])->name('hideleave');
        Route::post('/rota/clear_week', [RotaController::class,'clear_week'])->name('rotas.clear_week');
        Route::post('/rota/add_dayoff', [RotaController::class,'add_dayoff'])->name('rotas.add_dayoff');
        Route::post('/rota/send_email_rotas', [RotaController::class,'send_email_rotas'])->name('rotas.send_email_rotas');
        Route::post('copy_week_sheet', [RotaController::class,'copy_week_sheet'])->name('copy.week.sheet');
        Route::post('/rota/publish_week', [RotaController::class,'publish_week'])->name('rotas.publish_week');
        Route::post('/rota/un_publish_week', [RotaController::class,'un_publish_week'])->name('rotas.un_publish_week');
        Route::post('/rota/shift_copy', [RotaController::class,'shift_copy'])->name('rotas.shift_copy');
        Route::get('/rota/print_rotas_popup', [RotaController::class,'print_rotas_popup'])->name('rotas.print_rotas_popup');

        Route::post('/rota/print', [RotaController::class,'printrotasInvoice'])->name('rotas.print');

        Route::get('/rota/share_rotas_popup', [RotaController::class,'share_rotas_popup'])->name('rotas.share_popup');

        Route::post('/slug-match', [RotaController::class,'slug_match'])->name('slug.match');
        Route::post('/rota-date-change', [RotaController::class,'rota_date_change'])->name('rota.date.change');

        Route::get('/rota/shift_cancel/{id}', [RotaController::class,'shift_cancel'])->name('rotas.shift.cancel');
        Route::post('/rota/shift_disable', [RotaController::class,'shift_disable'])->name('rotas.shift.disable');
        Route::post('rota/shift_disable_reply', [RotaController::class,'shift_disable_reply'])->name('rotas.shift.disable.reply');

        Route::get('/rota/shift_disable_response/{id}', [RotaController::class,'shift_disable_response'])->name('rotas.shift.response');

        Route::resource('/rota', RotaController::class);
        Route::post('rotas/setting/store', [RotaController::class,'setting'])->name('rotas.setting.store');
        Route::resource('/availabilitie', AvailabilityController::class);


        Route::any('workschedule/{id?}', [RotaController::class,'workscheduleData'])->name('rota.workschedule');
        Route::post('workschedule/{id?}', [RotaController::class,'workscheduleDataSave'])->name('rota.workschedule.save');


        Route::get('rotas_data', [RotaController::class,'rotas_filter'])->name('rotas.filter');
        // branch

        Route::resource('branches', BranchesController::class);
        Route::get('branchesnameedit', [BranchesController::class,'BranchesNameEdit'])->name('branchesname.edit');
        Route::post('branch-setting', [BranchesController::class,'saveBranchesName'])->name('branchesname.update');

        // department
        Route::resource('departments', DepartmentsController::class);
        Route::get('departmentsnameedit', [DepartmentsController::class,'DepartmentsNameEdit'])->name('departmentsname.edit');
        Route::post('department-settings', [DepartmentsController::class,'saveDepartmentsName'])->name('departmentsname.update');

        // designation
        Route::resource('designations', DesignationsController::class);
        Route::get('designationsnameedit', [DesignationsController::class,'DesignationsNameEdit'])->name('designationsname.edit');
        Route::post('designation-settings', [DesignationsController::class,'saveDesignationsName'])->name('designationsname.update');

        // leave type and leave

        Route::resource('leavestype', RotaleaveTypeController::class);
        Route::get('rota-leave/{id}/action', [RotaleaveController::class,'action'])->name('rota.leave.action');
        Route::post('rota-leave/changeaction', [RotaleaveController::class,'changeaction'])->name('rota.leave.changeaction');
        Route::post('rota-leave/jsoncount', [RotaleaveController::class,'jsoncount'])->name('rota.leave.jsoncount');
        Route::resource('rota-leave', RotaleaveController::class);

        // Rotaemployee
        Route::resource('rotaemployee', RotaemployeeController::class);
        Route::get('rotaemployee-grid', [RotaemployeeController::class,'grid'])->name('rotaemployee.grid');

        Route::post('rotaemployee/getdepartment', [RotaemployeeController::class,'getDepartment'])->name('employee.getdepartment');
        Route::post('rotaemployee/getdesignation', [RotaemployeeController::class,'getdDesignation'])->name('employee.getdesignation');

        //employee import
        Route::get('rotaemployee/import/export', [RotaemployeeController::class,'fileImportExport'])->name('rotaemployee.file.import');
        Route::post('rotaemployee/import', [RotaemployeeController::class,'fileImport'])->name('rotaemployee.import');
        Route::get('rotaemployee/import/modal', [RotaemployeeController::class,'fileImportModal'])->name('rotaemployee.import.modal');
        Route::post('rotaemployee/data/import/', [RotaemployeeController::class,'rotaemployeeImportdata'])->name('rotaemployee.import.data');
    });
});

        Route::post('/rota/share_rotas_link', [RotaController::class,'share_rotas_link'])->name('rotas.share_rotas_link');
        Route::get('/rota/share_rotas/{slug}', [RotaController::class,'share_rotas'])->name('rotas.share');
        Route::post('/slug-match', [RotaController::class,'slug_match'])->name('slug.match');
        Route::post('/rota-date-change', [RotaController::class,'rota_date_change'])->name('rota.date.change');
