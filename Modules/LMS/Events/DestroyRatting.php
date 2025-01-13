<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyRatting
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $ratting;

    public function __construct($ratting)
    {
        $this->ratting = $ratting;
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
