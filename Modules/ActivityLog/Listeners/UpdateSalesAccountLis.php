<?php

namespace Modules\ActivityLog\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Sales\Events\UpdateSalesAccount;

class UpdateSalesAccountLis
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
    public function handle(UpdateSalesAccount $event)
    {
        if (module_is_active('ActivityLog')) {
            $salesaccount = $event->salesaccount;
            $user = User::find($salesaccount->user_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Sales';
            $activity['sub_module']     = 'Account';
            if (isset($user->name)) {
                $activity['description']    = __('Account Updated for ') . $user->name . __(' by the ');
            } else {
                $activity['description']    = __('Account Updated by the ');
            }
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $salesaccount->workspace;
            $activity['created_by']     = $salesaccount->created_by;
            $activity->save();
        }
    }
}
