<?php

namespace Modules\GoogleMeet\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'GoogleMeet';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Google Meet Settings'),
            'name' => 'googlemeet',
            'order' => 640,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'googlemeet-sidenav',
            'module' => $module,
            'permission' => 'googlemeet manage'
        ]);
    }
}
