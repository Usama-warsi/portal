<?php

namespace Modules\SalesAgent\Events;

use Illuminate\Queue\SerializesModels;

class SalesAgentRequestSent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $program;
    public $user_id;

    public function __construct($program , $user_id)
    {
        $this->program = $program;
        $this->user_id = $user_id;
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
