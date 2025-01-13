<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MedicalLabManagement\Entities\Patient;
use Modules\MedicalLabManagement\Events\UpdateLabRequest;

class UpdateLabRequestLis
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
    public function handle(UpdateLabRequest $event)
    {
        $labRequest = $event->labRequest;
        $patient = Patient::find($labRequest->patient_id);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Medical Lab';
        $activity['sub_module']     = 'Lab Request';
        $activity['description']    = __('Lab Request Updated of Patient ') . $patient->first_name . __(' ') . $patient->last_name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $labRequest->workspace_id;
        $activity['created_by']     = $labRequest->created_by;
        $activity->save();
    }
}
