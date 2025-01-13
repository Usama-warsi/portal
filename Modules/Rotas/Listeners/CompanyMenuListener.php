<?php

namespace Modules\Rotas\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Rotas';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Rotas Dashboard'),
            'icon' => '',
            'name' => 'rotasdashboard',
            'parent' => 'dashboard',
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'rotas.dashboard',
            'module' => $module,
            'permission' => 'rotas dashboard manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Rotas'),
            'icon' => 'layout-grid-add',
            'name' => 'rotas',
            'parent' => null,
            'order' => 625,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'rotas manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Rota'),
            'icon' => '',
            'name' => 'rota',
            'parent' => 'rotas',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'rota.index',
            'module' => $module,
            'permission' => 'rota manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Work Schedule'),
            'icon' => '',
            'name' => 'work-schedule',
            'parent' => 'rotas',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'rota.workschedule',
            'module' => $module,
            'permission' => 'rotas work schedule manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Availability'),
            'icon' => '',
            'name' => 'availability',
            'parent' => 'rotas',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'availabilitie.index',
            'module' => $module,
            'permission' => 'availability manage'
        ]);

        // demo performance
            // $menu->add([
            //     'title' => __('Employee'),
            //     'icon' => '',
            //     'name' => 'rota-employee',
            //     'parent' => 'rotas',
            //     'order' => 25,
            //     'ignore_if' => [],
            //     'depend_on' => [],
            //     'route' => 'rotaemployee.index',
            //     'module' => $module,
            //     'permission' => 'rotaemployee manage'
            // ]);

            // $menu->add([
            //     'title' => __('Leave'),
            //     'icon' => '',
            //     'name' => 'rota-leave',
            //     'parent' => 'rotas',
            //     'order' => 30,
            //     'ignore_if' => [],
            //     'depend_on' => [],
            //     'route' => 'rota-leave.index',
            //     'module' => $module,
            //     'permission' => 'rotaleave manage'
            // ]);

            // $menu->add([
            //     'title' => __('System Setup'),
            //     'icon' => '',
            //     'name' => 'system-setup',
            //     'parent' => 'rotas',
            //     'order' => 35,
            //     'ignore_if' => [],
            //     'depend_on' => [],
            //     'route' => 'branches.index',
            //     'module' => $module,
            //     'permission' => 'rotabranch manage'
            // ]);
    }
}
