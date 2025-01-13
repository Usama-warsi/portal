<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\SupportTicket\Events\CreatePublicTicket;

class CreatePublicTicketLis
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
    public function handle(CreatePublicTicket $event)
    {
        if (module_is_active('ActivityLog')) {
            $ticket = $event->ticket;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Support Ticket';
            $activity['sub_module']     = 'Tickets';
            $activity['description']    = __('New Public Ticket ') . $ticket->ticket_id . __(' created by the ');
            $activity['user_id']        =  $ticket->created_by;
            $activity['url']            = '';
            $activity['workspace']      = $ticket->workspace_id;
            $activity['created_by']     = $ticket->created_by;
            $activity->save();
        }
    }
}
