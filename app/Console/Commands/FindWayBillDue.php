<?php

namespace App\Console\Commands;

use App\Waybillinvoice;
use App\CashFlow;
use App\CronJobReport;
use App\Email;
use App\EmailAddress;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use seo2websites\ErpExcelImporter\ErpExcelImporter;
use Webklex\IMAP\Client;

/**
 * @author Sukhwinder <sukhwinder@sifars.com>
 * This command takes care of receiving all the emails from the smtp set in the environment
 *
 * All fetched emails will go inside emails table
 */
class FindWayBillDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:waybilldue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check date - if date over then status will be due';

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
        
        $report = CronJobReport::create([
            'signature'  => $this->signature,
            'start_time' => Carbon::now(),
        ]);

        Waybillinvoice::where("status","!=","paid")->whereDate('due_date','<',Carbon::today())->update(['status'=>'due']);
    }

    /**
     * Check all the emails in the DB and extract the model type from there
     *
     * @param [type] $email
     * @param [type] $email_list
     * @return array(model_id,miodel_type)
     */
    private function getModel($email, $email_list)
    {
        $model_id = null;
        $model_type = null;

        // Traverse all models
        foreach ($email_list as $key => $value) {
            // If email exists in the DB
            if (isset($value[$email])) {
                $model_id = $value[$email];
                $model_type = $key;
                break;
            }
        }

        return compact('model_id', 'model_type');
    }
}
