<?php

namespace Modules\SupportTicket\Events;

use Illuminate\Queue\SerializesModels;

class CreateKnowledgeBaseCategory
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $KnowledgeBaseCategory;
    public function __construct($request,$KnowledgeBaseCategory)
    {
        $this->request = $request;
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
