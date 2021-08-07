<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\InstagramPosts;
use App\InstagramPostsComments;
use App\Keywords;
use InstagramAPI\Instagram;
use App\InstagramUsersList;
use Carbon\Carbon;

class GetPostFromUserLocalInstagram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:get-user-post-from-local';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            $ch = curl_init();

            // set url
            curl_setopt($ch, CURLOPT_URL, "https://erp.theluxuryunlimited.com/api/local/instagram-user-post");

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // $output contains the output string
            $output = curl_exec($ch);

            // close curl resource to free up system resources
            curl_close($ch); 

            $user = json_decode($output);

            if(isset($user->user_id)){

                $ig = new \InstagramAPI\Instagram();

                try {
                    $ig->login('satyam_t', 'Schoolrocks93');
                } catch (\Exception $e) {
                    echo 'Something went wrong: '.$e->getMessage()."\n";
                    exit(0);
                }

                $feed = $ig->timeline->getUserFeed($user->user_id);
                $medias = $feed->asArray();
                $medias = $medias['items'];
                foreach ($medias as $media) {
                    
                    $postId = $media['id'];
                    $caption = $media['caption']['text'];
                    $user_id = $user->user_id;

                    if ($media['media_type'] === 1) {
                        $mediaDetail = $media['image_versions2']['candidates'][1]['url'];
                    } else if ($media['media_type'] === 2) {
                        $mediaDetail = $media['video_versions'][0]['url'];
                    } else if ($media['media_type'] === 8) {
                        $crousal = $media['carousel_media'];
                        $mediaDetail = [];
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
                    $mediaType = $media['media_type'];
                    $comment_count = $media['comment_count'];
                    $likes = $media['like_count'];
                    $code = $media['code'];
                    
                    $media             = new InstagramPosts();
                    $media->post_id    = $postId;
                    $media->caption    = $caption;
                    $media->user_id    = $user_id;
                    $media->username   = $media['user']['username'];
                    $media->media_type = $mediaType;
                    $media->code       = $code;
                    $media->location   = '';
                    $media->hashtag_id = 0;
                    $media->likes = $likes;
                    $media->comments_count = $comment_count;

                    if (!is_array($mediaDetail)) {
                        $mediaDetail = [$mediaDetail];
                    }
                    $media->media_url = json_encode($mediaDetail);
                    $media->posted_at = $media['posted_at'];
                    //$media->save();
                    $postData = $media->toArray();
                    
                   
                    echo "Sleeping for 15s...\n";
                    dump('Getting Posts');
                    sleep(15);
                    //Getting Comments

                    $comments = $ig->media->getComments($postId)->asArray();
                    
                    if(isset($comments['comments'])){
                        foreach ($comments['comments'] as $comment) {
                            
                            $commentEntry = InstagramPostsComments::where('comment_id', $comment['pk'])->where('user_id', $comment['user']['pk'])->first();

                            if (!$commentEntry) {
                                $commentEntry = new InstagramPostsComments();
                            }

                            $commentEntry->user_id = $comment['user']['pk'];
                            $commentEntry->name = $comment['user']['full_name'];
                            $commentEntry->username = $comment['user']['username'];
                            $commentEntry->instagram_post_id = '';
                            $commentEntry->comment_id = $comment['pk'];
                            $commentEntry->comment = $comment['text'];
                            $commentEntry->profile_pic_url = $comment['user']['profile_pic_url'];
                            $commentEntry->posted_at = Carbon::createFromTimestamp($comment['created_at'])->toDateTimeString();
                            //$commentEntry->save();
                            $postComments[] = $commentEntry;
                            echo "Sleeping for 15s...\n";
                            dump("Getting Comments");
                            sleep(15);
                        

                        }
                    }   
                      

                    //Get User Information 
                    if(!isset($postData)){
                        $postData = [];
                    }

                    if(!isset($userData)){
                        $userData = [];
                    }

                    if(!isset($postComments)){
                        $postComments = [];
                    }

                    $details = ['post' => $postData , 'userdetials' => $userData,'comments' => $postComments];    
                    
                    
                    $url = 'https://erp.theluxuryunlimited.com/api/local/instagram-post';

                    //Initiate cURL.
                    $ch = curl_init($url);
                    //Tell cURL that we want to send a POST request.
                    curl_setopt($ch, CURLOPT_POST, 1);
                     
                    //Attach our encoded JSON string to the POST fields.
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($details));
                     
                    //Set the content type to application/json
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
                     
                    //Execute the request
                    $result = curl_exec($ch);
                    
                    
                    echo "Sleeping for 15s...\n";
                    sleep(15);
                    
                    dump('User Details Stored');

                }
                
            }


    }
}
