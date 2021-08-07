<?php

namespace App\Console\Commands;

use App\Account;
use App\AutoCommentHistory;
use App\AutoReplyHashtags;
use App\Comment;
use App\CronJobReport;
use App\InstagramAutoComments;
use App\Services\Instagram\Hashtags;
use Illuminate\Console\Command;
use InstagramAPI\Instagram;
use Carbon\Carbon;

class AutoCommentBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:auto-comment-hashtags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $accounts = [];

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
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $hashtag = AutoReplyHashtags::where('status', 1)->first();

            $counter = 0;

            $hashtags = new Hashtags();
            $hashtags->login();
            $cursor = '';

            $commentCount = 0;

//        do {

//            [$posts, $cursor] = $hashtags->getFeed($hashtag->text, $cursor);

            $posts = AutoCommentHistory::where('status', 1)->take(50)->get();

            foreach ($posts as $post) {

                $country = $post->country;

                $comment = new InstagramAutoComments();
                $account = Account::where('platform', 'instagram')->where('bulk_comment', 1);

                if (strlen($country) >= 4) {
                    $comment = $comment->where(function ($query) use ($country) {
                        $query->where('country', $country)->orWhereNull('country');
                    });
                    $account = $account->where(function ($q) use ($country) {
                        $q->where('country', $country)->orWhereNull('country');
                    });
                }

                $caption = $post->caption;
                $caption = str_replace(['#', '@', '!', '-' . '/'], ' ', $caption);
                $caption = explode(' ', $caption);

                $comment = $comment->where(function ($query) use ($caption) {
                    foreach ($caption as $i => $cap) {
                        if (strlen($cap) > 3) {
                            $cap = trim($cap);
                            if ($i === 0) {
                                $query = $query->where('options', 'LIKE', "%$cap%");
                                continue;
                            }
                            $query = $query->orWhere('options', 'LIKE', "%$cap%");
                        }
                    }
                });

                $account = $account->inRandomOrder()->first();
                $comment = $comment->inRandomOrder()->first();

                if (!$comment) {
                    $comment = InstagramAutoComments::where('options', null)->orWhere('options', '[]')->inRandomOrder()->first();
                }

                if (!isset($this->accounts[$account->id])) {
                    $ig = new Instagram();
                    echo $account->last_name . "\n";
                    $ig->login($account->last_name, $account->password);
                    $this->accounts[$account->id] = $ig;
                }

                $this->accounts[$account->id]->media->comment($post->post_id, $comment->comment);

                $post->status     = 0;
                $post->account_id = $account->id;
                $post->comment    = $comment->comment;
                $post->save();

                sleep(5);
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
