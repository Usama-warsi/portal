<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCourseHeader
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $header;

    public function __construct($header)
    {
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
