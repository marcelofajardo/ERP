<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\CronJobReport;
use App\Customer;
use App\Helpers\OrderHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:auto-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending auto reminders to customers who didn\'t reply';

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

            $params = [
                'number'  => null,
                'status'  => 1,
                'user_id' => 6,
            ];

            $customers = Customer::with(['Orders' => function ($query) {
                $query->where('order_status_id', OrderHelper::$proceedWithOutAdvance)->where('auto_messaged', 1)->latest();
            }])->whereHas('Orders', function ($query) {
                $query->where('order_status_id', OrderHelper::$proceedWithOutAdvance)->where('auto_messaged', 1)->latest();
            })->get()->toArray();

            foreach ($customers as $customer) {
                foreach ($customer['orders'] as $order) {
                    $time_to_send = false;
                    $time_diff    = Carbon::parse($order['auto_messaged_date'])->diffInHours(Carbon::now());

                    if ($time_diff == 24) {
                        $params['customer_id'] = $customer['id'];
                        $params['message']     = 'Reminder about COD after 24 hours';
                        $time_to_send          = true;
                    }

                    if ($time_diff == 72) {
                        $params['customer_id'] = $customer['id'];
                        $params['message']     = 'Please also note that since your order was placed on c o d - an initial advance needs to be paid to process the order - pls let us know how you would like to make this payment.';
                        $time_to_send          = true;
                    }

                    if ($time_to_send) {
                        $chat_messages  = ChatMessage::where('customer_id', $customer['id'])->whereBetween('created_at', [$order['auto_messaged_date'], Carbon::now()])->latest()->get();
                        $received_count = false;

                        foreach ($chat_messages as $chat_message) {
                            if ($chat_message->number) {
                                $received_count = true;
                            }
                        }

                        if (!$received_count) {
                            $chat_message = ChatMessage::create($params);
                        }
                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
