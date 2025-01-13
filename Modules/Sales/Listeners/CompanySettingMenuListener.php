<?php

namespace Modules\Sales\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Sales';
        $menu = $event->menu;
        $menu->add([
            'title' => 'Quote Print Settings',
            'name' => 'quote-print-settings',
            'order' => 210,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'sales-print-sidenav',
            'module' => $module,
            'permission' => 'sales manage'
        ]);
        $menu->add([
            'title' => 'Sales Order Print Settings',
            'name' => 'salesorder-print-settings',
            'order' => 220,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'salesorder-print-sidenav',
            'module' => $module,
            'permission' => 'sales manage'
        ]);
        $menu->add([
            'title' => 'Sales Invoice Print Settings',
            'name' => 'salesinvoice-print-settings',
            'order' => 230,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'salesinvoice-print-sidenav',
            'module' => $module,
            'permission' => 'sales manage'
        ]);
    }
}
