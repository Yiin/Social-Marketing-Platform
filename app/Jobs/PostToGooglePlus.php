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
    private $_queue;

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
     * @var integer
     */
    private $members;
    /**
     * @var
     */
    private $communityName;

    /**
     * Create a new job instance.
     *
     * @param Queue $_queue
     * @param $members
     * @param $username
     * @param $password
     * @param $communityId
     * @param $communityName
     * @param $categories
     * @param $message
     * @param $url
     * @param $isImageUrl
     */
    public function __construct(
        Queue $_queue,
        $members,
        $username,
        $password,
        $communityId,
        $communityName,
        $categories,
        $message,
        $url,
        $isImageUrl
    ) {
        $this->_queue = $_queue;
        $this->username = $username;
        $this->password = $password;
        $this->communityId = $communityId;
        $this->categories = $categories;
        $this->message = $message;
        $this->url = $url;
        $this->isImageUrl = $isImageUrl;
        $this->members = $members;
        $this->communityName = $communityName;
    }

    /**
     * Execute the job.
     *
     * @param GooglePlusService $googlePlusService
     * @return void
     */
    public function handle(GooglePlusService $googlePlusService)
    {
        $posted = false;

        QueueService::log($this->_queue, json_encode([
            'type' => 'log',
            'log_message' => 'Posting to community ' . $this->communityName . ' (' . $this->communityId . ')',
            'message' => $this->message
        ]));

        if (empty($this->categories)) {
            $this->categories [] = null;
        } else {
            foreach ($this->categories as $category) {
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
                    QueueService::log($this->_queue, json_encode([
                        'type' => 'error',
                        'error_message' => $result
                    ]));
                    continue;
                }
                QueueService::log($this->_queue, json_encode([
                    'type' => 'posted',
                    'link' => $result['postURL']
                ]));
                $posted = true;
            }
        }
        if ($posted) {
            $this->_queue->increment('statistic_groups');
            $this->_queue->increment('statistic_members', $this->members);
        }
    }
}
