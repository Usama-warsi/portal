<?php

namespace Modules\Training\Events;

use Illuminate\Queue\SerializesModels;

class UpdateTrainingType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $trainingType;

    public function __construct($request, $trainingType)
    {
        $this->request = $request;
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
