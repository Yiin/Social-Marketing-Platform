<?php

namespace App\Jobs;

use App\GooglePlusQueue;
use App\Queue;
use App\Services\GooglePlusService;
use App\Services\QueueService;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostToGooglePlus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var GooglePlusService
     */
    private $googlePlusService;

    /**
     * @var User
     */
    private $user;

    /**
     * Google account username
     *
     * @var string
     */
    private $username;

    /**
     * Google account password
     *
     * @var string
     */
    private $password;

    /**
     * Google+ Community Id
     *
     * @var string
     */
    private $communityId;

    /**
     * Google+ Community Categories
     *
     * @var array
     */
    private $categories;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $url;

    /**
     * @var boolean
     */
    private $isImageUrl;

    /**
     * Create a new job instance.
     *
     * @param Queue $queue
     * @param $username
     * @param $password
     * @param $communityId
     * @param $categories
     * @param $message
     * @param $url
     * @param $isImageUrl
     */
    public function __construct(
        Queue $queue,
        $username,
        $password,
        $communityId,
        $categories,
        $message,
        $url,
        $isImageUrl
    ) {
        $this->queue = $queue;
        $this->username = $username;
        $this->password = $password;
        $this->communityId = $communityId;
        $this->categories = $categories;
        $this->message = $message;
        $this->url = $url;
        $this->isImageUrl = $isImageUrl;
    }

    /**
     * Execute the job.
     *
     * @param GooglePlusService $googlePlusService
     * @return void
     */
    public function handle(GooglePlusService $googlePlusService)
    {
        QueueService::log($this->queue->id, "Posting to {$this->communityId} categories if there is any...");

        foreach ($this->categories as $category) {
            QueueService::log($this->queue->id, "Trying to post to category $category...");

            $result = $googlePlusService->post(
                $this->username,
                $this->password,
                $this->message,
                $this->url,
                $this->isImageUrl,
                $this->communityId,
                $category
            );
            if (!is_array($result) || !isset($result['isPosted']) || $result['isPosted'] != '1') {
                QueueService::log($this->queue->id, "Coudn't post to $category");
                continue;
            }
            QueueService::log($this->queue->id,
                "Posted successfully! PostID: {$result['postID']}, URL: {$result['postURL']}");
//            $this->queue->
        }
    }
}
