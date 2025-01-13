<?php

namespace Modules\Assets\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAssets
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $asset;
    public function __construct($asset)
    {
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
