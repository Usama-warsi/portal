<?php

namespace Modules\Rotas\Events;

use Illuminate\Queue\SerializesModels;

class UpdateAvailability
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $availability;
    public function __construct($request,$availability)
    {
        $this->request = $request;
        $this->availability = $availability;
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
