<?php

namespace Modules\Assets\Events;

use Illuminate\Queue\SerializesModels;

class CreateAssets
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $assets;
    public function __construct($request, $assets)
    {
        $this->request = $request;
        $this->assets = $assets;
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
