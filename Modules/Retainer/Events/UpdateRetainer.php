<?php

namespace Modules\Retainer\Events;

use Illuminate\Queue\SerializesModels;

class UpdateRetainer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $retainer;
    public function __construct($retainer,$request)
    {
        $this->request = $request;
        $this->retainer = $retainer;
    }
}
