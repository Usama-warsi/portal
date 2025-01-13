<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCourse
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $course;

    public function __construct($course)
    {
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
