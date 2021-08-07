<?php

namespace App\Library\Hubstaff\Src\Repositories;

use Curl\Curl;

class Organization
{

    private $accessToken;
    private $authToken;
    private $urls = [
        'allOrgs'                => 'https://api.hubstaff.com/v1/organizations',
        'orgDetail'              => 'https://api.hubstaff.com/v1/organizations/{orgId}',
        'orgProjects'            => 'https://api.hubstaff.com/v2/organizations/{orgId}/projects',
        'orgUsers'               => 'https://api.hubstaff.com/v2/organizations/{orgId}/members',
        'organizations-activity' => 'https://api.hubstaff.com/v2/organizations/{orgId}/activities',
    ];

    /**
     * Constructor to initialize appToken and authToken
     *
     * @param appToken [string]  authToken [string]
     * @return object this
     */

    public function __construct($accessToken)
    {

        $this->accessToken = 'Bearer ' . $accessToken;

        return $this;
    }

    /**
     * Get all users list
     *
     * @param offset [numetric & optional]
     * @return object organizations
     */

    public function getAllOrgs($offset = 0)
    {

        $curl = new Curl();
        $curl->setHeader('Authorization', $this->accessToken);

        $curl->get($this->urls['allOrgs'], array(
            'offset' => $offset,
        ));
        if ($curl->error) {
            echo 'errorCode' . $curl->error_code;
            die();
        } else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        return $response->organizations;
    }

    /**
     * Get organization detail from organization Id
     *
     * @param orgId [integer]
     * @return object organization
     */

    public function getOrgDetail($orgId = null)
    {

        $curl = new Curl();
        $curl->setHeader('Authorization', $this->accessToken);

        $url = str_replace('{orgId}', $orgId, $this->urls['orgDetail']);

        $curl->get($url);
        if ($curl->error) {
            echo 'errorCode' . $curl->error_code;
            die();
        } else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        return $response->organization;
    }

    /**
     * Retrieve projects for an organization
     *
     * @param orgId [integer], offset [numetric & optional]
     * @return object user
     */

    public function getOrgProjects($orgId = null, $offset = 0)
    {

        $curl = new Curl();
        $curl->setHeader('Authorization', $this->accessToken);

        $url = str_replace('{orgId}', $orgId, $this->urls['orgProjects']);

        $curl->get($url, array(
            'offset' => $offset,
        ));

        if ($curl->error) {
            echo 'errorCode' . $curl->error_code;
            die();
        } else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        return $response;
    }

    public function createOrgProjects($orgId = null, $params = [])
    {

        $curl = new Curl();
        $curl->setHeader('Authorization', $this->accessToken);

        $url = str_replace('{orgId}', $orgId, $this->urls['orgProjects']);
        $curl->post($url, $params);

        if ($curl->error) {
            echo 'errorCode' . $curl->error_code;
            die();
        } else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        return $response;
    }

    /**
     * Retrieve users for an organization
     *
     * @param orgId [integer], offset [numetric & optional]
     * @return object organizationusers
     */

    public function getOrgUsers($orgId = null, $offset = 0 , $pagestartId = 0)
    {

        $curl = new Curl();
        $curl->setHeader('Authorization', $this->accessToken);

        $url = str_replace('{orgId}', $orgId, $this->urls['orgUsers']);

        $params = array(
            'offset' => $offset,
        );

        if($pagestartId > 0) {
            $params = array(
                'page_start_id' => $pagestartId,
            );
        }

        $curl->get($url, $params);
        
        if ($curl->error) {
            echo 'errorCode' . $curl->error_code;
            die();
        } else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        return $response;
    }


    /**
     * Get activitiy
     *
     * @param orgId [integer]
     */

    public function getActivity($orgId, $startTime , $stopTime)
    {

        $curl = new Curl();
        $curl->setHeader('Authorization', $this->accessToken);

        $url = str_replace('{orgId}', $orgId, $this->urls['organizations-activity']);
        $curl->get($url, array(
            'time_slot[start]' => date(DATE_ISO8601, strtotime($startTime)),
            'time_slot[stop]'   => date(DATE_ISO8601, strtotime($stopTime)),
        ));

        if ($curl->error) {
            echo '<pre>'; print_r($curl); echo '</pre>';exit;
            echo 'errorCode' . $curl->error_code;
            die();
        } else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        return $response;
    }
}
