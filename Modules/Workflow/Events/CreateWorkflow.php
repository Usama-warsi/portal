<?php

namespace Modules\Workflow\Events;

use Illuminate\Queue\SerializesModels;

class CreateWorkflow
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $Workflow;

    public function __construct($request ,$Workflow)
    {
        $this->request = $request;
        $this->Workflow = $Workflow;

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
