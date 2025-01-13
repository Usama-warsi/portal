<?php

namespace Modules\Commission\Providers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Modules\Commission\Entities\CommissionPlan;
use Carbon\Carbon;
use Modules\Sales\Entities\SalesInvoice;
use Modules\Sales\Entities\SalesInvoiceItem;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['sales::salesinvoice.invoiceitem', 'invoice.create'], function ($view) {
            $active_module =  ActivatedModule();
            $dependency = explode(',', 'Commission');

            if (!empty(array_intersect($dependency, $active_module))) {

                $type = \Request::segment(1);
                $currentDate = Carbon::now()->format('Y-m-d');
                $commissions = [];
                if ($type == 'salesinvoice') {
                    $commissions = CommissionPlan::where('created_by', \Auth::user()->id)->where('commission_module', '=', '3')->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->pluck('name', 'id');
                }
                $view->getFactory()->startPush('add_invoices_agent_filed', view('commission::invoice.agent', compact('commissions','type')));
            }
        });

        view()->composer(['sales::salesinvoice.invoiceitemEdit', 'invoice.edit'], function ($view) {
            $active_module =  ActivatedModule();
            $dependency = explode(',', 'Commission');
            if (!empty(array_intersect($dependency, $active_module))) {
                $type = \Request::segment(1);
                $ids = \Request::segment(4);
                $currentDate = Carbon::now()->format('Y-m-d');

                $commissions = CommissionPlan::where('created_by', \Auth::user()->id)->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->pluck('name', 'id');
                $salesCommissions = CommissionPlan::where('created_by', \Auth::user()->id)->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->where('commission_module', '=', '3')->pluck('name', 'id');
                if ($type == 'invoice') {
                    $ids = \Request::segment(2);
                    $id = \Illuminate\Support\Facades\Crypt::decrypt($ids);
                    $invoice    = Invoice::where('workspace', '=', getActiveWorkSpace())->where('created_by', creatorId())->where('id', $id)->first();
                } elseif ($type == 'salesinvoice') {
                    $ids = \Request::segment(4);
                    $invoice    = SalesInvoiceItem::where('workspace', '=', getActiveWorkSpace())->where('created_by', creatorId())->where('id', $ids)->first();
                }
                $agent   = User::whereIn('id', explode(',', $invoice->agent))->pluck('name', 'id');
                $view->getFactory()->startPush('add_invoices_agent_filed_edit', view('commission::invoice.edit', compact('commissions','salesCommissions', 'agent', 'invoice','type')));
            }
        });
    }


    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
