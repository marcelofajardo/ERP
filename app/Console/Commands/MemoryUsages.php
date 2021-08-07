<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MemoryUsage;

class MemoryUsages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memory_usage';

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

        MemoryUsage::where('updated_at', '<', now()->subDays(7))->delete();


        $free = shell_exec('free -m');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);



        $memory_usage = new MemoryUsage;
        $memory_usage->total =  $mem[1];
        $memory_usage->used =  $mem[2];
        $memory_usage->free =  $mem[3];
        $memory_usage->buff_cache =  $mem[4];
        $memory_usage->available =  $mem[5];



        $memory_usage->save();


    }
}
