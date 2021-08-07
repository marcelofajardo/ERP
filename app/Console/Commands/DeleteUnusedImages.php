<?php

namespace App\Console\Commands;

use App\CronJobReport;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeleteUnusedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unused-images';

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

            dd('stap');
            $file_types = array(
                'gif',
                'jpg',
                'jpeg',
                'png',
            );
            $directory = public_path('uploads');
            $files     = File::allFiles($directory);

            // dd($files);

            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $file_types)) {
                    $filename = pathinfo($file, PATHINFO_FILENAME);

                    if (DB::table('media')->where('filename', '=', $filename)->count()) {
                        dump('in-use');
                        continue; // continue if the picture is in use
                    }

                    echo 'removed' . basename($file) . "<br />";
                    unlink($file); // delete if picture isn't in use
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
