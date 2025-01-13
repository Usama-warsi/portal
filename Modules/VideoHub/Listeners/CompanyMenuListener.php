<?php

namespace Modules\VideoHub\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'VideoHub';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Video Hub'),
            'icon' => 'video-plus',
            'name' => 'videohub',
            'parent' => null,
            'order' => 1175,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'videos.index',
            'module' => $module,
            'permission' => 'videohub manage'
        ]);
    }
}
