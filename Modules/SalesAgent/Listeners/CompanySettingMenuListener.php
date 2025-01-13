<?php

namespace Modules\SalesAgent\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'SalesAgent';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Sales Agent Settings'),
            'name' => 'salesagent-settings',
            'order' => 615,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'salesagent-sidenav',
            'module' => $module,
            'permission' => 'salesagent manage'
        ]);
    }
}
