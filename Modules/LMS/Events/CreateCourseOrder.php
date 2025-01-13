<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateCourseOrder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

     public $request;
     public $courseOrder;

    public function __construct($request,$courseOrder)
    {
        $this->request = $request;
        $this->courseOrder = $courseOrder;
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
