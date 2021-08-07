<?php

namespace App\Library\Hubstaff\Src\Repositories;

use Curl\Curl;

class User
{

    private $accessToken;
    private $urls = [
        'allUsers'         => 'https://api.hubstaff.com/v2/users',
        'userDetail'       => 'https://api.hubstaff.com/v2/users/{userId}',
        'organizationUser' => 'https://api.hubstaff.com/v2/users/{userId}/organizations',
        'projectUser'      => 'https://api.hubstaff.com/v2/users/{userId}/projects',
    ];

    public function __construct($accessToken)
    {

        $this->accessToken = 'Bearer ' . $accessToken;

        return $this;
    }

    /**
     * Get user detail from user Id
     *
     * @param userId [integer]
     * @return object user
     */

    public function getUserDetail($userId = null)
    {

        $curl = new Curl();
        $curl->setHeader('Authorization', $this->accessToken);

        $url = str_replace('{userId}', $userId, $this->urls['userDetail']);

        $curl->get($url);
        if ($curl->error) {
            echo 'errorCode' . $curl->error_code;
            die();
        } else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        return $response;
    }

}
