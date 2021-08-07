<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\Services\Whatsapp\ChatApi\ChatApi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\MessageQueueHistory;

class SendQueuePendingChatMessagesGroup extends Command
{
    const BROADCAST_PRIORITY        = 8;
    const MARKETING_MESSAGE_TYPE_ID = 3;

    public $waitingMessages;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:queue-pending-chat-group-messages {number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send queue pending chat group messages, run at every 3rd minute';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function getNumberList()
    {

        $q = \DB::table("whatsapp_configs")->select([
            "number", "instance_id", "token", "is_customer_support", "status", "is_default",
        ])->where("instance_id", "!=", "")
            ->where("token", "!=", "")
            ->where("status", 1)
            ->orderBy("is_default", "DESC")
            ->get();

        $noList = [];
        foreach ($q as $queue) {
            $noList[] = $queue->number;
        }

        return $noList;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tempSettingData = \DB::table('settings')->where('name', 'is_queue_sending_limit')->get();

        try {

            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $numberList = [$this->argument('number')];
            \Log::info("send:queue-pending-chat-group-messages " . $this->argument('number') . " : Number found while sending request from the group " . $this->argument('number'));

            // get the status for approval
            $approveMessage = \App\Helpers\DevelopmentHelper::needToApproveMessage();
            $limit          = ChatMessage::getQueueLimit();
            \Log::info("send:queue-pending-chat-group-messages " . $this->argument('number') . " : Message is approve {$approveMessage} and limit found as  " . json_encode($limit));

            // if message is approve then only need to run the queue
            if ($approveMessage == 1) {

                $allWhatsappNo = config("apiwha.instances");

                $this->waitingMessages = [];
                if (!empty($numberList)) {
                    foreach ($numberList as $no) {
                        $chatApi                    = new ChatApi;
                        $waitingMessage             = $chatApi->waitingLimit($no);
                        $this->waitingMessages[$no] = $waitingMessage;
                    }
                }

                \Log::info("send:queue-pending-chat-group-messages " . $this->argument('number') . " : waiting limit is as below  " . json_encode($this->waitingMessages));

                if (!empty($numberList)) {
                    $groups = ChatMessage::where('is_queue', ">", 0)->where("group_id", ">", 0)->groupBy("group_id")->pluck("group_id")->toArray();
                    foreach ($numberList as $number) {

                        $sendLimit = isset($limit[$number]) ? $limit[$number] : 0;
                        \Log::info("send:queue-pending-chat-group-messages " . $this->argument('number') . " : sending limit found " . $sendLimit);
                        foreach ($groups as $group) {
                            // get the group list first
                            $chatMessage = ChatMessage::where('is_queue', ">", 0)
                                ->join("customers as c", "c.id", "chat_messages.customer_id")
                                ->where("chat_messages.group_id", $group)
                                ->where("c.whatsapp_number", $number)
                                ->select("chat_messages.*")
                                ->limit($sendLimit)
                                ->get();

                            \Log::info("send:queue-pending-chat-group-messages " . $this->argument('number') . " : Chat Message found  " . $chatMessage->count());

                            if (!$chatMessage->isEmpty()) {

                                foreach ($chatMessage as $value) {
                                    // check first if message need to be send from broadcast
                                    if ($value->is_queue > 1) {
                                        $sendNumber = \DB::table("whatsapp_configs")->where("id", $value->is_queue)->first();
                                        // if chat message has image then send as a multiple message
                                        if ($images = $value->getMedia(config('constants.media_tags'))) {
                                            foreach ($images as $k => $image) {
                                                \App\ImQueue::create([
                                                    "im_client"                 => "whatsapp",
                                                    "number_to"                 => $value->customer->phone,
                                                    "number_from"               => ($sendNumber) ? $sendNumber->number : $value->customer->whatsapp_number,
                                                    "text"                      => ($k == 0) ? $value->message : "",
                                                    "image"                     => $image->getUrl(),
                                                    "priority"                  => self::BROADCAST_PRIORITY,
                                                    "marketing_message_type_id" => self::MARKETING_MESSAGE_TYPE_ID,
                                                ]);
                                            }
                                        } else {
                                            \App\ImQueue::create([
                                                "im_client"                 => "whatsapp",
                                                "number_to"                 => $value->customer->phone,
                                                "number_from"               => ($sendNumber) ? $sendNumber->number : $value->customer->whatsapp_number,
                                                "text"                      => $value->message,
                                                "priority"                  => self::BROADCAST_PRIORITY,
                                                "marketing_message_type_id" => self::MARKETING_MESSAGE_TYPE_ID,
                                            ]);
                                        }

                                        $value->is_queue = 0;
                                        $value->save();

                                        $dataInsert = array(
                                            'counter'  => $sendLimit,
                                            'number'   => $number,
                                            'type'     => 'group',
                                            'user_id'  => $value->customer_id,
                                            'time'     => Carbon::now()->format('Y-m-d H:i:s')
                                        );
                                        MessageQueueHistory::insert($dataInsert);

                                    } else {

                                        // check message is full or not
                                        $isSendingLimitFull = isset($this->waitingMessages[$value->customer->whatsapp_number])
                                        ? $this->waitingMessages[$value->customer->whatsapp_number] : 0;

                                        \Log::info("send:queue-pending-chat-group-messages " . $this->argument('number') . " : Sending limit  " . $isSendingLimitFull . " < " . config("apiwha.message_queue_limit", 100));
                                        // if message queue is full then go for the next;
                                        if ($isSendingLimitFull >= config("apiwha.message_queue_limit", 100)) {
                                            continue;
                                        }

                                        $myRequest = new Request();
                                        $myRequest->setMethod('POST');
                                        $myRequest->request->add(['messageId' => $value->id]);
                                        app('App\Http\Controllers\WhatsAppController')->approveMessage('customer', $myRequest);

                                        $dataInsert = array(
                                            'counter'  => $sendLimit,
                                            'number'   => $number,
                                            'type'     => 'group',
                                            'user_id'  => $value->customer_id,
                                            'time'     => Carbon::now()->format('Y-m-d H:i:s')
                                        );
                                        MessageQueueHistory::insert($dataInsert);
                                        
                                    }
                                }
                            }
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
