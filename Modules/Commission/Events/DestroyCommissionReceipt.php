<?php

namespace Modules\Commission\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCommissionReceipt
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $commissionReceipt;
    public function __construct($commissionReceipt)
    {
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
