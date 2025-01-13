<?php

namespace Modules\Commission\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Commission';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Sales',
            'title' => __('Commission'),
            'icon' => 'calculator',
            'name' => 'commission',
            'parent' => null,
            'order' => 640,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'commission all manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Commission Plan'),
            'icon' => '',
            'name' => 'commissionplan',
            'parent' => 'commission',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'commission-plan.index',
            'module' => $module,
            'permission' => 'commission plan manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Commission Receipt'),
            'icon' => '',
            'name' => 'commissionreceipt',
            'parent' => 'commission',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'commission-receipt.index',
            'module' => $module,
            'permission' => 'commission receipt manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Bank Transfer Request'),
            'icon' => '',
            'name' => 'banktransferrequest',
            'parent' => 'commission',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'commission-bank-transfer.index',
            'module' => $module,
            'permission' => 'commission order'
        ]);
    }
}
