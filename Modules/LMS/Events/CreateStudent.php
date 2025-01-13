<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateStudent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $student;

    public function __construct($request,$student)
    {
        $this->request = $request;
        $this->student = $student;
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
