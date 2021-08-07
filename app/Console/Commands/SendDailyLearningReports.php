<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\DailyActivity;
use App\Learning;
use App\Exports\HourlyReportsExport;
use App\Mails\Manual\HourlyReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class SendDailyLearningReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:daily-reports-learning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduling notification';

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

            

            $daily_activities = DailyActivity::whereNotNull('repeat_type')->where('for_date',$date)->where('type','learning')->get();

            foreach ($daily_activities as $key) {


                $learning_record = Learning::find($key->type_table_id);

                if(!$learning_record){
                    continue;
                }


                $start_date = Carbon::parse($date);
                $end_date   = Carbon::parse( $key->repeat_end_date );
                
                if( $key->repeat_type == 'daily' ){
                
                        $selected = DailyActivity::find( $key->id );
                        $copy = $selected->replicate()->fill(
                            [
                                'for_date' => Carbon::now()->addDays(8)
                            ]
                        );
                        $copy->save();

                        $today = Carbon::today()->toDateString();
                        for ($i = 0; $i < 7; $i++) {
                            $today = Carbon::parse($today)->addDay()->toDateString();
                            $newLearning = $learning_record->replicate();
                            $newLearning->created_at = Carbon::now();
                            $newLearning->learning_duedate = $today;
                            $newLearning->save();
                        }
                         
               
                }elseif( $key->repeat_type == 'weekly' ){
                     if( $key->repeat_end == 'on' && $now->between( $start_date, $end_date ) ){
                        $selected = DailyActivity::find( $key->id );
                        $copy = $selected->replicate()->fill(
                            [
                                'for_date' => Carbon::parse("next $key->repeat_on")->toDateString(),
                            ]
                        );
                        $copy->save();
                        
                        $newLearning = $learning_record->replicate();
                        $newLearning->created_at = Carbon::now();
                        $newLearning->learning_duedate = Carbon::now();
                        $newLearning->save();

                    }elseif( $key->repeat_end == 'never' ){
                        $selected = DailyActivity::find( $key->id );
                        $copy = $selected->replicate()->fill(
                            [
                                'for_date' => Carbon::parse("next $key->repeat_on")->toDateString(),
                            ]
                        );
                        $copy->save();

                        $newLearning = $learning_record->replicate();
                        $newLearning->created_at = Carbon::now();
                        $newLearning->learning_duedate = Carbon::now();
                        $newLearning->save();
                    }

                }elseif( $key->repeat_type == 'monthly' ){
                    
                    if( $key->repeat_end == 'on' && $now->between( $start_date, $end_date ) ){
                        $selected = DailyActivity::find( $key->id );
                        $copy = $selected->replicate()->fill(
                            [
                                'for_date' => Carbon::parse($key->for_date)->addMonth(),
                            ]
                        );
                        $copy->save();

                        $newLearning = $learning_record->replicate();
                        $newLearning->created_at = Carbon::now();
                        $newLearning->learning_duedate = Carbon::now();
                        $newLearning->save();

                    }elseif( $key->repeat_end == 'never' ){
                        $selected = DailyActivity::find( $key->id );
                        $copy = $selected->replicate()->fill(
                            [
                                'for_date' => Carbon::parse("next $key->repeat_on")->toDateString(),
                            ]
                        );
                        $copy->save();

                        $newLearning = $learning_record->replicate();
                        $newLearning->created_at = Carbon::now();
                        $newLearning->learning_duedate = Carbon::now();
                        $newLearning->save();
                    }

                }
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
