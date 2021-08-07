<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PaymentReceipt;
use App\User;
use App\Hubstaff\HubstaffActivity;
use App\UserRate;
use DB;
class UserPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make payment request for users';

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
        DB::beginTransaction();
        $users = User::where('fixed_price_user_or_job',2)->get();
        $firstEntryInActivity = HubstaffActivity::orderBy('starts_at')->first();

        if($firstEntryInActivity) {
            $bigining = date('Y-m-d',strtotime($firstEntryInActivity->starts_at));
        }else {
            $bigining = date('Y-m-d');
        }
        foreach($users as $user) {
            $lastPayment = PaymentReceipt::where('user_id',$user->id)->orderBy('date','DESC')->first();
            $start =  $bigining;
            $end =  date('Y-m-d');
            //if($lastPayment) {
                //$start = date('Y-m-d',strtotime($lastPayment->date));
                //$end =  $start;
            //}
            $yesterday = date('Y-m-d',strtotime("-1 days"));
            echo PHP_EOL . "=====Checking $start - $end for $user->id ====" . PHP_EOL;

            $activityrecords  = HubstaffActivity::getTrackedActivitiesBetween($start, $end, $user->id);
            echo PHP_EOL . "===== Result found ".count($activityrecords)." ====" . PHP_EOL;

            $total = 0;
            $minutes = 0;
            $startsAt = null;
            foreach($activityrecords as $record) {
                $latestRatesOnDate = UserRate::latestRatesOnDate($record->starts_at,$user->id);
                if($record->tracked > 0 && $latestRatesOnDate && $latestRatesOnDate->hourly_rate > 0) {
                    $total = $total + ($record->tracked/60)/60 * $latestRatesOnDate->hourly_rate;
                    $minutes = $minutes + $record->tracked/60;
                    $record->paid = 1;
                    $record->save(); 
                    $startsAt = $record->starts_at;
                }
            }

            /*$billingStartDate = ($lastPayment && !empty($startsAt)) ? $startsAt : date("Y-m-d",strtotime("-1 day"));
            if($user->payment_frequency == 'fornightly') {
                $billingEndDate   = date('Y-m-d',strtotime($billingStartDate . "+1 days"));  
                if(strtotime($billingEndDate) > strtotime(date("Y-m-d"))){
                    $billingStartDate = date('Y-m-d',strtotime(date("Y-m-d") . "+1 days"));
                    $billingEndDate   = date('Y-m-d',strtotime($billingStartDate . "+1 days"));  
                }
            }else if($user->payment_frequency == 'weekly') {
                $billingEndDate   = date('Y-m-d',strtotime($billingStartDate . "+7 days"));  
                if(strtotime($billingEndDate) > strtotime(date("Y-m-d"))){
                    $billingStartDate = date('Y-m-d',strtotime(date("Y-m-d") . "+1 days"));
                    $billingEndDate   = date('Y-m-d',strtotime($billingStartDate . "+7 days"));  
                }

            }else if($user->payment_frequency == 'biweekly') {
                $billingEndDate   = date('Y-m-d',strtotime($billingStartDate . "+14 days"));  
                if(strtotime($billingEndDate) > strtotime(date("Y-m-d"))){
                    $billingStartDate = date('Y-m-d',strtotime(date("Y-m-d") . "+1 days"));
                    $billingEndDate   = date('Y-m-d',strtotime($billingStartDate . "+14 days"));  
                }

            }else if($user->payment_frequency == 'monthly') {
                $billingEndDate   = date('Y-m-d',strtotime($billingStartDate . "+30 days"));  
                if(strtotime($billingEndDate) > strtotime(date("Y-m-d"))){
                    $billingStartDate = date('Y-m-d',strtotime(date("Y-m-d") . "+1 days"));
                    $billingEndDate   = date('Y-m-d',strtotime($billingStartDate . "+30 days"));  
                }
            }*/
            

            if($total > 0) {
                $total = number_format($total,2);
                $paymentReceipt = new PaymentReceipt;
                $paymentReceipt->worked_minutes = $minutes;
                $paymentReceipt->status = 'Pending';
                $paymentReceipt->rate_estimated = $total;
                $paymentReceipt->date = $startsAt;
                $paymentReceipt->user_id = $user->id;
                /*$paymentReceipt->billing_start_date = isset($billingStartDate) ? $billingStartDate : null;
                $paymentReceipt->billing_end_date = isset($billingEndDate) ? $billingEndDate : $end;*/
                $paymentReceipt->currency = ''; //we need to change this.
                if($user->billing_frequency_day > 0) {
                    $paymentReceipt->billing_due_date = date("Y-m-d",strtotime($startsAt." +".$user->billing_frequency_day));
                }
                $paymentReceipt->save();


            }
        }
        DB::commit();
        echo PHP_EOL . "=====DONE====" . PHP_EOL;
        } catch (Exception $e) {
            echo $e->getMessage();
            DB::rollBack();
            echo PHP_EOL . "=====FAILED====" . PHP_EOL;
        }
    }
}
