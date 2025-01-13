<?php

namespace Modules\WordpressWoocommerce\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'WordpressWoocommerce';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Woocommerce Settings'),
            'name' => 'woocommerce settings',
            'order' => 630,
            'ignore_if' => [],
            'depend_on' => [],
            'navigation'=>'Woocommerce_sidenav',
            'route' => '',
            'module' => $module,
            'permission' => 'woocommerce manage'
        ]);
    }
}
