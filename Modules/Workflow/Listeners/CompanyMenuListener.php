<?php

namespace Modules\Workflow\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Workflow';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Workflow'),
            'icon' => 'arrows-split-2',
            'name' => 'workflow',
            'parent' => null,
            'order' => 1050,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'workflow.index',
            'module' => $module,
            'permission' => 'workflow manage'
        ]);
    }
}
