<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\CronJobReport;
use App\Http\Controllers\WhatsAppController;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendReminderToSupplierIfTheyHaventReplied extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send-to-supplier';

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

            //get latest message of the supplier exclusing the auto messages
            $messagesIds = DB::table('chat_messages')
                ->selectRaw('MAX(id) as id, supplier_id')
                ->groupBy('supplier_id')
                ->whereNotNull('message')
                ->where('supplier_id', '>', '0')
                ->where(function ($query) {
                    $query->whereNotIn('status', [7, 8, 9]);
                })
                ->get();

            foreach ($messagesIds as $messagesId) {
                $supplier = Supplier::find($messagesId->supplier_id);

                if (!$supplier) {
                    continue;
                }

                $frequency = $supplier->frequency;
                if (!($frequency >= 5)) {
                    continue;
                }

                // get the message if the interval is >= then that we have set for this supplier
                $message = ChatMessage::whereRaw('TIMESTAMPDIFF(MINUTE, `updated_at`, "' . $now . '") >= ' . $frequency)
                    ->where('id', $messagesId->id)
                    ->where('user_id', '>', '0')
                    ->where('approved', '1')
                    ->first();

                if (!$message) {
                    continue;
                }

                dump('saving...');

                $templateMessage = $supplier->reminder_message;

                //Send message to the supplier
                $this->sendMessage($supplier->id, $templateMessage);
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }

    /**
     * @param $supplier
     * @param $message
     * Create the chat_message record and then approve and send the message
     */
    private function sendMessage($supplier, $message): void
    {

        $params = [
            'number'      => null,
            'user_id'     => 6,
            'approved'    => 1,
            'status'      => 1,
            'supplier_id' => $supplier,
            'message'     => $message,
        ];

        $chat_message = ChatMessage::create($params);

        $myRequest = new Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['messageId' => $chat_message->id]);

        app(WhatsAppController::class)->approveMessage('supplier', $myRequest);
    }
}
