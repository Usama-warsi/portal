<?php

namespace Modules\Rotas\Events;

use Illuminate\Queue\SerializesModels;

class CreateRota
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $rotas;
    public function __construct($request, $rotas)
    {
        $this->request = $request;
        $this->rotas = $rotas;
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
