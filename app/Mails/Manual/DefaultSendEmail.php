<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefaultSendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $attchments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $attchments = [])
    {
        $this->email      = $email;
        $this->attchments      = $attchments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    // public function build()
    // {
    //     $email   = $this->email;
    //     $content = $email->message;

    //     return $this->to($email->to)
    //     ->from($email->from)
    //     ->subject($email->subject)
    //     ->view('emails.blank_content', compact('content'));
    // }

    public function build()
    {
        $email   = $this->email;
        $content = $email->message;

        $mailObj =  $this->to($email->to)
        ->from($email->from)
        ->subject($email->subject)
        ->view('emails.blank_content', compact('content'));
        
        foreach($this->attchments as $attchment){
            $mailObj->attachFromStorageDisk('files', $attchment);
        }
        
        return $mailObj;

    }
}
