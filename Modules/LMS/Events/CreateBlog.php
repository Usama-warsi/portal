<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateBlog
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $blog;

    public function __construct($request,$blog)
    {
        $this->request = $request;
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
