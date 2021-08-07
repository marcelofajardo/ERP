<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\Wetransfer;
use App\Website;
use App\scraperImags;
use Carbon\Carbon;
use seo2websites\ErpExcelImporter\ErpExcelImporter;
use Illuminate\Support\Facades\File;

class scrappersImagesDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrappersImagesDelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete scrappers Images older two day';

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
        $filesList = scraperImags::where('created_at', '<', Carbon::now()->subDays(2)->toDateTimeString())->pluck('img_url');
        
        foreach ($filesList as $images) {
            File::delete(  public_path('scrappersImages/'.$images) );
        }
        
        $queuesList = scraperImags::where('created_at', '<', Carbon::now()->subDays(2)->toDateTimeString())->delete();
        
        $this->output->write('Cron complated', true);
    }
}