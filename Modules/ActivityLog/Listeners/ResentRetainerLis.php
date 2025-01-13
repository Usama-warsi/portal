<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Retainer\Entities\Retainer;
use Modules\Retainer\Events\ResentRetainer;

class ResentRetainerLis
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
    public function handle(ResentRetainer $event)
    {
        if (module_is_active('ActivityLog')) {
            $retainer = $event->retainer;
            $user = User::find($retainer->user_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Retainer';
            $activity['sub_module']     = '--';
            $activity['description']    = __('Retainer ') . Retainer::retainerNumberFormat($retainer->retainer_id) . __(' Resend to ') . $user->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $retainer->workspace;
            $activity['created_by']     = $retainer->created_by;
            $activity->save();
        }
    }
}
