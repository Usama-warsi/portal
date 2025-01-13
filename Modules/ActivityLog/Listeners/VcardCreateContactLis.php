<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\VCard\Entities\Business;
use Modules\VCard\Events\CreateContact;

class VcardCreateContactLis
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
    public function handle(CreateContact $event)
    {
        if (module_is_active('ActivityLog')) {
            $contact = $event->contact;
            $Business = Business::find($contact->business_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'vCard';
            $activity['sub_module']     = 'Contact';
            $activity['description']    = __('New Contact Created in Business ') . $Business->title . __(' by the ');
            $activity['user_id']        =  $contact->created_by;
            $activity['url']            = '';
            $activity['workspace']      = $contact->workspace;
            $activity['created_by']     = $contact->created_by;
            $activity->save();
        }
    }
}
