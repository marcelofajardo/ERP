<?php

namespace App\Services\Scrap;

use Exception;
use GuzzleHttp\Client;

abstract class Scraper
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getContent($url, $method = 'GET', $country = 'it', $headers = true): string
    {
        $requestHeaders = [];
        if ($headers) {
            $requestHeaders = [
                    'User-Agent' => 'User-Agent:"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.134 Safari/537.36',
            ];
        }
        try {
            $response = $this->client->request($method, $url, [
                'headers'=> $requestHeaders
            ]);
            $content = $response->getBody()->getContents();
        } catch (Exception $exception) {
            $content = '';
        }

        return $content;
    }
}