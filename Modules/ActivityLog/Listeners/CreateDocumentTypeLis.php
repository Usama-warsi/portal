<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\PropertyManagement\Events\CreateDocumentType;

class CreateDocumentTypeLis
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
    public function handle(CreateDocumentType $event)
    {
        $document_type = $event->document_type;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Property Manage';
        $activity['sub_module']     = 'Document Type';
        $activity['description']    = __('New Document Type Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $document_type->workspace;
        $activity['created_by']     = $document_type->created_by;
        $activity->save();
    }
}
