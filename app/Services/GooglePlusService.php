<?php

namespace App\Services;

use nxsAPI_GP;

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
    }
}