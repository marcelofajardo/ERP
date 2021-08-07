<?php

namespace App\Console\Commands;

use App\AutoReply;
use App\ChatMessage;
use App\CronJobReport;
use App\PrivateView;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDeliveryDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:delivery-details';

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
                'status'   => 1,
            ];

            $tomorrow      = Carbon::now()->addDay()->format('Y-m-d');
            $private_views = PrivateView::where('date', 'LIKE', "%$tomorrow%")->get();
            $coordinators  = User::role('Delivery Coordinator')->get();

            foreach ($private_views as $private_view) {
                dump('Private Viewing');

                $product_information = '';

                foreach ($private_view->products as $key => $product) {
                    if ($key == 0) {
                        $product_information .= "$product->name - Size $product->size - $product->color";
                    } else {
                        $product_information .= ", $product->name - Size $product->size - $product->color";
                    }
                }

                $address = $private_view->customer->address . ", " . $private_view->customer->pincode . ", " . $private_view->customer->city;

                $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'private-viewing-details')->first();

                $auto_message = preg_replace("/{customer_name}/i", $private_view->customer->name, $auto_reply->reply);
                $auto_message = preg_replace("/{customer_phone}/i", $private_view->customer->phone, $auto_message);
                $auto_message = preg_replace("/{customer_address}/i", $address, $auto_message);
                $auto_message = preg_replace("/{product_information}/i", $product_information, $auto_message);

                // $params['message'] = "Details for Private Viewing: Customer - " . $private_view->customer->name . ", Phone: " . $private_view->customer->phone . ", Address: $address" . "; Products $product_information";
                $params['message'] = $auto_message;

                foreach ($coordinators as $coordinator) {
                    dump('Sending Message to Coordinator ' . $coordinator->name);
                    $params['erp_user'] = $coordinator->id;
                    $chat_message       = ChatMessage::create($params);

                    $whatsapp_number = $coordinator->whatsapp_number != '' ? $coordinator->whatsapp_number : null;

                    if ($whatsapp_number == '919152731483') {
                        app('App\Http\Controllers\WhatsAppController')->sendWithNewApi($coordinator->phone, $whatsapp_number, $params['message'], null, $chat_message->id);
                    } else {
                        app('App\Http\Controllers\WhatsAppController')->sendWithWhatsApp($coordinator->phone, $whatsapp_number, $params['message'], false, $chat_message->id);
                    }

                    $chat_message->update([
                        'approved' => 1,
                        'status'   => 2,
                    ]);
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
