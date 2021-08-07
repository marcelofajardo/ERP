<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCustomersEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customer-email-mailchimp';

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

            Customer::where('email', '!=', null)->chunk(100, function ($customers) {

                $bar = $this->output->createProgressBar(count($customers));

                $client = new \GuzzleHttp\Client();

                foreach ($customers as $customer) {

                    try {

                        $response = $client->request('POST',
                            'https://us3.api.mailchimp.com/3.0/' . 'lists/' . env('LIST_ID') . '/members',
                            [
                                'auth'       => ['app', env('MAILCHIMP_APIKEY')],
                                'json'       => [
                                    'email_address' => $customer->email,
                                    'email_type'    => 'html',
                                    'status'        => 'subscribed',
                                ],
                                'exceptions' => false,
                            ]);
                    } catch (ClientException $e) {
                        return $e . "Something went wrong please try again";
                    }

                    if ($this->getOutput()->isVerbose()) {
                        $this->info("\nPulled customer: " . $customer->name . " Email: " . $customer->email);
                    }

                    $bar->advance();

                }

                $bar->finish();

            });

            $this->info("\nDone");

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
