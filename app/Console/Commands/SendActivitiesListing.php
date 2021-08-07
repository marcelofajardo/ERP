<?php

namespace App\Console\Commands;

use App\Benchmark;
use App\CronJobReport;
use App\Mails\Manual\ActivityListings;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendActivitiesListing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:activity-listings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends 2 users activity listings daily';

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

            $start = Carbon::now()->format('Y-m-d 00:00:00');
            $end   = Carbon::now()->format('Y-m-d 23:59:00');

            $results = DB::select('
                  SELECT causer_id,subject_type,COUNT(*) AS total FROM
                    (SELECT DISTINCT activities.subject_id,activities.subject_type,activities.causer_id
                       FROM activities
                       WHERE activities.description = "create"
                       AND activities.causer_id IN (70,73)
                       AND activities.created_at BETWEEN ? AND ?)
                    AS SUBQUERY
                    GROUP BY subject_type,causer_id;
              ', [$start, $end]);

            $results2 = DB::select('
                  SELECT subject_type,COUNT(*) AS total FROM
                    (SELECT DISTINCT activities.subject_id,activities.subject_type
                       FROM activities
                       WHERE activities.description = "create"
                       AND activities.causer_id IN (70,73)
                       AND activities.created_at BETWEEN ? AND ?)
                    AS SUBQUERY
                    GROUP BY subject_type;
              ', [$start, $end]);

            $benchmark = Benchmark::whereBetween('for_date', [$start, $end])
                ->selectRaw('sum(selections) as selections,
                                           sum(searches) as searches,
                                           sum(attributes) as attributes,
                                           sum(supervisor) as supervisor,
                                           sum(imagecropper) as imagecropper,
                                           sum(lister) as lister,
                                           sum(approver) as approver,
                                           sum(inventory) as inventory')
                ->get()->toArray();

            $rows = [];

            foreach ($results as $result) {
                $rows[$result->causer_id]['selection']    = 0;
                $rows[$result->causer_id]['searcher']     = 0;
                $rows[$result->causer_id]['attribute']    = 0;
                $rows[$result->causer_id]['supervisor']   = 0;
                $rows[$result->causer_id]['imagecropper'] = 0;
                $rows[$result->causer_id]['lister']       = 0;
                $rows[$result->causer_id]['approver']     = 0;
                $rows[$result->causer_id]['inventory']    = 0;
                $rows[$result->causer_id]['sales']        = 0;
            }

            foreach ($results as $result) {
                $rows[$result->causer_id][$result->subject_type] = $result->total;
            }

            $total_data = [];

            $total_data['selection']    = 0;
            $total_data['searcher']     = 0;
            $total_data['attribute']    = 0;
            $total_data['supervisor']   = 0;
            $total_data['imagecropper'] = 0;
            $total_data['lister']       = 0;
            $total_data['approver']     = 0;
            $total_data['inventory']    = 0;
            $total_data['sales']        = 0;

            foreach ($results2 as $result) {
                $total_data[$result->subject_type] += $result->total;
            }

            $data['results']    = $rows;
            $data['total_data'] = $total_data;
            $data['benchmark']  = $benchmark[0];

            Mail::to('yogeshmordani@icloud.com')->send(new ActivityListings($data));

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
