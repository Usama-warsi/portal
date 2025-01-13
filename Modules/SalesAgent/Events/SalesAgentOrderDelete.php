<?php

namespace Modules\SalesAgent\Events;

use Illuminate\Queue\SerializesModels;

class SalesAgentOrderDelete
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $order;
    public $OrderItems;

    public function __construct($order , $OrderItems )
    {
        $this->order = $order;
        $this->OrderItems = $OrderItems;
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
