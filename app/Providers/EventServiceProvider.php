<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\QueueStarted' => [
            'App\Listeners\InitQueueStatistics',
            'App\Listeners\QueueLogging',
        ],
        'App\Events\QueueUpdate' => [
            'App\Listeners\CollectQueueStatistics',
            'App\Listeners\QueueLogging',
        ],
        'App\Events\AuthenticationFailed' => [
            'App\Listeners\QueueLogging',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
