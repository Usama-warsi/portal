<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCourseCoupon
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $coursecoupon;

    public function __construct($coursecoupon)
    {
        $this->coursecoupon = $coursecoupon;
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
