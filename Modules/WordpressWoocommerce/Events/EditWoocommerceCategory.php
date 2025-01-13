<?php

namespace Modules\WordpressWoocommerce\Events;

use Illuminate\Queue\SerializesModels;

class EditWoocommerceCategory
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $Category;

    public function __construct($request ,$Category)
    {
        $this->request = $request;
        $this->Category = $Category;
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
