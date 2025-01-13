<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Internalknowledge\Events\CreateBook;

class CreateBookLis
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
    public function handle(CreateBook $event)
    {
        $book = $event->book;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Internalknowledge';
        $activity['sub_module']     = 'Book';
        $activity['description']    = __('New Book Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $book->workspace;
        $activity['created_by']     = $book->created_by;
        $activity->save();
    }
}
