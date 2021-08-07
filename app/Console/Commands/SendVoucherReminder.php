<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Mails\Manual\VoucherReminder;
use App\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendVoucherReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:voucher-reminder';

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

            $before   = Carbon::now()->subDays(5)->format('Y-m-d 00:00:00');
            $vouchers = Voucher::where('date', '<=', $before)->get();

            foreach ($vouchers as $voucher) {
                $credit = $voucher->amount - $voucher->paid;

                if ($credit > 0) {
                    Mail::to('yogeshmordani@icloud.com')
                        ->cc('hr@sololuxury.co.in')
                        ->send(new VoucherReminder($voucher));
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
