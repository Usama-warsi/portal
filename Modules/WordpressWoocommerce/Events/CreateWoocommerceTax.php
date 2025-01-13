<?php

namespace Modules\WordpressWoocommerce\Events;

use Illuminate\Queue\SerializesModels;

class CreateWoocommerceTax
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $Tax;

    public function __construct($request ,$Tax)
    {
        $this->request = $request;
        $this->Tax = $Tax;
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
