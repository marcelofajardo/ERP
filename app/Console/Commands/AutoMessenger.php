<?php

namespace App\Console\Commands;

use App\AutoReply;
use App\ChatMessage;
use App\CommunicationHistory;
use App\CronJobReport;
use App\Customer;
use App\Order;
use App\PrivateView;
use App\ScheduledMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoMessenger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:auto-messenger';

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

            $params = [
                'number'   => null,
                'user_id'  => 6,
                'approved' => 0,
                'status'   => 9, // auto message status
            ];

            $communication_histories = CommunicationHistory::where('type', 'refund-initiated')->where('model_type', 'App\Order')->where('method', 'email')->get();
            $now                     = Carbon::now();

            foreach ($communication_histories as $history) {
                $time_diff = Carbon::parse($history->created_at)->diffInHours($now);
                // $time_diff = Carbon::parse($history->created_at)->diffInMinutes($now);

                if ($time_diff == 12) {
                    // if ($time_diff == 10) {
                    $order                 = Order::find($history->model_id);
                    $params['customer_id'] = $order->customer_id;
                    $params['message']     = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-refund-alternative')->first()->reply;

                    $chat_message = ChatMessage::create($params);

                    // try {
                    // app('App\Http\Controllers\WhatsAppController')->sendWithWhatsApp($order->customer->phone, $order->customer->whatsapp_number, $params['message'], false, $chat_message->id);
                    // } catch {
                    //   // ok
                    // }

                    // $chat_message->update([
                    //   'approved'  => 1
                    // ]);
                }
            }

            // Follow Up Sequence
            $follow_ups = CommunicationHistory::where('type', 'initiate-followup')->where('model_type', 'App\Customer')->where('method', 'whatsapp')->where('is_stopped', 0)->get();
            $now        = Carbon::now();

            foreach ($follow_ups as $follow_up) {
                $time_diff = Carbon::parse($follow_up->created_at)->diffInHours($now);
                // $time_diff = Carbon::parse($follow_up->created_at)->diffInMinutes($now);

                dump("FOLLOWUP - $time_diff");

                if ($time_diff == 24) {
                    // if ($time_diff == 10) {
                    $customer              = Customer::find($follow_up->model_id);
                    $params['customer_id'] = $customer->id;
                    $params['message']     = AutoReply::where('type', 'auto-reply')->where('keyword', 'customer-followup-24')->first()->reply;

                    $chat_message = ChatMessage::create($params);

                    // try {
                    // app('App\Http\Controllers\WhatsAppController')->sendWithWhatsApp($order->customer->phone, $order->customer->whatsapp_number, $params['message'], false, $chat_message->id);
                    // } catch {
                    //   // ok
                    // }

                    // $chat_message->update([
                    //   'approved'  => 1
                    // ]);
                }

                if ($time_diff == 48) {
                    // if ($time_diff == 20) {
                    $customer              = Customer::find($follow_up->model_id);
                    $params['customer_id'] = $customer->id;
                    $params['message']     = AutoReply::where('type', 'auto-reply')->where('keyword', 'customer-followup-48')->first()->reply;

                    $chat_message = ChatMessage::create($params);

                    // try {
                    // app('App\Http\Controllers\WhatsAppController')->sendWithWhatsApp($order->customer->phone, $order->customer->whatsapp_number, $params['message'], false, $chat_message->id);
                    // } catch {
                    //   // ok
                    // }

                    // $chat_message->update([
                    //   'approved'  => 1
                    // ]);
                }

                if ($time_diff == 72) {
                    // if ($time_diff == 30) {
                    $customer              = Customer::find($follow_up->model_id);
                    $params['customer_id'] = $customer->id;
                    $params['message']     = AutoReply::where('type', 'auto-reply')->where('keyword', 'customer-followup-72')->first()->reply;

                    $chat_message = ChatMessage::create($params);

                    // try {
                    // app('App\Http\Controllers\WhatsAppController')->sendWithWhatsApp($order->customer->phone, $order->customer->whatsapp_number, $params['message'], false, $chat_message->id);
                    // } catch {
                    //   // ok
                    // }

                    // $chat_message->update([
                    //   'approved'  => 1
                    // ]);

                    // On last follow up stop it
                    $follow_up->is_stopped = 1;
                    $follow_up->save();
                }
            }

            // Refunds Workflow

            $refunded_orders = Order::where('refund_answer', 'no')->get();
            $now             = Carbon::now();

            foreach ($refunded_orders as $order) {
                $time_diff = Carbon::parse($order->refund_answer_date)->diffInHours($now);
                // $time_diff = Carbon::parse($order->refund_answer_date)->diffInMinutes($now);
                dump("Refund No - $time_diff");
                if ($time_diff == 48) {
                    // if ($time_diff == 10) {
                    $params['customer_id'] = $order->customer_id;
                    $params['message']     = AutoReply::where('type', 'auto-reply')->where('keyword', 'refund-in-process')->first()->reply;

                    $chat_message = ChatMessage::create($params);

                    // try {
                    // app('App\Http\Controllers\WhatsAppController')->sendWithWhatsApp($order->customer->phone, $order->customer->whatsapp_number, $params['message'], false, $chat_message->id);
                    // } catch {
                    //   // ok
                    // }

                    // $chat_message->update([
                    //   'approved'  => 1
                    // ]);

                    // CommunicationHistory::create([
                    //     'model_id'        => $order->id,
                    //     'model_type'    => Order::class,
                    //     'type'                => 'refund-inprocess',
                    //     'method'            => 'whatsapp'
                    // ]);
                }

                if ($time_diff == 72) {
                    // if ($time_diff == 20) {
                    $params['customer_id'] = $order->customer_id;
                    $params['message']     = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-refund-alternative-72')->first()->reply;

                    $chat_message = ChatMessage::create($params);

                    // try {
                    // app('App\Http\Controllers\WhatsAppController')->sendWithWhatsApp($order->customer->phone, $order->customer->whatsapp_number, $params['message'], false, $chat_message->id);
                    // } catch {
                    //   // ok
                    // }

                    // $chat_message->update([
                    //   'approved'  => 1
                    // ]);

                    // CommunicationHistory::create([
                    //     'model_id'        => $order->id,
                    //     'model_type'    => Order::class,
                    //     'type'                => 'products-suggestion',
                    //     'method'            => 'whatsapp'
                    // ]);

                    sleep(5);

                    $params['message'] = AutoReply::where('type', 'auto-reply')->where('keyword', 'refund-transfer-details')->first()->reply;
                    $chat_message      = ChatMessage::create($params);
                }
            }

            // PRIVATE VIEWING ALERT
            $now           = Carbon::now();
            $private_views = PrivateView::whereNull('status')->get();

            foreach ($private_views as $private_view) {
                $time_diff = Carbon::parse($private_view->date)->diffInHours($now);
                // $time_diff = Carbon::parse($private_view->date)->diffInMinutes($now);
                dump("Private view - $time_diff");
                if ($time_diff == 24) {
                    // if ($time_diff == 10) {
                    $params['customer_id'] = $private_view->customer_id;
                    $params['message']     = AutoReply::where('type', 'auto-reply')->where('keyword', 'private-viewing-reminder')->first()->reply;

                    $chat_message = ChatMessage::create($params);

                    // try {
                    // app('App\Http\Controllers\WhatsAppController')->sendWithWhatsApp($private_view->customer->phone, $private_view->customer->whatsapp_number, $params['message'], false, $chat_message->id);
                    // } catch {
                    //   // ok
                    // }

                    // $chat_message->update([
                    //   'approved'  => 1
                    // ]);

                    // CommunicationHistory::create([
                    //     'model_id'        => $private_view->id,
                    //     'model_type'    => PrivateView::class,
                    //     'type'                => 'private-viewing-alert',
                    //     'method'            => 'whatsapp'
                    // ]);
                }
            }

            // SCHEDULED MESSAGES
            $now                = Carbon::now();
            $scheduled_messages = ScheduledMessage::where('sent', 0)->where('sending_time', '<', $now)->get();

            foreach ($scheduled_messages as $message) {
                if ($message->type == 'customer') {
                    dump('Scheduled Message for Customers');

                    $params = [
                        'number'      => null,
                        'user_id'     => $message->user_id,
                        'customer_id' => $message->customer_id,
                        'approved'    => 0,
                        'status'      => 1,
                        'message'     => $message->message,
                    ];

                    ChatMessage::create($params);

                    $message->sent = 1;
                    $message->save();
                } else if ($message->type == 'task') {
                    dump('Scheduled Reminder Message for Tasks');

                    $additional_params = json_decode($message->data, true);

                    $params = [
                        'number'      => null,
                        'user_id'     => $additional_params['user_id'],
                        'erp_user'    => $additional_params['erp_user'],
                        'task_id'     => $additional_params['task_id'],
                        'contact_id'  => $additional_params['contact_id'],
                        'approved'    => 0,
                        'status'      => 1,
                        'message'     => $message->message,
                        'is_reminder' => 1,
                    ];

                    ChatMessage::create($params);

                    $message->sent = 1;
                    $message->save();
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
