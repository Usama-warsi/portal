<?php

namespace Modules\Training\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTrainer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $trainings;

    public function __construct($trainings)
    {
        $this->trainings = $trainings;
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
