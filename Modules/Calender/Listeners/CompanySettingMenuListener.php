<?php

namespace Modules\Calender\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Calender';
        $menu = $event->menu;
        $menu->add([
            'title' => 'Google Calendar',
            'name' => 'calender',
            'order' => 660,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'navigation' => 'google_calendar_sidenav',
            'permission' => 'calander manage'
        ]);
    }
}
