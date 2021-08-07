<?php

namespace App\Console\Commands;

use App\Complaint;
use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FlagCustomersIfTheyHaveAComplaint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flag:customers-with-complaints';

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

            Complaint::where('is_customer_flagged', 0)->chunk(1000, function ($complaints) {
                foreach ($complaints as $complaint) {
                    $customer = $complaint->customer;
                    if ($customer) {
                        dump('flagging...');
                        $customer->is_flagged = 1;
                        $customer->save();
                        $complaint->is_customer_flagged = 1;
                        $complaint->save();
                    }
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
