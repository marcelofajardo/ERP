<?php

namespace App\Services\Whatsapp\ChatApi;

class ChatApi
{

    /**
     * Get instance from whatsapp number
     *
     */
    private function getInstance($number = null)
    {
        $number = !empty($number) ? $number : 0;

        return isset(config("apiwha.instances")[$number])
            ? config("apiwha.instances")[$number]
            : config("apiwha.instances")[0];

    }

    /**
     *
     * Get Queues from Chat-Api
     *
     * @param null $number
     * @return mixed
     *
     */
    public static function chatQueue($number = null)
    {
        $instance = getInstance($number);
        /*        dd($instance);*/
        $instanceId = isset($instance["instance_id"]) ? $instance["instance_id"] : 0;
        $token = isset($instance["token"]) ? $instance["token"] : 0;

        $waiting = 0;
        $result = null;

        if (!empty($instanceId) && !empty($token)) {
            // executing curl
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.chat-api.com/instance$instanceId/showMessagesQueue?token=$token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                // throw some error if you want
            } else {
                $result = json_decode($response, true);
            }

        }

        return $result;
    }

    /**
     * Get Chat history from Chat-Api
     *
     * @param null $number
     * @return mixed
     *
     */
    public static function chatHistory($number = null)
    {
        $instance = getInstance($number);
        $instanceId = isset($instance["instance_id"]) ? $instance["instance_id"] : 0;
        $token = isset($instance["token"]) ? $instance["token"] : 0;
        $waiting = 0;
        if (!empty($instanceId) && !empty($token)) {
            // executing curl
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.chat-api.com/instance$instanceId/messages?token=$token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                // throw some error if you want
            } else {
                $result = json_decode($response, true);
            }
        }

        return $result;

    }

    /**
     * Delete Chat Queue from chat Api
     *
     * @param null $number
     * @return mixed
     */
    public static function deleteQueues($number = null)
    {
        $instance = getInstance($number);
        /*        dd($instance);*/
        $instanceId = isset($instance["instance_id"]) ? $instance["instance_id"] : 0;
        $token = isset($instance["token"]) ? $instance["token"] : 0;

        $waiting = 0;

        if (!empty($instanceId) && !empty($token)) {
            // executing curl
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.chat-api.com/instance$instanceId/clearMessagesQueue?token=$token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                // throw some error if you want
            } else {
                $result = json_decode($response, true);
            }

        }

        return $result;
    }

    public static function sendMessage($data)
    {
        $token = config("apiwha.instances")[0]['token'];
        $instanceId = config("apiwha.instances")[0]['instance_id'];

        $json = json_encode($data); // Encode data to JSON
// URL for request POST /message
        $url = "https://api.chat-api.com/instance$instanceId/sendMessage?token=".$token;
// Make a POST request
        $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $json
        ]
        ]);
// Send a request
        $result = file_get_contents($url, false, $options);
    }

    public static function waitingLimit($number = null)
    {
        $result   = self::chatQueue($number);
        $waiting  = 0;
        
        if (isset($result["totalMessages"]) && is_numeric($result["totalMessages"])) {
            $waiting = $result["totalMessages"];
        }    

        return $waiting;

    }

}