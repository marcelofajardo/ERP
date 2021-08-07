<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteMagentoJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento-jobs:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete magento jobs';

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
        //
        $job = \App\Job::where("queue","magento")->get();
        foreach($job as $j) {
            $j->delete();
        }
    }
}
