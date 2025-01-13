<?php

namespace Modules\SalesAgent\Events;

use Illuminate\Queue\SerializesModels;

class SalesAgentOrderCreate
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
    public $request;
    public $order;
    public $OrderItems;

    public function __construct($request ,$order , $OrderItems )
    {
        $this->request = $request;
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
