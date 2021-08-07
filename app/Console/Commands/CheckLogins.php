<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\User;
use App\UserLogin;
use Cache;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class CheckLogins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-logins';

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

            \Log::channel('customer')->info(Carbon::now() . " begin checking users logins");
            $users = User::all();

            foreach ($users as $user) {
                if ($login = UserLogin::where('user_id', $user->id)->where('created_at', '>', Carbon::now()->format('Y-m-d'))->latest()->first()) {

                } else {
                    $login = UserLogin::create(['user_id' => $user->id]);
                }

                if (Cache::has('user-is-online-' . $user->id)) {
                    if ($login->logout_at) {
                        UserLogin::create(['user_id' => $user->id, 'login_at' => Carbon::now()]);
                    } else if (!$login->login_at) {
                        $login->update(['login_at' => Carbon::now()]);
                    }
                } else {
                    if ($login->created_at && !$login->logout_at) {
                        $login->update(['logout_at' => Carbon::now()]);
                    }
                }
            }

            \Log::channel('customer')->info(Carbon::now() . " end of checking users logins");

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
