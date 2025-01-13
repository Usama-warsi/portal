<?php

namespace Modules\SalesAgent\Events;

use Illuminate\Queue\SerializesModels;

class SalesAgentDelete
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $salesagent;

    public function __construct($salesagent )
    {
        $this->salesagent = $salesagent;

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
