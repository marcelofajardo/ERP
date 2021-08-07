<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\InstagramAutoComments;
use Carbon\Carbon;
use Illuminate\Console\Command;
use InstagramAPI\Instagram;

class GetCommentTemplatesFromDifferentWebsites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:get-comments-for-auto-reply';

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
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $accounts = [
                'darveys', 'farfetch',
            ];

            foreach ($accounts as $account) {
                echo "====================== $account ==================";
                $instagram = new Instagram();
                $instagram->login('sololuxury.official', "NcG}4u'z;Fm7");
                $response = $instagram->request('https://www.instagram.com/' . $account . '/?__a=1')->getDecodedResponse();

                $medias = $response['graphql']['user']['edge_owner_to_timeline_media']['edges'];

                foreach ($medias as $media) {
                    $mediaId  = $media['node']['id'];
                    $comments = $instagram->media->getComments($mediaId)->asArray();

                    if ($comments['comment_count'] === 0) {
                        continue;
                    }

                    foreach ($comments['comments'] as $comment) {
                        $text         = strtolower($comment['text']);
                        $text         = str_replace('farfetch', 'Sololuxury', $text);
                        $text         = str_replace('darveys', 'Sololuxury', $text);
                        $text         = str_replace('farfect', 'Sololuxury', $text);
                        $c            = new InstagramAutoComments();
                        $c->source    = $account;
                        $c->comment   = $text;
                        $c->use_count = 0;
                        $c->save();
                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
