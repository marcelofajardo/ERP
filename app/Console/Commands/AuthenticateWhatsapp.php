<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Marketing\WhatsappConfig;
use App\ApiKey;

class AuthenticateWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AuthenticateWhatsapp:instance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check if provide with CHAT_API type in whatsapp config table are authenticated or not';

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
       
        $controller = app()->make('App\Http\Controllers\Marketing\WhatsappConfigController');
        app()->call([$controller, 'checkInstanceAuthentication'], []);

    }
}
