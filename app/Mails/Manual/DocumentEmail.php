<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $subject;
    public $message;
    public $file_paths;

    public function __construct(string $subject, string $message, array $file_paths)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->file_paths = $file_paths;
        $this->fromMailer = 'documents@amourint.com';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this
            ->from('documents@amourint.com')
            ->subject($this->fromMailer)
            ->text('emails.documents.email_plain', ['body_message' => $this->message]);

        if (count($this->file_paths) > 0) {
            foreach ($this->file_paths as $file_path) {
                $email->attachFromStorageDisk('files', $file_path);
            }
        }

        return $email;
    }

}
