<?php

use Illuminate\Http\Request;
use Modules\SupportTicket\Http\Controllers\Api\FaqController;
use Modules\SupportTicket\Http\Controllers\Api\KnowledgeController;
use Modules\SupportTicket\Http\Controllers\Api\TicketContollerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/supportticket', function (Request $request) {
    return $request->user();
});

Route::prefix('SupportTicket')->group(function () {
    Route::middleware(['jwt.api.auth'])->group(function () {

        Route::get('dashboard',[TicketContollerController::class,'home']);
        Route::get('tickets',[TicketContollerController::class,'index']);
        Route::get('tickets/create',[TicketContollerController::class,'create']);
        Route::post('ticket/store',[TicketContollerController::class,'store']);
        Route::post('ticket/update/{ticket_id}',[TicketContollerController::class,'update']);
        Route::post('ticket/delete/{ticket_id}',[TicketContollerController::class,'destroy']);

        Route::post('ticket/note/store/{ticket_id}',[TicketContollerController::class,'storeNote']);
        Route::post('ticket/add-reply/{ticket_id}',[TicketContollerController::class,'addReply']);

        Route::get('knowledge-categories',[KnowledgeController::class,'knowledgeCategories']);
        Route::get('knowledges',[KnowledgeController::class,'index']);
        Route::post('knowledge/store',[KnowledgeController::class,'store']);
        Route::post('knowledge/update/{knowledge_id}',[KnowledgeController::class,'update']);
        Route::post('knowledge/delete/{knowledge_id}',[KnowledgeController::class,'destroy']);

        Route::get('faqs',[FaqController::class,'index']);
        Route::post('faqs/store',[FaqController::class,'store']);
        Route::post('faq/update/{faq_id}',[FaqController::class,'update']);
        Route::post('faq/delete/{faq_id}',[FaqController::class,'destroy']);
    });
});


