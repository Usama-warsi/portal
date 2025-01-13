<?php

namespace Modules\VideoHub\Events;

use Illuminate\Queue\SerializesModels;

class CreateVideoHubComment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $video;
    public $comments;

    public function __construct($request,$video,$comments)
    {
        $this->request = $request;
        $this->video = $video;
        $this->comments = $comments;
    }
}
