<?php

namespace App\Services;

use nxsAPI_FP;

class FacebookPagesService
{
    private $api;

    /**
     * FacebookPagesService constructor.
     *
     * @param nxsAPI_FP $api
     */
    public function __construct(nxsAPI_FP $api)
    {
        $this->api = $api;
    }
}