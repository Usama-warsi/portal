<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateRatting
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $ratting;

    public function __construct($request,$ratting)
    {
        $this->request = $request;
        $this->ratting = $ratting;
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
