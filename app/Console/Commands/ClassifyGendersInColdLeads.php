<?php

namespace App\Console\Commands;

use App\ColdLeads;
use App\CronJobReport;
use App\PeopleNames;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClassifyGendersInColdLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cold-leads:classify-genders';

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

            $coldLeads = ColdLeads::where('is_gender_processed', 0)->take(10000)->get();

            foreach ($coldLeads as $key => $coldLead) {
                echo "$key \n";
                $coldLead->gender = 'm';

                try {
                    $gender = PeopleNames::whereRaw("INSTR('$coldLead->username', `name`) > 0")->orWhereRaw("INSTR('$coldLead->name', `name`) > 0")->where('name', '!=', '')->first();
                    if ($gender) {
                        $gender           = $gender->gender;
                        $coldLead->gender = $gender;
                    }
                } catch (\Exception $exception) {
                }

                $coldLead->is_gender_processed = 1;
                $coldLead->save();
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
