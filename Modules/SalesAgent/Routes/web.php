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
use Modules\SalesAgent\Http\Controllers\SalesAgentController;
use Modules\SalesAgent\Http\Controllers\ProgramController;
use Modules\SalesAgent\Http\Controllers\SalesAgentPurchaseController;

Route::group(['middleware' => 'PlanModuleCheck:SalesAgent'], function ()
{
    /////////////////////
    Route::resource('salesagents', SalesAgentController::class)->middleware(['auth']);

    Route::get('dashboard/salesagent', [SalesAgentController::class, 'dashboard'])
        ->name('salesagent.dashboard')
        ->middleware(['auth']);

    Route::get('salesagent', [SalesAgentController::class, 'index'])
        ->name('management.index')
        ->middleware(['auth']);

    Route::any('salesagent/settings', [SalesAgentController::class, 'setting'])
        ->name('salesagents.setting.save')
        ->middleware(['auth']);

    Route::post('sales-agent-status', [SalesAgentController::class, 'changeSalesAgentStatus'])
        ->name('activeSalesAgent')
        ->middleware(['auth']);

    // programs
    Route::resource('programs', ProgramController::class)->middleware(['auth']);

    Route::get('salesagent/program/join-requests/{id}', [ProgramController::class, 'requestList'])
        ->name('salesagent.program.request.list')
        ->middleware(['auth']);

    Route::get('salesagent/program/send-request/{programId}/{id?}', [ProgramController::class, 'sendRequest'])
        ->name('salesagent.program.send.request')
        ->middleware(['auth']);

    Route::get('salesagent/program/accept-request/{programId}/{id?}', [ProgramController::class, 'acceptRequest'])
        ->name('salesagent.program.accept.request')
        ->middleware(['auth']);

    Route::any('salesagent/program/reject-request/{programId}/{id?}', [ProgramController::class, 'rejectRequest'])
        ->name('salesagent.program.reject.request')
        ->middleware(['auth']);

    // purchase
    Route::get('salesagent/purchase/order', [SalesAgentPurchaseController::class, 'index'])
        ->name('salesagent.purchase.order.index')
        ->middleware(['auth']);

    Route::get('salesagent/purchase/create/', [SalesAgentPurchaseController::class, 'create'])
        ->name('salesagents.purchase.order.create')
        ->middleware(['auth']);

    Route::post('salesagent/purchase/store/', [SalesAgentPurchaseController::class, 'store'])
        ->name('salesagents.purchase.order.store')
        ->middleware(['auth']);

    Route::get('salesagent/purchase/edit/{id}', [SalesAgentPurchaseController::class, 'edit'])
        ->name('salesagents.purchase.order.edit')
        ->middleware(['auth']);

    Route::get('salesagent/purchase/show/{id}', [SalesAgentPurchaseController::class, 'show'])
        ->name('salesagents.purchase.order.show')
        ->middleware(['auth']);

    Route::post('salesagent/purchase/update/{id}', [SalesAgentPurchaseController::class, 'update'])
        ->name('salesagents.purchase.order.update')
        ->middleware(['auth']);

    Route::any('salesagent/purchase/delete/{id}', [SalesAgentPurchaseController::class, 'destroy'])
        ->name('salesagents.purchase.order.destroy')
        ->middleware(['auth']);

    Route::any('salesagent/purchase/invoice', [SalesAgentPurchaseController::class, 'invoiceIndex'])
        ->name('salesagent.purchase.invoices.index')
        ->middleware(['auth']);

    Route::get('salesagent/purchase/invoice-details/{id}', [SalesAgentPurchaseController::class, 'invoiceShow'])
        ->name('salesagent.purchase.invoice.show')
        ->middleware(['auth']);

    Route::get('salesagent/purchase/invoice/{id}', [SalesAgentPurchaseController::class, 'invoiceCreate'])
        ->name('salesagents.purchase.invoice.model')
        ->middleware(['auth']);

    Route::get('salesagent/purchase/order/update/{order_id}/{key}', [SalesAgentPurchaseController::class, 'updateOrderStatus'])
        ->name('salesagents.update.purchase.order.status')
        ->middleware(['auth']);

    Route::any('salesagent/purchase/get-program-items', [SalesAgentPurchaseController::class, 'getProgramItems'])
        ->name('get.program.items')
        ->middleware(['auth']);

    Route::post('salesagent/product', [SalesAgentPurchaseController::class, 'product'])
        ->name('salesagent.program.product')
        ->middleware(['auth']);

    Route::post('salesagent/purchase/setting/store', [SalesAgentPurchaseController::class, 'settings'])
        ->name('salesagent.purchase.setting')
        ->middleware(['auth']);

    Route::get('salesagent/purchase/setting/create', [SalesAgentPurchaseController::class, 'settingsCreate'])
        ->name('salesagent.purchase.setting.create')
        ->middleware(['auth']);

    Route::get('salesagent/product/list/{id?}', [SalesAgentPurchaseController::class, 'productList'])
        ->name('salesagent.product.list')
        ->middleware(['auth']);

    Route::get('salesagent/customers', [SalesAgentPurchaseController::class, 'setting'])
        ->name('salesagent.customers.index')
        ->middleware(['auth']);
});
