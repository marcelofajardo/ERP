<?php

namespace App\Services\Instagram;


use App\HashTag;
use App\InstagramUsersList;
use App\TargetLocation;
use Carbon\Carbon;
use InstagramAPI\Instagram;
use InstagramAPI\Signatures;
use Config;

/**
 * Class Hashtags
 * @package App\Services\Instagram
 */
class Hashtags {

    /**
     * Instagram $instagram
     */
    public $instagram;
    private $token;

    /**
     * Logs in to the Instagram account
     */
    public function login($username = false, $password = false) {
        $instagram = new Instagram();
     //   $instagram->login('rishabh_aryal', 'R1shabh@12345');
        if(empty($username)) {
            $username = Config('instagram.admin_account');
        }
        if(empty($password)) {
            $password = Config('instagram.admin_password');
        }
        $instagram->setProxy('http://lum-customer-sololuxury-zone-instagramtest-country-in-city-mumbai:p9j2ow55p47j@zproxy.lum-superproxy.io:22225');
        $instagram->login($username , $password);
        $this->token = Signatures::generateUUID();
        $this->instagram = $instagram;
    }

    /**
     * @param $hashtag
     * @return mixed
     * returns the media count for a given hashtag
     */
    public function getMediaCount($hashtag) {
        return $this->instagram->hashtag->getInfo($hashtag)->asArray()['media_count'];
    }

    /**
     * @param $hashtag
     * @param string $maxId
     * @param null $country
     * @param null $keywords
     * @return array
     * Thie method returns the feeds by maxId, which acts as the cursor and loads until it reaches end, it used the Instagram mgp25 package
     */
    public function getFeed($hashtag, $maxId = '', $country = null, $keywords = null)
    {
        $media = $this->instagram->hashtag->getFeed($hashtag, $this->token, $maxId);
        $medias = $media->asArray();
        $maxId = $medias['next_max_id'] ?? 'END';
        $filteredMedia = [];

        //getting post in 3 array format
        foreach ($medias as $items) {
            //dd($items);
            if(is_array($items)){
                foreach ($items as $item) {
                   //getting each post
                    if(isset($item['layout_content']['medias'])){
                        $posts = $item['layout_content']['medias'];
                   
                       foreach ($posts as $post) {
                            foreach ($post as $p) {
                                $cap = $p['caption']['text'];
                                $show = true;
                                if ($keywords) {
                                    $show = false;
                                    foreach ($keywords as $keyword) {
                                        if (stripos(strtoupper($cap), strtoupper($keyword)) !== false) {
                                            $show = true;
                                            continue;
                                        }
                                    }
                                }

                                if (!$show) {
                                    continue;
                                }
                                // get all the hashtag inside this description
                                preg_match_all("/(#\w+)/", $cap, $matches);

                                $matches = $matches[0];
                                foreach ($matches as $match) {
                                    $match = str_replace('#', '', $match);
                                    $ht = HashTag::where('hashtag', $match)->first();
                                    // save the hashtag if its already not there
                                    if (!$ht) {
                                        $ht = new HashTag();
                                        $ht->hashtag = $match;
                                        $ht->rating = 5;
                                        $ht->save();
                                    }
                                }



                                // media_type : 1 image., 2 video, 8 multiple images + videos

                                if ($p['media_type'] === 1) {
                                    $media = $p['image_versions2']['candidates'][1]['url'];
                                } else if ($p['media_type'] === 2) {
                                    $media = $p['video_versions'][0]['url'];
                                } else if ($p['media_type'] === 8) {
                                    $crousal = $p['carousel_media'];
                                    $media = [];
                                    foreach ($crousal as $cro) {
                                        if ($cro['media_type'] === 1) {
                                            $media[] = [
                                                'media_type' => 1,
                                                'url' => $cro['image_versions2']['candidates'][0]['url']
                                            ];
                                        } else if ($cro['media_type'] === 2) {
                                            $media[] = [
                                                'media_type' => 2,
                                                'url' => $cro['video_versions'][0]['url']
                                            ];
                                        }
                                    }
                                }


                                $comments = [];

                                if (isset($p['comment_count']) && $p['comment_count']) {
                                    $comments = $p['preview_comments'];
                                }

                                $x =  $p['location']['lat'] ?? 0;
                                $y = $p['location']['lng'] ?? 0;

                                $point = new Location();

                                // if we dont have location, then simple get the post, else check if the post location is actually located in the list of our saved location
                                $target = TargetLocation::where('region', $country)->first();
                                if (!$target) {
                                    $filteredMedia[$p['taken_at']] = [
                                        'hashtag' => $hashtag,
                                        'username' => $p['user']['username'],
                                        'user_id' => $p['user']['pk'],
                                        'media_id' => $p['id'],
                                        'code' => $p['code'],
                                        'caption' => $p['caption']['text'],
                                        'like_count' => $p['like_count'],
                                        'comment_count' => $p['comment_count'] ?? '0',
                                        'media_type' => $p['media_type'],
                                        'media' => $media,
                                        'comments' => $comments,
                                        'location' => $p['location'] ?? '',
                                        'ts' => $p['taken_at'] ?? 0,
                                        'created_at' => Carbon::createFromTimestamp($p['taken_at'])->diffForHumans(),
                                        'posted_at' => Carbon::createFromTimestamp($p['taken_at'])->toDateTimeString(),
                                    ];
                                } else {
                                    $location = $point->pointInParticularLocation($x, $y, $target); // this will check if the given points are in the location
                                    if ($location[0]) {

                                        $l = InstagramUsersList::where('user_id', $p['user']['pk'])->first();

                                        if (!$l) {
                                            $l = new InstagramUsersList();
                                        }

                                        $l->username = $p['user']['username'];
                                        $l->user_id = $p['user']['pk'];
                                        $l->image_url = $p['user']['profile_pic_url'];
                                        $l->bio = $p['user']['biography'] ?? 'N/A';
                                        $l->rating = 0;
                                        $l->location_id = $location[1]->id ?? 1;
                                        $l->because_of = "Hashtags: $hashtag";
                                        $l->save();

                                        $filteredMedia[$p['taken_at']] = [
                                            'hashtag' => $hashtag,
                                            'username' => $p['user']['username'],
                                            'user_id' => $p['user']['pk'],
                                            'media_id' => $p['id'],
                                            'code' => $p['code'],
                                            'caption' => $p['caption']['text'],
                                            'like_count' => $p['like_count'],
                                            'comment_count' => $p['comment_count'] ?? '0',
                                            'media_type' => $p['media_type'],
                                            'media' => $media,
                                            'comments' => $comments,
                                            'location' => $p['location'] ?? '',
                                            'ts' => $p['taken_at'] ?? 0,
                                            'created_at' => Carbon::createFromTimestamp($p['taken_at'])->diffForHumans(),
                                            'posted_at' => Carbon::createFromTimestamp($p['taken_at'])->toDateTimeString(),
                                        ];
                                    }
                                }
                            }
                            
                       }
                    }
                   

                }
            }
            
        }

        return [$filteredMedia, $maxId];
    }

    /**
     * @param $hashtag
     * @return mixed
     * get related hashtag using the mgp25 instagram package
     */
    public function getRelatedHashtags($hashtag)
    {
        return $this->instagram->hashtag->getRelated($hashtag)->asArray();
    }


    /**
     * @param $hashtag
     * @return mixed
     * get related hashtag using the mgp25 instagram package
     */
    public function getUserInfo($userId)
    {
        return $this->instagram->people->getInfoById($userId)->asArray();
    }
}
