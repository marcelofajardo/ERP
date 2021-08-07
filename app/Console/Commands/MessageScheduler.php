<?php

namespace App\Console\Commands;

use App\AutoReply;
use App\CronJobReport;
use App\Customer;
use App\ScheduledMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MessageScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:message-scheduler';

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

            $customers     = Customer::where('is_priority', 1)->get();
            $auto_replies  = AutoReply::where('type', 'priority-customer')->whereNotNull('repeat')->get();
            $today_date    = Carbon::now()->format('Y-m-d');
            $today_weekday = strtoupper(Carbon::now()->format('l'));
            $today_day     = Carbon::now()->format('d');
            $today_month   = Carbon::now()->format('m');

            foreach ($auto_replies as $auto_reply) {
                $sending_date    = Carbon::parse($auto_reply->sending_time)->format('Y-m-d');
                $sending_weekday = strtoupper(Carbon::parse($auto_reply->sending_time)->format('l'));
                $sending_day     = Carbon::parse($auto_reply->sending_time)->format('d');
                $sending_month   = Carbon::parse($auto_reply->sending_time)->format('m');

                $params = [
                    'user_id'      => 6,
                    'message'      => $auto_reply->reply,
                    'sending_time' => "$today_date " . Carbon::parse($auto_reply->sending_time)->format('H:m'),
                    'type'         => 'customer',
                ];

                switch ($auto_reply->repeat) {
                    case "Every Day":
                        if ($today_date >= $sending_date) {
                            foreach ($customers as $customer) {
                                $params['customer_id'] = $customer->id;

                                ScheduledMessage::create($params);
                            }
                        }

                        break;
                    case "Every Week":
                        if ($today_date >= $sending_date && $today_weekday == $sending_weekday) {
                            foreach ($customers as $customer) {
                                $params['customer_id'] = $customer->id;

                                ScheduledMessage::create($params);
                            }
                        }

                        break;
                    case "Every Month":
                        if ($today_day == $sending_day) {
                            foreach ($customers as $customer) {
                                $params['customer_id'] = $customer->id;

                                ScheduledMessage::create($params);
                            }
                        }

                        break;
                    case "Every Year":
                        if ($today_day == $sending_day && $today_month == $sending_month) {
                            foreach ($customers as $customer) {
                                $params['customer_id'] = $customer->id;

                                ScheduledMessage::create($params);
                            }
                        }

                        break;
                    default:

                        break;
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
