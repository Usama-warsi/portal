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
use Modules\Toyyibpay\Http\Controllers\ToyyibpayController;

Route::group(['middleware' => 'PlanModuleCheck:Toyyibpay'], function ()
{
  Route::post('/setting/toyyibpay_store', [ToyyibpayController::class,'setting'])->name('toyyibpay.company_setting.store')->middleware(['auth']);

});
Route::prefix('toyyibpay')->group(function() {
Route::post('/plan/toyyibpay/payment', [ToyyibpayController::class,'planPayWithToyyibpay'])->name('plan.pay.with.toyyibpay')->middleware(['auth']);
Route::get('/plan/toyyibpay/{plan}', [ToyyibpayController::class,'planGetToyyibpayStatus'])->name('plan.get.toyyibpay.status')->middleware(['auth']);
Route::post('/invoice.pay.with.toyyibpay', [ToyyibpayController::class,'invoicePayWithtoyyibpay'])->name('invoice.pay.with.toyyibpay');
Route::get('/invoice/toyyibpay/{invoice}/{amount}/{type}', [ToyyibpayController::class,'getInvoicePaymentStatus'])->name('invoice.toyyibpay');

Route::post('{slug}/course.pay.with.toyyibpay', [ToyyibpayController::class, 'coursePayWithtoyyibpay'])->name('course.pay.with.toyyibpay');
Route::get('/course/toyyibpay/{slug}/{amount}/{couponCode}', [ToyyibpayController::class, 'getCoursePaymentStatus'])->name('course.toyyibpay');
});
Route::prefix('hotel/{slug}')->group(function() {
    Route::post('booking/toyyibpay', [ToyyibpayController::class, 'bookingPayWithToyyibpay'])->name('booking.toyyibpaypayment');
    Route::post('/booking-pay-with-toyyibpay/{status}/{coupon}', [ToyyibpayController::class, 'getBookingPaymentStatus'])->name('booking.status');
});

