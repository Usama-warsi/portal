<?php

use Illuminate\Support\Facades\Route;
use Modules\Newsletter\Http\Controllers\NewsletterController;
use Modules\Newsletter\Http\Controllers\HistoryController;



    Route::group(['middleware' => 'PlanModuleCheck:Newsletter'], function ()
    {


        Route::middleware(['auth'])->group(function () {

            Route::prefix('newsletter')->group(function() {
                Route::get('/', 'NewsletterController@index');
            });

            Route::post('template',[NewsletterController::class,'filter'])->name('newsletter.filter');
            Route::post('newsletter/getcondition',[NewsletterController::class,'getcondition'])->name('newsletter.getcondition');
            Route::resource('newsletter', NewsletterController::class);
            Route::resource('newsletter-history', HistoryController::class);

        });
    });
