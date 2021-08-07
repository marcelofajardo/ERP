<?php
namespace App\Library\Watson\Language\Assistant\V2;

use App\Library\Watson\Response;
use App\Library\Watson\Service;

class AssistantService extends Service
{
    CONST ASSISTANT_ID = "28754e1c-6281-42e6-82af-eec6e87618a6";
    /**
     * Base url for the service
     *
     * @var string
     */
    protected $url = "https://api.eu-gb.assistant.watson.cloud.ibm.com/instances/1875ce0b-ffe1-45a1-be2b-21a8488a0350";

    /**
     * API service version
     *
     * @var string
     */
    protected $version = 'v2';

    /**
     * ToneAnalyserService constructor
     *
     * @param $username string The service api username
     * @param $password string The service api password
     */
    public function __construct($username = null, $password = null)
    {
        parent::__construct($username, $password);
    }

    /**
     * Analyzes the tone of a piece of text
     *
     * @return Response
     */
    public function plainText($textToAnalyse, $version = '2016-05-19')
    {
        return $this->client->request(
            'GET',
            $this->getMountedUrl() . '/tone',
            ['query' => ['version' => $version, 'text' => $textToAnalyse]]
        );
    }

    public function set_url($url)
    {
        return $this->url = $url;
    }

    public function createSession($assistantId, $version = '2019-02-28')
    {
        return $this->client->request(
            'POST',
            $this->getMountedUrl() . 'assistants/' . $assistantId . '/sessions',
            ['query' => ['version' => $version]]
        );
    }

    public function deleteSession($assistantId, $sessionId, $version = '2019-02-28')
    {
        return $this->client->request(
            'DELETE',
            $this->getMountedUrl() . 'assistants/' . $assistantId . '/sessions/' . $sessionId,
            ['query' => ['version' => $version]]
        );
    }

    public function sendMessage($assistantId, $sessionId, $params = [], $version = '2019-02-28')
    {
        return $this->client->request(
            'POST',
            $this->getMountedUrl() . 'assistants/' . $assistantId . '/sessions/' . $sessionId . '/message',
            ['query' => ['version' => $version], "json" => $params]
        );
    }
}
