<?php

namespace Modules\ActivityLog\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Sales\Events\CreateOpportunities;

class CreateOpportunitiesLis
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
    public function handle(CreateOpportunities $event)
    {
        if (module_is_active('ActivityLog')) {
            $opportunities = $event->opportunities;
            $user = User::find($opportunities->user_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Sales';
            $activity['sub_module']     = 'Opportunities';
            if (isset($user->name)) {
                $activity['description']    = __('New Opportunity Created for ') . $user->name . __(' by the ');
            } else {
                $activity['description']    = __('New Opportunity Created by the ');
            }
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $opportunities->workspace;
            $activity['created_by']     = $opportunities->created_by;
            $activity->save();
        }
    }
}
