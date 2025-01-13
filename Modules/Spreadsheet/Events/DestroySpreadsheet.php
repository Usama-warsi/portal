<?php

namespace Modules\Spreadsheet\Events;

use Illuminate\Queue\SerializesModels;

class DestroySpreadsheet
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $spreadsheet;

    public function __construct($spreadsheet)
    {

        $this->spreadsheet = $spreadsheet;
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
