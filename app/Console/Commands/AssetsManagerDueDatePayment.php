<?php

namespace App\Console\Commands;

use App\AssetsManager;
use App\CashFlow;
use Illuminate\Console\Command;

class AssetsManagerDueDatePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assetsmanagerduedate:pay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command checks due date and add to cashflow';

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
        $results = AssetsManager::whereDate('due_date', date('Y-m-d'))->get();
        if (count($results) == 0) {
            return $this->info(" no record exist");
        }
        $count = count($results);

        $i       = 0;
        $success = false;
        foreach ($results as $result) {
            //create entry in table cash_flows
            CashFlow::create(
                [
                    'description'           => 'Asset Manager Payment for id ' . $result->id,
                    'date'                  => date('Y-m-d'),
                    'amount'                => $result->amount,
                    'expected'              => $result->amount,
                    'actual'                => $result->amount,
                    'type'                  => 'paid',
                    'cash_flow_able_id'     => $result->id,
                    'cash_flow_category_id' => $result->category_id,
                    'cash_flow_able_type'   => 'App\AssetsManager',
                ]
            );
            $i++;
            if ($i == $count) {
                $success = true;
            }
        }
        if ($success == true) {
            return $this->info("payment added to cashflow successfully");
        }
    }
}
