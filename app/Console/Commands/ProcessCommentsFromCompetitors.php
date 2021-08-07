<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\HashTag;
use App\InstagramPosts;
use App\InstagramPostsComments;
use App\Keywords;
use App\Services\Instagram\Hashtags;
use Carbon\Carbon;
use Illuminate\Console\Command;
use InstagramAPI\Instagram;
use App\InstagramUsersList;

class ProcessCommentsFromCompetitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'competitors:process-users';

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
        //try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $hashtag = HashTag::where('is_processed', 0)->first();
            if (!$hashtag) {
                return;
            }

            $hashtagText = $hashtag->hashtag;
            $hashtagId = $hashtag->id;
            $hash = new Hashtags();
            $hash->login();
            $maxId = '';

            $keywords = Keywords::get()->pluck('text')->toArray();

            do {
                $hashtagPostsAll = $hash->getFeed($hashtagText, $maxId);

                [$hashtagPosts, $maxId] = $hashtagPostsAll;

                foreach ($hashtagPosts as $hashtagPost) {
                    
                    $location = $hashtagPost['location'];

                    if (is_array($location)) {
                        $location_field = $location['name'];
                    } else {
                        $location_field = '';
                    }

                    $code   = $hashtagPost['code'];
                    $postId = $hashtagPost['media_id'];


                    $checkIfExist = InstagramPosts::where('post_id', $postId)->first();

                    if ($checkIfExist != null || $checkIfExist != '') {
                        continue;
                    }

                    $media             = new InstagramPosts();
                    $media->post_id    = $postId;
                    $media->caption    = $hashtagPost['caption'];
                    $media->user_id    = $hashtagPost['user_id'];
                    $media->username   = $hashtagPost['username'];
                    $media->media_type = $hashtagPost['media_type'];
                    $media->code       = $code;
                    $media->location   = $location_field;
                    $media->hashtag_id = $hashtagId;
                    $media->likes = $hashtagPost['like_count'];
                    $media->comments_count = $hashtagPost['comment_count'];



                    if (!is_array($hashtagPost['media'])) {
                        $hashtagPost['media'] = [$hashtagPost['media']];
                    }

                    $media->media_url = json_encode($hashtagPost['media']);
                    $media->posted_at = $hashtagPost['posted_at'];
                    $media->save();

                    echo "Sleeping for 15s...\n";
                    dump('Getting Posts');
                    sleep(15);
                    //Getting Comments

                    $comments = $hash->instagram->media->getComments($hashtagPost['media_id'])->asArray();
                    
                    if(isset($comments['comments'])){
                        foreach ($comments['comments'] as $comment) {
                            
                            $commentEntry = InstagramPostsComments::where('comment_id', $comment['pk'])->where('user_id', $comment['user']['pk'])->first();

                            if (!$commentEntry) {
                                $commentEntry = new InstagramPostsComments();
                            }

                            $commentEntry->user_id = $comment['user']['pk'];
                            $commentEntry->name = $comment['user']['full_name'];
                            $commentEntry->username = $comment['user']['username'];
                            $commentEntry->instagram_post_id = $media->id;
                            $commentEntry->comment_id = $comment['pk'];
                            $commentEntry->comment = $comment['text'];
                            $commentEntry->profile_pic_url = $comment['user']['profile_pic_url'];
                            $commentEntry->posted_at = Carbon::createFromTimestamp($comment['created_at'])->toDateTimeString();
                            $commentEntry->save();

                            echo "Sleeping for 15s...\n";
                            dump("Getting Comments");
                            sleep(15);
                        

                        }
                    }   
                      

                    //Get User Information 
                    $user = $hash->getUserInfo($hashtagPost['user_id']);
                    $info = $user['user'];

                    if(!isset($info['city_name'])){
                        $cityName = '';
                    }else{
                        $cityName = $info['city_name'];
                    }
                    $userList = InstagramUsersList::where('user_id',$hashtagPost['user_id'])->first();
                    if(empty($userList)){
                        $user = new InstagramUsersList;
                        $user->username = $info['username'];
                        $user->user_id = $hashtagPost['user_id'];
                        $user->image_url = $info['profile_pic_url'];
                        $user->bio = $info['biography'];
                        $user->rating = 0;
                        $user->location_id = 0;
                        $user->because_of = $hashtagText;
                        $user->posts = $info['media_count'];
                        $user->followers = $info['follower_count'];
                        $user->following = $info['following_count'];
                        $user->location = $cityName;
                        $user->save(); 
                    }
                        
                    
                    echo "Sleeping for 15s...\n";
                    sleep(15);
                    
                    dump('User Details Stored');

                }
            } while ($maxId != 'END');

            $hashtag->is_processed = true;
            $hashtag->save();

            $report->update(['end_time' => Carbon::now()]);
        // } catch (\Exception $e) {
        //     \App\CronJob::insertLastError($this->signature, $e->getMessage());
        // }

    }
}
