<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCustomPage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $pageOption;

    public function __construct($request,$pageOption)
    {
        $this->request = $request;
        $this->pageOption = $pageOption;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
