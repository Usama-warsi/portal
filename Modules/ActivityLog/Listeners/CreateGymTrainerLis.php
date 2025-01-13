<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\GymManagement\Events\CreateGymTrainer;

class CreateGymTrainerLis
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
    public function handle(CreateGymTrainer $event)
    {
        $gymtrainer = $event->gymtrainer;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'GYM Management';
        $activity['sub_module']     = 'Trainer';
        $activity['description']    = __('New Trainer Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $gymtrainer->workspace;
        $activity['created_by']     = $gymtrainer->created_by;
        $activity->save();
    }
}
