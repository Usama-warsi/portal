<?php

namespace Modules\LMS\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'LMS';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('LMS Dashboard'),
            'icon' => '',
            'name' => 'lms-dashboard',
            'parent' => 'dashboard',
            'order' => 80,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'lms.dashboard',
            'module' => $module,
            'permission' => 'lms dashboard manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('LMS'),
            'icon' => 'book',
            'name' => 'lms',
            'parent' => null,
            'order' => 575,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'lms manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Course'),
            'icon' => '',
            'name' => 'course',
            'parent' => 'lms',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'course.index',
            'module' => $module,
            'permission' => 'course manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Custom Page'),
            'icon' => '',
            'name' => 'custom-page',
            'parent' => 'lms',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'custom-page.index',
            'module' => $module,
            'permission' => 'custom page manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Blog'),
            'icon' => '',
            'name' => 'blog',
            'parent' => 'lms',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'blog.index',
            'module' => $module,
            'permission' => 'blog manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Subscriber'),
            'icon' => '',
            'name' => 'subscriber',
            'parent' => 'lms',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'subscriptions.index',
            'module' => $module,
            'permission' => 'subscriber manage'
        ]);

        $menu->add([
            'category' => 'Education',
            'title' => __('Course Coupon'),
            'icon' => '',
            'name' => 'course-coupon',
            'parent' => 'lms',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'course-coupon.index',
            'module' => $module,
            'permission' => 'course coupon manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Student'),
            'icon' => '',
            'name' => 'student',
            'parent' => 'lms',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'student.index',
            'module' => $module,
            'permission' => 'student manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Course Order'),
            'icon' => '',
            'name' => 'course-order',
            'parent' => 'lms',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'course_orders.index',
            'module' => $module,
            'permission' => 'course order manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'lms-system-setup',
            'parent' => 'lms',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'course-category.index',
            'module' => $module,
            'permission' => 'lms setup manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Report'),
            'icon' => '',
            'name' => 'lms-report',
            'parent' => 'lms',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'lms report manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Store Analytics'),
            'icon' => '',
            'name' => 'store-analytics',
            'parent' => 'lms-report',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'storeanalytic',
            'module' => $module,
            'permission' => 'lms store analytics'
        ]);
    }
}
