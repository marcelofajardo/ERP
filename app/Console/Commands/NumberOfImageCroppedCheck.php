<?php

namespace App\Console\Commands;

use App\CroppedImageReference;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NumberOfImageCroppedCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'numberofimages:cropped';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if there is 1000 images cropped in an hour , if no then it will send whatsapp message to number';

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
            $date = new Carbon;

            $count = CroppedImageReference::where('created_at', '>', $date->subHours(1))->count();

            if ($count < 1000) {
                $message = 'Images are scraped less then 1000';
                //$number = '+971569119192';
                app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi('+918082488108', '', $message);
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
