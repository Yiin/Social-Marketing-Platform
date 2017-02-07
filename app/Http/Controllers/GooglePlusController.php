<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\GooglePlus\AddGooglePlusAccount;
use App\Http\Requests\GooglePlus\GetGooglePlusAccountCommunities;
use App\Http\Requests\GooglePlus\GetGooglePlusCommunityInfo;
use App\Http\Requests\GooglePlus\GooglePlusPost;
use App\Http\Requests\GooglePlus\LogoutGooglePlusAccount;
use App\Services\GooglePlusService;

class GooglePlusController extends Controller
{
    private $googlePlusService;

    public function __construct(GooglePlusService $googlePlusService)
    {
        $this->googlePlusService = $googlePlusService;
    }

    public function index()
    {
        $clients = Client::all();
        return view('google-plus')->with(compact('clients'));
    }

    public function post(GooglePlusPost $request)
    {
        $client = Client::find($request->client_id);

        $queue = $this->googlePlusService->queuePost(
            $client, $request->delay, $request->message, $request->url, $request->isImageUrl, $request->queue
        );

        return response($queue->id);
    }

    public function accounts()
    {
        return $this->googlePlusService->getAccounts();
    }

    public function addAccount(AddGooglePlusAccount $request)
    {
        if ($this->googlePlusService->addAccount($request->username, $request->password)) {
            return $this->googlePlusService->getAccounts();
        }
        return response()->json([
            'password' => ['Invalid credentials.']
        ], 422);
    }

    public function logoutAccount(LogoutGooglePlusAccount $request)
    {
        $this->googlePlusService->logoutAccount($request->account['username']);

        return $this->googlePlusService->getAccounts();
    }

    public function accountCommunities(GetGooglePlusAccountCommunities $request)
    {
        $this->googlePlusService->selectAccount($request->username);

        return $this->googlePlusService->getCommunities();
    }
}
