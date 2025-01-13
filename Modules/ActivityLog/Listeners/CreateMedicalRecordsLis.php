<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\HospitalManagement\Events\CreateMedicalRecords;

class CreateMedicalRecordsLis
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
    public function handle(CreateMedicalRecords $event)
    {
        $medicalrecord = $event->medicalrecord;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hospital';
        $activity['sub_module']     = 'Medical Records';
        $activity['description']    = __('New Medical Record Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $medicalrecord->workspace;
        $activity['created_by']     = $medicalrecord->created_by;
        $activity->save();
    }
}
