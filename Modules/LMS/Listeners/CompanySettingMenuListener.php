<?php

namespace Modules\LMS\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'LMS';
        $menu = $event->menu;
        $menu->add([
            'title' => 'Lms Store Settings',
            'name' => 'lms-store-settings',
            'order' => 240,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'lms-store-sidenav',
            'module' => $module,
            'permission' => 'lms manage'
        ]);
        $menu->add([
            'title' => 'Lms Theme Settings',
            'name' => 'lms-theme-settings',
            'order' => 250,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'lms-theme-sidenav',
            'module' => $module,
            'permission' => 'lms manage'
        ]);
        $menu->add([
            'title' => 'Certificate Settings',
            'name' => 'certificate-settings',
            'order' => 260,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'certificate-sidenav',
            'module' => $module,
            'permission' => 'lms manage'
        ]);
    }
}
