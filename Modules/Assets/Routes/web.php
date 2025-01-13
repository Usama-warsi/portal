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
use Modules\Assets\Http\Controllers\AssetsController;
use Modules\Assets\Http\Controllers\AssetWithdrawController;
use Modules\Assets\Http\Controllers\AssetDistributionsController;
use Modules\Assets\Http\Controllers\AssetDefectiveController;
use Modules\Assets\Http\Controllers\AssetExtraController;
use Modules\Assets\Http\Controllers\AssetHistoryController;
use Modules\Assets\Http\Controllers\AssetsCategoryController;

Route::group(['middleware' => 'PlanModuleCheck:Assets'], function ()
{
    Route::middleware(['auth','verified'])->group(function () {

        Route::resource('asset', AssetsController::class);

        Route::get('assets/history/',[AssetHistoryController::class ,'index'])->name('asset.history.index');

        Route::get('withdraw', [AssetWithdrawController::class ,'index'])->name('assets.defective.index');
        Route::post('withdraw/store/{id}',[AssetWithdrawController::class ,'store'])->name('assets.defective.store');
        Route::get('withdraw/status/{id}',[AssetWithdrawController::class ,'status'])->name('assets.withdraw.status');

        Route::get('distribution/{id}',[AssetDistributionsController::class ,'create'])->name('distribution.create');
        Route::post('distribution/store/{id}',[AssetDistributionsController::class,'store'])->name('distribution.store');

        Route::get('defective/{id}',[AssetDefectiveController::class,'create'])->name('defective.create');
        Route::post('defective/store/{id}',[AssetDefectiveController::class,'store'])->name('defective.store');

        Route::get('extra/{id}',[AssetExtraController::class,'create'])->name('extra.create');
        Route::post('extra/store/{id}',[AssetExtraController::class,'store'])->name('extra.store');

        Route::get('asset/import/export', [AssetsController::class,'fileImportExport'])->name('assets.file.import');
        Route::post('asset/import', [AssetsController::class,'fileImport'])->name('assets.import');
        Route::get('asset/import/modal', [AssetsController::class,'fileImportModal'])->name('assets.import.modal');
        Route::post('asset/data/import/', [AssetsController::class,'assetsImportdata'])->name('assets.import.data');

        Route::resource('assets-category', AssetsCategoryController::class);

        });
    });

