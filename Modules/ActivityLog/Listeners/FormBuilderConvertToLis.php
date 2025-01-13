<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FormBuilder\Events\FormBuilderConvertTo;

class FormBuilderConvertToLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(FormBuilderConvertTo $event)
    {
        if (module_is_active('ActivityLog')) {
            $form = $event->form;
            $module = isset($event->module) ? $event->module : '';
            if (isset($form) && isset($module)) {
                $activity                   = new AllActivityLog();
                $activity['module']         = 'CRM';
                $activity['sub_module']     = 'Form Builder';
                $activity['description']    = __('Form ') . $form->name . __(' Converted to (') . $module->module . __('>>') . $module->submodule . __(') by the ');
                $activity['user_id']        =  Auth::user()->id;
                $activity['url']            = '';
                $activity['workspace']      = $form->workspace;
                $activity['created_by']     = $form->created_by;
                $activity->save();
            }
        }
    }
}
