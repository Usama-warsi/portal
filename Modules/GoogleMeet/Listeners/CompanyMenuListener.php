<?php

namespace Modules\GoogleMeet\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'GoogleMeet';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Communication',
            'title' => 'Google Meet',
            'icon' => 'device-computer-camera',
            'name' => 'googlemeet',
            'parent' => null,
            'order' => 975,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'googlemeet.index',
            'module' => $module,
            'permission' => 'googlemeet manage'
        ]);
    }
}
