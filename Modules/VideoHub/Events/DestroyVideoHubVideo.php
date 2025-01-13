<?php

namespace Modules\VideoHub\Events;

use Illuminate\Queue\SerializesModels;

class DestroyVideoHubVideo
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $video;

    public function __construct($video)
    {
        $this->video = $video;
    }
}
