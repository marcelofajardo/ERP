<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Document;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Webklex\IMAP\Client;

class DocumentReciever extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document:email';

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

            $oClient = new Client([
                'host'          => env('IMAP_HOST_DOCUMENT'),
                'port'          => env('IMAP_PORT_DOCUMENT'),
                'encryption'    => env('IMAP_ENCRYPTION_DOCUMENT'),
                'validate_cert' => env('IMAP_VALIDATE_CERT_DOCUMENT'),
                'username'      => env('IMAP_USERNAME_DOCUMENT'),
                'password'      => env('IMAP_PASSWORD_DOCUMENT'),
                'protocol'      => env('IMAP_PROTOCOL_DOCUMENT'),
            ]);

            $oClient->connect();

            $folder = $oClient->getFolder('INBOX');

            $message = $folder->query()->unseen()->setFetchBody(true)->get()->all();
            if (count($message) == 0) {
                echo 'No New Mail Found';
                echo '<br>';
                die();
            }

            foreach ($message as $messages) {
                $subject = $messages->getSubject();
                $subject = strtolower($subject);
                if (session()->has('email.subject')) {
                    session()->forget('email.subject');
                    session()->push('email.subject', $subject);
                } else {
                    session()->push('email.subject', $subject);
                }

                if ($messages->hasAttachments()) {
                    $aAttachment = $messages->getAttachments();
                    $aAttachment->each(function ($oAttachment) {
                        $name = $oAttachment->getName();
                        $oAttachment->save(storage_path('app/files/documents/'), $name);
                        $document             = new Document();
                        $subject              = session()->get('email.subject');
                        $document->name       = $subject[0];
                        $document->filename   = $name;
                        $document->version    = 1;
                        $document->from_email = 1;
                        $document->save();
                        echo 'Document Saved in Pending';
                    });

                }

            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
