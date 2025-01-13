<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FormBuilder\Events\ViewForm;

class ViewFormLis
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
    public function handle(ViewForm $event)
    {
        if (module_is_active('ActivityLog')) {

            $form = $event->form;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'CRM';
            $activity['sub_module']     = 'Form Builder';
            $activity['description']    = __('New Response Created in Form ') . $form->name . __(' by the ');
            $activity['user_id']        = $form->created_by;
            $activity['url']            = '';
            $activity['workspace']      = $form->workspace;
            $activity['created_by']     = $form->created_by;
            $activity->save();
        }
    }
}
