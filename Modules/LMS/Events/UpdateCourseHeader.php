<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCourseHeader
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $header;

    public function __construct($request,$header)
    {
        $this->request = $request;
        $this->header = $header;
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
