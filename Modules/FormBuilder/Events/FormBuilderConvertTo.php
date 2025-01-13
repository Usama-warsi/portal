<?php

namespace Modules\FormBuilder\Events;

use Illuminate\Queue\SerializesModels;

class FormBuilderConvertTo
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $form;
    public $module;

    public function __construct($request,$form,$module)
    {
        $this->request = $request;
        $this->form = $form;
        $this->module = $module;
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
