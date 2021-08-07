<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Customer;
use App\Jobs\SendMessageToAll;
use App\Jobs\SendMessageToSelected;
use App\MessageQueue;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RunMessageQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:message-queues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    // custom defined vars
    const WAITING_MESSAGE_LIMIT = 300;

    // waiting messages group
    public $waitingMessages = [];

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
        return; // STOP ALL
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $time    = Carbon::now();
            $morning = Carbon::create($time->year, $time->month, $time->day, 8, 0, 0);
            $evening = Carbon::create($time->year, $time->month, $time->day, 17, 00, 0);

            if ($time->between($morning, $evening, true)) {
                // Get groups
                $groups = DB::table('message_queues')->groupBy("group_id")->select("group_id")->get(['group_id']);

                $allWhatsappNo         = config("apiwha.instances");
                $this->waitingMessages = [];
                if (!empty($allWhatsappNo)) {
                    foreach ($allWhatsappNo as $no => $dataInstance) {
                        $waitingMessage             = $this->waitingLimit($no);
                        $this->waitingMessages[$no] = $waitingMessage;
                    }
                }

                foreach ($groups as $group) {
                    // Get messages
                    $message_queues = MessageQueue::where('group_id', $group->group_id)
                        ->where('sending_time', '<=', Carbon::now())
                        ->where('sent', 0)
                        ->where('status', '!=', 1)
                        ->orderBy('sending_time', 'ASC')
                        ->limit(12);

                    // Do we have results?
                    if (count($message_queues->get()) > 0) {
                        foreach ($message_queues->get() as $message) {

                            // check message can able to send
                            $number = !empty($message->whatsapp_number) ? (string) $message->whatsapp_number : 0;

                            if ($message->type == 'message_all') {

                                $customer = Customer::find($message->customer_id);
                                $number   = !empty($customer->whatsapp_number) ? (string) $customer->whatsapp_number : 0;

                                // No number? Set to default
                                if ($number == 0 || !key_exists($number, $allWhatsappNo)) {
                                    foreach ($allWhatsappNo as $no => $dataInstance) {
                                        if ($dataInstance['customer_number'] == true) {
                                            $customer->whatsapp_number = $no;
                                            $customer->save();
                                            $number = $no;
                                            break;
                                        }
                                    }
                                }

                                if (!$this->isWaitingFull($number)) {
                                    if ($customer && $customer->do_not_disturb == 0 && substr($number, 0, 3) == '971') {
                                        SendMessageToAll::dispatchNow($message->user_id, $customer, json_decode($message->data, true), $message->id, $group->group_id);

                                        dump('sent to all');
                                    } else {
                                        $message->delete();

                                        dump('deleting queue');
                                    }
                                } else {
                                    if (substr($number, 0, 3) == '971') {
                                        dump('sorry , message is full right now for this number : ' . $number);
                                    } else {
                                        $message->delete();
                                        dump('deleting queue');
                                    }
                                }

                            } else {

                                if (!$this->isWaitingFull($number)) {
                                    if (substr($message->whatsapp_number, 0, 3) == '971') {
                                        SendMessageToSelected::dispatchNow($message->phone, json_decode($message->data, true), $message->id, $message->whatsapp_number, $message->group_id);
                                    } else {
                                        $message->delete();
                                    }

                                    dump('sent to selected');
                                } else {
                                    dump('sorry , message is full right now for this number : ' . $number);
                                }
                            }

                            // start to add more if there is existing already
                            if (isset($this->waitingMessages[$number])) {
                                $this->waitingMessages[$number] = $this->waitingMessages[$number] + 1;
                            } else {
                                $this->waitingMessages[$number] = 1;
                            }
                        }
                    }
                }
            } else {
                dump('Not the right time for sending');
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    /**
     * Check waiting is full for given number
     *
     */

    private function isWaitingFull($number)
    {
        $number = !empty($number) ? $number : 0;

        if (isset($this->waitingMessages[$number]) && $this->waitingMessages[$number] > self::WAITING_MESSAGE_LIMIT) {
            return true;
        }

        return false;
    }

    /**
     * Get instance from whatsapp number
     *
     */

    private function getInstance($number = null)
    {
        $number = !empty($number) ? $number : 0;

        return isset(config("apiwha.instances")[$number])
        ? config("apiwha.instances")[$number]
        : config("apiwha.instances")[0];

    }

    /**
     * send request for find waiting message number
     *
     */

    private function waitingLimit($number = null)
    {
        $instance   = $this->getInstance($number);
        $instanceId = isset($instance["instance_id"]) ? $instance["instance_id"] : 0;
        $token      = isset($instance["token"]) ? $instance["token"] : 0;

        $waiting = 0;

        if (!empty($instanceId) && !empty($token)) {
            // executing curl
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => "https://api.chat-api.com/instance$instanceId/showMessagesQueue?token=$token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 300,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPHEADER     => array(
                    "content-type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);
            curl_close($curl);

            if ($err) {
                // throw some error if you want
            } else {
                $result = json_decode($response, true);
                if (isset($result["totalMessages"]) && is_numeric($result["totalMessages"])) {
                    $waiting = $result["totalMessages"];
                }
            }

        }

        return $waiting;

    }
}
