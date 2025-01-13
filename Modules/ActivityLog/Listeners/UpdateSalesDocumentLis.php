<?php

namespace Modules\ActivityLog\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Sales\Events\UpdateSalesDocument;

class UpdateSalesDocumentLis
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
    public function handle(UpdateSalesDocument $event)
    {
        if (module_is_active('ActivityLog')) {
            $salesdocument = $event->salesdocument;
            $user = User::find($salesdocument->user_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Sales';
            $activity['sub_module']     = 'Sales Document';
            if (isset($user->name)) {
                $activity['description']    = __('Sales Document Updated for ') . $user->name . __(' by the ');
            } else {
                $activity['description']    = __('Sales Document Updated by the ');
            }
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $salesdocument->workspace;
            $activity['created_by']     = $salesdocument->created_by;
            $activity->save();
        }
    }
}
