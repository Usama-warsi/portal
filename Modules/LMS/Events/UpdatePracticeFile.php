<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePracticeFile
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $practices_file;

    public function __construct($request,$practices_file)
    {
        $this->request = $request;
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
