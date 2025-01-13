<?php

namespace Modules\Spreadsheet\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSpreadsheet
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $spreadsheets;

    public function __construct($request ,$spreadsheets)
    {
        $this->request = $request;
        $this->spreadsheets = $spreadsheets;

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
