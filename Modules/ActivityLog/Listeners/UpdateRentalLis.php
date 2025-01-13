<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\RentalManagement\Entities\Rental;
use Modules\RentalManagement\Events\UpdateRental;

class UpdateRentalLis
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
    public function handle(UpdateRental $event)
    {
        $rental = $event->rental;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Rental';
        $activity['sub_module']     = '--';
        $activity['description']    = __('Rental ') . Rental::rentalNumberFormat($rental->rental_id) . __(' Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $rental->workspace;
        $activity['created_by']     = $rental->created_by;
        $activity->save();
    }
}
