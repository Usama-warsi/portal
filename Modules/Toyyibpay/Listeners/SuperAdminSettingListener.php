<?php

namespace Modules\Toyyibpay\Listeners;
use App\Events\SuperAdminSettingEvent;

class SuperAdminSettingListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminSettingEvent $event): void
    {
        $module = 'Toyyibpay';
        $methodName = 'index';
        $controllerClass = "Modules\\Toyyibpay\\Http\\Controllers\\SuperAdmin\\SettingsController";
        if (class_exists($controllerClass)) {
            $controller = \App::make($controllerClass);
            if (method_exists($controller, $methodName)) {
                $html = $event->html;
                $settings = $html->getSettings();
                $output =  $controller->{$methodName}($settings);
                $html->add([
                    'html' => $output->toHtml(),
                    'order' => 1110,
                    'module' => $module,
                    'permission' => 'toyyibpay payment manage'
                ]);
            }
        }
    }
}
