<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\CronJobReport;
use App\Dubbizle;
use App\Http\Controllers\WhatsAppController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendReminderToDubbizlesIfTheyHaventReplied extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send-to-dubbizle';

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

            $messagesIds = DB::table('chat_messages')
                ->selectRaw('MAX(id) as id, dubbizle_id')
                ->groupBy('dubbizle_id')
                ->whereNotNull('message')
                ->where('dubbizle_id', '>', '0')
                ->where(function ($query) {
                    $query->whereNotIn('status', [7, 8, 9]);
                })
                ->get();

            foreach ($messagesIds as $messagesId) {
                $dubbizle = Dubbizle::find($messagesId->dubbizle_id);

                if (!$dubbizle) {
                    continue;
                }

                $frequency = $dubbizle->frequency;
                if (!($frequency >= 5)) {
                    continue;
                }

                $message = ChatMessage::whereRaw('TIMESTAMPDIFF(MINUTE, `updated_at`, "' . $now . '") >= ' . $frequency)
                    ->where('id', $messagesId->id)
                    ->where('user_id', '>', '0')
                    ->where('approved', '1')
                    ->first();

                if (!$message) {
                    continue;
                }

                dump('saving...');

                $templateMessage = $dubbizle->reminder_message;

                $this->sendMessage($dubbizle->id, $templateMessage);
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function sendMessage($dubbizle, $message): void
    {

        $params = [
            'number'      => null,
            'user_id'     => 6,
            'approved'    => 1,
            'status'      => 1,
            'dubbizle_id' => $dubbizle,
            'message'     => $message,
        ];

        $chat_message = ChatMessage::create($params);

        $myRequest = new Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['messageId' => $chat_message->id]);

        app(WhatsAppController::class)->approveMessage('dubbizle', $myRequest);
    }
}
