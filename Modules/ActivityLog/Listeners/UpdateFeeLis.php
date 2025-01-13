<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\ChildcareManagement\Entities\Child;
use Modules\ChildcareManagement\Events\UpdateFee;

class UpdateFeeLis
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
    public function handle(UpdateFee $event)
    {
        $childFee = $event->childFee;
        $child = Child::find($childFee->child_id);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Childcare';
        $activity['sub_module']     = 'Fee';
        $activity['description']    = __('Fee Updated for child ') . $child->first_name . __(' ') . $child->last_name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $childFee->workspace;
        $activity['created_by']     = $childFee->created_by;
        $activity->save();
    }
}
