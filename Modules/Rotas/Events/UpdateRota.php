<?php

namespace Modules\Rotas\Events;

use Illuminate\Queue\SerializesModels;

class UpdateRota
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $rota;

    public function __construct($request, $rota)
    {
        $this->request = $request;
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
