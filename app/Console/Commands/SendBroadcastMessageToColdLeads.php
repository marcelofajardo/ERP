<?php

namespace App\Console\Commands;

use App\Account;
use App\ColdLeadBroadcasts;
use App\CronJobReport;
use App\Services\Instagram\Broadcast;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBroadcastMessageToColdLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cold-leads:send-broadcast-messages';

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

            $broadcast = ColdLeadBroadcasts::where('started_at', '<=', date('Y-m-d H:i:s'))
                ->whereRaw('`messages_sent` != `number_of_users`')
                ->first();

            $bs      = new Broadcast();
            $account = Account::where('platform', 'instagram')->where('broadcast', 1)->first();

//        $bs->followUser($broadcast);

            //After users are followed, start DMing 10 people...
            //        sleep(10);
            //
            $leads = $broadcast->lead()->where('cold_leads.status', 1)->where('followed_by', '>', 0)->get();
//        dd(count($leads));
            $message = $broadcast->message;
            $bs->login($account);
            $bs->sendBulkMessages($leads, $message, $broadcast->image, $account, $broadcast);
            $broadcast->save();

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
