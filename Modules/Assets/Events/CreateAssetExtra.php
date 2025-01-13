<?php

namespace Modules\Assets\Events;

use Illuminate\Queue\SerializesModels;

class CreateAssetExtra
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $assetextra;

    public function __construct($request ,$assetextra)
    {
        $this->request = $request;
        $this->assetextra = $assetextra;

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
