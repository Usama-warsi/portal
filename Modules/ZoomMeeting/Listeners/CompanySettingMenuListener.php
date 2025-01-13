<?php

namespace Modules\ZoomMeeting\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'ZoomMeeting';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Zoom Meeting'),
            'name' => 'zoommeeting',
            'order' => 650,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'zoom-sidenav',
            'module' => $module,
            'permission' => 'zoommeeting manage'
        ]);
    }
}
