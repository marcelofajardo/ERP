<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AssetsManager;
use App\CashFlow;
use Carbon\Carbon;
class AssetsManagerPaymentCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assetsmanagerpayment:cron {payment_cycle}';

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
        $payment_cycle = $this->argument('payment_cycle');
        if(!$payment_cycle){
            $this->info(" please input payment_cycle 'Weekly' or 'Daily' or 'Monthly' or 'Bi-Weekly' or 'Yearly' ");
            return false;
        }
        switch ($payment_cycle) {
            case 'Weekly':
                $this->addPaymentCycleToCashflow('Weekly');
              break;
            case 'Daily':
                $this->addPaymentCycleToCashflow('Daily');
              break;
            case 'Monthly':
                $this->addPaymentCycleToCashflow('Monthly');
              break;
            case 'Bi-Weekly':
                $this->addPaymentCycleToCashflow('Bi-Weekly');
                break;
            case 'Yearly':
                $this->addPaymentCycleToCashflow('Yearly');
                break;
            default:
            $this->info(" please input payment_cycle 'Weekly' or 'Daily' or 'Monthly' or 'Bi-Weekly' or 'Yearly' ");
          }         
    }
    public function addPaymentCycleToCashflow($payment_cycle){
        $results = AssetsManager::where('payment_cycle',$payment_cycle)->where(function($q){
          $q->whereDate("due_date",date("Y-m-d"))->orWhere("due_date","=","")->orWhereNull("due_date");
        })->get();
        if(count($results)==0){
           return $this->info(" no record exist for ".$payment_cycle." payments ");
           
        }
        $count = count($results);
        $i=0;
        $success =false;
        foreach($results as $result){
            
            //create entry in table cash_flows
            /* CashFlow::create(
                [
                    'description'=>'Asset Manager Payment for id '.$result->id,
                    'date'=>date('Y-m-d'),
                    'amount'=>$result->amount,
                    'type'=>'paid',
                    'cash_flow_able_type'=>'App\AssetsManager',
                ]
            ); */

            if($result->payment_cycle =='Weekly')
            {
                AssetsManager::where('id',$result->id)->update(['due_date'=>Carbon::parse('next monday')->toDateString()]);
            }elseif($result->payment_cycle =='Monthly'){
                AssetsManager::where('id',$result->id)->update(['due_date'=>Carbon::now()->day(5)->addMonth(1)->toDateString()]);
            }elseif($result->payment_cycle =='Bi-Weekly'){
                AssetsManager::where('id',$result->id)->update(['due_date'=>Carbon::now()->addWeeks(2)->toDateString()]);
            }elseif($result->payment_cycle =='Yearly'){
                AssetsManager::where('id',$result->id)->update(['due_date'=>Carbon::now()->addYear()->toDateString()]);
            }elseif($result->payment_cycle =='Daily'){
                AssetsManager::where('id',$result->id)->update(['due_date'=>Carbon::now()->addDay(1)->toDateString()]);
            }
            $i++;
            if($i==$count){
                $success=true;
            }
        }
        if($success==true){
        return $this->info($payment_cycle." payment added to cashflow successfully ");
        }
    }
}
