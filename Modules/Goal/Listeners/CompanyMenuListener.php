<?php

namespace Modules\Goal\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Goal';
        $menu = $event->menu;
        $menu->add([
            'category'  => 'Finance',
            'title' => __('Financial Goal'),
            'icon' => '',
            'name' => 'goal',
            'parent' => 'accounting',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'goal.index',
            'module' => $module,
            'permission' => 'goal manage'
        ]);
    }
}
