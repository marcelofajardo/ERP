<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\AutoCompleteMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class StoreChatMessagesToAutoCompleteMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StoreChatMessagesToAutoCompleteMessages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'StoreChatMessagesToAutoCompleteMessages';

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

        $chat_messages = ChatMessage::whereDate('created_at', '>', Carbon::now()->subDays(7))
            ->get();

        foreach ($chat_messages as $message) {
            $exist = AutoCompleteMessage::where('message', $message->message)->exists();

            if (!$exist && $message->message !== null) {
                if (strlen($message->message) <= 160) {

                    AutoCompleteMessage::create([
                        'message' => $message->message,
                    ]);
                }
            }
        }


    }
}
