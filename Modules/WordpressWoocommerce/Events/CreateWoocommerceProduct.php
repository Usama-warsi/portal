<?php

namespace Modules\WordpressWoocommerce\Events;

use Illuminate\Queue\SerializesModels;

class CreateWoocommerceProduct
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $Product;

    public function __construct($request ,$Product)
    {
        $this->request = $request;
        $this->Product = $Product;
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
