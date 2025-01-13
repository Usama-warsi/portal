<?php

namespace Modules\SupportTicket\Events;

use Illuminate\Queue\SerializesModels;

class DestroyKnowledgeBase
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $knowledge;
    public function __construct($knowledge)
    {
       
        $this->knowledge = $knowledge;
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
