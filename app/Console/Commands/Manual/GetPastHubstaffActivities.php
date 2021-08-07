<?php

namespace App\Console\Commands\Manual;

use App\Helpers\hubstaffTrait;
use App\Hubstaff\HubstaffActivity;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;

class GetPastHubstaffActivities extends Command
{
    private $HUBSTAFF_ACTIVITY_LAST_SYNC_FILE_NAME = 'hubstaff_activity_sync.json';

    use hubstaffTrait;

    private $client;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubstaff:load_past_activities {start=2019-09-01} {end=2020-04-18} {user_ids=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load past Hubstaff Activities till time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
        // $this->init(getenv('HUBSTAFF_SEED_PERSONAL_TOKEN'));
        $this->init(config('env.HUBSTAFF_SEED_PERSONAL_TOKEN'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $now = time();

        $startString = $this->argument('start');
        $endString   = $this->argument('end');
        $userIds     = $this->argument('user_ids');
        $userIds     = explode(",", $userIds);
        $userIds     = array_filter($userIds);

        $start = strtotime($startString . ' UTC');
        $now   = strtotime($endString . ' UTC');

        while ($start < $now) {
            $end = $start + 7 * 24 * 60 * 60; // 1 week limited by API

            echo '=====================' . PHP_EOL;
            echo 'Start: ' . gmdate('c', $start) . PHP_EOL;
            echo 'End: ' . gmdate('c', $end) . PHP_EOL;

            $activities = $this->getActivitiesBetween(gmdate('c', $start), gmdate('c', $end), 0, [], $userIds);
            
            echo "Got activities(count): " . sizeof($activities) . PHP_EOL;
            foreach ($activities as $id => $data) {
                HubstaffActivity::updateOrCreate(
                    [
                        'id' => $id,
                    ],
                    [
                        'user_id'   => $data['user_id'],
                        'task_id'   => is_null($data['task_id']) ? 0 : $data['task_id'],
                        'starts_at' => $data['starts_at'],
                        'tracked'   => $data['tracked'],
                        'keyboard'  => $data['keyboard'],
                        'mouse'     => $data['mouse'],
                        'overall'   => $data['overall'],
                    ]
                );
            }

            sleep(5);

            $start = $end;
        }
    }

    
}
