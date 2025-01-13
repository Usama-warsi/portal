<?php

namespace Modules\ActivityLog\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Sales\Events\UpdateOpportunities;

class UpdateOpportunitiesLis
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
    public function handle(UpdateOpportunities $event)
    {
        if (module_is_active('ActivityLog')) {
            $opportunities = $event->opportunities;
            $user = User::find($opportunities->user_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Sales';
            $activity['sub_module']     = 'Opportunities';
            if (isset($user->name)) {
                $activity['description']    = __('Opportunity Updated for ') . $user->name . __(' by the ');
            } else {
                $activity['description']    = __('Opportunity Updated by the ');
            }
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $opportunities->workspace;
            $activity['created_by']     = $opportunities->created_by;
            $activity->save();
        }
    }
}
