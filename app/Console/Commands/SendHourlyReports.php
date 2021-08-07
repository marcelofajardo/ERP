<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\DailyActivity;
use App\Exports\HourlyReportsExport;
use App\Mails\Manual\HourlyReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class SendHourlyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:hourly-reports';

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

            $now  = Carbon::now();
            $date = Carbon::now()->format('Y-m-d');
            $nine = Carbon::parse('09:00');
            $one  = Carbon::parse('13:00');
            $four = Carbon::parse('16:00');

            if ($now->between($nine, $one)) {
                $time_slots = [
                    '09:00am - 10:00am',
                    '10:00am - 11:00am',
                    '11:00am - 12:00pm',
                ];
            } elseif ($now->between($one, $four)) {
                $time_slots = [
                    '12:00pm - 01:00pm',
                    '01:00pm - 02:00pm',
                    '02:00pm - 03:00pm',
                ];
            } else {
                $time_slots = [
                    '03:00pm - 04:00pm',
                    '04:00pm - 05:00pm',
                    '05:00pm - 06:00pm',
                ];
            }

            $daily_activities = DailyActivity::where('for_date', $date)
                ->whereIn('time_slot', $time_slots)
                ->get()->groupBy('user_id');

            if (count($daily_activities) > 0) {
                $path = "hourly_reports/" . $date . "_hourly_reports.xlsx";
                Excel::store(new HourlyReportsExport(), $path, 'files');

                Mail::to('hr@sololuxury.co.in')
                    ->send(new HourlyReport($path));
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
