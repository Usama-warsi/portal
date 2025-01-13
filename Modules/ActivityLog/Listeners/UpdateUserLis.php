<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use App\Events\UpdateUser;

class UpdateUserLis
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
    public function handle(UpdateUser $event)
    {
        if (module_is_active('ActivityLog')) {
            $user = $event->user;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'User Management';
            $activity['sub_module']     = 'User';
            $activity['description']    = __('User Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $user->workspace_id;
            $activity['created_by']     = $user->created_by;
            $activity->save();
        }
    }
}
