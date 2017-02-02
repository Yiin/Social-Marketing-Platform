<?php

namespace App\Events;

use App\Queue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class QueueStarted
{
    use Dispatchable, SerializesModels;

    public $queue;

    /**
     * Create a new event instance.
     *
     * @param $queue Queue
     */
    public function __construct($queue)
    {
        $this->queue = $queue;
    }
}
