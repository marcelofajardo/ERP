<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Email;
use App\ReturnExchange;
use App\CronJobReport;
use Carbon\Carbon;

class ExchangeBuybackEmailSending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $success;
    protected $emailObject;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $to, $success, $emailObject )
    {
        $this->to           = $to;
        $this->success      = $success;
        $this->$emailObject = $emailObject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailObject = $this->emailObject;

        try {

            \MultiMail::to( $this->to )->send( new \App\Mails\Manual\InitializeRefundRequest( $this->success ) );
            $emailObject->is_draft = 0;

        } catch (\Throwable $th) {

            $emailObject->error_message = $th->getMessage();

        }

        $emailObject->save();
        
    }
}
