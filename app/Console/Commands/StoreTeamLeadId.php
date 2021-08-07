<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StoreTeamLeadId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:team-lead-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store team lead id';

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
        set_time_limit(0);
        $developerTask = \App\DeveloperTask::whereNull("team_lead_id")->get();
        if (!$developerTask->isEmpty()) {
            foreach ($developerTask as $dt) {
                $teamUser = \App\TeamUser::where("user_id", $dt->assigned_to)->first();
                if ($teamUser) {
                    $team = $teamUser->team;
                    if ($team) {
                        $dt->team_lead_id = $team->user_id;
                        $dt->save();
                        echo $dt->id . " updated to team lead id : " . $dt->team_lead_id;
                        echo PHP_EOL;
                    }
                }
            }
        }

    }
}
