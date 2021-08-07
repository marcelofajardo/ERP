<?php

namespace App\Console\Commands;

use App\Agent;
use App\CronJobReport;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Webklex\IMAP\Client;

class CheckEmailsErrors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:emails-errors';

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

            $imap = new Client([
                'host'          => env('IMAP_HOST_PURCHASE'),
                'port'          => env('IMAP_PORT_PURCHASE'),
                'encryption'    => env('IMAP_ENCRYPTION_PURCHASE'),
                'validate_cert' => env('IMAP_VALIDATE_CERT_PURCHASE'),
                'username'      => env('IMAP_USERNAME_PURCHASE'),
                'password'      => env('IMAP_PASSWORD_PURCHASE'),
                'protocol'      => env('IMAP_PROTOCOL_PURCHASE'),
            ]);

            $imap->connect();

            $inbox           = $imap->getFolder('INBOX');
            $email_addresses = [
                'Mailer-Daemon@se1.mailspamprotection.com',
                'Mailer-Daemon@se2.mailspamprotection.com',
                'Mailer-Daemon@se3.mailspamprotection.com',
                'Mailer-Daemon@se4.mailspamprotection.com',
                'Mailer-Daemon@se5.mailspamprotection.com',
                'Mailer-Daemon@se6.mailspamprotection.com',
                'Mailer-Daemon@se7.mailspamprotection.com',
                'Mailer-Daemon@se8.mailspamprotection.com',
                'Mailer-Daemon@se9.mailspamprotection.com',
                'Mailer-Daemon@se10.mailspamprotection.com',
                'Mailer-Daemon@se11.mailspamprotection.com',
                'Mailer-Daemon@se12.mailspamprotection.com',
                'Mailer-Daemon@se13.mailspamprotection.com',
                'Mailer-Daemon@se14.mailspamprotection.com',
                'Mailer-Daemon@se15.mailspamprotection.com',
                'Mailer-Daemon@se16.mailspamprotection.com',
                'Mailer-Daemon@se17.mailspamprotection.com',
                'Mailer-Daemon@se18.mailspamprotection.com',
                'Mailer-Daemon@se19.mailspamprotection.com',
                'Mailer-Daemon@se20.mailspamprotection.com',
            ];

            foreach ($email_addresses as $address) {
                $emails = $inbox->messages()->where('from', $address);
                $emails = $emails->leaveUnread()->get();

                foreach ($emails as $email) {
                    dump('Error Email');

                    if ($email->hasHTMLBody()) {
                        $content = $email->getHTMLBody();
                    } else {
                        $content = $email->getTextBody();
                    }

                    if (preg_match_all("/failed: ([\a-zA-Z0-9_.-@]+) host/i", preg_replace('/\s+/', ' ', $content), $match)) {
                        dump('Found address ' . $match[1][0]);

                        $suppliers = Supplier::where('email', $match[1][0])->get();
                        $agents    = Agent::where('email', $match[1][0])->get();

                        foreach ($agents as $agent) {
                            dump('Found agent email');

                            $agent->supplier->has_error = 1;
                            $agent->supplier->save();
                        }

                        foreach ($suppliers as $supplier) {
                            dump('Found supplier email');

                            $supplier->has_error = 1;
                            $supplier->save();
                        }
                    }

                    dump('__________');
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
