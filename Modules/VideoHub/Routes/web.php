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
use Modules\VideoHub\Http\Controllers\VideoHubController;

Route::group(['middleware' => 'PlanModuleCheck:VideoHub'], function ()
{
    Route::middleware(['auth','verified'])->group(function () {

        // Route::prefix('videohub')->group(function() {
        //     Route::get('/', 'VideoHubController@index');
        // });

        Route::resource('videos', VideoHubController::class);

        Route::POST('/videos/modules', [VideoHubController::class,'module'])->name('videos.modules');
        Route::POST('/videos/getfield', [VideoHubController::class,'getfield'])->name('videos.getfield');
        Route::get('videos-list', [VideoHubController::class,'List'])->name('videos.list');

        Route::post('videos/{id}/comment', [VideoHubController::class,'videoCommentStore'])->name('videos.comment.store');
        Route::get('videos/{id}/comment/{cid}/reply', [VideoHubController::class,'videoCommentReply'])->name('videos.comment.reply');
    });
});
