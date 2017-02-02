<?php

namespace App\Events;

use App\FacebookQueue;
use App\GooglePlusQueue;
use App\LinkedInQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class QueueUpdate
{
    use Dispatchable, SerializesModels;

    public $queue;

    /**
     * Create a new event instance.
     * @param $queue GooglePlusQueue|FacebookQueue|LinkedInQueue
     */
    public function __construct($queue)
    {
        $this->queue = $queue;
    }
}
