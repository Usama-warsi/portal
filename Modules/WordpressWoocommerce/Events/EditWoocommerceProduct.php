<?php

namespace Modules\WordpressWoocommerce\Events;

use Illuminate\Queue\SerializesModels;

class EditWoocommerceProduct
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $productService;

    public function __construct($request ,$productService)
    {
        $this->request = $request;
        $this->productService = $productService;
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
