<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\ChildcareManagement\Events\CreateInquiry;

class CreateInquiryLis
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
    public function handle(CreateInquiry $event)
    {
        $inquiry = $event->inquiry;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Childcare';
        $activity['sub_module']     = 'Inquiry';
        $activity['description']    = __('New Inquiry Created by the ');
        $activity['user_id']        =  isset(Auth::user()->id) ? Auth::user()->id : $inquiry->created_by;
        $activity['url']            = '';
        $activity['workspace']      = $inquiry->workspace;
        $activity['created_by']     = $inquiry->created_by;
        $activity->save();
    }
}
