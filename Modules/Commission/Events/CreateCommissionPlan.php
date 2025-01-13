<?php

namespace Modules\Commission\Events;

use Illuminate\Queue\SerializesModels;

class CreateCommissionPlan
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $commissionPlan;
    public function __construct($request, $commissionPlan)
    {
        $this->request        = $request;
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
