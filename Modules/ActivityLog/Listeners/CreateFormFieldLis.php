<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FormBuilder\Events\CreateFormField;


class CreateFormFieldLis
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
    public function handle(CreateFormField $event)
    {
        if (module_is_active('ActivityLog')) {
            $formField = $event->formbuilder;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'CRM';
            $activity['sub_module']     = 'Form Builder';
            $activity['description']    = __('New Form Field created in Form ') . $formField->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $formField->workspace;
            $activity['created_by']     = $formField->created_by;
            $activity->save();
        }
    }
}
