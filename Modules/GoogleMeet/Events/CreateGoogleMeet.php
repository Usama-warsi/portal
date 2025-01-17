<?php

namespace Modules\GoogleMeet\Events;

use Illuminate\Queue\SerializesModels;

class CreateGoogleMeet
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request,$new ;
    public function __construct($request,$new)
    {
        $this->request = $request;
        $this->new = $new;
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
