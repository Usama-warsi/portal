<?php

namespace Modules\Retainer\Events;

use Illuminate\Queue\SerializesModels;

class RetainerConvertToInvoice
{
    use SerializesModels;

    public $convertInvoice;

    public function __construct($convertInvoice)
    {
        $this->convertInvoice = $convertInvoice;
    }
}
