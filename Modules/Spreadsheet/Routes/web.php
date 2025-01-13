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
use Modules\Spreadsheet\Http\Controllers\SpreadsheetController;

Route::group(['middleware' => 'PlanModuleCheck:Spreadsheet'], function () {
    
        Route::middleware(['auth','verified'])->group(function () {

        Route::get('/spreadsheet/{id?}',[SpreadsheetController::class,'index'])->name('spreadsheet.index');
        Route::get('/spreadsheet/file/create/{id?}',[SpreadsheetController::class,'create'])->name('spreadsheet.file.create');
        Route::post('/spreadsheet/file/store',[SpreadsheetController::class,'store'])->name('spreadsheet.file.store');
        Route::post('/spreadsheet/spreadsheet',[SpreadsheetController::class,'spreadsheet'])->name('spreadsheet.spreadsheet');
        Route::post('/spreadsheet/update',[SpreadsheetController::class,'update'])->name('spreadsheet.file.update');
        Route::get('/spreadsheet/file/edit/{id}',[SpreadsheetController::class,'edit'])->name('spreadsheets.file.edit');
        Route::get('/spreadsheet/file/show/{id}',[SpreadsheetController::class,'show'])->name('spreadsheets.file.show');

        Route::delete('/spreadsheet/destroy',[SpreadsheetController::class,'destroy'])->name('spreadsheet.destroy');

        Route::get('/spreadsheets/folder/create/{id?}', [SpreadsheetController::class,'foldercreate'])->name('spreadsheets.folder.create');
        Route::post('/spreadsheets/folder/store/{id?}', [SpreadsheetController::class,'folderstore'])->name('spreadsheets.folder.store');
        Route::get('/spreadsheets/folder/edit/{id}', [SpreadsheetController::class,'folderedit'])->name('spreadsheets.folder.edit');
        Route::get('/spreadsheets/folder/show/{id}', [SpreadsheetController::class,'foldershow'])->name('spreadsheets.folder.show');
        Route::post('/spreadsheets/folder/update/{id}', [SpreadsheetController::class,'folderupdate'])->name('spreadsheets.folder.update');
        Route::get('/spreadsheets/folder/share/{id}', [SpreadsheetController::class,'foldershare'])->name('spreadsheets.folder.share');
        Route::delete('/spreadsheets/folder/destroy/{id}', [SpreadsheetController::class,'folderdestroy'])->name('spreadsheets.folder.destroy');

        Route::post('/spreadsheets/share/{id}', [SpreadsheetController::class,'share'])->name('spreadsheets.share');

        Route::get('/spreadsheets/related/create/{id}', [SpreadsheetController::class,'related'])->name('spreadsheets.related.create');
        Route::post('/spreadsheets/related/store/{id}', [SpreadsheetController::class,'relatedStore'])->name('spreadsheets.related.store');
        Route::post('/spreadsheets/relateds/get', [SpreadsheetController::class,'relatedGet'])->name('spreadsheets.relateds.get');
    });

});
