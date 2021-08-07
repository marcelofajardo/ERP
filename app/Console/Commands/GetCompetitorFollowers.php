<?php

namespace App\Console\Commands;

use App\ColdLeads;
use App\CompetitorPage;
use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use InstagramAPI\Instagram;
use InstagramAPI\Signatures;

class GetCompetitorFollowers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compig:get-followers';

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

            $comp      = CompetitorPage::whereRaw('`competitor_pages`.`is_processed` = 0')->first();
            $instagram = new Instagram();
            $instagram->login('sololuxury.official', "NcG}4u'z;Fm7");

            try {
                $profileData = $instagram->people->getInfoByName($comp->username)->asArray();
            } catch (\Exception $exception) {
                dd($exception);
                $profileData = [];
            }

            if (!isset($profileData['user'])) {
                return [];
            }

            $profileData = $profileData['user'];
            $rank        = Signatures::generateUUID();
            $lastId      = $comp->cursor ?? '';

            do {
                try {
                    $followersAll = $instagram->people->getFollowers($profileData['pk'], $rank, '', $lastId)->asArray();
                } catch (\Exception $exception) {
                    sleep(120);
                }
                $followers = $followersAll['users'];
                $lastId    = $followersAll['next_max_id'];

                sleep(5);

                if (strlen($lastId) < 5) {
                    $lastId = 'END';
                }

                foreach ($followers as $follower) {
                    echo $follower['username'] . "\n";

                    $u = CompetitorPage::where('username', $follower['username'])->first();
                    if ($u) {
                        continue;
                    }

                    try {
                        $accountInfo = $instagram->people->getInfoByName($follower['username']);
                    } catch (\Exception $exception) {
                        continue;
                    }

                    $accountInfo = $accountInfo->asArray();
                    $accountInfo = $accountInfo['user'];

                    if ($accountInfo['media_count'] < 20) {
                        continue;
                    }

                    $u              = new ColdLeads();
                    $u->name        = $accountInfo['full_name'];
                    $u->username    = $accountInfo['username'];
                    $u->platform    = 'instagram';
                    $u->platform_id = $accountInfo['pk'];
                    $u->because_of  = 'via ' . $comp->username;
                    $u->rating      = 7;
                    $u->bio         = $accountInfo['biography'];
                    $u->status      = 1;
                    $u->save();

                    echo "CREATED \n";
                    sleep(5);
                }

                $comp->cursor = $lastId;
                $comp->save();

                sleep(5);

            } while ($lastId != 'END');

            $comp->cursor       = 'END';
            $comp->is_processed = 1;
            $comp->save();

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
