<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\InnovationCenter\Events\CreateCreativity;

class CreateCreativityLis
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
    public function handle(CreateCreativity $event)
    {
        $creativity = $event->creativity;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Innovation Center';
        $activity['sub_module']     = 'New Creativity';
        $activity['description']    = __('New Creativity Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $creativity->workspace;
        $activity['created_by']     = $creativity->created_by;
        $activity->save();
    }
}