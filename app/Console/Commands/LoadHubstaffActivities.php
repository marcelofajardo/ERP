<?php

namespace App\Console\Commands;

use App\Helpers\hubstaffTrait;
use App\Hubstaff\HubstaffActivity;
use App\Hubstaff\HubstaffMember;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;

class LoadHubstaffActivities extends Command
{
    use hubstaffTrait;

    private $client;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubstaff:load_activities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load activities for users per task from Hubstaff';

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
        try {
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $time      = strtotime(date("c"));
            $time      = $time - ((60 * 60)); //one hour
            $startTime = date("c", strtotime(gmdate('Y-m-d H:i:s', $time)));
            $time     = strtotime($startTime);
            $time     = $time + (10 * 60); //10 mins
            $stopTime = date("c", $time);

            $activities = $this->getActivitiesBetween($startTime, $stopTime);
            if ($activities === false) {
                echo 'Error in activities' . PHP_EOL;
                return;
            }
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

                if(is_null($data['task_id'])) {
                    //STOPPED CERTAIN MESSAGES
                    /*$user = HubstaffMember::join('users', 'hubstaff_members.user_id', '=', 'users.id')->where('hubstaff_members.hubstaff_user_id',$data['user_id'])->first(); 
                    if($user) {
                        $message = "You haven't selected any task on your last activity period ".$startTime. " to ".$stopTime." , Please select appropriate task or put notes on it.";
                        $requestData = new Request();
                        $requestData->setMethod('POST');
                        $requestData->request->add(['user_id' => $user->user_id, 'message' => $message, 'status' => 1]);
                        app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'activity');
                    }*/
                }
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function getActivitiesBetween($start, $stop)
    {

        try {
            $response = $this->doHubstaffOperationWithAccessToken(
                function ($accessToken) use ($start, $stop) {
                    // $url = 'https://api.hubstaff.com/v2/organizations/' . getenv('HUBSTAFF_ORG_ID') . '/activities?time_slot[start]=' . $start . '&time_slot[stop]=' . $stop;
                    $url = 'https://api.hubstaff.com/v2/organizations/' . config('env.HUBSTAFF_ORG_ID') . '/activities?time_slot[start]=' . $start . '&time_slot[stop]=' . $stop;

                    echo $url . PHP_EOL;
                    return $this->client->get(
                        $url,
                        [
                            RequestOptions::HEADERS => [
                                'Authorization' => 'Bearer ' . $accessToken,
                            ],
                        ]
                    );
                },
                true
            );

            $responseJson = json_decode($response->getBody()->getContents());

            $activities = array();

            foreach ($responseJson->activities as $activity) {

                $activities[$activity->id] = array(
                    'user_id'   => $activity->user_id,
                    'task_id'   => $activity->task_id,
                    'starts_at' => $activity->starts_at,
                    'tracked'   => $activity->tracked,
                    'keyboard'  => $activity->keyboard,
                    'mouse'     => $activity->mouse,
                    'overall'   => $activity->overall,
                );
            }
            return $activities;
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return false;
        }
    }
}
