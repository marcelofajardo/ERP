<?php

namespace App\Library\Hubstaff\Src;


use Storage;
use App\Library\Hubstaff\Src\Authentication\Token;

/**
 * Package is using for maintane hubstaff
 *
 * @phpcredits()  https://github.com/techsemicolon/hubstaffphp
 *
 */

class Hubstaff
{

    protected static $instance = null;
    private $accessToken;

    public $HUBSTAFF_TOKEN_FILE_NAME = "hubstaff_tokens.json";
    public $SEED_REFRESH_TOKEN;

    public function __construct()
    {
        // $this->SEED_REFRESH_TOKEN = getenv('HUBSTAFF_SEED_PERSONAL_TOKEN');
        $this->SEED_REFRESH_TOKEN = config('env.HUBSTAFF_SEED_PERSONAL_TOKEN');
    }

    public static function getInstance()
    {

        if (is_null(self::$instance)) {
            self::$instance = new Hubstaff();
        }

        return self::$instance;
    }

    public function authenticate()
    {

        /*if (!Storage::disk('local')->exists($this->HUBSTAFF_TOKEN_FILE_NAME)) {
            
        }*/

        $token = new Token();
        $token->getAuthToken($this->SEED_REFRESH_TOKEN, $this->HUBSTAFF_TOKEN_FILE_NAME);

        $this->accessToken = json_decode(Storage::disk('local')->get($this->HUBSTAFF_TOKEN_FILE_NAME))->access_token;

        return $this;
    }

    public function getRepository($repo)
    {

        $repo = ucwords(strtolower($repo));
        $repo = '\\App\\Library\\Hubstaff\\Src\\Repositories\\' . $repo;
        return new $repo($this->accessToken);
    }

}
