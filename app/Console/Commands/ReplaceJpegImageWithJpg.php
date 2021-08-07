<?php

namespace App\Console\Commands;

use App\CronJobReport;
use Illuminate\Console\Command;
use Plank\Mediable\Media;
use Carbon\Carbon;

class ReplaceJpegImageWithJpg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:jpegtojpg';

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

            $medias = Media::where('extension', 'jpeg')->get();

            foreach ($medias as $media) {
                $absolutePath    = $media->getAbsolutePath();
                $newAbsolutePath = substr($absolutePath, 0, -4) . 'jpg';

                if (file_exists($absolutePath)) {
                    dump('exists..');
                }

                try {
                    rename($absolutePath, $newAbsolutePath);
                    $media->extension = 'jpg';
                    $media->save();
                    dump('done...');
                } catch (\Exception $exception) {
//                dump($exception);
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
