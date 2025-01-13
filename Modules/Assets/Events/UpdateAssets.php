<?php

namespace Modules\Assets\Events;

use Illuminate\Queue\SerializesModels;

class UpdateAssets
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $asset;

    public function __construct($request, $asset)
    {
        $this->request = $request;
        $this->asset = $asset;
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
