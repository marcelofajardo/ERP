<?php

namespace App\Console\Commands;

use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateShoeAndClothingSizeFromChatMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-shoe-and-clothing-size-from-chat-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update shoe and clothing size from chat messages';

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

            $chatMessage = DB::table('chat_messages')
                ->leftJoin('customers', 'customers.id', '=', 'customer_id')
                ->select(['chat_messages.id', 'customers.id as customer_id', 'message', 'shoe_size', 'clothing_size'])
                ->where(function ($q) {
                    $q->where('shoe_size', '=', null)
                        ->orWhere('clothing_size', '=', null);
                })
                ->get();
            if ($chatMessage) {
                foreach ($chatMessage as $message) {
                    if ($message->customer_id) {
                        $customerParams = [];
                        if (empty($message->shoe_size)) {
                            $patternArr = [
                                '/size\s*\w*\s([0-9\.]+)/',
                                '/size\s*\?\s([0-9\.]+)/',
                                '/size([0-9\.]+)/',
                                '/([0-9\.]+)\s*size/',
                            ];
                            foreach ($patternArr as $pattern) {
                                $matches = [];
                                preg_match_all($pattern, strtolower($message->message), $matches);
                                if (!empty($matches[1][0])) {
                                    $customerParams['shoe_size'] = $matches[1][0];
                                    break;
                                }
                            }
                        }

                        /*if (empty($message->clothing_size)) {
                        $patternArr = [
                        '/wear\s*\w*\s([0-9\.]+)/',
                        '/wear\s*\?\s([0-9\.]+)/',
                        '/([0-9\.]+)\s*wear/',
                        ];
                        foreach ($patternArr as $pattern) {
                        $matches = [];
                        preg_match_all($pattern, strtolower($message->message), $matches);
                        if (!empty($matches[1][0])) {
                        $customerParams['clothing_size'] = $matches[1][0];
                        break;
                        }
                        }
                        }*/

                        if (!empty($customerParams)) {
                            \App\Customer::where('id', $message->customer_id)->update($customerParams);
                        }
                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
