<?php

namespace Modules\Toyyibpay\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Modules\Retainer\Entities\Retainer;

class RetainerPayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot()
    {
        view()->composer(['retainer::retainer.retainerpay'], function ($view) {
            $route = \Request::route()->getName();

            if ($route == "pay.retainer") {
                try {
                    $ids = Request::segment(3);

                    if (!empty($ids)) {
                        $id = \Illuminate\Support\Facades\Crypt::decrypt($ids);
                        $invoice = Retainer::where('id', $id)->first();
                        $type = 'retainer';
                        $company_settings = getCompanyAllSetting($invoice->created_by, $invoice->workspace);
                        if (module_is_active('Toyyibpay', $invoice->created_by) && ($company_settings['toyyibpay_payment_is_on']  == 'on') && ($company_settings['company_toyyibpay_secrect_key']) && ($company_settings['company_toyyibpay_category_code'])) {
                            $view->getFactory()->startPush('retainer_payment_tab', view('toyyibpay::payment.sidebar'));
                            $view->getFactory()->startPush('retainer_payment_div', view('toyyibpay::payment.nav_containt_div', compact('type', 'invoice','company_settings')));
                        }
                    }
                } catch (\Throwable $th) {
                }
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
