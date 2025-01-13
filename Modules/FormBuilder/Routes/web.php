<?php

use Illuminate\Support\Facades\Route;
use Modules\FormBuilder\Http\Controllers\FormBuilderController;
use App\Http\Middleware\Cors;
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

Route::group(['middleware' => 'PlanModuleCheck:FormBuilder'], function ()
{
    Route::prefix('formbuilder')->group(function() {
        Route::get('/', [FormBuilderController::class, 'index']);
    });

    // Form Builder
    Route::resource('form_builder', FormBuilderController::class)->middleware(['auth']);

    // Form Response
    Route::get('/form_response/{id}', [FormBuilderController::class, 'viewResponse'])->name('form.response')->middleware(['auth']);

    Route::get('/response/{id}', [FormBuilderController::class, 'responseDetail'])->name('response.detail')->middleware(['auth']);

    // Form Field Bind
    Route::get('/form_field/{id}',[FormBuilderController::class, 'formFieldBind'])->name('form.field.bind')->middleware(['auth']);
    Route::post('/form_field_store/{id}', [FormBuilderController::class, 'bindStore'])->name('form.bind.store')->middleware(['auth']);

    // Form Field
    Route::get('/form_builder/{id}/field', [FormBuilderController::class, 'fieldCreate'])->name('form.field.create')->middleware(['auth']);
    Route::post('/form_builder/{id}/field', [FormBuilderController::class, 'fieldStore'])->name('form.field.store')->middleware(['auth']);
    Route::get('/form_builder/{id}/field/{fid}/show', [FormBuilderController::class, 'fieldShow'])->name('form.field.show')->middleware(['auth']);
    Route::get('/form_builder/{id}/field/{fid}/edit', [FormBuilderController::class, 'fieldEdit'])->name('form.field.edit')->middleware(['auth']);
    Route::post('/form_builder/{id}/field/{fid}', [FormBuilderController::class, 'fieldUpdate'])->name('form.field.update')->middleware(['auth']);
    Route::delete('/form_builder/{id}/field/{fid}', [FormBuilderController::class, 'fieldDestroy'])->name('form.field.destroy')->middleware(['auth']);
    Route::get('/userlist/{id}', [FormBuilderController::class, 'userlist'])->name('user.list')->middleware(['auth']);
    Route::post('/user_list/{id}/', [FormBuilderController::class, 'userlistStore'])->name('user.list.store')->middleware(['auth']);
    Route::post('/form_builder/modules', [FormBuilderController::class,'module'])->name('form.builder.modules')->middleware(['auth']);
    Route::get('/email', [FormBuilderController::class, 'send'])->middleware(['auth']);
    Route::delete('/form_builder/response/{fid}/{id}', [FormBuilderController::class, 'singlequerydestroy'])->name('form.response.destroy')->middleware(['auth']);


});
    // Form link base view
    Route::get('/form/{code}', [FormBuilderController::class, 'formView'])->name('form.view');
    Route::post('/form_view_store', [FormBuilderController::class, 'formViewStore'])->name('form.view.store');
    Route::get('/response_convert_to_lead/{formid}/{responseid}',[FormBuilderController::class, 'LeadConvertResponse'])->name('form.response.convert.lead')->middleware(['auth']);
    Route::post('/response_convert_to_lead_store/{code}', [FormBuilderController::class, 'LeadConvertResponseStore'])->name('form.response.convert.lead.store')->middleware(['auth']);
  
Route::post('/form_store/{code}', [FormBuilderController::class, 'formStore'])
    ->name('form.store')
    ->middleware([Cors::class]);