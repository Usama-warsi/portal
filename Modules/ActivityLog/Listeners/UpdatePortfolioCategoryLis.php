<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Portfolio\Events\UpdatePortfolioCategory;

class UpdatePortfolioCategoryLis
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
    public function handle(UpdatePortfolioCategory $event)
    {
        $portfolioCategory = $event->portfolioCategory;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Portfolio';
        $activity['sub_module']     = 'Category';
        $activity['description']    = __('Category Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $portfolioCategory->workspace;
        $activity['created_by']     = $portfolioCategory->created_by;
        $activity->save();
    }
}
