<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\CronJobReport;
use App\Message;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TransferMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:messages';

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

            $messages = Message::all();

            foreach ($messages as $key => $message) {
                dump("$key Transferring");

                if ($message->status == 3) {
                    $status = 2;
                } else {
                    $status = $message->status;
                }

                $chat_message = ChatMessage::create([
                    'user_id'     => $message->userid,
                    'customer_id' => $message->customer_id,
                    'assigned_to' => $message->assigned_to,
                    'message'     => $message->body,
                    'status'      => $status,
                    'approved'    => $status == 2 ? 1 : 0,
                    'created_at'  => $message->created_at,
                ]);

                if ($message->hasMedia(config('constants.media_tags'))) {
                    foreach ($message->getMedia(config('constants.media_tags')) as $image) {
                        $chat_message->attachMedia($image, config('constants.media_tags'));
                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
