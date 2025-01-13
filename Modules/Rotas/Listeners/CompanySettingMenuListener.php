<?php

namespace Modules\Rotas\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Rotas';
        $menu = $event->menu;
        $menu->add([
            'title' => 'Rotas Dashboard Settings',
            'name' => 'rotas-dashboard-setting',
            'order' => 270,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'rotas-sidenav',
            'module' => $module,
            'permission' => 'rotas manage'
        ]);
        $menu->add([
            'title' => 'Rotas Work Schedule',
            'name' => 'rotas-work-schedule',
            'order' => 280,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'rotas-work-schedule',
            'module' => $module,
            'permission' => 'rotas manage'
        ]);
    }
}
