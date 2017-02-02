<?php

namespace App\Services;


use App\Client;
use App\Events\QueueStarted;
use App\GooglePlusQueue;
use App\Queue;
use App\QueueLog;

class QueueService
{
    /**
     * @param $client Client
     * @return Queue
     */
    public function init(Client $client)
    {
        $queue = new Queue([
            'type' => Queue::TYPE_GP,
            'client_id' => $client->id
        ]);

        event(new QueueStarted($queue));

        return $queue;
    }

    /**
     * @param Queue $queue
     * @param array $jobs
     */
    public function start(Queue $queue, $jobs)
    {
        self::log($queue, "Starting running jobs");

        array_walk($jobs, 'dispatch');
    }

    public static function log($queue, $message)
    {
        return QueueLog::create([
            'queue_id' => $queue->id,
            'log' => $message
        ]);
    }
}