<?php

namespace Modules\GoogleCaptcha\Events;

use Illuminate\Queue\SerializesModels;

class VerifyReCaptchaToken
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
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