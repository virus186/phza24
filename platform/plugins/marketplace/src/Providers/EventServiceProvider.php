<?php

namespace Botble\Marketplace\Providers;

use Botble\Marketplace\Listeners\AddStoreSiteMapListener;
use Botble\Marketplace\Listeners\SaveVendorInformationListener;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SaveVendorInformationListener::class,
        ],
        RenderingSiteMapEvent::class => [
            AddStoreSiteMapListener::class,
        ],
    ];
}
