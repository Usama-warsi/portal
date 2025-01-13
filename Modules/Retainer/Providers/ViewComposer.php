<?php

namespace Modules\Retainer\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    public function boot()
    {

        view()->composer(['account::customer.show','account::vendor.show'], function ($view)
        {
            if(\Auth::check())
            {
                try {
                    $ids = \Request::segment(2);
                    if(!empty($ids))
                    {
                        try {
                            $id = \Illuminate\Support\Facades\Crypt::decrypt($ids);
                            $customer = \Modules\Account\Entities\Customer::where('user_id',$id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->first();
                            if(module_is_active('Retainer'))
                            {
                                $view->getFactory()->startPush('customer_retainer_tab', view('retainer::setting.sidebar'));
                                $view->getFactory()->startPush('customer_retainer_div', view('retainer::setting.nav_containt_div',compact('customer')));
                            }

                        } catch (\Throwable $th)
                        {
                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });
    }
}
