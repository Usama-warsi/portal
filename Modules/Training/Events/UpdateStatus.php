<?php

namespace Modules\Training\Events;

use Illuminate\Queue\SerializesModels;

class UpdateStatus
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $training;

    public function __construct($request, $training)
    {
        $this->request = $request;
        $this->training = $training;
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
