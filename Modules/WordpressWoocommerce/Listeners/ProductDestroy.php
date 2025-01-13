<?php

namespace Modules\WordpressWoocommerce\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ProductService\Events\DestroyProduct;
use Modules\WordpressWoocommerce\Entities\Woocommerceconection;



class ProductDestroy
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DestroyProduct $event)
    {
        $wp_connection = Woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','product')->where('original_id' ,$event->productService->id)->first();
        if($wp_connection){
        $wp_connection->delete();
        }
    }
}
