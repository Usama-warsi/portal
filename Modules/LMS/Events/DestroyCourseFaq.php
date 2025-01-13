<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCourseFaq
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $faqs;

    public function __construct($faqs)
    {
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
