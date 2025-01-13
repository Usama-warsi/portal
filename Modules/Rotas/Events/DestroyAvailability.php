<?php

namespace Modules\Rotas\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAvailability
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $availability;
    public function __construct($availability)
    {
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
