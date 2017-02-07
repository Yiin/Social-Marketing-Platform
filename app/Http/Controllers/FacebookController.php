<?php

namespace App\Http\Controllers;

use App\Client;
use App\Services\FacebookPagesService;

class FacebookController extends Controller
{
    private $facebookService;

    public function __construct(FacebookPagesService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function index()
    {
        $clients = Client::all();
        return view('facebook')->with(compact('clients'));
    }

    public function post(FacebookPost $request)
    {
        $client = Client::find($request->client_id);

        $queue = $this->facebookService->queuePost(
            $client, $request->delay, $request->message, $request->url, $request->isImageUrl, $request->queue
        );

        return response('OK ' . $queue->id);
    }

    public function accounts()
    {
        return $this->facebookService->getAccounts();
    }

    public function addAccount(AddFacebookAccount $request)
    {
        if ($this->facebookService->addAccount($request->username, $request->password)) {
            return $this->facebookService->getAccounts();
        }
        return response()->json([
            'password' => ['Invalid credentials.']
        ], 422);
    }

    public function logoutAccount(LogoutFacebookAccount $request)
    {
        $this->facebookService->logoutAccount($request->account['username']);

        return $this->facebookService->getAccounts();
    }

    public function accountCommunities(GetFacebookAccountCommunities $request)
    {
        $this->facebookService->selectAccount($request->username);

        return $this->facebookService->getCommunities();
    }
}
