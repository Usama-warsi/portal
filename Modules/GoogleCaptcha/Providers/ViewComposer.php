<?php

namespace Modules\GoogleCaptcha\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot(){
        view()->composer(['auth.login','auth.register','auth.forgot-password'], function ($view)
        {   
            $view->getFactory()->startPush('recaptcha_field', view('googlecaptcha::recaptcha.recaptcha'));
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
