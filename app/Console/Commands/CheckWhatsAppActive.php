<?php

namespace App\Console\Commands;

use App\Marketing\WhatsappConfig;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckWhatsAppActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the Whatsapp number is active and alert if the number is inactive';

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
            // Check only active numbers which are not customer support numbers
            $numbers = WhatsappConfig::where('is_customer_support', '!=', 1)->where('status', 1)->get();

            // Set the current time
            $time = Carbon::now();

            // Check only during the day
            $morning = Carbon::create($time->year, $time->month, $time->day, 8, 0, 0);
            $evening = Carbon::create($time->year, $time->month, $time->day, 18, 00, 0);
            if ($time->between($morning, $evening, true)) {
                foreach ($numbers as $number) {
                    //Checking if device was active from last 15 mins
                    if ($number->last_online > Carbon::now()->subMinutes(15)->toDateTimeString()) {
                        continue;
                    }
                    $phones  = ['+971569119192', '+31629987287'];
                    $message = $number->number . 'Username : ' . $number->username . ' Phone Number is not working Please Check It';

                    foreach ($phones as $phone) {
                        // app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($phone, '', $message, '', '');
                    }

                }
            } else {
                dump('We only check during the day');
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
