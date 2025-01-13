<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FileSharing\Events\UpdateFile;

class UpdateFileLis
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
    public function handle(UpdateFile $event)
    {
        $file = $event->file;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'File Sharing';
        $activity['sub_module']     = 'File';
        $activity['description']    = __('File Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $file->workspace;
        $activity['created_by']     = $file->created_by;
        $activity->save();
    }
}
