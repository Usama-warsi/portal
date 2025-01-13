<?php

namespace Modules\SalesAgent\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'SalesAgent';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Sales Agent Dashboard'),
            'icon' => '',
            'name' => 'salesagent-dashboard',
            'parent' => 'dashboard',
            'order' => 55,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'salesagent.dashboard',
            'module' => $module,
            'permission' => 'salesagent dashboard'
        ]);

        $menu->add([
            'category' => 'Sales',
            'title' => __('Sales Agent'),
            'icon' => 'user-check',
            'name' => 'salesagents',
            'parent' => null,
            'order' => 720,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'salesagent manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Sales Agents'),
            'icon' => '',
            'name' => 'salesagent',
            'parent' => 'salesagents',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'management.index',
            'module' => $module,
            'permission' => 'management manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Programs'),
            'icon' => '',
            'name' => 'program',
            'parent' => 'salesagents',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'programs.index',
            'module' => $module,
            'permission' => 'programs manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Order'),
            'icon' => '',
            'name' => 'order',
            'parent' => 'salesagents',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'salesagent.purchase.order.index',
            'module' => $module,
            'permission' => 'order manage'
        ]);

            /////////////////////// For Sales Agent Role

        $menu->add([
            'category' => 'Sales',
            'title' => __('Programs'),
            'icon' => 'steering-wheel',
            'name' => 'agent-program',
            'parent' => null,
            'order' => 200,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'programs.index',
            'module' => $module,
            'permission' => 'salesagent programs manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Product List'),
            'icon' => 'list-check',
            'name' => 'agent-product-list',
            'parent' => null,
            'order' => 300,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'salesagent.product.list',
            'module' => $module,
            'permission' => 'salesagent product list'
        ]);
        // $menu->add([
        //     'title' => 'Customers',
        //     'icon' => 'users',
        //     'name' => 'agent-customers',
        //     'parent' => null,
        //     'order' => 400,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => 'salesagent.customers.index',
        //     'module' => $module,
        //     'permission' => 'salesagent customers manage'
        // ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Purchase'),
            'icon' => 'shopping-cart',
            'name' => 'agent-purchase',
            'parent' => null,
            'order' => 500,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'salesagent purchase manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Purchase Orders'),
            'icon' => '',
            'name' => '',
            'parent' => 'agent-purchase',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'salesagent.purchase.order.index',
            'module' => $module,
            'permission' => 'salesagent purchase manage'
        ]);
        // $menu->add([
        //     'title' => 'Contracts',
        //     'icon' => '',
        //     'name' => '',
        //     'parent' => 'agent-purchase',
        //     'order' => 20,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => 'salesagent.purchase.contracts.index',
        //     'module' => $module,
        //     'permission' => 'salesagent purchase manage'
        // ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Invoices'),
            'icon' => '',
            'name' => '',
            'parent' => 'agent-purchase',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'salesagent.purchase.invoices.index',
            'module' => $module,
            'permission' => 'salesagent purchase manage'
        ]);
        // $menu->add([
        //     'title' => 'Reports',
        //     'icon' => 'settings',
        //     'name' => 'agent-report',
        //     'parent' => null,
        //     'order' => 600,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => 'salesagent.purchase.invoices.index',
        //     'module' => $module,
        //     'permission' => 'salesagent purchase manage'
        // ]);

    }
}
