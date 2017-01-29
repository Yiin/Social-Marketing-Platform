<?php

namespace App\Services;

use nxsAPI_GP;
use App\Services\CurlService;
use simple_html_dom;

class GooglePlusService
{
    private $api;

    /**
     * GooglePlusService constructor.
     *
     * @param nxsAPI_GP $api
     */
    public function __construct(nxsAPI_GP $api)
    {
        $this->api = $api;
        $this->curl = resolve('App\Services\CurlService');
    }

    public function checkAuth()
    {
        return $this->api->check('GP', session('nxs_gp.username'));
    }

    public function isAccountSaved($username, $password)
    {
        $accounts = session('nxs_gp.accounts', []);

        foreach ($accounts as $index => $account) {
            if ($account['nxs_gp.username'] === $username) {
                if ($account['nxs_gp.password'] !== $password) {
                    unset($accounts[$index]);
                    session()->put('nxs_gp.accounts', $accounts);

                    return false;
                }
                return true;
            }
        }
        return false;
    }

    public function getAccounts()
    {
        return session('nxs_gp.accounts', []);
    }

    public function addAccount($username, $password)
    {
        if ($this->isAccountSaved($username, $password)) {
            return false;
        }
        $error = $this->api->connect($username, $password);

        if ($error) {
            return false;
        }

        session($account = [
            'nxs_gp.username' => $username,
            'nxs_gp.password' => $password,
            'nxs_gp.communities' => $this->getCommunities()
        ]);

        $accounts = session('nxs_gp.accounts', []);

        $accounts[] = $account;

        session()->put('nxs_gp.accounts', $accounts);

        return $this;
    }

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

    public function getCommunities()
    {
        if ($this->checkAuth()) {
            $communities = $this->api->grabGroups('/u/0', '');

            return $communities;
        }
        return false;
    }

    public function getCommunityCategories($communityId)
    {
        $html = new simple_html_dom();
        $html->load($this->curl->get('https://plus.google.com/communities/' . $communityId));

        $categories = [];

        foreach ($html->find('.clmEye') as $element) {
            $category = $element->getAttribute('data-categoryid');

            if ($category) {
                $categories[] = $category;
            }
        }

        return $categories;
    }
}