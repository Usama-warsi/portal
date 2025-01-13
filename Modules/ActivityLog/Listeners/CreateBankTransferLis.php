<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Account\Events\CreateBankTransfer;

class CreateBankTransferLis
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
    public function handle(CreateBankTransfer $event)
    {
        if (module_is_active('ActivityLog')) {
            $transfer = $event->transfer;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Accounting';
            $activity['sub_module']     = 'Banking';
            $activity['description']    = __('New Bank Balance Transfer Created by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $transfer->workspace;
            $activity['created_by']     = $transfer->created_by;
            $activity->save();
        }
    }
}
