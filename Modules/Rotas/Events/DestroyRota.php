<?php

namespace Modules\Rotas\Events;

use Illuminate\Queue\SerializesModels;

class DestroyRota
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
        public $rota;
        public function __construct($rota)
        {
            $this->rota = $rota;
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
