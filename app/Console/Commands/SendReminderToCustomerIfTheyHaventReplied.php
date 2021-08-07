<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\CronJobReport;
use App\Customer;
use App\Http\Controllers\WhatsAppController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendReminderToCustomerIfTheyHaventReplied extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send-to-customer';

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

            $now = Carbon::now()->toDateTimeString();

            //get latest messages for each customer ignoring the auto messages
            $messagesIds = DB::table('chat_messages')
                ->selectRaw('MAX(id) as id, customer_id')
                ->groupBy('customer_id')
                ->whereNotNull('message')
                //->where("frequency",">",0)
                ->where('customer_id', '>', '0')
                ->where(function ($query) {
                    $query->whereNotIn('status', [7, 8, 9, 10]);
                })
                ->get();

            foreach ($messagesIds as $messagesId) {
                $customer = Customer::find($messagesId->customer_id);
                if (!$customer) {
                    continue;
                }

                $frequency = $customer->frequency;
                if (!($frequency >= 5)) {
                    continue;
                }

                if($customer->reminder_from == "0000-00-00 00:00" || strtotime($customer->reminder_from) >= strtotime("now")) {
                    dump('here' . $customer->name);
                    $templateMessage = $customer->reminder_message;
                    if($customer->reminder_last_reply == 0) {
                        //sends messahe
                        $this->sendMessage($customer->id, $templateMessage);
                    }else{
                        // get the message if the interval is greater or equal to time which is set for this customer
                        $message = ChatMessage::whereRaw('TIMESTAMPDIFF(MINUTE, `updated_at`, "' . $now . '") >= ' . $frequency)
                            ->where('id', $messagesId->id)
                            ->where('user_id', '>', '0')
                            ->where('approved', '1')
                            ->first();

                        if (!$message) {
                            continue;
                        }
                        $this->sendMessage($customer->id, $templateMessage);
                    }
                    dump('saving...');
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }

    /**
     * @param $customer
     * @param $message
     * Send message to customer, create message and then approve message...
     */
    private function sendMessage($customer, $message): void
    {

        $params = [
            'number'      => null,
            'user_id'     => 6,
            'approved'    => 1,
            'status'      => 1,
            'customer_id' => $customer,
            'message'     => $message,
        ];

        $chat_message = ChatMessage::create($params);

        $myRequest = new Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['messageId' => $chat_message->id]);

        app(WhatsAppController::class)->approveMessage('customer', $myRequest);
    }
}
