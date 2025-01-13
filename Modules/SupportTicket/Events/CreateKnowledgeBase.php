<?php

namespace Modules\SupportTicket\Events;

use Illuminate\Queue\SerializesModels;

class CreateKnowledgeBase
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $KnowledgeBase;
    public function __construct($request,$KnowledgeBase)
    {
        $this->request = $request;
        $this->KnowledgeBase = $KnowledgeBase;
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
