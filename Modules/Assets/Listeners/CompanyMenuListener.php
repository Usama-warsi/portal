<?php

namespace Modules\Assets\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Assets';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Finance',
            'title' => __('Assets'),
            'icon' => 'calculator',
            'name' => 'assets',
            'parent' => null,
            'order' => 875,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'assets manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Assets'),
            'icon' => '',
            'name' => 'asset',
            'parent' => 'assets',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'asset.index',
            'module' => $module,
            'permission' => 'assets manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('History'),
            'icon' => '',
            'name' => 'history',
            'parent' => 'assets',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'asset.history.index',
            'module' => $module,
            'permission' => 'assets history manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Defective Manage'),
            'icon' => '',
            'name' => 'defective manage',
            'parent' => 'assets',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'assets.defective.index',
            'module' => $module,
            'permission' => 'assets defective manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'assets system setup',
            'parent' => 'assets',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'assets system-setup manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Category'),
            'icon' => '',
            'name' => 'category',
            'parent' => 'assets system setup',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'assets-category.index',
            'module' => $module,
            'permission' => 'assets category manage'
        ]);

    }
}
