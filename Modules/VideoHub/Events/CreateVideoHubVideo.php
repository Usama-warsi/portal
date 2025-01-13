<?php

namespace Modules\VideoHub\Events;

use Illuminate\Queue\SerializesModels;

class CreateVideoHubVideo
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $video;

    public function __construct($request,$video)
    {
        $this->request = $request;
        $this->video = $video;
    }
}
