<?php

namespace Modules\ActivityLog\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Sales\Events\CreateContact;

class CreateContactLis
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
            $user = User::find($contact->user_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Sales';
            $activity['sub_module']     = 'Contact';
            if (isset($user->name)) {
                $activity['description']    = __('New Contact Created for ') . $user->name . __(' by the ');
            } else {
                $activity['description']    = __('New Contact Created by the ');
            }
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $contact->workspace;
            $activity['created_by']     = $contact->created_by;
            $activity->save();
        }
    }
}
