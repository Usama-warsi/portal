<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Sales\Events\SalesInvoiceItemDuplicate;

class SalesInvoiceItemDuplicateLis
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
    public function handle(SalesInvoiceItemDuplicate $event)
    {
        if (module_is_active('ActivityLog')) {

            $duplicate = $event->duplicate;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Sales';
            $activity['sub_module']     = 'Sales Invoice';
            $activity['description']    = __('Duplicate Sales Invoice created by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $duplicate->workspace;
            $activity['created_by']     = $duplicate->created_by;
            $activity->save();
        }
    }
}
