<?php

namespace Modules\Rotas\Events;

use Illuminate\Queue\SerializesModels;

class UpdateWorkSchedule
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $employees;
    public function __construct($request,$employees)
    {
        $this->request = $request;
        $this->employees = $employees;
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
