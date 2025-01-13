<?php

namespace Modules\SalesAgent\Events;

use Illuminate\Queue\SerializesModels;

class SalesAgentProgramCreate
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $program;
    public $ProgramItems;

    public function __construct($request, $program , $ProgramItems)
    {
        $this->request      = $request;
        $this->program      = $program;
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
