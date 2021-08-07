<?php
namespace App\Library\Watson\Language\Workspaces\V1;

use App\Library\Watson\Service;

class LogService extends Service
{
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
    protected $version = 'v1';

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

    public function get($workspaceId, $params = [], $version = '2019-02-28')
    {
        return $this->client->request(
            'GET',
            $this->getMountedUrl() . 'workspaces/' . $workspaceId . '/logs/',
            ['query' => ['version' => $version] + $params]
        );
    }

}
