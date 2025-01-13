<?php

namespace Modules\Retainer\Events;

use Illuminate\Queue\SerializesModels;

class RetainerDuplicate
{
    use SerializesModels;

    public $duplicateRetainer;

    public function __construct($duplicateRetainer)
    {
        $this->duplicateRetainer = $duplicateRetainer;
    }
   
}