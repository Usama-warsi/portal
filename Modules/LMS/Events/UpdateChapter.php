<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateChapter
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $chapters;

    public function __construct($request,$chapters)
    {
        $this->request = $request;
        $this->chapters = $chapters;
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
