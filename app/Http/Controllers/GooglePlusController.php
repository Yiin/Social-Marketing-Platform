<?php

namespace App\Http\Controllers;

use App\Services\GooglePlusService;
use Illuminate\Http\Request;

class GooglePlusController extends Controller
{
    private $googlePlusService;

    public function __construct(GooglePlusService $googlePlusService)
    {
        $this->googlePlusService = $googlePlusService;
    }

    public function index()
    {
        return view('google-plus');
    }

    public function accounts()
    {
        return $this->googlePlusService->getAccounts();
    }
}
