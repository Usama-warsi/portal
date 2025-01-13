<?php

namespace Modules\FormBuilder\Events;

use Illuminate\Queue\SerializesModels;

class ViewForm
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $post;
    public $form;
    public $module;

    public function __construct($request,$post,$form,$module)
    {
        $this->request = $request;
        $this->post = $post;
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
