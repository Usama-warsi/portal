<?php

namespace Modules\Calender\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Calender';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Calendar'),
            'icon' => 'calendar-event',
            'name' => 'calender',
            'parent' => null,
            'order' => 925,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'calender.index',
            'module' => $module,
            'permission' => 'calander manage'
        ]);
    }
}
