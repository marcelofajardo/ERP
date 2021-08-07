<?php

namespace App\Jobs;

use App\Http\Controllers\WhatsAppController;
use App\MessageQueue;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMessageToSelected implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $number;
    protected $whatsAppNumber;
    protected $content;
    protected $messageQueueId;
    protected $groupId;

    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $number, array $content, int $messageQueueId, string $whatsAppNumber, $groupId = null)
    {
        $this->number = $number;
        $this->whatsAppNumber = $whatsAppNumber;
        $this->content = $content;
        $this->messageQueueId = $messageQueueId;
        $this->groupId = $groupId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->content[ 'message' ]) {
            $message = $this->content[ 'message' ];

            app(WhatsAppController::class)->sendWithThirdApi($this->customer->phone, $this->whatsAppNumber, $message, false);
        }

        if (isset($this->content[ 'image' ])) {
            foreach ($this->content[ 'image' ] as $image) {
                app(WhatsAppController::class)->sendWithThirdApi($this->customer->phone, $this->whatsAppNumber, null, str_replace(' ', '%20', $image[ 'url' ]));

            }
        }

        $message_queue = MessageQueue::find($this->messageQueueId);
        $message_queue->sent = 1;
        $message_queue->save();
    }
}
