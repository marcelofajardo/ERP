<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttachImagesSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 1;
    public $timeout = 7200;

    protected $_token;
    protected $send_pdf;
    protected $pdf_file_name;
    protected $images;
    protected $image;
    protected $screenshot_path;
    protected $message;
    protected $customer_id;
    protected $status;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        // Set product
        $this->_token          = $data['_token'];
        $this->send_pdf        = $data['send_pdf'];
        $this->pdf_file_name   = !empty($data["pdf_file_name"]) ? $data["pdf_file_name"] : "";
        $this->images          = $data['images'];
        $this->image           = $data['image'];
        $this->screenshot_path = $data['screenshot_path'];
        $this->message         = $data['message'];
        $this->customer_id     = $data['customer_id'];
        $this->status          = $data['status'];
        $this->type          = $data['type'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        // Set time limit
        set_time_limit(0);

        $requestData = new Request();
        $requestData->setMethod('POST');
        $requestData->request->add([
            '_token'          => $this->_token,
            'send_pdf'        => $this->send_pdf,
            'pdf_file_name'   => $this->pdf_file_name,
            'images'          => $this->images,
            'image'           => $this->image,
            'screenshot_path' => $this->screenshot_path,
            'message'         => $this->message,
            'customer_id'     => $this->customer_id,
            'status'          => $this->status,
            'type'          => $this->type
        ]);
        app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');



    }

}
