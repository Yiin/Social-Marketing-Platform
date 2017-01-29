<?php

namespace App\Services;

use nxsAPI_LI;

class LinkedInService
{
    private $api;

    /**
     * LinkedInService constructor.
     *
     * @param nxsAPI_LI $api
     */
    public function __construct(nxsAPI_LI $api)
    {
        $this->api = $api;
    }
}