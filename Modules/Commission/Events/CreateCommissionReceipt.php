<?php

namespace Modules\Commission\Events;

use Illuminate\Queue\SerializesModels;

class CreateCommissionReceipt
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $commissionReceipt;
    public function __construct($request, $commissionReceipt)
    {
        $this->request        = $request;
        $this->commissionReceipt = $commissionReceipt;
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
