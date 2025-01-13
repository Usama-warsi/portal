<?php

namespace Modules\LMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateCourseSubCategory
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $subcategory;

    public function __construct($request,$subcategory)
    {
        $this->request = $request;
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
