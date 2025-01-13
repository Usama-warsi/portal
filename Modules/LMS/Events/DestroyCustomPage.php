<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCustomPage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $pageOption;

    public function __construct($pageOption)
    {
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
