<?php

namespace App\Services;


use App\Client;
use App\Events\QueueStarted;
use App\GooglePlusQueue;
use App\Jobs\PostToGooglePlus;
use App\Queue;
use App\QueueLog;
use Carbon\Carbon;

class QueueService
{
    /**
     * @param $client Client
     * @return Queue
     */
    public function init(Client $client)
    {
        $queue = Queue::create([
            'type' => Queue::TYPE_GP,
            'client_id' => $client->id
        ]);

        return $queue;
    }

    /**
     * @param Queue $queue
     * @param array $jobs
     */
    public function start(Queue $queue, $jobs)
    {
        foreach ($jobs as $job) {
            dispatch((new PostToGooglePlus($queue, $job['members'],
                // auth
                $job['username'], $job['password'],
                // where we should post
                $job['communityId'], $job['communityName'], $job['categories'],
                // what we should post
                $job['message'], $job['url'], $job['isImageUrl']
            ))->delay(Carbon::now()->addMinutes($job['delay'])));
            \Log::debug('Job dispatched to post to community ' . $job['communityName'] . ' (' . $job['communityId'] . ')');
        }
    }

    public static function log($queue, $message)
    {
        return QueueLog::create([
            'queue_id' => $queue->id,
            'log' => $message
        ]);
    }
}