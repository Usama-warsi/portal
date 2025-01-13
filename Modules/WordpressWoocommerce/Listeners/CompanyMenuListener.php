<?php

namespace Modules\WordpressWoocommerce\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'WordpressWoocommerce';
        $menu = $event->menu;
        $menu->add([
            'category' => 'eCommerce',
            'title' => __('WooCommerce'),
            'icon' => 'shopping-cart',
            'name' => 'woocommerce',
            'parent' => null,
            'order' => 775,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'woocommerce manage'
        ]);
        $menu->add([
            'category' => 'eCommerce',
            'title' => __('Customer'),
            'icon' => '',
            'name' => 'customer',
            'parent' => 'woocommerce',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'wp-customer.index',
            'module' => $module,
            'permission' => 'woocommerce customer manage'
        ]);
        $menu->add([
            'category' => 'eCommerce',
            'title' => __('Product'),
            'icon' => '',
            'name' => 'product',
            'parent' => 'woocommerce',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'wp-product.index',
            'module' => $module,
            'permission' => 'woocommerce product manage'
        ]);
        $menu->add([
            'category' => 'eCommerce',
            'title' => __('Order'),
            'icon' => '',
            'name' => 'order',
            'parent' => 'woocommerce',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'wp-order.index',
            'module' => $module,
            'permission' => 'woocommerce order manage'
        ]);
        $menu->add([
            'category' => 'eCommerce',
            'title' => __('Category'),
            'icon' => '',
            'name' => 'category',
            'parent' => 'woocommerce',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'wp-category.index',
            'module' => $module,
            'permission' => 'woocommerce category manage'
        ]);
        $menu->add([
            'category' => 'eCommerce',
            'title' => __('Coupon'),
            'icon' => '',
            'name' => 'coupon',
            'parent' => 'woocommerce',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'wp-coupon.index',
            'module' => $module,
            'permission' => 'woocommerce coupon manage'
        ]);
        $menu->add([
            'category' => 'eCommerce',
            'title' => __('Tax'),
            'icon' => '',
            'name' => 'tax',
            'parent' => 'woocommerce',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'wp-tax.index',
            'module' => $module,
            'permission' => 'woocommerce tax manage'
        ]);
    }
}
