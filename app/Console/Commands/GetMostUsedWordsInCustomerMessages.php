<?php

namespace App\Console\Commands;

use App\BulkCustomerRepliesKeyword;
use App\ChatMessage;
use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetMostUsedWordsInCustomerMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk-customer-message:get-most-used-keywords';

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

            $messages = ChatMessage::where('is_processed_for_keyword', 0)->whereNotNull('number')->where('customer_id', '>', '0')->limit(1000)->get();
            if ($messages != null) {
                foreach ($messages as $message) {
                    // Set text
                    $text = $message->message;

                    // Set to processed
                    $message->is_processed_for_keyword = 1;
                    $message->save();

                    // Explode the words
                    $words = explode(' ', $text);

                    foreach ($words as $word) {
                        $word = preg_replace('/[^\w]/', '', $word);
                        var_dump($word);

                        if (strlen(trim($word)) <= 3) {
                            continue;
                        }

                        //$this->addOrUpdateCountOfKeyword(trim($word));
                    }
                }
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function addOrUpdateCountOfKeyword($word): void
    {
        $keyword = BulkCustomerRepliesKeyword::where('value', $word)->first();

        if ($keyword !== null) {
            $keyword->count = (int) $keyword->count + 1;
            $keyword->save();
            echo "UPDATED: " . $word . " " . $keyword->count . "\n";
            return;
        }

        $keyword            = new BulkCustomerRepliesKeyword();
        $keyword->value     = $word;
        $keyword->text_type = 'keyword';
        $keyword->is_manual = 0;
        $keyword->count     = 1;
        $keyword->save();

        // NEW
        echo "NEW: " . $word . "\n";
    }
}
