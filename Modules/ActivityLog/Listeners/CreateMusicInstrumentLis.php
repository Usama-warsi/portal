<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MusicInstitute\Events\CreateMusicInstrument;

class CreateMusicInstrumentLis
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
    public function handle(CreateMusicInstrument $event)
    {
        $instrument = $event->instrument;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Music Institute';
        $activity['sub_module']     = 'Instrument';
        $activity['description']    = __('New Instrument Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $instrument->workspace;
        $activity['created_by']     = $instrument->created_by;
        $activity->save();
    }
}