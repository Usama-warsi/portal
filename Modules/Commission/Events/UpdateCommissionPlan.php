<?php

namespace Modules\Commission\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCommissionPlan
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $commissionPlanId;
    public function __construct($request, $commissionPlanId)
    {
        $this->request        = $request;
        $this->commissionPlanId = $commissionPlanId;
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
