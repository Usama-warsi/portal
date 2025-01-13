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
use Modules\Commission\Http\Controllers\CommissionController;
use Modules\Commission\Http\Controllers\CommissionPlanController;
use Modules\Commission\Http\Controllers\CommissionReceiptController;
use Modules\Commission\Http\Controllers\CommissionBankTransferController;


Route::group(['middleware' => 'PlanModuleCheck:Commission'], function () {
    Route::prefix('commission')->group(function () {
        Route::get('/', [CommissionController::class,'index']);
    });
    Route::resource('commission-plan', CommissionPlanController::class)->middleware(['auth']);
    Route::post('/getagent', [CommissionPlanController::class,'getagent'])->name('getagent');
    Route::post('/gettask', [CommissionPlanController::class,'gettask'])->name('gettask');
    Route::post('/commission-plan/attribute', [CommissionPlanController::class,'attribute'])->name('commission.plan.attribute')->middleware(['auth']);


    Route::post('/get-commission-agent', [CommissionReceiptController::class,'getcommissionagent'])->name('get.commission.agent');
    Route::resource('commission-receipt', CommissionReceiptController::class)->middleware(['auth']);
    Route::post('/calculation', [CommissionReceiptController::class,'calc'])->name('commission.cal');
    Route::match(['get', 'post'], '/com-receipt/{receipt}', [CommissionReceiptController::class,'receipt'])->name('commission.receipt');
    Route::any('/com-receipt/payment/{id}',  [CommissionReceiptController::class,'receiptPayment'])->name('commission.receipt.payment');
    Route::post('/commission-banktransfer',  [CommissionReceiptController::class,'banktransfer'])->name('receipt.banktransfer');
    Route::post('/commission/bank/payment', [CommissionReceiptController::class,'payment'])->name('commission.bank.payment');

    Route::post('/commission/commission-plans', [CommissionReceiptController::class,'commissionPlans'])->name('get.commission.plans');

    Route::resource('commission-bank-transfer', CommissionBankTransferController::class)->middleware(['auth']);
});
