<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\DoubleEntry\Events\CreateJournalAccount;

class CreateJournalAccountLis
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
    public function handle(CreateJournalAccount $event)
    {
        $journal = $event->journal;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'DoubleEntry';
        $activity['sub_module']     = 'Journal Account';
        $activity['description']    = __('New Journal Entry Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $journal->workspace;
        $activity['created_by']     = $journal->created_by;
        $activity->save();
    }
}
