<?php

namespace Modules\Toyyibpay\Providers;

use Illuminate\Support\ServiceProvider;

class CoursePayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['lms::storefront.*.checkout'], function ($view)
        {
            try {
                $ids = \Request::segment(1);
                if(!empty($ids))
                {
                    try {
                        $store = \Modules\LMS\Entities\Store::where('slug',$ids)->first();
                        $company_settings = getCompanyAllSetting($store->created_by, $store->workspace_id);
                        if (module_is_active('Toyyibpay', $store->created_by) && ($company_settings['toyyibpay_payment_is_on']  == 'on') && ($company_settings['company_toyyibpay_secrect_key']) && ($company_settings['company_toyyibpay_category_code']))
                        {
                            $view->getFactory()->startPush('course_payment', view('toyyibpay::payment.course_payment',compact('store')));
                        }
                    } catch (\Throwable $th)
                    {

                    }
                }
            } catch (\Throwable $th) {

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
