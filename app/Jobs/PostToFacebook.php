<?php

namespace App\Jobs;

use App\GooglePlusQueue;
use App\Post;
use App\Queue;
use App\Services\FacebookPagesService;
use App\Services\GooglePlusService;
use App\Services\QueueService;
use App\User;
use ChillDev\Spintax\Parser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostToFacebook implements ShouldQueue
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
     * @param FacebookPagesService $facebookPagesService
     * @return void
     */
    public function handle(FacebookPagesService $facebookPagesService)
    {
        $posted = false;

        QueueService::log($this->_queue, json_encode([
            'type' => 'log',
            'communityId' => $this->communityId,
            'communityName' => $this->communityName,
            'message' => $this->message
        ]));

        $postGroup = $this->communityId;

        if (empty($this->categories)) {
            $this->categories [] = null;
        }

        $spintax = Parser::parse($this->message);

        foreach ($this->categories as $category) {
            $message = $spintax->generate();

            $result = $facebookPagesService->post(
                $this->username,
                $this->password,
                $message,
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
            $post = Post::create([
                'queue_id' => $this->_queue->id,
                'group' => $postGroup,
                'url' => $result['postURL'],
                'message' => $message
            ]);
            QueueService::log($this->_queue, json_encode([
                'type' => 'posted',
                'post_id' => $post->id
            ]));
            $posted = true;
        }
        if ($posted) {
            $this->_queue->increment('statistic_groups');
            $this->_queue->increment('statistic_members', $this->members);
        }
    }
}
