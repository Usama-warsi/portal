<?php

namespace Modules\Workflow\Events;

use Illuminate\Queue\SerializesModels;

class UpdateWorkflow
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $workflow;

    public function __construct($request ,$workflow)
    {
        $this->request = $request;
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
