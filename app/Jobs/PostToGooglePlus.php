<?php

namespace App\Jobs;

use App\Services\GooglePlusService;
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
     * @var
     */
    private $username;
    /**
     * @var
     */
    private $password;
    /**
     * @var
     */
    private $message;
    /**
     * @var
     */
    private $url;
    /**
     * @var
     */
    private $isImageUrl;
    /**
     * @var
     */
    private $communityId;
    /**
     * @var
     */
    private $categories;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $username
     * @param $password
     * @param $message
     * @param $url
     * @param $isImageUrl
     * @param $communityId
     * @param $categories
     */
    public function __construct(
        User $user,
        $username,
        $password,
        $message,
        $url,
        $isImageUrl,
        $communityId,
        $categories
    ) {
        $this->user = $user;
        $this->username = $username;
        $this->password = $password;
        $this->message = $message;
        $this->url = $url;
        $this->isImageUrl = $isImageUrl;
        $this->communityId = $communityId;
        $this->categories = $categories;
    }

    /**
     * Execute the job.
     *
     * @param GooglePlusService $googlePlusService
     * @return void
     */
    public function handle(GooglePlusService $googlePlusService)
    {
        foreach ($this->categories as $category) {
            $googlePlusService->post($this->username, $this->password, $this->message, $this->url, $this->isImageUrl,
                $this->communityId, $category);
        }
    }
}
