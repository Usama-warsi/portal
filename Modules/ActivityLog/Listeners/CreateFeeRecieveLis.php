<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\LegalCaseManagement\Events\CreateFeeRecieve;

class CreateFeeRecieveLis
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
    public function handle(CreateFeeRecieve $event)
    {
        $feeReceive = $event->feeReceive;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Legal Case';
        $activity['sub_module']     = 'Fee Receive';
        $activity['description']    = __('New Fee Receive Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $feeReceive->workspace;
        $activity['created_by']     = $feeReceive->created_by;
        $activity->save();
    }
}