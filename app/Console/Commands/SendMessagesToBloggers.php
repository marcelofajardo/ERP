<?php

namespace App\Console\Commands;

use App\Account;
use App\CronJobReport;
use App\Influencers;
use App\InfluencersDM;
use App\InstagramAutomatedMessages;
use Carbon\Carbon;
use Illuminate\Console\Command;
use InstagramAPI\Instagram;

class SendMessagesToBloggers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bloggers:send-message';

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

            $bloggers = Influencers::whereDoesntHave('message')->get();

            foreach ($bloggers as $blogger) {
                $targetUsername = $blogger->username;
                echo "$targetUsername \n";

                if (!$targetUsername) {
                    continue;
                }

                $account = Account::where('platform', 'instagram')->inRandomOrder()->first();
                $message = '';

                $ig = new Instagram();

                try {
                    $ig->login($account->last_name, $account->password);
//                $ig->login('rishabh_aryal', 'R1shabh@12345');
                    $userinfo = $ig->people->getInfoByName($targetUsername)->asArray();
                } catch (\Exception $exception) {
                    continue;
                }

                $message = InstagramAutomatedMessages::where('type', 'text')
                    ->where('sender_type', 'normal')
                    ->where('receiver_type', 'inf_dm')
                    ->where('status', '1')
                    ->orderBy('use_count', 'ASC')
                    ->first();

                $ig->direct->sendText([
                    'users' => [
                        $userinfo['user']['pk'],
                    ],
                ], $message->message);

                $msg                = new InfluencersDM();
                $msg->influencer_id = $blogger->id;
                $msg->message_id    = $message->id;
                $msg->save();

            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
