<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPracticeFile
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $practices_file;

    public function __construct($practices_file)
    {
        $this->practices_file = $practices_file;
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
