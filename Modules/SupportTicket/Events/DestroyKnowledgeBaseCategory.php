<?php

namespace Modules\SupportTicket\Events;

use Illuminate\Queue\SerializesModels;

class DestroyKnowledgeBaseCategory
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
   
    public $KnowledgeBaseCategory;
    public function __construct($KnowledgeBaseCategory)
    {
       
        $this->KnowledgeBaseCategory = $KnowledgeBaseCategory;
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
