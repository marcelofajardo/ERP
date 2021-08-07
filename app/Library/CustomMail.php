<?php

namespace App\Library;

use IWasHereFirst2\LaravelMultiMail\MailSettings;

class CustomMail implements MailSettings
{
    public $config;
    public $setting  = [];
    public $provider = [];

    public function initialize($key)
    {
        $this->config = \App\EmailAddress::where('from_address', $key)->first();


        if ($this->config) {
            $this->provider = [
                'host'       => $this->config->host,
                'port'       => $this->config->port,
                'encryption' => $this->config->encryption,
            ];

            $this->setting = [
                'pass'          => $this->config->password,
                'username'      => $this->config->username,
                'from_name'     => $this->config->from_name,
                'from'          => $this->config->from_address,
                'reply_to_mail' => $this->config->from_address,
            ];
        }else{
            $this->provider = [
                // 'host'       => env('MAIL_HOST'),
                // 'port'       => env('MAIL_PORT'),
                // 'encryption' => env('MAIL_ENCRYPTION'),
                'host'       => config('env.MAIL_HOST'),
                'port'       => config('env.MAIL_PORT'),
                'encryption' => config('env.MAIL_ENCRYPTION'),
            ];

            $this->setting = [
                // 'pass'          => env('MAIL_PASSWORD'),
                // 'username'      => env('MAIL_USERNAME'),
                // 'from_name'     => env('MAIL_FROM_NAME'),
                // 'from'          => env('MAIL_FROM_ADDRESS'),
                // 'reply_to_mail' => env('MAIL_FROM_ADDRESS'),
                'pass'          => config('env.MAIL_PASSWORD'),
                'username'      => config('env.MAIL_USERNAME'),
                'from_name'     => config('env.MAIL_FROM_NAME'),
                'from'          => config('env.MAIL_FROM_ADDRESS'),
                'reply_to_mail' => config('env.MAIL_FROM_ADDRESS'),
            ];
        }

        if (empty($this->name)) {
            $this->name = $this->settings['from_name'] ?? null;
        }

        return $this;

    }

    /**
     * Check if log driver is currently used.
     *
     * @return boolean
     */
    public function isLogDriver()
    {
        return false;
    }

    /**
     * Get provider.
     *
     * @return array
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Get setting.
     *
     * @return array
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * Return email of sender.
     *
     * @return string
     */
    public function getFromEmail()
    {
        return ($this->setting && $this->setting['from']) ? $this->setting['from'] : null;
    }

    /**
     * Return name of sender.
     *
     * @return string
     */
    public function getFromName()
    {
        return ($this->setting && $this->setting['from_name']) ? $this->setting['from_name'] : null;
    }

    /**
     * Return email of sender.
     *
     * @return string
     */
    public function getReplyEmail()
    {
        return ($this->setting && $this->setting['reply_to_mail']) ? $this->setting['reply_to_mail'] : null;
    }

    /**
     * Return name of sender.
     *
     * @return string
     */
    public function getReplyName()
    {
        return ($this->setting && $this->setting['from_name']) ? $this->setting['from_name'] : null;
    }

    /**
     * Return email
     * @return string
     */
    public function getEmail()
    {
        return ($this->setting && $this->setting['from']) ? $this->setting['from'] : null;
    }
}
