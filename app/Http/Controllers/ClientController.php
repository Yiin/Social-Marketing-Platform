<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\CreateClient;
use App\Http\Requests\UpdateClient;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();

        return view('clients')->with(compact('clients'));
    }

    public function store(CreateClient $request)
    {
        Client::create($request->only((new Client)->getFillable()));

        return Client::all();
    }

    public function update(UpdateClient $request, Client $client)
    {
        $client->update($request->only($client->getFillable()));

        return response('OK');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        
        return response('OK');
    }
}
