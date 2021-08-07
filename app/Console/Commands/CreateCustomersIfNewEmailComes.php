<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Webklex\IMAP\Client;

class CreateCustomersIfNewEmailComes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:create-customers-if-new-email-comes';

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

            $inbox = $imap->getFolder('INBOX');

            $messages = $inbox->getMessages();

            foreach ($messages as $message) {
                $email    = $message->getAttributes()['from'][0]->mail;
                $customer = Customer::where('email', $email)->first();

                if ($customer) {
                    continue;
                }

                $customer        = new Customer();
                $customer->email = $email;
                $customer->name  = $message->getAttributes()['from'][0]->personal;
                $customer->save();

            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
