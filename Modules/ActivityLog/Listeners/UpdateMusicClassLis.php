<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MusicInstitute\Entities\MusicClass;
use Modules\MusicInstitute\Events\UpdateMusicClass;

class UpdateMusicClassLis
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
    public function handle(UpdateMusicClass $event)
    {
        $class = $event->class;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Music Institute';
        $activity['sub_module']     = 'Class';
        $activity['description']    = __('Class ') . MusicClass::classNumberFormat($class->music_class_id) . __(' Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = Auth::user()->workspace_id;
        $activity['created_by']     = Auth::user()->id;
        $activity->save();
    }
}
