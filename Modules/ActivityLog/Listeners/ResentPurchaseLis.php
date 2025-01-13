<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use App\Models\Purchase;
use App\Events\ResentPurchase;

class ResentPurchaseLis
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
    public function handle(ResentPurchase $event)
    {
        if (module_is_active('ActivityLog')) {
            $purchase = $event->purchase;

            $user = User::find($purchase->user_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'POS';
            $activity['sub_module']     = 'Purchase';
            $activity['description']    = __('Purchase ') . Purchase::purchaseNumberFormat($purchase->purchase_id) . __(' Resend to ') . $user->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $purchase->workspace;
            $activity['created_by']     = $purchase->created_by;
            $activity->save();
        }
    }
}
