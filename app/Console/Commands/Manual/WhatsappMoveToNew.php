<?php

namespace App\Console\Commands\Manual;

use App\CronJobReport;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WhatsappMoveToNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:move-to-new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move all WhatsApp clients to a new number';

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

            // Set variables
            $newNumber = '971545889192';
            $days      = 60;

            // Query to find all customers of $number
            $sql = "
            SELECT
                DISTINCT(customer_id)
            FROM
                chat_messages
            WHERE
                customer_id IN (
                    SELECT
                        c.id
                    FROM
                        customers c
                    WHERE
                        do_not_disturb=0 AND
                        is_blocked=0
                ) AND
                number IS NOT NULL AND
                created_at > DATE_SUB(NOW(), INTERVAL " . $days . " DAY)
        ";
            // echo $sql;
            $rs = DB::select(DB::raw($sql));

            // Loop over customers
            if ($rs !== null) {
                foreach ($rs as $result) {
                    // Find customer
                    $customer                  = Customer::find($result->customer_id);
                    $customer->whatsapp_number = $newNumber;
                    $customer->save();

                    // Output customer information
                    echo $customer->id . ' ' . $customer->phone . "\n";
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
