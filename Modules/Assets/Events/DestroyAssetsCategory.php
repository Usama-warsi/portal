<?php

namespace Modules\Assets\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAssetsCategory
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $category;
    
    public function __construct($category)
    {
        $this->category = $category;

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
