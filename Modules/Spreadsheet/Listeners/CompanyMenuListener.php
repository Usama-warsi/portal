<?php

namespace Modules\Spreadsheet\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Spreadsheet';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Spreadsheet'),
            'icon' => 'file',
            'name' => 'spreadsheet',
            'parent' => null,
            'order' => 1075,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'spreadsheet.index',
            'module' => $module,
            'permission' => 'spreadsheet manage'
        ]);
    }
}
