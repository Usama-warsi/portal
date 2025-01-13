<?php

namespace Modules\Toyyibpay\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer(['plans.marketplace','plans.planpayment'], function ($view)
        {
            if(\Auth::check())
            {
                $admin_settings = getAdminAllSetting();
                if(isset($admin_settings['toyyibpay_payment_is_on']) && $admin_settings['toyyibpay_payment_is_on'] == 'on' && !empty($admin_settings['company_toyyibpay_secrect_key']) && !empty($admin_settings['company_toyyibpay_category_code']))
                {
                    $view->getFactory()->startPush('company_plan_payment', view('toyyibpay::payment.plan_payment'));
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
