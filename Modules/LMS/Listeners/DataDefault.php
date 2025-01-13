<?php

namespace Modules\LMS\Listeners;

use App\Events\DefaultData;
use App\Models\WorkSpace;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LMS\Entities\LmsUtility;

class DataDefault
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
    public function handle(DefaultData $event)
    {
        $company_id = $event->company_id;
        $workspace_id = $event->workspace_id;
        $user_module = $event->user_module;
        if(!empty($user_module))
        {
            if (in_array("LMS", $user_module))
            {
                LmsUtility::defaultdata($company_id,$workspace_id);
            }
        }
    }
}
