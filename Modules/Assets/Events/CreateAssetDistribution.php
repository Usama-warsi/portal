<?php

namespace Modules\Assets\Events;

use Illuminate\Queue\SerializesModels;

class CreateAssetDistribution
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $assetdistribution;

    public function __construct($request ,$assetdistribution)
    {
        $this->request = $request;
        $this->assetdistribution = $assetdistribution;

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
