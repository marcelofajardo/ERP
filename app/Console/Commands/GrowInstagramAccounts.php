<?php

namespace App\Console\Commands;

use App\Account;
use App\CronJobReport;
use Illuminate\Console\Command;
use Carbon\Carbon;
use InstagramAPI\Instagram;

class GrowInstagramAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:grow-accounts';

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

            $accounts = Account::where('is_seeding', 1)->get();

            foreach ($accounts as $account) {
                $username = $account->last_name;
                $password = $account->password;

                $instagram = new Instagram();

                try {
                    $instagram->login($username, $password);
                } catch (\Exception $exception) {
                    $this->warn($account->last_name);
                    $this->info($exception->getMessage());
                    continue;
                }

                $this->warn($username);

                $stage = $account->seeding_stage;

                $account->manual_comment = 1;
                $account->save();

                if ($stage >= 7) {
                    $account->bulk_comment   = 1;
                    $account->manual_comment = 0;
                    $account->is_seeding     = 0;
                    $account->save();
                    continue;
                }

                $imageSet = [
                    0 => ['1', '2'],
                    1 => ['3', '4'],
                    2 => ['5', '6'],
                    3 => ['7', '8'],
                    4 => ['9', '10'],
                    5 => ['11', '12'],
                    6 => ['13', '14'],
                ];

                $followSet = [
                    0 => ['gucci', 'prada'],
                    1 => ['givenchyofficial', 'tods'],
                    2 => ['alexandermcqueen', 'burberry'],
                    3 => ['balenciaga', 'bulgariofficial'],
                    4 => ['dolcegabbana', 'bottegaveneta'],
                    5 => ['celine', 'chloe'],
                    6 => ['dior', 'fendi'],
                ];

                $imagesToPost = $imageSet[$stage];
                try {
                    $id1 = $instagram->people->getUserIdForName($followSet[$stage][0]);
                    $id2 = $instagram->people->getUserIdForName($followSet[$stage][1]);
                } catch (\Exception $exception) {
                    $this->info($exception->getMessage());
                }

                $instagram->people->follow($id1);
                $instagram->people->follow($id2);

                foreach ($imagesToPost as $i) {
                    $filename             = __DIR__ . '/images/' . $i . '.jpeg';
                    $source               = imagecreatefromjpeg($filename);
                    list($width, $height) = getimagesize($filename);

                    $newwidth  = 800;
                    $newheight = 800;

                    $destination = imagecreatetruecolor($newwidth, $newheight);
                    imagecopyresampled($destination, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    imagejpeg($destination, __DIR__ . '/images/' . $i . '.jpeg', 100);

                    try {
                        $instagram->timeline->uploadPhoto($filename);
                    } catch (\Exception $exception) {
                        $this->info($exception->getMessage());
                    }

                }

                ++$account->seeding_stage;
                $account->save();

            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
