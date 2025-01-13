<?php

namespace Modules\Retainer\Events;

use Illuminate\Queue\SerializesModels;

class CreatePaymentRetainer
{
    use SerializesModels;

    public $request;
    public $retainer;

    public function __construct($request ,$retainer)
    {
        $this->request = $request;
        $this->retainer = $retainer;
    }
}
