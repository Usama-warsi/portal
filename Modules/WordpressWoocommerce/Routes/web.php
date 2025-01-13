<?php

use Illuminate\Support\Facades\Route;
use Modules\WordpressWoocommerce\Http\Controllers\WordpressWoocommerceController;
use Modules\WordpressWoocommerce\Http\Controllers\WpCategoryController;
use Modules\WordpressWoocommerce\Http\Controllers\WpCouponController;
use Modules\WordpressWoocommerce\Http\Controllers\WpCustomerController;
use Modules\WordpressWoocommerce\Http\Controllers\WpOrderController;
use Modules\WordpressWoocommerce\Http\Controllers\WpProductController;

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

Route::group(['middleware' => 'PlanModuleCheck:WordpressWoocommerce'], function ()
{
    Route::post('woocommerce/setting', [WordpressWoocommerceController::class,'setting'])->name('wordpress.setting')->middleware(['auth']);
    Route::resource('wp-customer',WpCustomerController::class)->middleware(['auth']);
    Route::resource('wp-product',WpProductController::class)->middleware(['auth']);
    Route::resource('wp-order',WpOrderController::class)->middleware(['auth']);
    Route::resource('wp-category',WpCategoryController::class)->middleware(['auth']);
    Route::resource('wp-coupon',WpCouponController::class)->middleware(['auth']);
    Route::resource('wp-tax',WpTaxController::class)->middleware(['auth']);
});
