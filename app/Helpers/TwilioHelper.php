<?php

namespace App\Helpers;

class TwilioHelper
{
    public static function curlGetRequest($url, $sid, $token)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_USERPWD, $sid . ':' . $token);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            die;
        }
        curl_close($ch);
        return $result;
    }


    public static function curlPostRequest($url, $post_params , $user_cred)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        curl_setopt($ch, CURLOPT_USERPWD, $user_cred);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            //echo 'Error:' . curl_error($ch);
            return curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

}