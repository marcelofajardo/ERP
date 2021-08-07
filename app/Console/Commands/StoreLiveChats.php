<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use App\ChatMessage;

class StoreLiveChats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live-chat:get-tickets';

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
        $data = app(\App\Http\Controllers\LiveChatController::class)->getLiveChatIncTickets();
        if($data) {
            foreach ($data as $key => $value) {
                $email =$value['events'][0]['author']['id'];
                $name = $value['events'][0]['author']['name'];
                $uniqueId = $value['id'];
                $message = str_replace('Message:', '',$value['events'][0]['message']);
                $customer = Customer::where('email',$email)->first();
                if($customer == null && $customer == ''){
                    $customer = new Customer;
                    $customer->name = $name;
                    $customer->email = $email;
                    $customer->save();
                }
               $isChatMessageExist = ChatMessage::where('unique_id',$uniqueId)->first();
               if(empty($isChatMessageExist)) {
                    $chatMessage = new ChatMessage;
                    $chatMessage->customer_id = $customer ->id;
                    $chatMessage->message = $message;
                    $chatMessage->unique_id = $uniqueId;
                    $chatMessage->message_application_id = 2;
                    $chatMessage->save();
                }
            }
        }
    }
}
