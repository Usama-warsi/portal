<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCourseSubCategory
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $subcategory;

    public function __construct($subcategory)
    {
        $this->subcategory = $subcategory;
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
