<?php

namespace App\Console\Commands;

use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use InstagramAPI\Instagram;

class PostMediaToInstagramAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:post-media-to-accounts';

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

            $instagram = new Instagram();

            $accounts = 'shrikirtiraha23,balachander83,ashnauppalapati81,vinayafalodiya55';
            $accounts = explode(',', $accounts);

            foreach ($accounts as $account) {
                try {
                    $instagram->login($account, 'This123!@#');
                } catch (\Exception $exception) {
                    continue;
                }

                for ($i = 1; $i < 10; $i++) {
                    echo "FOR $account \n";
                    $filename             = __DIR__ . '/images/' . $i . '.jpeg';
                    $source               = imagecreatefromjpeg($filename);
                    list($width, $height) = getimagesize($filename);

                    $newwidth  = 800;
                    $newheight = 800;

                    $destination = imagecreatetruecolor($newwidth, $newheight);
                    imagecopyresampled($destination, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                    imagejpeg($destination, __DIR__ . '/images/' . $i . '.jpeg', 100);

                    $instagram->timeline->uploadPhoto($filename);

                    sleep(10);

                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
