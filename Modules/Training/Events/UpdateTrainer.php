<?php

namespace Modules\Training\Events;

use Illuminate\Queue\SerializesModels;

class UpdateTrainer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $trainer;

    public function __construct($trainer, $request)
    {
        $this->request = $request;
        $this->trainer = $trainer;
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
