<?php

namespace App\Console\Commands;

use DB;
use App\User;
use App\UserRate;
use App\PaymentReceipt;
use App\Hubstaff\HubstaffActivity;
use Illuminate\Console\Command;

class CreatePreviousPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:previous-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Previous Payments';

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
            // Start transaction
            DB::beginTransaction();

            // Get first entry activity.
            $firstEntryInActivity = HubstaffActivity::orderBy('starts_at')->first();

            // Start date default value.
            $bigining = $firstEntryInActivity 
                ? date('Y-m-d', strtotime($firstEntryInActivity->starts_at))
                : date('Y-m-d');

            // Get all active records which does not payed yet. 
            $activityRecords = HubstaffActivity::getAllTrackedActivities();

            $paidCounts = 0;

            foreach( $activityRecords as $record) {

                $total = 0;
                $minutes = 0;
                $start_date = $bigining;
                $end_date = date('Y-m-d',strtotime("-1 days"));

                // Get user rate connected with start date.
                $latestRatesOnDate = UserRate::latestRatesOnDate($record->starts_at, $record->hm_user_id);

                // Get user last payment.
                $lastPayment = PaymentReceipt::where('user_id',$record->hm_user_id)->orderBy('date','DESC')->first();

                // Change start date if user last payment exist.
                if($lastPayment) {
                    $start_date = date('Y-m-d',strtotime($lastPayment->date . "+1 days"));
                }

                // Make hubstaff activity paid.
                if($record->tracked > 0 && $latestRatesOnDate && $latestRatesOnDate->hourly_rate > 0) {
                    $total = $total + ($record->tracked/60)/60 * $latestRatesOnDate->hourly_rate;
                    $minutes = $minutes + $record->tracked/60;
                    $record->paid = 1;
                    $record->save();
                    $paidCounts += 1;
                }
                
                // Create new payment receipt.
                if($total > 0) {
                    $total = number_format($total,2);
                    $paymentReceipt = new PaymentReceipt;
                    $paymentReceipt->worked_minutes = $minutes;
                    $paymentReceipt->status = 'Pending';
                    $paymentReceipt->rate_estimated = $total;
                    $paymentReceipt->date = $end_date;
                    $paymentReceipt->user_id = $record->hm_user_id;
                    $paymentReceipt->billing_start_date = $start_date;
                    $paymentReceipt->billing_end_date = $end_date;
                    $paymentReceipt->currency = ''; //we need to change this.
                    $paymentReceipt->save();
                }

            }

            // Commit transaction.
            DB::commit();
            if (!$paidCounts) {
                echo PHP_EOL . "Not paid hubstaff activities not found.".  PHP_EOL;
            } else {
                echo PHP_EOL . $paidCounts . " hubstaff activities make paid.".  PHP_EOL;
            }
            echo PHP_EOL . "=====DONE====" . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollBack();
            echo PHP_EOL . "=====FAILED====" . PHP_EOL;
        }
    }
}
