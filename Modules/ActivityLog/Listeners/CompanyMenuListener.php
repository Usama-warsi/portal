<?php

namespace Modules\ActivityLog\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'ActivityLog';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Activity Log'),
            'icon' => 'activity',
            'name' => 'activitylog',
            'parent' => null,
            'order' => 1325,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'activitylog.index',
            'module' => $module,
            'permission' => 'activitylog manage'
        ]);
    }
}
