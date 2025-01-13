<?php

namespace Modules\Commission\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCommissionPlan
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $commissionPlan;
    public function __construct($commissionPlan)
    {
        $this->commissionPlan = $commissionPlan;
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
