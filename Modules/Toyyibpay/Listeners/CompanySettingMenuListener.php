<?php

namespace Modules\Toyyibpay\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Toyyibpay';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Toyyibpay'),
            'name' => 'toyyibpay',
            'order' => 1110,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'toyyibpay-sidenav',
            'module' => $module,
            'permission' => 'toyyibpay payment manage'
        ]);
        
    }
}
