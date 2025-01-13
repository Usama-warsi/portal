<?php

namespace Modules\Workflow\Events;

use Illuminate\Queue\SerializesModels;

class DestroyWorkflow
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $workflow;

    public function __construct($workflow)
    {

        $this->workflow = $workflow;
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
