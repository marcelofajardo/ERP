<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace App\plesk;

class PleskServer extends \PleskX\Api\Operator\Server
{

    /**
     * @return array
     */


    public function getDomain($name)
    {
        $packet = $this->_client->getPacket();
        $site =  $packet->addChild('site')->addChild('get');
        $site->addChild('filter')->addChild('name',$name);
        $site->addChild('dataset')->addChild('hosting');
        $response = $this->_client->request($packet);
        return (array)$response;
    }

    public function getDomainById($id)
    {
        $packet = $this->_client->getPacket();
        $site =  $packet->addChild('site')->addChild('get');
        $site->addChild('filter')->addChild('site-id',$id);
        $site->addChild('dataset')->addChild('hosting');
        $response = $this->_client->request($packet);
        return (array)$response;
    }
}
