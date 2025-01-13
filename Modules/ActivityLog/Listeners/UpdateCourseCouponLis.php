<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\LMS\Events\UpdateCourseCoupon;

class UpdateCourseCouponLis
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
    public function handle(UpdateCourseCoupon $event)
    {
        $coursecoupon = $event->coursecoupon;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'LMS';
        $activity['sub_module']     = 'Course Coupon';
        $activity['description']    = __('Course Coupon Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $coursecoupon->workspace_id;
        $activity['created_by']     = $coursecoupon->created_by;
        $activity->save();
    }
}
