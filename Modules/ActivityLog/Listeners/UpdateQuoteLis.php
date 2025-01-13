<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Sales\Entities\Quote;
use Modules\Sales\Events\UpdateQuote;

class UpdateQuoteLis
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
    public function handle(UpdateQuote $event)
    {
        if (module_is_active('ActivityLog')) {
            $quote = $event->quote;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Sales';
            $activity['sub_module']     = 'Quote';
            $activity['description']    = __('Quote ') . Quote::quoteNumberFormat($quote->quote_id) . __(' Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $quote->workspace;
            $activity['created_by']     = $quote->created_by;
            $activity->save();
        }
    }
}
