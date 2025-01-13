<?php

namespace Modules\SalesAgent\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use App\Events\CreateInvoice;
use App\Events\UpdateRole;
use App\Events\CreateUser;
use Modules\SalesAgent\Listeners\CreateInvoiceLis;
use Modules\SalesAgent\Listeners\AddSalesAgentPermissions;
use Modules\SalesAgent\Listeners\CreateSalesAgentRole;

class EventServiceProvider extends Provider
{
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    protected $listen = [
    
        CreateInvoice::class => [
            CreateInvoiceLis::class
        ],
        UpdateRole::class => [
            AddSalesAgentPermissions::class
        ],
        CreateUser::class => [
            CreateSalesAgentRole::class
        ],
        
    ];

    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            __DIR__ . '/../Listeners',
        ];
    }
}
