<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\ChildcareManagement\Events\UpdateChild;

class UpdateChildLis
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
    public function handle(UpdateChild $event)
    {
        $child = $event->child;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Childcare';
        $activity['sub_module']     = 'Child';
        $activity['description']    = __('Child Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $child->workspace;
        $activity['created_by']     = $child->created_by;
        $activity->save();
    }
}