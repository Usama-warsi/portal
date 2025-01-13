<?php

namespace Modules\Training\Events;

use Illuminate\Queue\SerializesModels;

class CreateTrainingType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $trainingtype;

    public function __construct($request, $trainingtype)
    {
        $this->request = $request;
        $this->trainingtype = $trainingtype;
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
