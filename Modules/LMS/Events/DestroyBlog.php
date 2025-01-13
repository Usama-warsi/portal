<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyBlog
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $blog;

    public function __construct($blog)
    {
        $this->blog = $blog;
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
