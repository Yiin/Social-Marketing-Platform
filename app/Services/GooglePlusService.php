<?php

namespace App\Services;

use App\Client;
use App\Jobs\PostToGooglePlus;
use ChillDev\Spintax\Parser;
use nxsAPI_GP;
use simple_html_dom;

/**
 * Class GooglePlusService
 * @package App\Services
 */
class GooglePlusService
{
    /**
     * @var nxsAPI_GP
     */
    private $api;

    /**
     * @var \App\Services\CurlService
     */
    private $curl;

    /**
     * @var \App\Services\QueueService
     */
    private $queueService;

    /**
     * GooglePlusService constructor.
     *
     * @param nxsAPI_GP $api
     * @param CurlService $curl
     */
    public function __construct(nxsAPI_GP $api, CurlService $curl)
    {
        $this->api = $api;
        $this->curl = $curl;
    }

    /**
     * Dispatch jobs from queue to send posts to communities
     *
     * @param Client $client
     * @param $message
     * @param $url
     * @param $isImageUrl
     * @param array $list
     * @return \App\Queue
     */
    public function queuePost(Client $client, $delay, $message, $url, $isImageUrl, $list)
    {
        /**
         * @var QueueService $queueService
         */
        $queueService = resolve('App\Services\QueueService');

        $queue = $queueService->init($client);

        $jobs = [];

        $spintax = Parser::parse($message);

        foreach ($list as $item) {
            $password = $this->getAccountPassword($item['username']);
            $communityName = $this->getCommunityName($item['communityId']);
            $categories = $this->getCommunityCategories($item['communityId']);
            $members = $this->getCommunityMembers($item['communityId']);

            if (!$password) {
                QueueService::log($queue,
                    "Couldn't sign in with account {$item['username']}. Password not found.");
                continue;
            }

            $jobs [] = [
                'delay' => $delay,
                'members' => $members,

                // auth
                'username' => $item['username'],
                'password' => $password,

                // where we should post
                'communityId' => $item['communityId'],
                'communityName' => $communityName,
                'categories' => $categories,

                // what we should post
                'message' => $spintax->generate(),
                'url' => $url,
                'isImageUrl' => $isImageUrl
            ];
        }
        $queueService->start($queue, $jobs);

        return $queue;
    }

    public function post($username, $password, $message, $url, $isImageUrl, $communityId, $categoryId = null)
    {
        $error = $this->api->connect($username, $password);

        if ($error) {
            return false;
        }

        if ($isImageUrl) {
            $url = [
                'img' => $url
            ];
        }

        return [
            'isPosted' => '1',
            'postID' => ($postId = str_random() . '-' . str_random()),
            'postURL' => 'https://plus.google.com/' . $postId,
            'pDate' => date('Y-m-d H:i:s')
        ];

//        return $this->api->postGP($message, $url, '', $communityId, $categoryId);
    }

    /**
     * Are we signed in?
     *
     * @return bool
     */
    public function checkAuth()
    {
        return $this->api->check('GP', session('nxs_gp.username'));
    }

    /**
     * Check if account is already added
     *
     * @param $username
     * @return bool
     */
    public function isAccountSaved($username)
    {
        $accounts = session('nxs_gp.accounts', []);

        foreach ($accounts as $index => $account) {
            if ($account['nxs_gp.username'] === $username) {
                return true;
            }
        }
        return false;
    }

    public function getAccountPassword($username)
    {
        $accounts = session('nxs_gp.accounts', []);

        foreach ($accounts as $index => $account) {
            if ($account['nxs_gp.username'] === $username) {
                return $account['nxs_gp.password'];
            }
        }
        return false;
    }

    /**
     * Returns an array of added accounts
     *
     * @return array
     */
    public function getAccounts()
    {
        $accounts = [];

        foreach (session('nxs_gp.accounts', []) as $account) {
            $accounts[] = [
                'username' => $account['nxs_gp.username']
            ];
        }

        return $accounts;
    }

    /**
     * Adds new account and sets as active one
     *
     * @param $username
     * @param $password
     * @return \App\Services\GooglePlusService|bool
     */
    public function addAccount($username, $password)
    {
        if ($this->isAccountSaved($username)) {
            // account is already added, do not throw error, just silently ignore
            return true;
        }
        $account = $this->selectAccount($username, $password);

        if (!$account) {
            // could not login
            return false;
        }

        $accounts = session('nxs_gp.accounts', []);

        $accounts[] = $account;

        session()->put('nxs_gp.accounts', $accounts);

        return $this;
    }

    /**
     * Removes account from memory
     *
     * @param $username
     */
    public function logoutAccount($username)
    {
        $accounts = session('nxs_gp.accounts', []);

        foreach ($accounts as $index => $account) {
            if ($account['nxs_gp.username'] === $username) {
                unset($accounts[$index]);
            }
        }
        session()->put('nxs_gp.accounts', $accounts);
    }

    /**
     * Sets account as signed in
     *
     * @param $username
     * @param null $password
     * @return array|bool
     */
    public function selectAccount($username, $password = null)
    {
        if ($password === null) {
            $accounts = session('nxs_gp.accounts', []);

            foreach ($accounts as $account) {
                if ($account['nxs_gp.username'] === $username) {
                    $password = $account['nxs_gp.password'];
                    break;
                }
            }
        }
        $error = $this->api->connect($username, $password);

        if ($error) {
            return false;
        }

        session($account = [
            'nxs_gp.username' => $username,
            'nxs_gp.password' => $password
        ]);

        return $account;
    }

    public function getCurrentAccount()
    {
        return [
            'username' => session('nxs_gp.username'),
            'communities' => session('nxs_gp.communities'),
        ];
    }

    /**
     * Returns communities of signed in account
     *
     * @return array|bool
     */
    public function getCommunities()
    {
        $communities = array_map(function ($group) {
            $community = [
                'id' => $group[0],
                'name' => $group[1]
            ];
            return $community;
        }, $this->api->grabGroups('/u/0', ''));

        $this->updateCommunities($communities);

        return $communities;
    }

    /**
     * Get community categories and member count
     *
     * @param $communities
     * @return array
     */
    public function updateCommunities(&$communities)
    {
        $urls = [];

        foreach ($communities as $community) {
            $urls [] = 'https://plus.google.com/communities/' . $community['id'];
        }
        $results = $this->curl->get_multi($urls);

        $html = new simple_html_dom();

        foreach ($results as $index => $result) {
            if (!$result) {
                $communities[$index]['categories'] = 'Loading failed.';
                $communities[$index]['members'] = 'Loading failed.';
                continue;
            }
            $html->load($result);

            // Categories
            $categories = $this->scrapeCategories($html);

            // Member count
            $member_count = $this->scrapeMembers($html);

            $communities[$index]['categories'] = sizeof($categories);
            $communities[$index]['members'] = $member_count;

            session([
                'nxs_gp.category_name.' . $communities[$index]['id'] => $communities[$index]['name'],
                'nxs_gp.categories.' . $communities[$index]['id'] => $categories,
                'nxs_gp.members.' . $communities[$index]['id'] => $member_count
            ]);
        }
    }

    public function getCommunityName($communityId)
    {
        return session('nxs_gp.category_name.' . $communityId, "Unknown, session expired");
    }

    /**
     * @param $communityId
     * @return mixed
     */
    private function getCommunityCategories($communityId)
    {
        if (session()->has('nxs_gp.categories.' . $communityId)) {
            return session('nxs_gp.categories.' . $communityId);
        }
        \Log::info('getCommunityCategories ' . $communityId);
        $result = $this->curl->get('https://plus.google.com/communities/' . $communityId);

        if (!$result) {
            return [];
        }

        $html = new simple_html_dom();
        $html->load($result);

        return $this->scrapeCategories($html);
    }

    private function scrapeCategories(simple_html_dom $html)
    {
        $categories = [];

        foreach ($html->find('.clmEye') as $element) {
            $category = $element->getAttribute('data-categoryid');

            if ($category) {
                $categories[] = $category;
            }
        }

        return $categories;
    }

    private function getCommunityMembers($communityId)
    {
        if (session()->has('nxs_gp.members.' . $communityId)) {
            return session('nxs_gp.members.' . $communityId);
        }
        $result = $this->curl->get('https://plus.google.com/communities/' . $communityId);

        if (!$result) {
            return 0;
        }

        $html = new simple_html_dom();
        $html->load($result);

        return $this->scrapeMembers($html);
    }

    private function scrapeMembers(simple_html_dom $html)
    {
        $member_count_array = explode(' ', $html->find('.rZHH0e')[0]->text());
        $member_count = str_replace(",", "", $member_count_array[0]);

        return (int)$member_count;
    }
}