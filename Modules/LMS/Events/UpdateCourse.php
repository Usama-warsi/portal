<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCourse
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $course;

    public function __construct($request,$course)
    {
        $this->request = $request;
        $this->course = $course;
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
