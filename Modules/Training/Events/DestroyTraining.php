<?php

namespace Modules\Training\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTraining
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $training;

    public function __construct($training)
    {
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
