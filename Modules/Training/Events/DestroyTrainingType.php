<?php

namespace Modules\Training\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTrainingType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $trainingType;

    public function __construct($trainingType)
    {
        $this->trainingType = $trainingType;
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
