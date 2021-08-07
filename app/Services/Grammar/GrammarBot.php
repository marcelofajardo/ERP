<?php


namespace App\Services\Grammar;


use GuzzleHttp\Client;

class GrammarBot
{
    private $request;
    public function __construct(Client $client)
    {
        $this->request = $client;
    }

    public function validate($text)
    {
        sleep(1.2);

        //log to microsoft spellcheck api and get the results
        try {
            $response = $this->request->request('POST', 'https://api.cognitive.microsoft.com/bing/v7.0/SpellCheck', [
                'form_params' => [
//                    'mode' => 'Spell',
                    'text' => $text
                ],
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => 'fdcfc2cb689346a39265829bb50bf39b',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ]);
        } catch (\Exception $exception) {
            dump($exception);
            return false;
        }

        $data = json_decode($response->getBody()->getContents(), true);

//        dump($data);

        if ($data['flaggedTokens'] === []) {
//            dump($text);
            return $text;
        }
        //if there are tokens to be corrected, loop through them and correct it.
        foreach ($data['flaggedTokens'] as $suggestion) {
            $text = substr_replace($text, $suggestion['suggestions'][0]['suggestion'], $suggestion['offset'], strlen($suggestion['token']));
        }

//        dump($text);

        return $text;

    }
}