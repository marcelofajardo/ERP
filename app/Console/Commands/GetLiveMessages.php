<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetLiveMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live:message';

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
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            $login    = \Config('livechat.account_id');
            $password = \Config('livechat.password');
            $curl     = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => "https://api.livechatinc.com/v3.1/agent/action/get_chats_summary",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => "{}",
                CURLOPT_USERPWD, "$login:$password",
                CURLOPT_HTTPHEADER     => array(
                    "Accept: */*",
                    "Accept-Encoding: gzip, deflate",
                    "Authorization: Basic NTYwNzZkODktZjJiZi00NjUxLTgwMGQtNzE5YmEyNTYwOWM5OmRhbDpUQ3EwY2FZYVRrMndCTHJ3dTgtaG13",
                    "Cache-Control: no-cache",
                    "Connection: keep-alive",
                    "Content-Length: 2",
                    "Content-Type: application/json",
                    "Cookie: AASID=AA1-DAL10",
                    "Host: api.livechatinc.com",
                    "Postman-Token: 4cedf58b-a89a-4654-bb94-20ab2936060b,97c6a781-69d0-47a5-925e-527a02523144",
                    "User-Agent: PostmanRuntime/7.19.0",
                    "cache-control: no-cache",
                ),
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {

                $chats   = json_decode($response);
                $summary = $chats->chats_summary;
                $count   = $chats->found_chats;

                foreach ($summary as $chat) {

                    //Getting Customers
                    $user = $chat->users[0];

                    //FInding if customers is present in database
                    $customer = Customer::where('email', $user->email)->first();
                    //dd($user);
                    if ($customer != null) {
                        $id = $customer->id;
                    } else {
                        $customer        = new Customer();
                        $customer->name  = $user->name;
                        $customer->email = $user->email;
                        $customer->save();
                        $id = $customer->id;
                    }

                    if (isset($id) && $id != 0 && $id != null) {

                        $message                         = new ChatMessage;
                        $message->message                = $chat->last_event_per_type->message->event->text;
                        $message->customer_id            = $id;
                        $message->message_application_id = 2;
                        $message->save();
                        echo ('Message Saved');

                    }
                }
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
