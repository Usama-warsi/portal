<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCourseFaq
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $faqs;

    public function __construct($request,$faqs)
    {
        $this->request = $request;
        $this->faqs = $faqs;
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
