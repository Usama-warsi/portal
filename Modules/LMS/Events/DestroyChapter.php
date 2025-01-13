<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyChapter
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $chapters;

    public function __construct($chapters)
    {
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
