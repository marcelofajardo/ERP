<?php

namespace App\Services\Instagram;


use App\TargetedAccounts;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram;
use InstagramAPI\Request;
use InstagramAPI\Signatures;

class Automation {

    /**
     * @var Instagram $instagram
     */
    private $instagram;
    private $token;

    private $currentLead;
    private $currenrLeadPercentage;
    private $rudewords;
    private $hashtags;

    private $locationId;


    private $usernamePercentage = 0;
    private $bioPercentage = 0;
    private $followersPercentage = 0;
    private $followingPercentage = 0;
    private $hashtagPercentage = 0;

    private $bioRudePercentage = 0;
    private $captionRudePercentage = 0;
    private $hashtagsUsed = [];

    public function login() {
        $instagram = new Instagram();
        $instagram->login('sololuxury.official', "NcG}4u'z;Fm7");
        $this->token = Signatures::generateUUID();
        $this->instagram = $instagram;
    }

    public function getUserDetails($user, $onlyFollow = false) {
        $this->login();
        $userInfo = $this->instagram->people->getInfoByName($user)->asArray();

        $userId = $userInfo['user']['pk'];
        $followers = $this->instagram->people->getFollowers($userId, $this->token)->asArray()['users'];
        $following = $this->instagram->people->getFollowing($userId, $this->token)->asArray()['users'];

        if ($onlyFollow) {
            return [$followers, $following];
        }

        $request = $this->instagram->request('https://www.instagram.com/'.$user.'/?__a=1')->getDecodedResponse();
        if (!isset($request["graphql"]['user']['edge_owner_to_timeline_media']['edges'])) {
            return [false, $userInfo];
        }

        $medias = $request["graphql"]['user']['edge_owner_to_timeline_media']['edges'];
        $captions = '';

        foreach ($medias as $media) {
            $captions .= ($media['node']['edge_media_to_caption']['edges'][0]['node']['text'] ?? '') . ' ';
        }

        $userInfo['caption'] = $captions;
        $userInfo['info'] = $userInfo['user'];
        $userInfo['followers'] = $followers;
        $userInfo['following'] = $following;

        return $userInfo;

    }

    public function getOverallLeadPercentage($lead) {
        $this->currentLead = $lead;
        $this->currenrLeadPercentage = 0;
        $this->rudewords = DB::table('rude_words')->get()->pluck(['value'])->toArray();
        $this->hashtags = DB::table('hash_tags')->get()->pluck(['hashtag'])->toArray();

        return $this->isUsernameGood()
                ->getBioPercentage()
                ->getHashtagsPercentage()
                ->getFollowersPercentage()
                ->getFollowingPercentage()
                ->getPercentage();
    }

    private function getPercentage(){

        $usernamePercentage = $this->usernamePercentage;
        $bioPercentage = $this->bioPercentage;
        $followersPercentage = $this->followersPercentage;
        $followingPercentage = $this->followingPercentage;
        $hashtagPercentage = $this->hashtagPercentage;

        $bioRudePercentage = $this->bioRudePercentage;
        $captionRudePercentage = $this->captionRudePercentage;
        $decreasePercentage = 0;

        if ($bioRudePercentage || $captionRudePercentage) {
            $decreasePercentage = 10;
        }

        $percentSum = ($usernamePercentage/100)*5;
        $params = 1;
        if ($bioPercentage !== false) {
            $params++;
            $percentSum += ($bioPercentage/100)*5;
        }
        if ($hashtagPercentage !== false) {
            $params++;
            $percentSum += ($hashtagPercentage/100)*60;
        }
        if ($followingPercentage !== false) {
            $params++;
            $percentSum += ($followingPercentage/100)*15;
        }

        if ($followersPercentage !== false) {
            $params++;
            $percentSum += ($followersPercentage/100)*15;
        }

        if ($params >= 3) {
            $this->currenrLeadPercentage = $percentSum - $decreasePercentage;
        }

        return [
            $this->currenrLeadPercentage,
            [
                'bio' => $this->bioPercentage,
                'username' => $this->usernamePercentage,
                'hashtags' => $this->hashtagPercentage,
                'following' => $this->followingPercentage,
                'followers' => $this->followersPercentage,
            ]
        ];
    }

    private function isUsernameGood() {
        $this->usernamePercentage = 100;
        return $this;
    }

    private function getBioPercentage() {
        $bio = $this->currentLead['info']['biography'];

        if (!$bio) {
            $this->bioPercentage = false;
            return $this;
        }

        $rudeWordNum = 0;
        foreach ($this->rudewords as $word) {
            if (stripos($bio, $word) !== false) {
                $rudeWordNum++;
            }
        }

        if ($rudeWordNum > 0 && $rudeWordNum < 4) {
            $this->bioRudePercentage = 40;
        }

        if ($rudeWordNum > 4) {
            $this->bioRudePercentage = 100;
        }

        if ($rudeWordNum === 0) {
            $this->bioRudePercentage += 30;
        }

        $hashtagPresenseCount = 0;
        foreach ($this->hashtags as $hashtag) {
            if (stripos($bio, $hashtag) !== false) {
                $hashtagPresenseCount++;
            }
        }

        if ($hashtagPresenseCount > 0) {
            $this->bioPercentage += 70;
        }


        return $this;
    }

    private function getHashtagsPercentage() {
        $caption = str_replace(' ', '', $this->currentLead['caption']);


        if (!$caption) {
            $this->hashtagPercentage = false;
            return $this;
        }

        $rudeWordNum = 0;
        foreach ($this->rudewords as $word) {
            if (stripos($caption, $word) !== false) {
                $rudeWordNum++;
            }
        }

        if ($rudeWordNum > 0 && $rudeWordNum < 4) {
            $this->captionRudePercentage = 40;
        }

        if ($rudeWordNum > 4) {
            $this->captionRudePercentage = 100;
        }

        if ($rudeWordNum === 0) {
            $this->hashtagPercentage += 10;
        }

        $hashtagPresenceCount = 0;
        foreach ($this->hashtags as $hashtag) {
            if (!in_array($hashtag, $this->hashtagsUsed) && stripos($caption, $hashtag) !== false) {
                $this->hashtagsUsed[] = $hashtag;
                    $hashtagPresenceCount++;
            }
        }



        if ($hashtagPresenceCount > 0 && $hashtagPresenceCount <= 5) {
            $this->hashtagPercentage += 60;

            return $this;
        }

        if ($hashtagPresenceCount > 5) {
            $this->hashtagPercentage += 100;
        }

        return $this;
    }

    private function getFollowersPercentage() {

        $followers = $this->currentLead['followers'];
        $numFollows = 0;
        $totalFollowers = count($followers);


        if ($totalFollowers === 0) {
            $this->followersPercentage = false;
            return $this;
        }

        foreach ($followers as $follower) {
            $username = $follower['username'];
            $record = TargetedAccounts::where('username', $username)->first();

            if ($record) {
                $numFollows++;
            }
        }


        $followPercent = ($numFollows/$totalFollowers)*100;


        if ($followPercent === 0) {
            $this->followersPercentage = 10;
            return $this;
        }

        if ($followPercent < 5) {
            $this->followersPercentage = 40;
            return $this;
        }

        if ($followPercent > 5) {
            $this->followersPercentage = 100;
        }

        return $this;
    }

    private function getFollowingPercentage() {

        $followers = $this->currentLead['following'];
        $numFollows = 0;
        $totalFollowers = count($followers);

        if ($totalFollowers === 0) {
            $this->followingPercentage = false;
            return $this;
        }

        foreach ($followers as $follower) {
            $username = $follower['username'];
            $record = TargetedAccounts::where('username', $username)->first();

            if ($record) {
                $numFollows++;
            }
        }

        $followPercent = ($numFollows/$totalFollowers)*100;

        if ($followPercent === 0) {
            $this->followingPercentage = 10;
            return $this;
        }

        if ($followPercent < 5) {
            $this->followingPercentage = 40;
            return $this;
        }

        if ($followPercent > 5) {
            $this->followingPercentage = 100;
            return $this;
        }

        return $this;
    }

    public function getHashtagUsed() {
        return $this->hashtagsUsed;
    }

    public function sendMessageTo($username) {
        $instagram = new Instagram();
        $instagram->login('daddys.princess.bd', 'splitter41');
        $instagram->direct->sendText(['users' => [$username]], 'Hey there! You can get a lot of luxury products like shoes, jewelery and what not. Visit sololuxury.com for more details :)');
    }
}
