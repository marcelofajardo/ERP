<?php

namespace App\Services\Instagram;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use InstagramAPI\Instagram;
use InstagramAPI\Media\Photo\InstagramPhoto;

Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class DirectMessage {

    private $instagram;
    private $currentId;

    public function __construct()
//    public function __construct(Instagram $instagram)
    {
//        $this->instagram = $instagram;
        $username = env('IG_USERNAME');
        $password = env('IG_PASSWORD');
        $verification_method = 0;
        $this->currentId = env('IG_CURRENT_USER_ID');

//        $this->instagram->login($username, $password);

        // try {
        // 	$loginResponse = $this->instagram->login($username, $password);
        // 	$user_id       = $this->instagram->account_id;
        //
        // 	if ($loginResponse !== null && $loginResponse->isTwoFactorRequired()) {
        // 		echo '2FA not supported in this example';
        // 		exit;
        // 	}
        //
        // 	if ($loginResponse instanceof LoginResponse || $loginResponse === null) {
        // 		echo "Not a challenge required exception...\n";
        // 	}
        //
        // 	echo 'Logged in!';
        // } catch (\Exception $exception) {
        // 	/** @var LoginResponse $response */
        // 	if ( ! method_exists( $exception, 'getResponse' ) ) {
        // 		echo $exception->getMessage();
        // 		exit;
        // 	}
        // 	$response = $exception->getResponse();
        //
        // 	// if ( $exception instanceof ChallengeRequiredException ) {
        // 		sleep( 5 );
        //
        // 		$customResponse = $this->instagram->request( substr( $response->getChallenge()->getApiPath(), 1 ) )->setNeedsAuth( false )->addPost( 'choice', $verification_method )->getDecodedResponse();
        //     // dd($customResponse);
        // 		if ( is_array( $customResponse ) ) {
        // 			$user_id      = $customResponse['user_id'];
        // 			$challenge_id = $customResponse['nonce_code'];
        // 		} else {
        // 			echo "Weird response from challenge request...\n";
        // 			var_dump( $customResponse );
        // 			exit;
        // 		}
        // 	// } else {
        // 	// 	echo "Not a challenge required exception...\n";
        //   //
        // 	// 	// var_dump( $exception );
        // 	// 	exit;
        // 	// }
        //
        // 	try {
        // 		$code = readln( 'Code that you received via ' . ( $verification_method ? 'email' : 'sms' ) . ':' );
        // 		$this->instagram->changeUser($username, $password);
        // 		$customResponse = $this->instagram->request( "challenge/$user_id/$challenge_id/" )->setNeedsAuth( false )->addPost( 'security_code', $code )->getDecodedResponse();
        //
        // 		if ( ! is_array( $customResponse ) ) {
        // 			echo "Weird response from challenge validation...\n";
        // 			var_dump( $customResponse );
        // 			exit;
        // 		}
        //
        // 		if ( $customResponse['status'] === 'ok' && (int) $customResponse['logged_in_user']['pk'] === (int) $user_id ) {
        // 			echo 'Finished, logged in successfully! Run this file again to validate that it works.';
        // 		} else {
        // 			echo "Probably finished...\n";
        // 			var_dump( $customResponse );
        // 		}
        // 	} catch (\Exception $ex ) {
        // 		echo $ex->getMessage();
        // 	}
        // }
    }

//    public function changeUser( $username, $password ) {
//  		$this->instagram->_setUser( $username, $password );
//  	}

//    function readln( $prompt ) {
//    	if ( PHP_OS === 'WINNT' ) {
//    		echo "$prompt ";
//
//    		return trim( (string) stream_get_line( STDIN, 6, "\n" ) );
//    	}
//
//    	return trim( (string) readline( "$prompt " ) );
//    }

    /**
     * @return mixed
     * get the inbox for this person
     */
    public function getInbox() {
        $inbox = $this->instagram->direct->getInbox();
        return $inbox;
    }

    /**
     * @param $threadId
     * @return mixed
     * Get the thread for a particular chat
     */
    public function getThread($threadId) {
        $thread = $this->instagram->direct->getThread($threadId);
        return $thread;
    }

    /**
     * @param $receipt
     * @param $photo
     * @throws \Exception
     * Send an image
     */
    public function sendImage($receipt, $photo) {
        $photo = new InstagramPhoto($photo);
        $this->instagram->direct->sendPhoto($receipt, $photo->getFile());
    }

    /**
     * @param $receipt
     * @param $message
     * Send Message..
     */
    public function sendMessage($receipt, $message) {
        $this->instagram->direct->sendText($receipt, $message);
    }

    /**
     * @return mixed
     * returns the current user ID for Instagram user
     */
    public function getCurrentUserId() {
        return $this->currentId;
    }
}
