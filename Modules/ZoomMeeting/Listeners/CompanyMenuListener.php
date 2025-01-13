<?php

namespace Modules\ZoomMeeting\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'ZoomMeeting';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Communication',
            'title' => __('Zoom Meeting'),
            'icon' => 'video',
            'name' => 'zoommeeting',
            'parent' => null,
            'order' => 950,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'zoom-meeting.index',
            'module' => $module,
            'permission' => 'zoommeeting manage'
        ]);
    }
}
