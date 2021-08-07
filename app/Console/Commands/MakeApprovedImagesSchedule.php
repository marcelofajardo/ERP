<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Image;
use App\ImageSchedule;
use App\ScheduleGroup;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MakeApprovedImagesSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:create-schedule';

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
            $images = $this->getImages();

            foreach ($images as $image) {
                $schedule                = new ImageSchedule();
                $schedule->image_id      = $image;
                $schedule->facebook      = 1;
                $schedule->instagram     = 1;
                $schedule->description   = 'Auto Scheduled';
                $schedule->scheduled_for = Carbon::tomorrow()->toDateString() . ' ' . date('H:i:00');
                $schedule->status        = 0;
                $schedule->save();
            }

            $scheduleGroup                = new ScheduleGroup();
            $scheduleGroup->images        = $images;
            $scheduleGroup->scheduled_for = Carbon::tomorrow()->toDateString() . ' ' . date('H:i:00');
            $scheduleGroup->description   = 'Auto Scheduled';
            $scheduleGroup->status        = 1;
            $scheduleGroup->save();

            Image::whereIn('id', $images)->update([
                'is_scheduled' => 1,
            ]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function getImages($count = 10)
    {

        $report = CronJobReport::create([
            'signature'  => $this->signature,
            'start_time' => Carbon::now(),
        ]);

        if ($count === 0) {
            $images = Image::where('is_scheduled', 0)->where('status', 2)->inRandomOrder()->take(1)->pluck('id')->toArray();
            return $images;
        }

        $schedules = ImageSchedule::orderBy('id', 'DESC')->take($count)->pluck('image_id');

        $brands = Image::whereIn('id', $schedules)->pluck('brand');

        $images = Image::where('is_scheduled', 0)->where('status', 2)->whereNotIn('brand', $brands)->take(1)->pluck('id')->toArray();

        if ($images) {
            return $images;
        }

        return $this->getImages($count - 1);

        $report->update(['end_time' => Carbon::now()]);
    }
}
