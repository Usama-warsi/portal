<?php

namespace Modules\Retainer\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Retainer';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Finance',
            'title' => __('Retainer'),
            'icon' => 'device-floppy',
            'name' => 'retainer',
            'parent' => null,
            'order' => 170,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'retainer.index',
            'module' => $module,
            'permission' => 'retainer manage'
        ]);
    }
}
