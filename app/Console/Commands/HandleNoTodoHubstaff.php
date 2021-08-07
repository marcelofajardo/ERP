<?php

namespace App\Console\Commands;

use App\Helpers\hubstaffTrait;
use App\Hubstaff\HubstaffMember;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;

/**
 * Remove user from projects in case he has not selected a ToDo
 */
class HandleNoTodoHubstaff extends Command
{
    use hubstaffTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubstaff:notodo';

    private $client;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remote user from projects in case of no todo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->init(getenv('HUBSTAFF_SEED_PERSONAL_TOKEN'));
        $this->init(config('env.HUBSTAFF_SEED_PERSONAL_TOKEN'));
        $this->client = new Client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $response = $this->doHubstaffOperationWithAccessToken(
                function ($accessToken) {
                    // $url = 'https://api.hubstaff.com/v2/organizations/' . getenv('HUBSTAFF_ORG_ID') . '/last_activities';
                    $url = 'https://api.hubstaff.com/v2/organizations/' . config('env.HUBSTAFF_ORG_ID') . '/last_activities';

                    echo $url . PHP_EOL;
                    return $this->client->get(
                        $url,
                        [
                            RequestOptions::HEADERS => [
                                'Authorization' => 'Bearer ' . $accessToken,
                            ],
                        ]
                    );
                }
            );

            $responseJson = json_decode($response->getBody()->getContents());

            $nonTaskActivities = array_filter($responseJson->last_activities, function ($activity) {
                return is_null($activity->last_task_id);
            });

            $projectsToRemoveFrom = array();

            foreach ($nonTaskActivities as $activity) {
                if (!array_key_exists($activity->last_project_id, $projectsToRemoveFrom)) {
                    $projectsToRemoveFrom[$activity->last_project_id] = array();
                }
                $projectsToRemoveFrom[$activity->last_project_id][] = [
                    'user_ids' => $activity->user_id,
                    'role' => 'remove'
                ];
            }

            foreach ($projectsToRemoveFrom as $projectId => $users) {


                $this->doHubstaffOperationWithAccessToken(function ($accessToken) use ($projectId, $users) {

                    $url = 'https://api.hubstaff.com/v2/projects/' . $projectId . '/update_members';

                    $body = json_encode(
                        array(
                            'members' => $users,
                            'ignored' => true
                        )
                    );

                    echo $url . PHP_EOL;
                    echo print_r($body, true) . PHP_EOL;

                    return $this->client->put(
                        $url,
                        [
                            RequestOptions::HEADERS => [
                                'Authorization' => 'Bearer ' . $accessToken,
                            ],
                            RequestOptions::BODY => $body
                        ]
                    );
                });
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
