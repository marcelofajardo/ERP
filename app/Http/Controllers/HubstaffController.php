<?php

namespace App\Http\Controllers;

use App\Article;
use App\Hubstaff\HubstaffMember;
use App\Hubstaff\HubstaffProject;
use App\Hubstaff\HubstaffTask;
use App\Library\Hubstaff\Src\Hubstaff;
use App\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Storage;

define('HUBSTAFF_TOKEN_FILE_NAME', 'hubstaff_tokens.json');
// define('SEED_REFRESH_TOKEN', getenv('HUBSTAFF_SEED_PERSONAL_TOKEN'));
define('SEED_REFRESH_TOKEN', config('env.HUBSTAFF_SEED_PERSONAL_TOKEN'));
define('STATE_MEMBERS', 'STATE_MEMBERS');

class HubstaffController extends Controller
{

    private function getTokens()
    {
        if (!Storage::disk('local')->exists(HUBSTAFF_TOKEN_FILE_NAME)) {
            $this->generateAccessToken(SEED_REFRESH_TOKEN);
        }
        $tokens = json_decode(Storage::disk('local')->get(HUBSTAFF_TOKEN_FILE_NAME));
        return $tokens;
    }

    private function refreshTokens()
    {
        $tokens = $this->getTokens();
        $this->generateAccessToken($tokens->refresh_token);
    }

    /**
     * returns boolean
     */
    private function generateAccessToken(string $refreshToken)
    {
        $httpClient = new Client();
        try {
            $response = $httpClient->post(
                'https://account.hubstaff.com/access_tokens',
                [
                    RequestOptions::FORM_PARAMS => [
                        'grant_type'    => 'refresh_token',
                        'refresh_token' => $refreshToken,
                    ],
                ]
            );

            $responseJson = json_decode($response->getBody()->getContents());

            $tokens = [
                'access_token'  => $responseJson->access_token,
                'refresh_token' => $responseJson->refresh_token,
            ];

            return Storage::disk('local')->put(HUBSTAFF_TOKEN_FILE_NAME, json_encode($tokens));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = HubstaffMember::all();
        $users   = User::all('id', 'name');

        return view(
            'hubstaff.members',
            [
                'members' => $members,
                'users'   => $users,
            ]
        );
    }

    private function refreshProjectsFromApi($shouldRetry = true)
    {
        // start hubstaff section from here
        $hubstaff             = Hubstaff::getInstance();
        $hubstaff             = $hubstaff->authenticate();
        // $organizationProjects = $hubstaff->getRepository('organization')->getOrgProjects(env("HUBSTAFF_ORG_ID"));
        $organizationProjects = $hubstaff->getRepository('organization')->getOrgProjects(config('env.HUBSTAFF_ORG_ID'));

        if (!empty($organizationProjects->projects)) {
            $projects = $organizationProjects->projects;
            HubstaffProject::updateOrCreateApiProjects($projects);
        }
    }

    public function getProjects()
    {

        $this->refreshProjectsFromApi();
        $projects = HubstaffProject::all();
        return view('hubstaff.projects', [
            'projects' => $projects,
        ]);
    }

    public function editProject(Request $request)
    {
        $project = HubstaffProject::find($request->route('id'));

        return view(
            'hubstaff.projectedit',
            [
                'project' => array(
                    'id'                  => $project->id,
                    'hubstaff_project_id' => $project->hubstaff_project_id,
                    'name'                => $project->hubstaff_project_name,
                    'description'         => $project->hubstaff_project_description,
                ),
            ]
        );
    }

    private function updateProjectOnHubstaff(
        int $hubstaffProjectId,
        string $hubstaffProjectName,
        string $hubstaffProjectDescription,
        bool $shouldRetryOnRefresh = true
    ) {
        $url        = 'https://api.hubstaff.com/v2/projects/' . $hubstaffProjectId;
        $httpClient = new Client();
        try {

            $tokens = $this->getTokens();

            $httpClient->put(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token,
                        'Content-Type'  => 'application/json',
                    ],

                    RequestOptions::BODY    => json_encode([
                        'name'        => $hubstaffProjectName,
                        'description' => $hubstaffProjectDescription,
                    ]),
                ]
            );
            return true;
        } catch (Exception $e) {
            if ($e instanceof ClientException) {
                if ($e->getResponse()->getStatusCode() == 401) {
                    // token has expired
                    $this->refreshTokens();
                    if ($shouldRetryOnRefresh) {
                        return $this->updateProjectOnHubstaff(
                            $hubstaffProjectId,
                            isset($hubstaffProjectName) ? $hubstaffProjectName : '',
                            isset($hubstaffProjectDescription) ? $hubstaffProjectDescription : '',
                            false
                        );
                    }
                }
            }
        }
        return false;
    }

    public function editProjectData()
    {
        $projectName        = Input::get('name');
        $projectDescription = Input::get('description');
        $projectId          = Input::get('id');
        $hubstaffProjectId  = Input::get('hubstaff_project_id');

        if ($this->updateProjectOnHubstaff(
            $hubstaffProjectId,
            isset($projectName) ? $projectName : '',
            isset($projectDescription) ? $projectDescription : ''
        )) {
            $project = HubstaffProject::find($projectId);
            $project->editProject($projectName, $projectDescription);
            return redirect('hubstaff/projects');
        } else {
            echo '<h1>Error in saving data to hubstaff</h1>';
        }
    }

    private function refreshTasksFromApi(bool $shouldRetry = true)
    {

        $tokens = $this->getTokens();

        // $url        = 'https://api.hubstaff.com/v2/organizations/' . getenv('HUBSTAFF_ORG_ID') . '/tasks?status=active%2Ccompleted';
        $url        = 'https://api.hubstaff.com/v2/organizations/' . config('env.HUBSTAFF_ORG_ID') . '/tasks?status=active%2Ccompleted';

        $httpClient = new Client();
        try {
            $response = $httpClient->get(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token,
                    ],
                ]
            );

            $responseJson = json_decode($response->getBody()->getContents());
            $tasks        = $responseJson->tasks;

            $hubstaffProjectIds = [];
            foreach ($tasks as $task) {
                $hubstaffProjectIds[] = $task->project_id;
            }

            $projects = HubstaffProject::whereIn('hubstaff_project_id', array_unique($hubstaffProjectIds))->get();

            $hubstaffProjects = [];
            foreach ($projects as $project) {
                $hubstaffProjects[$project->hubstaff_project_id] = $project;
            }

            foreach ($tasks as $task) {

                $project = $hubstaffProjects[$task->project_id];

                HubstaffTask::updateOrCreate(
                    [
                        'hubstaff_task_id' => $task->id,
                    ],
                    [
                        'hubstaff_task_id'    => $task->id,
                        'project_id'          => $project ? $project->id : null,
                        'hubstaff_project_id' => $task->project_id,
                        'summary'             => $task->summary,
                    ]
                );
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($e instanceof ClientException) {
                if ($e->getResponse()->getStatusCode() == 401) {
                    // the access token might have expired and hence refresh
                    $this->refreshTokens();
                    if ($shouldRetry) {
                        $this->refreshTasksFromApi(false);
                    }
                }
            }
        }
    }

    public function getTasks()
    {

        $this->refreshTasksFromApi();

        $tasks = HubstaffTask::all();

        return view(
            'hubstaff.tasks',
            [
                'tasks' => $tasks,
            ]
        );
    }

    public function addTaskFrom()
    {
        $usersDatabase = HubstaffMember::whereNotNull('hubstaff_user_id')
            ->leftJoin('users', 'users.id', '=', 'hubstaff_members.user_id')
            ->select('users.name', 'hubstaff_members.hubstaff_user_id')
            ->get();

        $users = [];
        foreach ($usersDatabase as $user) {
            $users[$user->hubstaff_user_id] = $user->name;
        }

        $projectsDatabase = HubstaffProject::all();

        $projects = [];
        foreach ($projectsDatabase as $project) {
            $projects[$project->hubstaff_project_id] = $project->hubstaff_project_name;
        }

        return view(
            'hubstaff.taskedit',
            [
                'projects' => $projects,
                'isNew'    => true,
                'users'    => $users,
            ]
        );
    }

    /**
     * Returns taskId  of newly created task
     */
    private function addTaskToHubstaff(string $taskSummary, int $projectId, int $assigneeId, bool $shouldRetry = false)
    {

        $tokens = $this->getTokens();

        $url        = 'https://api.hubstaff.com/v2/projects/' . $projectId . '/tasks';
        $httpClient = new Client();
        try {
            $response = $httpClient->post(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token,
                        'Content-Type'  => 'application/json',
                    ],

                    RequestOptions::BODY    => json_encode([
                        'summary'     => $taskSummary,
                        'assignee_id' => $assigneeId,
                    ]),
                ]
            );
            $parsedResponse = json_decode($response->getBody()->getContents());
            return $parsedResponse->task->id;
        } catch (Exception $e) {
            if ($e instanceof ClientException) {
                $this->refreshTokens();
                if ($shouldRetry) {
                    return $this->addTaskToHubstaff(
                        $taskSummary,
                        $projectId,
                        $assigneeId,
                        false
                    );
                }
            }
        }
        return false;
    }

    public function addTask()
    {
        $taskSummary = Input::get('summary');
        $projectId   = Input::get('project_id');
        $assigneeId  = Input::get('assignee_id');

        $taskId = $this->addTaskToHubstaff($taskSummary, $projectId, $assigneeId);

        if ($taskId) {

            $project = HubstaffProject::where('hubstaff_project_id', $projectId)->first();

            $task = new HubstaffTask();

            $task->hubstaff_task_id    = $taskId;
            $task->project_id          = $project->id;
            $task->hubstaff_project_id = $projectId;
            $task->summary             = $taskSummary;

            $task->save();

            return redirect('hubstaff/tasks');
        } else {
            echo '<h1>Error in saving data to hubstaff</h1>';
        }
    }

    /**
     * Return tasks object:
     * [
     *  id
     *  status
     *  project_id
     *  summary
     *  lock_version
     *  created_at
     *  updated_at
     * ]
     */
    private function getHubstaffTask(int $hubstaffTaskId, $shouldRetry = true)
    {

        $tokens = $this->getTokens();

        $url        = 'https://api.hubstaff.com/v2/tasks/' . $hubstaffTaskId;
        $httpClient = new Client();
        try {
            $response = $httpClient->get(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token,
                    ],
                ]
            );
            $parsedResponse = json_decode($response->getBody()->getContents());
            return $parsedResponse->task;
        } catch (Exception $e) {
            if ($e instanceof ClientException) {
                if ($e->getResponse()->getStatusCode() == 401) {
                    // the access token might have expired
                    $this->refreshTokens();
                    if ($shouldRetry) {
                        return $this->getHubstaffTask($hubstaffTaskId);
                    }
                }
            }
        }
        return null;
    }

    public function editTaskForm(Request $request)
    {
        $task = HubstaffTask::find($request->route('id'));

        if (!$task) {
            return response('<h1>Error in getting task data</h1>');
        }

        $hubstaffTask = $this->getHubstaffTask($task->hubstaff_task_id);

        if (!$hubstaffTask) {
            return response('<h1>Error in getting hubstaff task data</h1>');
        }

        $task = array(
            'id'           => $task->id,
            'summary'      => $task->summary,
            'project_id'   => $task->hubstaff_project_id,
            'lock_version' => $hubstaffTask->lock_version,
        );

        $projectsDatabase = HubstaffProject::all();
        $projects         = [];
        foreach ($projectsDatabase as $project) {
            $projects[$project->hubstaff_project_id] = $project->hubstaff_project_name;
        }

        $usersDatabase = HubstaffMember::whereNotNull('hubstaff_user_id')
            ->leftJoin('users', 'users.id', '=', 'hubstaff_members.user_id')
            ->select('users.name', 'hubstaff_members.hubstaff_user_id')
            ->get();

        $users = [];
        foreach ($usersDatabase as $user) {
            $users[$user->hubstaff_user_id] = $user->name;
        }

        return view(
            'hubstaff.taskedit',
            [
                'projects' => $projects,
                'task'     => $task,
                'users'    => $users,
            ]
        );
    }

    private function editTaskOnHubstaff(int $hubstaffTaskId, string $taskSummary, int $hubstaffProjectId, int $lockVersion, $shouldRetry = true)
    {
        $tokens = $this->getTokens();
        $url    = 'https://api.hubstaff.com/v2/tasks/' . $hubstaffTaskId;

        $httpClient = new Client();
        try {
            $httpClient->put(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token,
                        'Content-Type'  => 'application/json',
                    ],

                    RequestOptions::BODY    => json_encode([
                        'summary'      => $taskSummary,
                        'project_id'   => $hubstaffProjectId,
                        'lock_version' => $lockVersion,
                    ]),
                ]
            );
            return true;
        } catch (Exception $e) {
            if ($e instanceof ClientException) {
                if ($e->getResponse()->getStatusCode() == 401) {
                    // access token might be invalid and hence retry after refresh
                    $this->refreshTokens();
                    if ($shouldRetry) {
                        return $this->editTaskOnHubstaff(
                            $hubstaffTaskId,
                            $taskSummary,
                            $hubstaffProjectId,
                            $lockVersion,
                            false
                        );
                    }
                }
            }
        }
        return false;
    }

    public function editTask()
    {
        $taskId            = Input::get('id');
        $taskSummary       = Input::get('summary');
        $hubstaffProjectId = Input::get('project_id');
        $lockVersion       = Input::get('lock_version');

        $dbTask    = HubstaffTask::find($taskId);
        $dbProject = HubstaffProject::where('hubstaff_project_id', $hubstaffProjectId)->first();

        if (!$dbTask) {
            return response('<h1>No task found</h1>');
        }

        $hasSavedToHubstaff = $this->editTaskOnHubstaff(
            $dbTask->hubstaff_task_id,
            $taskSummary,
            $hubstaffProjectId,
            $lockVersion
        );

        if (!$hasSavedToHubstaff) {
            return response('<h1>Error in saving data to hubstaff</h1>');
        }

        $dbTask->summary             = $taskSummary;
        $dbTask->project_id          = $dbProject->id;
        $dbTask->hubstaff_project_id = $hubstaffProjectId;
        return redirect('hubstaff/tasks');
    }

    public function get_data($url)
    {

        $ch = curl_init($url);

        $app_token  = '2YuxAoBm9PHUtruFNYTnA9HhvI3xMEGSU-EICdO5VoM';
        $auth_token = 'Bearer 6f2bab2f1813745b689d3446f37d11bf177ca40ede4f9985155fd9e485039f36';
        //$auth_token = 'Bearer e2f8c8e136c73b1e909bb1021b3b4c29';
        //$auth_token = 'je_2A29CStS3J-YPasj1UIjlg2qpYNs-hoLmw8SToe8';

        $http_header = array(
            "App-Token: 2YuxAoBm9PHUtruFNYTnA9HhvI3xMEGSU-EICdO5VoM",
            "Authorization: " . $auth_token,
            "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
            "Accept: application/json",
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }

    /**
     * Get Broken Links Details
     * Function for display
     *
     * @return json response
     */
    public function updateTitle(Request $request)
    {
        $article        = Article::findOrFail($request['id']);
        $article->title = $request['article_title'];
        $article->save();
        return response()->json([
            'type'    => 'success',
            'message' => 'Title Updated',
        ]);
    }

    /**
     * Updated Title
     * Function for display
     *
     * @return json response
     */
    public function updateDescription(Request $request)
    {
        $article              = Article::findOrFail($request['id']);
        $article->description = $request['article_desc'];
        $article->save();
        return response()->json([
            'type'    => 'success',
            'message' => 'Description Updated',
        ]);
    }

    public function linkUser(Request $request)
    {
        $bodyContent     = $request->getContent();
        $jsonDecodedBody = json_decode($bodyContent);

        $userId         = $jsonDecodedBody->user_id;
        $hubstaffUserId = $jsonDecodedBody->hubstaff_user_id;

        if (!$userId || !$hubstaffUserId) {
            return response()->json(
                [
                    'error' => 'Missing parameters',
                ],
                400
            );
        }

        HubstaffMember::linkUser($hubstaffUserId, $userId);

        return response()->json([
            'message' => 'link success',
        ]);
    }

    public function createProject(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'hubstaff_project_name' => 'required|unique:hubstaff_projects',
        ]);

        if ($validator->fails()) {
            $outputString = "";
            $messages     = $validator->errors()->getMessages();
            foreach ($messages as $k => $errr) {
                foreach ($errr as $er) {
                    $outputString .= "$k : " . $er . "<br>";
                }
            }
            return response()->json(["code" => 500, "error" => $outputString]);
        }

        // create hubstaff project

        // start hubstaff section from here
        $hubstaff             = Hubstaff::getInstance();
        $hubstaff             = $hubstaff->authenticate();
        // $organizationProjects = $hubstaff->getRepository('organization')->createOrgProjects(env("HUBSTAFF_ORG_ID"), [
        $organizationProjects = $hubstaff->getRepository('organization')->createOrgProjects(config('env.HUBSTAFF_ORG_ID'), [
            "name"        => $request->hubstaff_project_name,
            "description" => $request->hubstaff_project_description,
        ]);

        return response()->json(["code" => 200, "Project added successfully"]);

    }

    public function saveMemberField(Request $request, $id)
    {
        $fieldName  = $request->field_name;
        $fieldValue = $request->field_value;

        $memeber = \App\Hubstaff\HubstaffMember::find($id);

        if($memeber) {
           $memeber->{$fieldName} = $fieldValue;
           $memeber->save();

           return response()->json(["code" => 200 , "data" => [], "message" => "Date updated successfully"]);
        }

        return response()->json(["code" => 500 , "data" => [], "message" => "Id is missing"]);

    }
}
