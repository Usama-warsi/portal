<?php

namespace Modules\Retainer\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Retainer';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Retainer Print Settings'),
            'name' => 'retainer-settings',
            'order' => 70,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'retainer-print-sidenav',
            'module' => $module,
            'permission' => 'retainer manage'
        ]);
    }
}
