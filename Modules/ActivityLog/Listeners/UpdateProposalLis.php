<?php

namespace Modules\ActivityLog\Listeners;

use App\Models\Proposal;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use App\Events\UpdateProposal;

class UpdateProposalLis
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
    public function handle(UpdateProposal $event)
    {
        if (module_is_active('ActivityLog')) {
            $proposal = $event->proposal;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Proposal';
            $activity['sub_module']     = '--';
            $activity['description']    = __('Proposal ') . Proposal::proposalNumberFormat($proposal->proposal_id) . __(' Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $proposal->workspace;
            $activity['created_by']     = $proposal->created_by;
            $activity->save();
        }
    }
}
