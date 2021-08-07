<?php

namespace App\Library\Hubstaff\Src\Repositories;

class Activity
{

    private $accessToken;
    private $urls = [];

    public function __construct($accessToken)
    {

        $this->accessToken = 'Bearer ' . $accessToken;

        return $this;
    }

}
