<?php

namespace App\Console\Commands;

use App\Email;
use App\ScrapRemark;
use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * @author Sukhwinder <sukhwinder@sifars.com>
 * This command takes care of receiving all the emails from the smtp set in the environment
 *
 * All fetched emails will go inside emails table
 */
class StoreLogScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:log-scraper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store log scraper from log file';

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

            $dateBeforeSevenday = date("Y-m-d", strtotime("-7 day"));

            ScrapRemark::where("scrap_field", 'last_line_error')->whereDate("created_at", "<=", $dateBeforeSevenday)->delete();

            $yesterdayDate = date('j', strtotime("-1 days"));
            // $root          = env('SCRAP_LOGS_FOLDER');
            $root          = config('env.SCRAP_LOGS_FOLDER');

            $counter       = 0;
            foreach (File::allFiles($root) as $file) {
                $needed = explode('-', $file->getFilename());
                if (isset($needed[1])) {
                    $day      = explode('.', $needed[1]);
                    if(isset($day[0]) && isset($day[1])) {
                        $filePath = $root . '/' . $file->getRelativePath() . '/' . $needed[0] . '-' . $day[0] . '.' . $day[1];
                        if ($day[0] === $yesterdayDate) {
                            $result   = File::get($filePath);
                            $lines    = array_filter(explode("\n", $result));
                            $lastLine = end($lines);
                            $scraper  = \App\Scraper::where("scraper_name", $needed[0])->first();
                            if (!is_null($scraper)) {
                                ScrapRemark::create([
                                    'scraper_name' => $needed[0],
                                    'scrap_id'     => $scraper->id,
                                    'module_type'  => '',
                                    'scrap_field'  => 'last_line_error',
                                    'remark'       => $lastLine,
                                ]);
                            }
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
