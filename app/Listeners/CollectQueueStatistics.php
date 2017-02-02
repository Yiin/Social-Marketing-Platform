<?php

namespace App\Listeners;

use App\Events\QueueStarted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CollectQueueStatistics
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
     * @param  QueueStarted  $event
     * @return void
     */
    public function handle(QueueStarted $event)
    {
        //
    }
}
