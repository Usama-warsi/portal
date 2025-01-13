<?php

use Modules\GoogleCaptcha\Http\Controllers\GoogleCaptchaController;
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

Route::prefix('googlecaptcha')->group(function() {
    Route::get('/', 'GoogleCaptchaController@index');
});
Route::post('/recaptcha-settings/store', [GoogleCaptchaController::class,'setting'])->name('recaptcha.setting.store')->middleware(['auth']);

