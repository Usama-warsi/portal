<?php

namespace Modules\VideoHub\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\VideoHub\Entities\VideoHubModule;

class AddHookServiceProvider extends ServiceProvider
{
    public $views;

    public function boot(){

        $this->views = VideoHubModule::get_view_to_stack_hook();
        view()->composer(array_values($this->views), function ($view) {
            if (isset($view['productService']) && !empty($view['productService'])) {
                if ($view['productService']->type == 'product') {
                    $module = 'Products';
                } elseif ($view['productService']->type == 'service') {
                    $module = 'Services';
                } elseif ($view['productService']->type == 'parts') {
                    $module = 'Parts';
                } elseif ($view['productService']->type == 'rent') {
                    $module = 'Rent';
                } elseif ($view['productService']->type == 'music institute') {
                    $module = 'Music Institute';
                }
                elseif ($view['productService']->type == 'restaurants') {
                    $module = 'Restaurants';
                }
                else{
                    $module = null;
                }
            } else {
                $module = array_search($view->getName(), $this->views);
            }
            $module = VideoHubModule::filter($module,$view);
            if(\Auth::check())
            {
                $active_module = ActivatedModule();
                $dependency = explode(',', 'VideoHub');
                if (!empty(array_intersect($dependency, $active_module))) {
                    $view->getFactory()->startPush('addButtonHook', view('videohub::layouts.addhook',compact('module')));
                }
            }
        });
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
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
