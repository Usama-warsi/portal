<?php

namespace Modules\Rotas\Events;

use Illuminate\Queue\SerializesModels;

class SendRotasViaEmail
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $rotas_data;
    public function __construct($request, $rotas_data)
    {
        $this->request = $request;
        $this->rotas_data = $rotas_data;
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
