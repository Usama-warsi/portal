<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateCourseCoupon
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $coursecoupon;

    public function __construct($request,$coursecoupon)
    {
        $this->request = $request;
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
