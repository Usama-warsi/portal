<?php

namespace Modules\Sales\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Sales';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Sales Dashboard'),
            'icon' => '',
            'name' => 'sales-dashboard',
            'parent' => 'dashboard',
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sales.dashboard',
            'module' => $module,
            'permission' => 'sales dashboard manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Sales'),
            'icon' => 'file-invoice',
            'name' => 'sales',
            'parent' => null,
            'order' => 525,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'sales manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Account'),
            'icon' => '',
            'name' => 'account',
            'parent' => 'sales',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'salesaccount.index',
            'module' => $module,
            'permission' => 'salesaccount manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Contact'),
            'icon' => '',
            'name' => 'contact',
            'parent' => 'sales',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'contact.index',
            'module' => $module,
            'permission' => 'contact manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Opportunities'),
            'icon' => '',
            'name' => 'opportunities',
            'parent' => 'sales',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'opportunities.index',
            'module' => $module,
            'permission' => 'opportunities manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Quote'),
            'icon' => '',
            'name' => 'quote',
            'parent' => 'sales',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'quote.index',
            'module' => $module,
            'permission' => 'quote manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Sales Invoice'),
            'icon' => '',
            'name' => 'salesinvoice',
            'parent' => 'sales',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'salesinvoice.index',
            'module' => $module,
            'permission' => 'salesinvoice manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Sales Order'),
            'icon' => '',
            'name' => 'salesorder',
            'parent' => 'sales',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'salesorder.index',
            'module' => $module,
            'permission' => 'salesorder manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Cases'),
            'icon' => '',
            'name' => 'cases',
            'parent' => 'sales',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'commoncases.index',
            'module' => $module,
            'permission' => 'case manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Stream'),
            'icon' => '',
            'name' => 'stream',
            'parent' => 'sales',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'stream.index',
            'module' => $module,
            'permission' => 'stream manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Sales Document'),
            'icon' => '',
            'name' => 'salesdocument',
            'parent' => 'sales',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'salesdocument.index',
            'module' => $module,
            'permission' => 'salesdocument manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Calls'),
            'icon' => '',
            'name' => 'call',
            'parent' => 'sales',
            'order' => 55,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'call.index',
            'module' => $module,
            'permission' => 'call manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Meeting'),
            'icon' => '',
            'name' => 'meeting',
            'parent' => 'sales',
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'meeting.index',
            'module' => $module,
            'permission' => 'meeting manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Report'),
            'icon' => '',
            'name' => 'sales-report',
            'parent' => 'sales',
            'order' => 65,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'sales report manage'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Quote Analytics'),
            'icon' => 'file-invoice',
            'name' => 'quoteanalytic',
            'parent' => 'sales-report',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.quoteanalytic',
            'module' => $module,
            'permission' => 'quote report'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Sales Invoice Analytics'),
            'icon' => 'file-invoice',
            'name' => 'invoiceanalytic',
            'parent' => 'sales-report',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.invoiceanalytic',
            'module' => $module,
            'permission' => 'salesinvoice report'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('Sales Order Analytics'),
            'icon' => 'file-invoice',
            'name' => 'salesorderanalytic',
            'parent' => 'sales-report',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.salesorderanalytic',
            'module' => $module,
            'permission' => 'salesorder report'
        ]);
        $menu->add([
            'category' => 'Sales',
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'systemsetup',
            'parent' => 'sales',
            'order' => 70,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'account_type.index',
            'module' => $module,
            'permission' => 'sales setup manage'
        ]);
    }
}
