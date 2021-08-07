<?php

namespace App\Library\Hubstaff\Src\Authentication;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Storage;

class Token
{

    private $url = 'https://account.hubstaff.com/access_tokens';

    public function getAuthToken($refreshToken, $filename = "")
    {

        $httpClient = new Client();
        
        try {
            
            $response = $httpClient->post($this->url,
                [
                    RequestOptions::FORM_PARAMS => [
                        'grant_type'    => 'refresh_token',
                        'refresh_token' => $refreshToken,
                    ],
                ]
            );

            $responseJson = json_decode($response->getBody()->getContents());

            $tokens = [
                'access_token'  => $responseJson->access_token,
                'refresh_token' => $responseJson->refresh_token,
            ];

            return Storage::disk('local')->put($filename, json_encode($tokens));

        } catch (Exception $e) {
            return false;
        }
    }
}
