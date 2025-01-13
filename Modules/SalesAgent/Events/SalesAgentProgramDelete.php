<?php

namespace Modules\SalesAgent\Events;

use Illuminate\Queue\SerializesModels;

class SalesAgentProgramDelete
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $program;
    public $ProgramItems;

    public function __construct($program , $ProgramItems)
    {
        $this->program = $program;
        $this->ProgramItems = $ProgramItems;
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
