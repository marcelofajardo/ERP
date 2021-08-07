<?php
/**
 * Pilot - Extend basic functionality of the Instagram API
 * @name Pilot
 * @copyright DM PIlot - https://dmpilot.live
 */

namespace App\Library;

use InstagramAPI\Instagram;

class Pilot extends Instagram
{
    /**
     * Send the choice to get the verification code in case of checkpoint.
     * @param  string  $username       Instagram username.
     * @param  string  $password       Instagram password.
     * @param  string $apiPath         Challange api path
     * @param  int $choice             Choice of the user. Possible values: 0, 1
     * @return Array
     */
    public function sendChallangeCode($username, $password, $apiPath, $choice)
    {
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('You must provide a username and password to sendChallangeCode().');
        }

        if (!is_string($apiPath) || !$apiPath) {
            throw new \InvalidArgumentException('You must provide a valid apiPath to sendChallangeCode().');
        }

        $this->_setUser($username, $password);
        $this->_sendPreLoginFlow();

        return $this->request(ltrim($apiPath, "/"))
            ->setNeedsAuth(false)
            ->addPost('choice', $choice)
            ->addPost('_uuid', $this->uuid)
            ->addPost('_uid', $this->account_id)
            ->addPost('device_id', $this->device_id)
            ->addPost('guid', $this->uuid)
            ->addPost('_csrftoken', $this->client->getToken())
            ->getDecodedResponse();
    }

    /**
     * Re-send the virification code for the checkpoint challenge
     * @param  string  $username       Instagram username.
     * @param  string  $password       Instagram password.
     * @param  string $apiPath         Api path to send a resend request
     * @return Array
     */
    public function resendChallengeCode($username, $password, $apiPath, $choice)
    {
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('You must provide a username and password to resendChallengeCode().');
        }

        if (empty($apiPath)) {
            throw new \InvalidArgumentException('You must provide a api path to resendChallengeCode().');
        }

        $this->_setUser($username, $password);

        return $this->request(ltrim($apiPath, "/"))
            ->setNeedsAuth(false)
            ->addPost('choice', $choice)
            ->getDecodedResponse();
    }

    /**
     * Finish a challenge login
     *
     * This function finishes a checkpoint challenge that was provided by the
     * sendChallangeCode method. If you successfully answer their challenge,
     * you will be logged in after this function call.
     *
     * @param  string  $username           Instagram username.
     * @param  string  $password           Instagram password.
     * @param  string  $apiPath            Relative path to the api endpoint
     *                                     for the challenge.
     * @param  string  $securityCode       Verification code you have received
     *                                     via SMS or Email.
     * @param  integer $appRefreshInterval See `login()` for description of this
     *                                     parameter.
     *
     * @throws \InvalidArgumentException
     * @throws \InstagramAPI\Exception\InstagramException
     *
     * @return \InstagramAPI\Response\LoginResponse
     */
    public function finishChallengeLogin($username, $password, $apiPath, $securityCode, $appRefreshInterval = 1800)
    {
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('You must provide a username and password to finishChallengeLogin().');
        }

        if (empty($apiPath) || empty($securityCode)) {
            throw new \InvalidArgumentException('You must provide a api path and security code to finishChallengeLogin().');
        }

        // Remove all whitespace from the verification code.
        $securityCode = preg_replace('/\s+/', '', $securityCode);

        $this->_setUser($username, $password);
        $this->_sendPreLoginFlow();

        return $this->request(ltrim($apiPath, "/"))
            ->setNeedsAuth(false)
            ->addPost('security_code', $securityCode)
            ->addPost('_uuid', $this->uuid)
            ->addPost('guid', $this->uuid)
            ->addPost('device_id', $this->device_id)
            ->addPost('_uid', $this->account_id)
            ->addPost('_csrftoken', $this->client->getToken())
            ->getDecodedResponse();

    }
}
