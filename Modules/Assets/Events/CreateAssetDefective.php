<?php

namespace Modules\Assets\Events;

use Illuminate\Queue\SerializesModels;

class CreateAssetDefective
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $assetdefective;

    public function __construct($request ,$assetdefective)
    {
        $this->request = $request;
        $this->assetdefective = $assetdefective;

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
