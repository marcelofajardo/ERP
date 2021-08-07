<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendImagesWithWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $phone;
    protected $whatsapp_number;
    protected $image_url;
    protected $message_id;

    public function __construct($phone, $whatsapp_number, $image_url, $message_id)
    {
      $this->phone = $phone;
      $this->whatsapp_number = $whatsapp_number;
      $this->image_url = $image_url;
      $this->message_id = $message_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      app('App\Http\Controllers\WhatsAppController')->sendWithNewApi($this->phone, $this->whatsapp_number, NULL, $this->image_url, $this->message_id);
    }
}
