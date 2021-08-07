<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Meetings\ZoomMeetings;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ZoomMeetingRecordings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meeting:getrecordings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To get zoom recordings based on meeting id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->zoomkey    = env('ZOOM_API_KEY');
        // $this->zoomsecret = env('ZOOM_API_SECRET');
        $this->zoomkey = config('env.ZOOM_API_KEY');
        $this->zoomsecret = config('env.ZOOM_API_SECRET');
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

            $zoomKey    = $this->zoomkey;
            $zoomSecret = $this->zoomsecret;
            $meetings   = new ZoomMeetings();
            $date       = Carbon::now();
            $meetings->getRecordings($zoomKey, $zoomSecret, $date);
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
        exit('Data inserted in db..Now, you can check meetings screen');
    }
}
