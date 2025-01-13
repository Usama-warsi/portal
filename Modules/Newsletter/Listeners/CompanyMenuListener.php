<?php

namespace Modules\Newsletter\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Newsletter';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Newsletter'),
            'icon' => 'mail',
            'name' => 'newsletter',
            'parent' => null,
            'order' => 1250,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'newsletter manage'
        ]);

        $menu->add([
            'category' => 'Productivity',
            'title' => __('Mails'),
            'icon' => '',
            'name' => 'mails',
            'parent' => 'newsletter',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'newsletter.index',
            'module' => $module,
            'permission' => 'mail manage'
        ]);

        $menu->add([
            'category' => 'Productivity',
            'title' => __('History'),
            'icon' => '',
            'name' => 'history',
            'parent' => 'newsletter',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'newsletter-history.index',
            'module' => $module,
            'permission' => 'newsletter history manage'
        ]);


    }
}
