<?php

namespace App\Http\Controllers;

use App\Helpers\DevelopmentHelper;
use App\LeadHubstaffDetail;
use App\Setting;
use App\TaskAttachment;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use App\TasksHistory;
use App\TaskTypes;
use App\DeveloperTask;
use App\DeveloperModule;
use App\DeveloperComment;
use App\DeveloperTaskComment;
use App\DeveloperCost;
use App\DeveloperTaskHistory;
use App\Github\GithubRepository;
use App\PushNotification;
use App\User;
use App\PaymentReceipt;
use App\Helpers;
use App\Hubstaff\HubstaffMember;
use App\Hubstaff\HubstaffProject;
use App\Hubstaff\HubstaffTask;
use App\Issue;
use App\Task;
use App\TaskUserHistory;
use App\Team;
use App\TaskStatus;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Response;
use Storage;
use App\MeetingAndOtherTime;
use App\Helpers\HubstaffTrait;
use App\ChatMessage;
use App\Helpers\MessageHelper;

class DevelopmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use hubstaffTrait;
    private $githubClient;



    public function __construct()
    {
        //  $this->middleware( 'permission:developer-tasks', [ 'except' => [ 'issueCreate', 'issueStore', 'moduleStore' ] ] );
        $this->githubClient = new Client([
            // 'auth' => [getenv('GITHUB_USERNAME'), getenv('GITHUB_TOKEN')]
            'auth' => [config('env.GITHUB_USERNAME'), config('env.GITHUB_TOKEN')],
        ]);
        // $this->init(getenv('HUBSTAFF_SEED_PERSONAL_TOKEN'));
        $this->init(config('env.HUBSTAFF_SEED_PERSONAL_TOKEN'));
    }
    /*public function index_bkup(Request $request)
    {
        // Set required data
        $user = $request->user ?? Auth::id();
        $start = $request->range_start ? "$request->range_start 00:00" : '2018-01-01 00:00';
        $end = $request->range_end ? " $request->range_end 23:59" : Carbon::now()->endOfWeek();
        $id = null;
        // Set initial variables
        $progressTasks = new DeveloperTask();
        $plannedTasks = new DeveloperTask();
        $completedTasks = new DeveloperTask();
        // For non-admins get tasks assigned to the user
        if (!Auth::user()->hasRole('Admin')) {
            $progressTasks = DeveloperTask::where('user_id', Auth::id());
            $plannedTasks = DeveloperTask::where('user_id', Auth::id());
            $completedTasks = DeveloperTask::where('user_id', Auth::id());
        }
        // Get tasks for specific user if you are admin
        if (Auth::user()->hasRole('Admin') && (int)$request->user > 0) {
            $progressTasks = DeveloperTask::where('user_id', $user);
            $plannedTasks = DeveloperTask::where('user_id', $user);
            $completedTasks = DeveloperTask::where('user_id', $user);
        }
        // Filter by date
        if ($request->get('range_start') != '') {
            $progressTasks = $progressTasks->whereBetween('created_at', [$start, $end]);
            $plannedTasks = $plannedTasks->whereBetween('created_at', [$start, $end]);
            $completedTasks = $completedTasks->whereBetween('created_at', [$start, $end]);
        }
        // Filter by ID
        if ($request->get('id')) {
            $progressTasks = $progressTasks->where(function ($query) use ($request) {
                $id = $request->get('id');
                $query->where('id', $id)->orWhere('subject', 'LIKE', "%$id%");
            });
            $plannedTasks = $plannedTasks->where(function ($query) use ($request) {
                $id = $request->get('id');
                $query->where('id', $id)->orWhere('subject', 'LIKE', "%$id%");
            });
            $completedTasks = $completedTasks->where(function ($query) use ($request) {
                $id = $request->get('id');
                $query->where('id', $id)->orWhere('subject', 'LIKE', "%$id%");
            });
        }
        // Get all data with user and messages
        $plannedTasks = $plannedTasks->where('status', 'Planned')->orderBy('created_at')->with(['user', 'messages'])->get();
        $completedTasks = $completedTasks->where('status', 'Done')->orderBy('created_at')->with(['user', 'messages'])->get();
        $progressTasks = $progressTasks->where('status', 'In Progress')->orderBy('created_at')->with(['user', 'messages'])->get();
        // Get all modules
        $modules = DeveloperModule::all();
        // Get all developers
        $users = Helpers::getUserArray(User::role('Developer')->get());
        // Get all task types
        $tasksTypes = TaskTypes::all();
        // Create empty array for module names
        $moduleNames = [];
        // Loop over all modules and store them
        foreach ($modules as $module) {
            $moduleNames[ $module->id ] = $module->name;
        }
        $times = [];
        $priority  = \App\ErpPriority::where('model_type', '=', DeveloperTask::class)->pluck('model_id')->toArray();
        return view('development.index', [
            'times' => $times,
            'users' => $users,
            'modules' => $modules,
            'user' => $user,
            'start' => $start,
            'end' => $end,
            'moduleNames' => $moduleNames,
            'completedTasks' => $completedTasks,
            'plannedTasks' => $plannedTasks,
            'progressTasks' => $progressTasks,
            'tasksTypes' => $tasksTypes,
            'priority' => $priority,
        ]);
    }*/
    public function taskListByUserId(Request $request)
    {
        $user_id = $request->get('user_id', 0);
        $issues = DeveloperTask::select('developer_tasks.id', 'developer_tasks.module_id', 'developer_tasks.subject', 'developer_tasks.task', 'developer_tasks.created_by')
            ->leftJoin('erp_priorities', function ($query) use ($user_id) {
                $query->on('erp_priorities.model_id', '=', 'developer_tasks.id');
                $query->where('erp_priorities.model_type', '=', DeveloperTask::class);
                $query->where('erp_priorities.user_id', $user_id);
            })
            ->where('status', '!=', 'Done');
        // if admin the can assign new task
        if (auth()->user()->isAdmin()) {
            $issues = $issues->whereIn('developer_tasks.id', $request->get('selected_issue', []));
        } else {
            $issues = $issues->whereNotNull('erp_priorities.id');
        }
        $issues = $issues->orderBy('erp_priorities.id')->get();
        foreach ($issues as &$value) {
            $value->module = $value->developerModule->name;
            $value->created_by = User::where('id', $value->created_by)->value('name');
        }
        unset($value);
        return response()->json($issues);
    }
    public function setTaskPriority(Request $request)
    {
        $priority = $request->get('priority', null);
        $user_id = $request->get('user_id', 0);
        //get all user task
        //$developerTask = DeveloperTask::where('user_id', $request->get('user_id', 0))->pluck('id')->toArray();

        //delete old priority
        \App\ErpPriority::where("user_id", $user_id)->where('model_type', '=', DeveloperTask::class)->delete();

        if (!empty($priority)) {
            foreach ((array) $priority as $model_id) {
                \App\ErpPriority::create([
                    'model_id' => $model_id,
                    'model_type' => DeveloperTask::class,
                    'user_id' => $user_id
                ]);
            }
            $developerTask = DeveloperTask::select('developer_tasks.id', 'developer_tasks.module_id', 'developer_tasks.subject', 'developer_tasks.task', 'developer_tasks.created_by')
                ->join('erp_priorities', function ($query) use ($user_id) {
                    $query->on('erp_priorities.model_id', '=', 'developer_tasks.id');
                    $query->where('erp_priorities.model_type', '=', DeveloperTask::class);
                    $query->where('erp_priorities.user_id', '=', $user_id);
                })
                ->where('is_resolved', '0')
                ->orderBy('erp_priorities.id')
                ->get();
            $message = "";
            $i = 1;
            foreach ($developerTask as $value) {
                $message .= $i . " : #Task-" . $value->id . "-" . $value->subject . "\n";
                $i++;
            }
            if (!empty($message)) {
                $requestData = new Request();
                $requestData->setMethod('POST');
                $params = [];
                $params['user_id'] = $request->get('user_id', 0);

                $string = "";
                if (!empty($request->get('global_remarkes', null))) {
                    $string .= $request->get('global_remarkes') . "\n";
                }
                $string .= "Task Priority is : \n" . $message;

                $params['message'] = $string;
                $params['status'] = 2;
                $requestData->request->add($params);
                app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'priority');
            }
        }
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function index(Request $request)
    {
        //        //$this->issueTaskIndex( $request,'task');
        //        return Redirect::to('/development/list/task');

        // Set required data
        $user = $request->user ?? Auth::id();
        $start = $request->range_start ? "$request->range_start 00:00" : '2018-01-01 00:00';
        $end = $request->range_end ? "$request->range_end 23:59" : Carbon::now()->endOfWeek();
        $id = null;
        // Set initial variables
        $progressTasks = new DeveloperTask();
        $plannedTasks = new DeveloperTask();
        $completedTasks = new DeveloperTask();
        // For non-admins get tasks assigned to the user
        if (!Auth::user()->hasRole('Admin')) {
            $progressTasks = DeveloperTask::where('user_id', Auth::id());
            $plannedTasks = DeveloperTask::where('user_id', Auth::id());
            $completedTasks = DeveloperTask::where('user_id', Auth::id());
        }
        // Get tasks for specific user if you are admin
        if (Auth::user()->hasRole('Admin') && (int) $request->user > 0) {
            $progressTasks = DeveloperTask::where('user_id', $user);
            $plannedTasks = DeveloperTask::where('user_id', $user);
            $completedTasks = DeveloperTask::where('user_id', $user);
        }
        // Filter by date/
        if ($request->get('range_start') != '') {
            $progressTasks = $progressTasks->whereBetween('created_at', [$start, $end]);
            $plannedTasks = $plannedTasks->whereBetween('created_at', [$start, $end]);
            $completedTasks = $completedTasks->whereBetween('created_at', [$start, $end]);
        }
        // Filter by ID
        if ($request->get('id')) {
            $progressTasks = $progressTasks->where(function ($query) use ($request) {
                $id = $request->get('id');
                $query->where('id', $id)->orWhere('subject', 'LIKE', "%$id%");
            });
            $plannedTasks = $plannedTasks->where(function ($query) use ($request) {
                $id = $request->get('id');
                $query->where('id', $id)->orWhere('subject', 'LIKE', "%$id%");
            });
            $completedTasks = $completedTasks->where(function ($query) use ($request) {
                $id = $request->get('id');
                $query->where('id', $id)->orWhere('subject', 'LIKE', "%$id%");
            });
        }
        // Get all data with user and messages
        $plannedTasks = $plannedTasks->where('status', 'Planned')->orderBy('created_at')->with(['user', 'messages', 'timeSpent'])->get();
        $completedTasks = $completedTasks->where('status', 'Done')->orderBy('created_at')->with(['user', 'messages', 'timeSpent'])->get();
        $progressTasks = $progressTasks->where('status', 'In Progress')->orderBy('created_at')->with(['user', 'messages', 'timeSpent'])->get();
        // Get all modules
        $modules = DeveloperModule::all();
        // Get all developers
        $users = Helpers::getUserArray(User::role('Developer')->get());
        // Get all task types
        $tasksTypes = TaskTypes::all();
        // Create empty array for module names
        $moduleNames = [];
        // Loop over all modules and store them
        foreach ($modules as $module) {
            $moduleNames[$module->id] = $module->name;
        }
        $times = [];
        return view('development.index', [
            'times' => $times,
            'users' => $users,
            'modules' => $modules,
            'user' => $user,
            'start' => $start,
            'end' => $end,
            'moduleNames' => $moduleNames,
            'completedTasks' => $completedTasks,
            'plannedTasks' => $plannedTasks,
            'progressTasks' => $progressTasks,
            'tasksTypes' => $tasksTypes,
            'title' => 'Dev'
        ]);
    }
    public function moveTaskToProgress(Request $request)
    {
        $task = DeveloperTask::find($request->get('task_id'));
        $date = $request->get('date');
        $task->status = 'In Progress';
        $hour = $request->get('hour') ?? '00';
        $minutes = $request->get('mimutes') ?? '00';
        $task->estimate_time = $date . ' ' . "$hour:$minutes:00 ";
        $task->start_time = Carbon::now()->toDateTimeString();
        $task->save();
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function completeTask(Request $request)
    {
        $task = DeveloperTask::find($request->get('task_id'));
        $task->status = 'Done';
        $task->end_time = Carbon::now()->toDateTimeString();
        $task->save();
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function relistTask(Request $request)
    {
        $task = DeveloperTask::find($request->get('task_id'));
        $task->status = 'Planned';
        $task->end_time = null;
        $task->start_time = null;
        $task->estimate_time = null;
        $task->save();
        return response()->json([
            'status' => 'success'
        ]);
    }



    public function updateAssignee(Request $request)
    {

        $task = DeveloperTask::find($request->get('task_id'));

        $old_assignee = $task->user_id;
        $task->user_id = $request->get('user_id');
        $task->save();
        $task_history = new TasksHistory;
        $task_history->date_time = date('Y-m-d H:i:s');
        $task_history->task_id = $request->get('task_id');
        $task_history->user_id = Auth::id();
        $task_history->old_assignee = $old_assignee;
        $task_history->new_assignee = $request->get('user_id');
        $task_history->save();
        return response()->json([
            'success'
        ]);
    }
    public function issueTaskIndex(Request $request)
    {
        
        //$request->request->add(["order" => $request->get("order","communication_desc")]);
        // Load issues

        $type = $request->tasktype ? $request->tasktype : 'all';
        $estimate_date = "";

        $title = 'Task List';

        $issues = DeveloperTask::with('timeSpent','developerTaskHistory','assignedUser','masterUser','timeSpent','leadtimeSpent','testertimeSpent','messages.taskUser','messages.user','tester');
        if($type == 'issue') {
            $issues = $issues->where('developer_tasks.task_type_id', '3');
        }
        if(!empty($request->estimate_date)){
            $estimate_date = date("Y-m-d", strtotime($request->estimate_date));
            $issues = $issues->where('developer_tasks.estimate_date', $estimate_date);
        }
        if($type == 'devtask') {
            $issues = $issues->where('developer_tasks.task_type_id', '1');
        }
        if ((int) $request->get('submitted_by') > 0) {
            $issues = $issues->where('developer_tasks.created_by', $request->get('submitted_by'));
        }
        if ((int) $request->get('responsible_user') > 0) {
            $issues = $issues->where('developer_tasks.responsible_user_id', $request->get('responsible_user'));
        }

        if ((int) $request->get('corrected_by') > 0) {
            $issues = $issues->where('developer_tasks.user_id', $request->get('corrected_by'));
        }

        if ((int) $request->get('assigned_to') > 0) {
            $issues = $issues->where('developer_tasks.assigned_to', $request->get('assigned_to'));
        }
        if ((int) $request->get('master_user_id') > 0) {
            $issues = $issues->where('developer_tasks.master_user_id', $request->get('master_user_id'));
        }
        if ((int) $request->get('team_lead_id') > 0) {
            $issues = $issues->where('developer_tasks.team_lead_id', $request->get('team_lead_id'));
        }
        if ((int) $request->get('tester_id') > 0) {
            $issues = $issues->where('developer_tasks.tester_id', $request->get('tester_id'));
        }
        if ($request->get('module')) {
            $issues = $issues->where('developer_tasks.module_id', $request->get('module'));
        }
        if (!empty($request->get('task_status', []))) {
            $issues = $issues->whereIn('developer_tasks.status', $request->get('task_status'));
        }
        if( isset( $request->is_estimated ) ){
            if( $request->get('is_estimated') == 'null' ){
                $issues = $issues->notEstimated();
            }
            if( $request->get('is_estimated') == 'not_approved'){
                $issues = $issues->adminNotApproved();
            }
        }
        else {
            //$issues = $issues->where('developer_tasks.status', 'In Progress');
        }

        if (!empty($request->get('repo_id'))) {
            $issues = $issues->where('repository_id', $request->get('repo_id'));
        }

        $whereCondition = "";
        if ($request->get('subject') != '') {
            $whereCondition = ' and message like  "%' . $request->get('subject') . '%"';
            $issues = $issues->where(function ($query) use ($request) {
                $subject = $request->get('subject');
                $query->where('developer_tasks.id', 'LIKE', "%$subject%")->orWhere('subject', 'LIKE', "%$subject%")->orWhere("task", "LIKE", "%$subject%")
                    ->orwhere("chat_messages.message", 'LIKE', "%$subject%");
            });
        }
        // if ($request->get('language') != '') {
        //     $issues = $issues->where('language', 'LIKE', "%" . $request->get('language') . "%");
        // }
        $issues = $issues->leftJoin(DB::raw('(SELECT MAX(id) as  max_id, issue_id, message  FROM `chat_messages` where issue_id > 0 ' . $whereCondition . ' GROUP BY issue_id ) m_max'), 'm_max.issue_id', '=', 'developer_tasks.id');
        $issues = $issues->leftJoin('chat_messages', 'chat_messages.id', '=', 'm_max.max_id');
        if ($request->get('last_communicated', "off") == "on") {
            $issues = $issues->orderBy('chat_messages.id', "desc");
        }
        
        $issues = $issues->select("developer_tasks.*","chat_messages.message","chat_messages.user_id AS message_user_id", "chat_messages.is_reminder AS message_is_reminder", "chat_messages.status as message_status","chat_messages.sent_to_user_id");
        

        // for devloper time 
        // $issues->selectRaw('IF(developer_tasks.assigned_to IS NOT NULL, sum(mot.time) , 0) as assigned_to_time');
        // $issues->leftJoin('meeting_and_other_times as mot', function($q){
        //     $q->on('mot.model_id', '=', 'developer_tasks.id');
        //     $q->on('mot.user_id', '=', 'developer_tasks.assigned_to');
        //     // $q->where('mot.model','=','App\DeveloperTask');
        // });

        // // for lead time
        // $issues->selectRaw('IF(developer_tasks.master_user_id IS NOT NULL,  sum(mott.time), 0) as master_time');
        // $issues->leftJoin('meeting_and_other_times as mott', function($q){
        //     $q->on('mott.model_id', '=', 'developer_tasks.id');
        //     $q->on('mott.user_id', '=', 'developer_tasks.master_user_id');
        //     // $q->where('mott.model','=','App\DeveloperTask');

        // });

        // // // for tester time
        // $issues->selectRaw('IF(developer_tasks.tester_id IS NOT NULL, sum(mottt.time), 0) as tester_time');
        // $issues->leftJoin('meeting_and_other_times as mottt', function($q){
        //     $q->on('mottt.model_id', '=', 'developer_tasks.id');
        //     $q->on('mottt.user_id', '=', 'developer_tasks.tester_id');
        //     // $q->where('mottt.model','=','App\DeveloperTask');

        // });

        // Set variables with modules and users
        $modules = DeveloperModule::orderBy('name')->get();
        
        $usrlst = User::orderBy('name')->where('is_active',1)->get();
        $users = Helpers::getUserArray($usrlst);

        // $statusList = \DB::table("developer_tasks")->where("status", "!=", "")->groupBy("status")->select("status")->pluck("status", "status")->toArray();

        $statusList = \DB::table("task_statuses")->select("name")->pluck("name", "name")->toArray();

        /*$statusList = array_merge([
            "" => "Select Status",
        ], $statusList);*/

        // Hide resolved
        /*if ((int)$request->show_resolved !== 1) {
            $issues = $issues->where('is_resolved', 0);
        }*/
        if (!auth()->user()->isReviwerLikeAdmin()) {
            // $issues = $issues->where(function ($q) {
            //     $q->where("developer_tasks.assigned_to", auth()->user()->id)->where('is_resolved', 0);
            // });
            $issues = $issues->where(function ($query) use ($request) {
                $query->where("developer_tasks.assigned_to", auth()->user()->id)
                ->orWhere("developer_tasks.master_user_id", auth()->user()->id)
                ->orWhere("developer_tasks.tester_id", auth()->user()->id)
                ->orWhere("developer_tasks.team_lead_id", auth()->user()->id);
            });
        }
        // category filter start count
        $issuesGroups = clone ($issues);
        $issuesGroups = $issuesGroups->where('developer_tasks.status', 'Planned')->groupBy("developer_tasks.assigned_to")->select([\DB::raw("count(developer_tasks.id) as total_product"), "developer_tasks.assigned_to"])->pluck("total_product", "assigned_to")->toArray();
        $userIds = array_values(array_filter(array_keys($issuesGroups)));
        $userModel = \App\User::whereIn("id", $userIds)->pluck("name", "id")->toArray();

        $countPlanned = [];
        if (!empty($issuesGroups) && !empty($userModel)) {
            foreach ($issuesGroups as $key => $count) {
                $countPlanned[] = [
                    "id" => $key,
                    "name" => !empty($userModel[$key]) ? $userModel[$key] : "N/A",
                    "count" => $count,
                ];
            }
        }
        // category filter start count
        $issuesGroups = clone ($issues);
        $issuesGroups = $issuesGroups->where('developer_tasks.status', 'In Progress')->groupBy("developer_tasks.assigned_to")->select([\DB::raw("count(developer_tasks.id) as total_product"), "developer_tasks.assigned_to"])->pluck("total_product", "assigned_to")->toArray();
        $userIds = array_values(array_filter(array_keys($issuesGroups)));

        $userModel = \App\User::whereIn("id", $userIds)->pluck("name", "id")->toArray();
        $countInProgress = [];
        if (!empty($issuesGroups) && !empty($userModel)) {
            foreach ($issuesGroups as $key => $count) {
                $countInProgress[] = [
                    "id" => $key,
                    "name" => !empty($userModel[$key]) ? $userModel[$key] : "N/A",
                    "count" => $count,
                ];
            }
        }

        // Sort
        if ($request->order == 'priority') {
            $issues = $issues->orderBy('priority', 'ASC')->orderBy('created_at', 'DESC')->with('communications');
        }
        else if ($request->order == 'latest_task_first') {
            $issues = $issues->orderBy('developer_tasks.id', 'DESC');
        } else {
            $issues = $issues->orderBy('chat_messages.id', "desc");
        }

        $issues =  $issues->groupBy("developer_tasks.id");

        $issues =  $issues->with('communications');
        //DB::enableQueryLog();
        // return $issues = $issues->limit(20)->get();
        
        //dd(DB::getQueryLog());

        if($request->download == 2){
            $issues = $issues->get();
            $tasks_csv = [];
            foreach ($issues as $value) {
                $task_csv = [];
                $task_csv['ID'] = $value->id;
                $task_csv['Subject'] = $value->subject;
                $task_csv['Communication'] = $value->message;
                $task_csv['Developer'] = ($value->assignedUser) ? $value->assignedUser->name : 'Unassigned';
                array_push($tasks_csv,$task_csv);
            }
            $this->outputCsv('downaload-task-summaries.csv', $tasks_csv);
        }else{
            $issues = $issues->paginate(Setting::get('pagination'));
        }

        $priority = \App\ErpPriority::where('model_type', '=', DeveloperTask::class)->pluck('model_id')->toArray();

        $respositories = GithubRepository::all();


        // $languages = \App\DeveloperLanguage::get()->pluck("name", "id")->toArray();

        if ( request()->ajax() ) {
			return view("development.partials.load-more", compact('issues', 'users', 'modules', 'request','title','type','countPlanned','countInProgress','statusList','priority'));
        }


        return view('development.issue', [
            'issues' => $issues,
            'users' => $users,
            'modules' => $modules,
            'request' => $request,
            'title' => $title,
            'type' => $type,
            'priority' => $priority,
            'countPlanned' => $countPlanned,
            'countInProgress' => $countInProgress,
            'statusList' => $statusList,
            'respositories' => $respositories,
            // 'languages' => $languages
        ]);
    }

    public function exportTask(Request $request){

        $type = 'all';
        $whereCondition = "";
        $issues = DeveloperTask::with('timeSpent');

        if($type == 'issue') {
            $issues = $issues->where('developer_tasks.task_type_id', '3');
        }
        if(!empty($request->estimate_date)){
            $estimate_date = date("Y-m-d", strtotime($request->estimate_date));
            $issues = $issues->where('developer_tasks.estimate_date', $estimate_date);
        }
        if($type == 'devtask') {
            $issues = $issues->where('developer_tasks.task_type_id', '1');
        }
        if ((int) $request->get('submitted_by') > 0) {
            $issues = $issues->where('developer_tasks.created_by', $request->get('submitted_by'));
        }
        if ((int) $request->get('responsible_user') > 0) {
            $issues = $issues->where('developer_tasks.responsible_user_id', $request->get('responsible_user'));
        }
        if ((int) $request->get('corrected_by') > 0) {
            $issues = $issues->where('developer_tasks.user_id', $request->get('corrected_by'));
        }
        if ((int) $request->get('assigned_to') > 0) {
            $issues = $issues->where('developer_tasks.assigned_to', $request->get('assigned_to'));
        }
        if ((int) $request->get('master_user_id') > 0) {
            $issues = $issues->where('developer_tasks.master_user_id', $request->get('master_user_id'));
        }
        if ((int) $request->get('team_lead_id') > 0) {
            $issues = $issues->where('developer_tasks.team_lead_id', $request->get('team_lead_id'));
        }
        if ((int) $request->get('tester_id') > 0) {
            $issues = $issues->where('developer_tasks.tester_id', $request->get('tester_id'));
        }
        if ($request->get('module')) {
            $issues = $issues->where('developer_tasks.module_id', $request->get('module'));
        }
        if (!empty($request->get('task_status', []))) {
            $issues = $issues->whereIn('developer_tasks.status', $request->get('task_status'));
        }

        $issues = $issues->leftJoin(DB::raw('(SELECT MAX(id) as  max_id, issue_id, message  FROM `chat_messages` where issue_id > 0 ' . $whereCondition . ' GROUP BY issue_id ) m_max'), 'm_max.issue_id', '=', 'developer_tasks.id');
        $issues = $issues->leftJoin('chat_messages', 'chat_messages.id', '=', 'm_max.max_id');
        if ($request->get('last_communicated', "off") == "on") {
            $issues = $issues->orderBy('chat_messages.id', "desc");
        }
        
        $issues = $issues->select("developer_tasks.*","chat_messages.message","chat_messages.user_id AS message_user_id", "chat_messages.is_reminder AS message_is_reminder", "chat_messages.status as message_status","chat_messages.sent_to_user_id");
        if (!auth()->user()->isReviwerLikeAdmin()) {
            $issues = $issues->where(function ($query) use ($request) {
                $query->where("developer_tasks.assigned_to", auth()->user()->id)
                ->orWhere("developer_tasks.master_user_id", auth()->user()->id)
                ->orWhere("developer_tasks.tester_id", auth()->user()->id)
                ->orWhere("developer_tasks.team_lead_id", auth()->user()->id);
            });
        }
        // category filter start count
        $issuesGroups = clone ($issues);
        $issuesGroups = $issuesGroups->where('developer_tasks.status', 'Planned')->groupBy("developer_tasks.assigned_to")->select([\DB::raw("count(developer_tasks.id) as total_product"), "developer_tasks.assigned_to"])->pluck("total_product", "assigned_to")->toArray();
        $userIds = array_values(array_filter(array_keys($issuesGroups)));
        $userModel = \App\User::whereIn("id", $userIds)->pluck("name", "id")->toArray();

        $countPlanned = [];
        if (!empty($issuesGroups) && !empty($userModel)) {
            foreach ($issuesGroups as $key => $count) {
                $countPlanned[] = [
                    "id" => $key,
                    "name" => !empty($userModel[$key]) ? $userModel[$key] : "N/A",
                    "count" => $count,
                ];
            }
        }
        
        // category filter start count
        $issuesGroups = clone ($issues);
        $issuesGroups = $issuesGroups->where('developer_tasks.status', 'In Progress')->groupBy("developer_tasks.assigned_to")->select([\DB::raw("count(developer_tasks.id) as total_product"), "developer_tasks.assigned_to"])->pluck("total_product", "assigned_to")->toArray();
        $userIds = array_values(array_filter(array_keys($issuesGroups)));

        $userModel = \App\User::whereIn("id", $userIds)->pluck("name", "id")->toArray();
        $countInProgress = [];
        if (!empty($issuesGroups) && !empty($userModel)) {
            foreach ($issuesGroups as $key => $count) {
                $countInProgress[] = [
                    "id" => $key,
                    "name" => !empty($userModel[$key]) ? $userModel[$key] : "N/A",
                    "count" => $count,
                ];
            }
        }

        // Sort
        if ($request->order == 'priority') {
            $issues = $issues->orderBy('priority', 'ASC')->orderBy('created_at', 'DESC')->with('communications');
        }
        else if ($request->order == 'latest_task_first') {
            $issues = $issues->orderBy('developer_tasks.id', 'DESC');
        } else {
            $issues = $issues->orderBy('chat_messages.id', "desc");
        }

        $issues =  $issues->with('communications');
        
        $issues = $issues->get();
        $tasks_csv = [];


        
        foreach ($issues as $value) {
            $task_csv = [];
            $task_csv['id'] = $value->id;
            $task_csv['Subject'] = $value->subject;
            $task_csv['Communication'] = $value->message;
            $task_csv['Developer'] = (!empty($users[$value->assigned_to]) ? $users[$value->assigned_to] : 'Unassigned' );
            $task_csv['Approved Time'] = $value->estimate_minutes;
            $task_csv['Status'] = $value->status;
            $startTime = Carbon::parse($value->start_time);
            $endTime = Carbon::parse($value->end_time);
            $totalDuration = $endTime->diffInMinutes($startTime);
            $task_csv['Tracked Time'] = $totalDuration;
            $task_csv['Difference'] = ($totalDuration-$value->estimate_minutes > 0 ? $totalDuration-$value->estimate_minutes : "+".abs($totalDuration-$value->estimate_minutes));
            array_push($tasks_csv,$task_csv);

        }
        
        $this->outputCsv('download-task-summaries.csv', $tasks_csv);
    }

    private function outputCsv($fileName, $assocDataArray)
    {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $fileName);
        if(isset($assocDataArray['0'])){

            $fp = fopen('php://output', 'w');
            fputcsv($fp, array_keys($assocDataArray['0']));
            foreach($assocDataArray AS $values){
                fputcsv($fp, $values);
            }
            fclose($fp);
        }
    }

    public function summaryList(Request $request)
    {
        //$request->request->add(["order" => $request->get("order","communication_desc")]);
        // Load issues
        $type = $request->tasktype ? $request->tasktype : 'all';

        $title = 'Task List';

        $issues = DeveloperTask::with('timeSpent');
        if($type == 'issue') {
            $issues = $issues->where('developer_tasks.task_type_id', '3');
        }
        if($type == 'devtask') {
            $issues = $issues->where('developer_tasks.task_type_id', '1');
        }
        if ((int) $request->get('submitted_by') > 0) {
            $issues = $issues->where('developer_tasks.created_by', $request->get('submitted_by'));
        }
        if ((int) $request->get('responsible_user') > 0) {
            $issues = $issues->where('developer_tasks.responsible_user_id', $request->get('responsible_user'));
        }

        if ((int) $request->get('corrected_by') > 0) {
            $issues = $issues->where('developer_tasks.user_id', $request->get('corrected_by'));
        }

        if ((int) $request->get('assigned_to') > 0) {
            $issues = $issues->where('developer_tasks.assigned_to', $request->get('assigned_to'));
        }
        if ($request->get('module')) {
            $issues = $issues->where('developer_tasks.module_id', $request->get('module'));
        }
        if (!empty($request->get('task_status', []))) {
            $issues = $issues->whereIn('developer_tasks.status', $request->get('task_status'));
        }
        else {
            $issues = $issues->where('developer_tasks.status', 'In Progress');
        }

        $whereCondition = "";
        if ($request->get('subject') != '') {
            $whereCondition = ' and message like  "%' . $request->get('subject') . '%"';
            $issues = $issues->where(function ($query) use ($request) {
                $subject = $request->get('subject');
                $query->where('developer_tasks.id', 'LIKE', "%$subject%")->orWhere('subject', 'LIKE', "%$subject%")->orWhere("task", "LIKE", "%$subject%")
                    ->orwhere("chat_messages.message", 'LIKE', "%$subject%");
            });
        }
        // if ($request->get('language') != '') {
        //     $issues = $issues->where('language', 'LIKE', "%" . $request->get('language') . "%");
        // }
        $issues = $issues->leftJoin(DB::raw('(SELECT MAX(id) as  max_id, issue_id, message  FROM `chat_messages` where issue_id > 0 ' . $whereCondition . ' GROUP BY issue_id ) m_max'), 'm_max.issue_id', '=', 'developer_tasks.id');
        $issues = $issues->leftJoin('chat_messages', 'chat_messages.id', '=', 'm_max.max_id');

        if ($request->get('last_communicated', "off") == "on") {
            $issues = $issues->orderBy('chat_messages.id', "desc");
        }

        $issues = $issues->select("developer_tasks.*","chat_messages.message");

        // Set variables with modules and users
        $modules = DeveloperModule::orderBy('name')->get();

        $users = Helpers::getUserArray(User::orderBy('name')->get());
        
        // $statusList = \DB::table("developer_tasks")->where("status", "!=", "")->groupBy("status")->select("status")->pluck("status", "status")->toArray();

        $statusList = \DB::table("task_statuses")->select("name")->orderBy('name')->pluck("name", "name")->toArray();

        $statusList = array_merge([
            "" => "Select Status",
        ], $statusList);

        // Hide resolved
        /*if ((int)$request->show_resolved !== 1) {
            $issues = $issues->where('is_resolved', 0);
        }*/
        if (!auth()->user()->isReviwerLikeAdmin()) {
            // $issues = $issues->where(function ($q) {
            //     $q->where("developer_tasks.assigned_to", auth()->user()->id)->where('is_resolved', 0);
            // });

            $issues = $issues->where(function ($query) use ($request) {
                $query->where("developer_tasks.assigned_to", auth()->user()->id)
                ->orWhere("developer_tasks.master_user_id", auth()->user()->id);
            });

        }

        // category filter start count
        $issuesGroups = clone ($issues);
        $issuesGroups = $issuesGroups->where('developer_tasks.status', 'Planned')->groupBy("developer_tasks.assigned_to")->select([\DB::raw("count(developer_tasks.id) as total_product"), "developer_tasks.assigned_to"])->pluck("total_product", "assigned_to")->toArray();
        $userIds = array_values(array_filter(array_keys($issuesGroups)));
        $userModel = \App\User::whereIn("id", $userIds)->pluck("name", "id")->toArray();

        $countPlanned = [];
        if (!empty($issuesGroups) && !empty($userModel)) {
            foreach ($issuesGroups as $key => $count) {
                $countPlanned[] = [
                    "id" => $key,
                    "name" => !empty($userModel[$key]) ? $userModel[$key] : "N/A",
                    "count" => $count,
                ];
            }
        }
        // category filter start count
        $issuesGroups = clone ($issues);
        $issuesGroups = $issuesGroups->where('developer_tasks.status', 'In Progress')->groupBy("developer_tasks.assigned_to")->select([\DB::raw("count(developer_tasks.id) as total_product"), "developer_tasks.assigned_to"])->pluck("total_product", "assigned_to")->toArray();
        $userIds = array_values(array_filter(array_keys($issuesGroups)));

        $userModel = \App\User::whereIn("id", $userIds)->pluck("name", "id")->toArray();
        $countInProgress = [];
        if (!empty($issuesGroups) && !empty($userModel)) {
            foreach ($issuesGroups as $key => $count) {
                $countInProgress[] = [
                    "id" => $key,
                    "name" => !empty($userModel[$key]) ? $userModel[$key] : "N/A",
                    "count" => $count,
                ];
            }
        }
        // Sort
        if ($request->order == 'priority') {
            $issues = $issues->orderBy('priority', 'ASC')->orderBy('created_at', 'DESC')->with('communications');
        }
        else if ($request->order == 'latest_task_first') {
            $issues = $issues->orderBy('developer_tasks.id', 'DESC');
        } else {
            $issues = $issues->orderBy('chat_messages.id', "desc");
        }

        $issues =  $issues->with('communications');

        // return $issues = $issues->limit(20)->get();
        $issues = $issues->paginate(Setting::get('pagination'));
        $priority = \App\ErpPriority::where('model_type', '=', DeveloperTask::class)->pluck('model_id')->toArray();

        // $languages = \App\DeveloperLanguage::get()->pluck("name", "id")->toArray();

        if ( request()->ajax() ) {
            //return view("development.partials.summary-load-more", compact('issues', 'users', 'modules', 'request','title','type','countPlanned','countInProgress','statusList','priority'));
        }

        return view('development.summarylist', [
            'issues' => $issues,
            'users' => $users,
            'modules' => $modules,
            'request' => $request,
            'title' => $title,
            'type' => $type,
            'priority' => $priority,
            'countPlanned' => $countPlanned,
            'countInProgress' => $countInProgress,
            'statusList' => $statusList
            // 'languages' => $languages
        ]);
    }

    public function summaryList1(Request $request)
    {
        $modules = DeveloperModule::all();
        print_r($modules);

        $statusList = \DB::table("task_statuses")->select("name")->pluck("name", "name")->toArray();

        $statusList = array_merge([
            "" => "Select Status",
        ], $statusList);

        return view('development.summarylist',compact('modules','statusList'));
    }
    public function issueIndex(Request $request)
    {
        $issues = new Issue;

        if ((int) $request->get('submitted_by') > 0) {
            $issues = $issues->where('submitted_by', $request->get('submitted_by'));
        }
        if ((int) $request->get('responsible_user') > 0) {
            $issues = $issues->where('responsible_user_id', $request->get('responsible_user'));
        }
        if ((int) $request->get('assigned_to') > 0) {
            $issues = $issues->where('assigned_to', $request->get('assigned_to'));
        }
        if ((int) $request->get('corrected_by') > 0) {
            $issues = $issues->where('user_id', $request->get('corrected_by'));
        }
        if ($request->get('module')) {
            $issues = $issues->where('module', $request->get('module'));
        }
        if ($request->get('subject') != '') {
            $issues = $issues->where(function ($query) use ($request) {
                $subject = $request->get('subject');
                $query->where('id', 'LIKE', "%$subject%")->orWhere('subject', 'LIKE', "%$subject%");
            });
        }
        $modules = DeveloperModule::all();
        $users = Helpers::getUserArray(User::all());
        // Hide resolved
        if ((int) $request->show_resolved !== 1) {
            $issues = $issues->where('is_resolved', 0);
        }
        // Sort
        if ($request->order == 'create') {
            $issues = $issues->orderBy('created_at', 'DESC')->with('communications')->get();
        } else {
            $issues = $issues->orderBy('priority', 'ASC')->orderBy('created_at', 'DESC')->with('communications')->get();
        }
        $priority = \App\ErpPriority::where('model_type', '=', Issue::class)->pluck('model_id')->toArray();
        return view('development.issue', [
            'issues' => $issues,
            'users' => $users,
            'modules' => $modules,
            'request' => $request,
            'title' => 'Issue',
            'priority' => $priority,
        ]);
    }
    public function listByUserId(Request $request)
    {
        $user_id = $request->get('user_id', 0);
        $selected_issue = $request->get('selected_issue', []);

        $issues = DeveloperTask::select('developer_tasks.id', 'developer_tasks.module', 'developer_tasks.module_id', 'developer_tasks.subject', 'developer_tasks.task', 'developer_tasks.created_by')
            ->leftJoin('erp_priorities', function ($query) use ($user_id) {
                $query->on('erp_priorities.model_id', '=', 'developer_tasks.id');
                $query->where('erp_priorities.model_type', '=', DeveloperTask::class);
            })->where('is_resolved', '0');

        if (auth()->user()->isAdmin()) {
            $issues = $issues->where(function ($q) use ($selected_issue, $user_id) {
                $user_id = is_null($user_id) ? 0 : $user_id;
                $q->whereIn('developer_tasks.id', $selected_issue)->orWhere("erp_priorities.user_id", $user_id);
            });
            //$issues = $issues->whereIn('developer_tasks.id', $request->get('selected_issue', []));
        } else {
            $issues = $issues->whereNotNull('erp_priorities.id');
        }

        $issues = $issues->groupBy("developer_tasks.id")->orderBy('erp_priorities.id')->get();

        foreach ($issues as &$value) {
            $value->module = $value->developerModule ? $value->developerModule->name : 'Not Specified';
            $value->submitted_by = ($value->submitter) ? $value->submitter->name : "";
        }
        unset($value);
        return response()->json($issues);
    }
    public function setPriority(Request $request)
    {
        $priority = $request->get('priority', null);
        $user_id = $request->get('user_id', 0);
        //get all user task
        $issues = DeveloperTask::where('assigned_to', $request->get('user_id', 0))->pluck('id')->toArray();
        //delete old priority
        \App\ErpPriority::where("user_id", $user_id)->where('model_type', '=', DeveloperTask::class)->delete();

        if (!empty($priority)) {
            foreach ((array) $priority as $model_id) {
                \App\ErpPriority::create([
                    'model_id' => $model_id,
                    'model_type' => DeveloperTask::class,
                    'user_id' => $user_id
                ]);
            }

            $issues = DeveloperTask::select('developer_tasks.id', 'developer_tasks.module_id', 'developer_tasks.subject', 'developer_tasks.task', 'developer_tasks.created_by', 'developer_tasks.task_type_id')
                ->join('erp_priorities', function ($query) use ($user_id) {
                    $query->on('erp_priorities.model_id', '=', 'developer_tasks.id');
                    $query->where('erp_priorities.model_type', '=', DeveloperTask::class);
                    $query->where('erp_priorities.user_id', '=', $user_id);
                })
                ->where('is_resolved', '0')
                ->orderBy('erp_priorities.id')
                ->get();
            $message = '';
            $i = 1;
            foreach ($issues as $value) {
                $mode  = ($value->task_type_id == 3) ? "#ISSUE-" : "#TASK-";
                $message .= $i . " : " . $mode . $value->id . "-" . $value->subject . "\n";
                $i++;
            }
            if (!empty($message)) {
                $requestData = new Request();
                $requestData->setMethod('POST');
                $params = [];
                $params['user_id'] = $request->get('user_id', 0);

                $string = "";
                if (!empty($request->get('global_remarkes', null))) {
                    $string .= $request->get('global_remarkes') . "\n";
                }
                $string .= "Issue Priority is : \n" . $message;

                $params['message'] = $string;
                $params['status'] = 2;
                $requestData->request->add($params);
                app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'priority');
            }
        }
        return response()->json([
            'status' => 'success'
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function issueCreate()
    {
        return view('development.issue-create');
    }

    // private function getTokens()
    // {
    //     if (!Storage::disk('local')->exists(HUBSTAFF_TOKEN_FILE_NAME)) {
    //         $this->generateAccessToken(SEED_REFRESH_TOKEN);
    //     }
    //     $tokens = json_decode(Storage::disk('local')->get(HUBSTAFF_TOKEN_FILE_NAME));
    //     return $tokens;
    // }

    // private function generateAccessToken(string $refreshToken)
    // {
    //     $httpClient = new Client();
    //     try {
    //         $response = $httpClient->post(
    //             'https://account.hubstaff.com/access_tokens',
    //             [
    //                 RequestOptions::FORM_PARAMS => [
    //                     'grant_type' => 'refresh_token',
    //                     'refresh_token' => $refreshToken
    //                 ]
    //             ]
    //         );

    //         $responseJson = json_decode($response->getBody()->getContents());

    //         $tokens = [
    //             'access_token' => $responseJson->access_token,
    //             'refresh_token' => $responseJson->refresh_token
    //         ];

    //         return Storage::disk('local')->put(HUBSTAFF_TOKEN_FILE_NAME, json_encode($tokens));
    //     } catch (Exception $e) {
    //         return false;
    //     }
    // }

    // private function refreshTokens()
    // {
    //     $tokens = $this->getTokens();
    //     $this->generateAccessToken($tokens->refresh_token);
    // }

    private function createHubstaffTask(string $taskSummary, ?int $hubstaffUserId, int $projectId, bool $shouldRetry = true)
    {
        $tokens = $this->getTokens();
        $url = 'https://api.hubstaff.com/v2/projects/' . $projectId . '/tasks';
        $httpClient = new Client();
        try {

            $body = array(
                'summary' => $taskSummary
            );

            if ($hubstaffUserId) {
                $body['assignee_id'] = $hubstaffUserId;
            } else {
                // $body['assignee_id'] = getenv('HUBSTAFF_DEFAULT_ASSIGNEE_ID');
                $body['assignee_id'] = config('env.HUBSTAFF_DEFAULT_ASSIGNEE_ID');
            }

            $response = $httpClient->post(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token,
                        'Content-Type' => 'application/json'
                    ],

                    RequestOptions::BODY => json_encode($body)
                ]
            );
            $parsedResponse = json_decode($response->getBody()->getContents());
            return $parsedResponse->task->id;
        } catch (ClientException $e) {
        	if($e->getCode() == 401) {
        		$this->refreshTokens();
                if ($shouldRetry) {
                    return $this->createHubstaffTask(
                        $taskSummary,
                        $hubstaffUserId,
                        $projectId,
                        false
                    );
                }
        	}
        }
        return false;
    }

    /**
     * return branch name or false
     */
    private function createBranchOnGithub($repositoryId, $taskId, $taskTitle,  $branchName = 'master')
    {
        $newBranchName = 'DEVTASK-' . $taskId;

        // get the master branch SHA
        // https://api.github.com/repositories/:repoId/branches/master
        $url = 'https://api.github.com/repositories/' . $repositoryId . '/branches/' . $branchName;
        try {
            $response = $this->githubClient->get($url);
            $masterSha = json_decode($response->getBody()->getContents())->commit->sha;
        } catch (Exception $e) {
            return false;
        }

        // create a branch
        // https://api.github.com/repositories/:repo/git/refs
        $url = 'https://api.github.com/repositories/' . $repositoryId . '/git/refs';
        try {
            $this->githubClient->post(
                $url,
                [
                    RequestOptions::BODY => json_encode([
                        "ref" => "refs/heads/" . $newBranchName,
                        "sha" => $masterSha
                    ])
                ]
            );
            return $newBranchName;
        } catch (Exception $e) {

            if ($e instanceof ClientException && $e->getResponse()->getStatusCode() == 422) {
                // branch already exists
                return $newBranchName;
            }
            return false;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $loggedUser = $request->user();

        $this->validate($request, [
            'subject' => 'sometimes|nullable|string',
            'task' => 'required|string|min:3',
            //'cost' => 'sometimes|nullable|integer',
            'status' => 'required',
            'repository_id' => 'required',
            'module_id' => 'required',

        ]);
        
        $data = $request->except('_token');
        // $data['hubstaff_project'] = getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID');
        $data['hubstaff_project'] = config('env.HUBSTAFF_BULK_IMPORT_PROJECT_ID');

        $data['user_id'] = $request->user_id ? $request->user_id : Auth::id();
        //$data[ 'responsible_user_id' ] = $request->user_id ? $request->user_id : Auth::id();
        $data['created_by'] = Auth::id();
        $data['priority'] = 0;
        //$data[ 'submitted_by' ] = Auth::id();
        $data['hubstaff_task_id'] = 0;
        $data['repository_id'] = $request->get('repository_id');
        // $module = $request->get('module_id');
        // if (!empty($module)) {
        //     $module = DeveloperModule::find($module);
        //     if (!$module) {
        //         $module = new DeveloperModule();
        //         $module->name = $request->get('module_id');
        //         $module->save();
        //         $data['module_id'] = $module->id;
        //     }
        // }
        $task = DeveloperTask::create($data);

        //check the assinged user in any team ?
        if($request->assigned_to > 0 && empty($task->team_lead_id)) {
            $teamUser = \App\TeamUser::where("user_id",$task->assigned_to)->first();
            if($teamUser) {
                $team = $teamUser->team;
                if($team) {
                    $task->team_lead_id = $team->user_id;
                    $task->save();
                }
            }else{
                $isTeamLeader = \App\Team::where("user_id",$task->assigned_to)->first();
                if($isTeamLeader) {
                    $task->team_lead_id = $task->assigned_to;
                    $task->save();
                }
            }

        }




        // if ($request->hasfile('images')) {
        //     foreach ($request->file('images') as $image) {
        //         $media = MediaUploader::fromSource($image)
        //             ->toDirectory('developertask/' . floor($task->id / config('constants.image_per_folder')))
        //             ->upload();
        //         $task->attachMedia($media, config('constants.media_tags'));
        //     }
        // }

        // CREATE GITHUB REPOSITORY BRANCH
        $newBranchName = $this->createBranchOnGithub(
            $request->get('repository_id'),
            $task->id,
            $task->subject
        );

        // UPDATE TASK WITH BRANCH NAME
        if ($newBranchName) {
            $task->github_branch_name = $newBranchName;
            $task->save();
        }

        if (is_string($newBranchName)) {
            $message = $request->input('task') . PHP_EOL . "A new branch " . $newBranchName . " has been created. Please pull the current code and run 'git checkout " . $newBranchName . "' to work in that branch.";
        } else {
            $message = $request->input('task');
        }
        $requestData = new Request();
        $requestData->setMethod('POST');
        $requestData->request->add(['issue_id' => $task->id, 'message' => $message, 'status' => 1]);

        app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'issue');

        MessageHelper::sendEmailOrWebhookNotification([$task->user_id, $task->assigned_to, $task->master_user_id, $task->responsible_user_id, $task->team_lead_id, $task->tester_id], ' [ '.$loggedUser->name.' ] - '. $message);

        // if ($task->status == 'Done') {
        //   NotificationQueueController::createNewNotification([
        //     'message' => 'New Task to Verify',
        //     'timestamps' => ['+0 minutes'],
        //     'model_type' => DeveloperTask::class,
        //     'model_id' =>  $task->id,
        //     'user_id' => Auth::id(),
        //     'sent_to' => 6,
        //     'role' => '',
        //   ]);
        //
        //   NotificationQueueController::createNewNotification([
        //     'message' => 'New Task to Verify',
        //     'timestamps' => ['+0 minutes'],
        //     'model_type' => DeveloperTask::class,
        //     'model_id' =>  $task->id,
        //     'user_id' => Auth::id(),
        //     'sent_to' => 56,
        //     'role' => '',
        //   ]);
        // }

        $hubstaff_project_id = $data['hubstaff_project'];

        $assignedUser = HubstaffMember::where('user_id', $request->input('assigned_to'))->first();
        // $hubstaffProject = HubstaffProject::find($request->input('hubstaff_project'));

        $hubstaffUserId = null;
        if ($assignedUser) {
            $hubstaffUserId = $assignedUser->hubstaff_user_id;
        }
        $summary = substr($request->input('task'), 0, 200);
        if($data['task_type_id'] == 1) {
            $taskSummery = '#DEVTASK-' . $task->id . ' => ' . $summary;
        }
        else {
            $taskSummery = '#TASK-' . $task->id . ' => ' . $summary;
        }


        $hubstaffTaskId = $this->createHubstaffTask(
            $taskSummery,
            $hubstaffUserId,
            $hubstaff_project_id
        );

        if($hubstaffTaskId) {
            $task->hubstaff_task_id = $hubstaffTaskId;
            $task->save();
        }
        if ($hubstaffUserId) {
            $task = new HubstaffTask();
            $task->hubstaff_task_id = $hubstaffTaskId;
            $task->project_id = $hubstaff_project_id;
            $task->hubstaff_project_id = $hubstaff_project_id;
            $task->summary = $request->input('task');
            $task->save();
        }

        if ($request->ajax()) {
            return response()->json(['task' => $task]);
        }
        return redirect(url('development/list'))->with('success', 'You have successfully added task!');
    }

    public function issueStore(Request $request)
    {
        $this->validate($request, [
            'priority' => 'required|integer',
            'issue' => 'required|min:3'
        ]);
        $data = $request->except('_token');
        $module = $request->get('module');


        if ($request->response == 1) {

            $reference = md5(strtolower($request->reference));
            //Check if reference exist
            $existReference = DeveloperTask::where('reference', $reference)->first();
            if ($existReference != null || $existReference != '') {
                return redirect()->back()->withErrors(['Issue Already Created!']);
            }
        }

        if (!isset($reference)) {
            $reference = null;
        }
        
        if(is_string($module)) {
            $module = DeveloperModule::where("name","like",$module)->first();
        }else{
            $module = DeveloperModule::find($module);
        }

        if (!$module) {
            $module = new DeveloperModule();
            $module->name = $request->get('module');
            $module->save();
            $data['module'] = $module->id;
        }
        //$issue = Issue::create($data);
        /*$responsibleUser = $request->get('responsible_user_id', 0);
        if (empty($responsibleUser)) {
            $responsibleUser = Auth::id();
        }*/
        $userId = Auth::id();
        $userId = !empty($userId) ? $userId : $request->get('assigned_to', 0);
        $task = new DeveloperTask;
        $task->priority = $request->input('priority');
        $task->subject = $request->input('subject');
        $task->task = $request->input('issue');
        $task->responsible_user_id = 0;
        $task->assigned_to = $request->get('assigned_to', 0);
        $task->module_id = $module->id;
        $task->user_id = 0;
        $task->assigned_by = $userId;
        $task->created_by = $userId;
        $task->reference = $reference;
        $task->status = $request->get("status",'Issue');
        $task->task_type_id = $request->get("task_type_id",3);
        $task->scraper_id = $request->input('scraper_id',null);
        $task->brand_id = $request->input('brand_id',null);
        $task->save();

        $repo = GithubRepository::where('name', 'erp')->first();

        if ($repo) {
            $this->createBranchOnGithub($repo->id, $task->id, $task->subject);
        }

        //$issue->submitted_by = Auth::user()->id;
        //$issue->save();
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $image) {
                $media = MediaUploader::fromSource($image)
                    ->toDirectory('issue/' . floor($task->id / config('constants.image_per_folder')))
                    ->upload();
                $task->attachMedia($media, config('constants.media_tags'));
            }
        }
        $requestData = new Request();
        $requestData->setMethod('POST');
        $requestData->request->add(['issue_id' => $task->id, 'message' => $request->input('issue'), 'status' => 1]);
        app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'issue');

        return redirect()->back()->with('success', 'You have successfully submitted an issue!');
    }
    public function moduleStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3'
        ]);
        $data = $request->except('_token');
        DeveloperModule::create($data);
        return redirect()->back()->with('success', 'You have successfully submitted an issue!');
    }

    public function statusStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);
        $data = $request->except('_token');
        TaskStatus::create($data);
        return redirect()->back()->with('success', 'You have successfully created a status!');
    }
    public function commentStore(Request $request)
    {
        $this->validate($request, [
            'message' => 'required|string|min:3'
        ]);
        $data = $request->except('_token');
        $data['user_id'] = Auth::id();

        DeveloperComment::create($data);
        return redirect()->back()->with('success', 'You have successfully wrote a comment!');
    }
    public function costStore(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'paid_date' => 'required'
        ]);
        $data = $request->except('_token');
        DeveloperCost::create($data);
        return redirect()->back()->with('success', 'You have successfully added payment!');
    }
    public function awaitingResponse(Request $request, $id)
    {
        $comment = DeveloperComment::find($id);
        $comment->status = 1;
        $comment->save();
        return response('success');
    }
    public function issueAssign(Request $request, $id)
    {
        $this->validate($request, [
            'user_id' => 'required|integer'
        ]);
        $issue = Issue::find($id);
        $task = new DeveloperTask;
        $task->priority = $issue->priority;
        $task->task = $issue->issue;
        $task->user_id = $request->user_id;
        $task->status = 'Planned';
        $task->save();
        foreach ($issue->getMedia(config('constants.media_tags')) as $image) {
            $task->attachMedia($image, config('constants.media_tags'));
        }
        $issue->user_id = $request->user_id;
        $issue->save();
        $issue->delete();
        return redirect()->back()->with('success', 'You have successfully assigned the issue!');
    }
    public function moduleAssign(Request $request, $id)
    {
        $this->validate($request, [
            'user_id' => 'required|integer'
        ]);
        $module = DeveloperTask::find($id);
        $module->user_id = $request->user_id;
        $module->module = 0;
        $module->save();
        return redirect()->route('development.index')->with('success', 'You have successfully assigned the module!');
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'priority' => 'required|integer',
            'task' => 'required|string|min:3',
            'cost' => 'sometimes||nullable|integer',
            'status' => 'required'
        ]);
        $data = $request->except('_token');
        $data['user_id'] = $request->user_id ? $request->user_id : Auth::id();

        $task = DeveloperTask::find($id);
        $task->update($data);
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $image) {
                $media = MediaUploader::fromSource($image)
                    ->toDirectory('developertask/' . floor($task->id / config('constants.image_per_folder')))
                    ->upload();
                $task->attachMedia($media, config('constants.media_tags'));
            }
        }
        return redirect()->route('development.index')->with('success', 'You have successfully updated task!');
    }
    public function updateCost(Request $request, $id)
    {
        $task = DeveloperTask::find($id);
        if ($task->user_id == Auth::id()) {
            $task->cost = $request->cost;
            $task->save();
        }
        return response('success');
    }
    public function updateStatus(Request $request, $id)
    {
        $task = DeveloperTask::find($id);
        $task->status = $request->status;
        if ($request->status == 'In Progress') {
            $task->start_time = Carbon::now();
        }
        if ($request->status == 'Done') {
            $task->end_time = Carbon::now();
        }
        $task->save();
        // if ($task->status == 'Done' && $task->completed == 0) {
        //   NotificationQueueController::createNewNotification([
        //     'message' => 'New Task to Verify',
        //     'timestamps' => ['+0 minutes'],
        //     'model_type' => DeveloperTask::class,
        //     'model_id' =>  $task->id,
        //     'user_id' => Auth::id(),
        //     'sent_to' => 6,
        //     'role' => '',
        //   ]);
        //
        //   NotificationQueueController::createNewNotification([
        //     'message' => 'New Task to Verify',
        //     'timestamps' => ['+0 minutes'],
        //     'model_type' => DeveloperTask::class,
        //     'model_id' =>  $task->id,
        //     'user_id' => Auth::id(),
        //     'sent_to' => 56,
        //     'role' => '',
        //   ]);
        // }
        return response('success');
    }
    public function updateTask(Request $request, $id)
    {
        $task = DeveloperTask::find($id);
        $task->task = $request->task;
        $task->save();
        return response('success');
    }
    public function updatePriority(Request $request, $id)
    {
        $task = DeveloperTask::find($id);
        $task->priority = $request->priority;
        $task->save();
        return response()->json([
            'priority' => $task->priority
        ]);
    }
    public function verify(Request $request, $id)
    {
        $task = DeveloperTask::find($id);
        $task->completed = 1;
        $task->save();
        $notifications = PushNotification::where('model_type', 'App\DeveloperTask')->where('model_id', $task->id)->where('isread', 0)->get();
        foreach ($notifications as $notification) {
            $notification->isread = 1;
            $notification->save();
        }
        if ($request->ajax()) {
            return response('success');
        }
        return redirect()->route('development.index')->with('success', 'You have successfully verified the task!');
    }
    public function verifyView(Request $request)
    {
        $task = DeveloperTask::find($request->id);
        PushNotification::where('model_type', 'App\DeveloperTask')->where('model_id', $request->id)->delete();
        if ($request->tab) {
            $message = 'New Task to Verify';
            // NotificationQueueController::createNewNotification([
            //   'message' => $message,
            //   'timestamps' => ['+10 minutes'],
            //   'model_type' => DeveloperTask::class,
            //   'model_id' =>  $task->id,
            //   'user_id' => Auth::id(),
            //   'sent_to' => 6,
            //   'role' => '',
            // ]);
            //
            // NotificationQueueController::createNewNotification([
            //   'message' => $message,
            //   'timestamps' => ['+10 minutes'],
            //   'model_type' => DeveloperTask::class,
            //   'model_id' =>  $task->id,
            //   'user_id' => Auth::id(),
            //   'sent_to' => 56,
            //   'role' => '',
            // ]);
            return redirect(url("/development#task_$request->id"));
        } else {
            $message = 'New Task Remark';
            if ($request->user == Auth::id()) {
                // NotificationQueueController::createNewNotification([
                //   'message' => $message,
                //   'timestamps' => ['+10 minutes'],
                //   'model_type' => DeveloperTask::class,
                //   'model_id' =>  $task->id,
                //   'user_id' => Auth::id(),
                //   'sent_to' => 6,
                //   'role' => '',
                // ]);
                //
                // NotificationQueueController::createNewNotification([
                //   'message' => $message,
                //   'timestamps' => ['+10 minutes'],
                //   'model_type' => DeveloperTask::class,
                //   'model_id' =>  $task->id,
                //   'user_id' => Auth::id(),
                //   'sent_to' => 56,
                //   'role' => '',
                // ]);
            } else {
                // NotificationQueueController::createNewNotification([
                //   'message' => $message,
                //   'timestamps' => ['+10 minutes'],
                //   'model_type' => DeveloperTask::class,
                //   'model_id' =>  $task->id,
                //   'user_id' => Auth::id(),
                //   'sent_to' => $request->user,
                //   'role' => '',
                // ]);
            }
            return redirect(url("/development?user=$request->user#task_$task->id"));
            // if ($task->status == 'Done' && $task->completed == 1) {
            // } elseif ($task->status == 'Done' && $task->completed == 0) {
            //   return redirect(url("/development#task_$request->id"));
            // } else {
            //   return redirect(url("/development#task_$task->id"));
            // }
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $task = DeveloperTask::find($id);
        $task->development_details()->delete();
        $task->delete();
        if ($request->ajax()) {
            return response('success');
        }
        return redirect()->route('development.index')->with('success', 'You have successfully archived the task!');
    }
    public function issueDestroy($id)
    {
        DeveloperTask::find($id)->delete();
        return redirect()->route('development.issue.index')->with('success', 'You have successfully archived the issue!');
    }
    public function moduleDestroy($id)
    {
        $module = DeveloperModule::find($id);
        foreach ($module->tasks as $task) {
            $task->module_id = '';
            $task->save();
        }
        $module->delete();
        return redirect()->route('development.index')->with('success', 'You have successfully archived the module!');
    }

    private function getHubstaffLockVersion($hubstaffTaskId, $shouldRetry = true)
    {

        $tokens = $this->getTokens();

        $url = 'https://api.hubstaff.com/v2/tasks/' . $hubstaffTaskId;

        $httpClient = new Client();

        try {
            $response = $httpClient->get(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token
                    ],
                ]
            );
            $parsedResponse = json_decode($response->getBody()->getContents());

            return $parsedResponse->task->lock_version;
        } catch (ClientException $e) {
            if($e->getCode() == 401) {
        		$this->refreshTokens();
                if ($shouldRetry) {
                    return $this->getHubstaffLockVersion(
                        $hubstaffTaskId,
                        false
                    );
                }
            }
        }
        return false;
    }

    private function updateHubstaffAssignee($hubstaffTaskId, $assigneeId, $shouldRetry = true)
    {
        $lockVersion = $this->getHubstaffLockVersion($hubstaffTaskId);



        if ($lockVersion === false) {
            return false;
        }

        $tokens = $this->getTokens();

        $url = 'https://api.hubstaff.com/v2/tasks/' . $hubstaffTaskId;

        $httpClient = new Client();


        try {

            $body = array(
                'lock_version' => $lockVersion,
                'assignee_id' => $assigneeId
            );

            $response = $httpClient->put(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token,
                        'Content-Type' => 'application/json'
                    ],

                    RequestOptions::BODY => json_encode($body)
                ]
            );
            $parsedResponse = json_decode($response->getBody()->getContents());
            return $parsedResponse->task->id;
        } catch (ClientException $e) {
            if($e->getCode() == 401) {
                $this->refreshTokens();
                if ($shouldRetry) {
                    return $this->updateHubstaffAssignee(
                        $hubstaffTaskId,
                        $assigneeId,
                        false
                    );
                }
            }
        }
        return false;
    }

    public function assignUser(Request $request)
    {
        $issue = DeveloperTask::find($request->get('issue_id'));

        $user = User::find($request->get('assigned_to'));

        if(!$user) {
            return response()->json([
                'status' => 'success', 'message' =>'user not found'
            ],500);
        }


        // $hubstaffUser = HubstaffMember::where('user_id', $request->get('assigned_to'))->first();

        // $hubstaff_project_id = getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID');
        $hubstaff_project_id = config('env.HUBSTAFF_BULK_IMPORT_PROJECT_ID');

        $assignedUser = HubstaffMember::where('user_id', $request->get('assigned_to'))->first();

        $hubstaffUserId = null;
        if ($assignedUser) {
            $hubstaffUserId = $assignedUser->hubstaff_user_id;
        }

        $summary = substr($issue->task, 0, 200);
        if($issue->task_type_id == 1) {
            $taskSummery = '#DEVTASK-' . $issue->id . ' => ' . $summary;
        }
        else {
            $taskSummery = '#TASK-' . $issue->id . ' => ' . $summary;
        }
        if($hubstaffUserId) {
            $hubstaffTaskId = $this->createHubstaffTask(
                $taskSummery,
                $hubstaffUserId,
                $hubstaff_project_id
            );
            if($hubstaffTaskId) {
                $issue->hubstaff_task_id = $hubstaffTaskId;
                $issue->save();

                $task = new HubstaffTask();
                $task->hubstaff_task_id = $hubstaffTaskId;
                $task->project_id = $hubstaff_project_id;
                $task->hubstaff_project_id = $hubstaff_project_id;
                $task->summary = $taskSummery;
                $task->save();
            }
        }

        // if ($hubstaffUser) {
        //     $this->updateHubstaffAssignee(
        //         $issue->hubstaff_task_id,
        //         $hubstaffUser->hubstaff_user_id
        //     );
        // }
        $old_id = $issue->assigned_to;
        if(!$old_id) {
            $old_id = 0;
        }
        $issue->assigned_to = $request->get('assigned_to');
        $issue->save();

        $taskUser = new TaskUserHistory;
        $taskUser->model = 'App\DeveloperTask';
        $taskUser->model_id = $issue->id;
        $taskUser->old_id = $old_id;
        $taskUser->new_id = $request->get('assigned_to');
        $taskUser->user_type = 'developer';
        $taskUser->updated_by = Auth::user()->name;
        $taskUser->save();

        return response()->json([
            'status' => 'success'
        ]);
    }


    public function assignMasterUser(Request $request)
    {
        $masterUserId = $request->get("master_user_id");
        $issue = DeveloperTask::find($request->get('issue_id'));

        $old_hubstaff_id = $issue->lead_hubstaff_task_id;

        $user = User::find($masterUserId);

        if(!$user) {
            return response()->json([
                'status' => 'success', 'message' =>'user not found'
            ],500);
        }
        $old_id = $issue->master_user_id;
        if(!$old_id) {
            $old_id = 0;
        }
        $issue->master_user_id = $masterUserId;

        $issue->save();


        // $hubstaff_project_id = getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID');
        $hubstaff_project_id = config('env.HUBSTAFF_BULK_IMPORT_PROJECT_ID');

        $assignedUser = HubstaffMember::where('user_id', $masterUserId)->first();
        $hubstaffUserId = null;

        if ($assignedUser) {
            $hubstaffUserId = $assignedUser->hubstaff_user_id;
        }

        $summary = substr($issue->task, 0, 200);
        if($issue->task_type_id == 1) {
            $taskSummery = '#DEVTASK-' . $issue->id . ' => ' . $summary;
        }
        else {
            $taskSummery = '#TASK-' . $issue->id . ' => ' . $summary;
        }
        if($hubstaffUserId) {
            $hubstaffTaskId = $this->createHubstaffTask(
                $taskSummery,
                $hubstaffUserId,
                $hubstaff_project_id
            );

            if($hubstaffTaskId) {
                $issue->lead_hubstaff_task_id = $hubstaffTaskId;
                $issue->save();

                $task = new HubstaffTask();
                $task->hubstaff_task_id = $hubstaffTaskId;
                $task->project_id = $hubstaff_project_id;
                $task->hubstaff_project_id = $hubstaff_project_id;
                $task->summary = $taskSummery;
                $task->save();
            }
        }



        $taskUser = new TaskUserHistory;
        $taskUser->model = 'App\DeveloperTask';
        $taskUser->model_id = $issue->id;
        $taskUser->old_id = $old_id;
        $taskUser->new_id = $masterUserId;
        $taskUser->user_type = 'leaddeveloper';
        $taskUser->master_user_hubstaff_task_id = $old_hubstaff_id;
        $taskUser->updated_by = Auth::user()->name;
        $taskUser->save();

        return response()->json([
            'status' => 'success'
        ]);
    }


    public function assignTeamlead(Request $request)
    {
        $team_lead_id = $request->get("team_lead_id");
        $issue = DeveloperTask::find($request->get('issue_id'));

        $user = User::find($team_lead_id);

        if(!$user) {
            return response()->json([
                'status' => 'success', 'message' =>'user not found'
            ],500);
        }


        $isMember = $user->teams()->first();
        if($isMember) {
            return response()->json([
                "message"       => 'This user is already a team member'
            ],500);
        }
        else {
            $isLeader = Team::where('user_id',$team_lead_id)->first();
            if(!$isLeader) {
                $team = new Team;
                $team->name = $request->name;
                $team->user_id = $team_lead_id;
                $team->save();
            }
            $issue->team_lead_id = $team_lead_id;
            $issue->save();

        }
        return response()->json([
            'status' => 'success'
        ],200);
    }


    public function assignTester(Request $request)
    {
        $tester_id = $request->get("tester_id");
        $issue = DeveloperTask::find($request->get('issue_id'));

        $user = User::find($tester_id);

        if(!$user) {
            return response()->json([
                'status' => 'success', 'message' =>'user not found'
            ],500);
        }
        $old_id = $issue->tester_id;
        if(!$old_id) {
            $old_id = 0;
        }
        $issue->tester_id = $tester_id;
        $issue->save();


        // $hubstaff_project_id = getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID');
        $hubstaff_project_id = config('env.HUBSTAFF_BULK_IMPORT_PROJECT_ID');

        $assignedUser = HubstaffMember::where('user_id', $tester_id)->first();

        $hubstaffUserId = null;
        if ($assignedUser) {
            $hubstaffUserId = $assignedUser->hubstaff_user_id;
        }

        $summary = substr($issue->task, 0, 200);
        if($issue->task_type_id == 1) {
            $taskSummery = '#DEVTASK-' . $issue->id . ' => ' . $summary;
        }
        else {
            $taskSummery = '#TASK-' . $issue->id . ' => ' . $summary;
        }
        if($hubstaffUserId) {
            $hubstaffTaskId = $this->createHubstaffTask(
                $taskSummery,
                $hubstaffUserId,
                $hubstaff_project_id
            );
            if($hubstaffTaskId) {
                $issue->tester_hubstaff_task_id = $hubstaffTaskId;
                $issue->save();

                $task = new HubstaffTask();
                $task->hubstaff_task_id = $hubstaffTaskId;
                $task->project_id = $hubstaff_project_id;
                $task->hubstaff_project_id = $hubstaff_project_id;
                $task->summary = $taskSummery;
                $task->save();
            }
        }

        $taskUser = new TaskUserHistory;
        $taskUser->model = 'App\DeveloperTask';
        $taskUser->model_id = $issue->id;
        $taskUser->old_id = $old_id;
        $taskUser->new_id = $tester_id;
        $taskUser->user_type = 'tester';
        $taskUser->updated_by = Auth::user()->name;
        $taskUser->save();

        return response()->json([
            'status' => 'success'
        ]);
    }
    public function assignResponsibleUser(Request $request)
    {
        $issue = DeveloperTask::find($request->get('issue_id'));
        //$issue = Issue::find($request->get('issue_id'));
        //$issue->responsible_user_id = $request->get('responsible_user_id');
        $issue->assigned_by = \Auth::id();
        $issue->responsible_user_id = $request->get('responsible_user_id');
        $issue->save();
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function saveAmount(Request $request)
    {
        $issue = DeveloperTask::find($request->get('issue_id'));
        $issue->cost = $request->get('cost');
        $issue->save();
        return response()->json([
            'status' => 'success'
        ]);
    }


    public function saveMilestone(Request $request)
    {
        $issue = DeveloperTask::find($request->get('issue_id'));
        if(!$issue->is_milestone) {
            return;
        }
        $total = $request->total;
        if($issue->milestone_completed) {
            if($total <= $issue->milestone_completed) {
                return response()->json([
                    'message' => 'Milestone no can\'t be reduced'
                ],500);
            }
        }

        if($total > $issue->no_of_milestone) {
            return response()->json([
                'message' => 'Estimated milestone exceeded'
            ],500);
        }
        if(!$issue->cost || $issue->cost == '') {
            return response()->json([
                'message' => 'Please provide cost first'
            ],500);
        }

        $newCompleted = $total - $issue->milestone_completed;
        $individualPrice = $issue->cost / $issue->no_of_milestone;
        $totalCost = $individualPrice * $newCompleted;

        $issue->milestone_completed = $total;
        $issue->save();
        $payment_receipt = new PaymentReceipt;
        $payment_receipt->date = date( 'Y-m-d' );
        $payment_receipt->worked_minutes = $issue->estimate_minutes;
        $payment_receipt->rate_estimated = $totalCost;
        $payment_receipt->status = 'Pending';
        $payment_receipt->developer_task_id = $issue->id;
        $payment_receipt->user_id = $issue->assigned_to;
        $payment_receipt->save();
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function resolveIssue(Request $request)
    {
        
        $issue = DeveloperTask::find($request->get('issue_id'));
        if($issue->is_resolved == 1) {
            return response()->json([
                'message'	=> 'DONE Status can not change further.'
            ],500);
        }
        if (strtolower($request->get('is_resolved')) == "done") {
            if(Auth::user()->isAdmin()) {
                $old_status = $issue->status;
                $issue->status = $request->get('is_resolved');
                $assigned_to = User::find($issue->assigned_to);
                if($assigned_to && $assigned_to->fixed_price_user_or_job == 1) {
                    // Fixed price task.
                    if($issue->cost == null) {
                        return response()->json([
                            'message'	=> 'Please provide cost for fixed price task.'
                        ],500);
                    }
                    if(!$issue->is_milestone) {
                        $payment_receipt = new PaymentReceipt;
                        $payment_receipt->date = date( 'Y-m-d' );
                        $payment_receipt->worked_minutes = $issue->estimate_minutes;
                        $payment_receipt->rate_estimated = $issue->cost;
                        $payment_receipt->status = 'Pending';
                        $payment_receipt->developer_task_id = $issue->id;
                        $payment_receipt->user_id = $issue->assigned_to;
                        $payment_receipt->save();
                    }
                }
                $issue->responsible_user_id = $issue->assigned_to;
                $issue->is_resolved = 1;
                $issue->save();
                
                DeveloperTaskHistory::create([
                    'developer_task_id' => $issue->id,
                    'model' => 'App\DeveloperTask',
                    'attribute' => "task_status",
                    'old_value' => $old_status,
                    'new_value' => $request->is_resolved,
                    'user_id' => Auth::id(),
                ]);


            }
            else {
                return response()->json([
                    'message'	=> 'Only admin can change status to DONE.'
                ],500);
            }
        }
        else {
            $old_status = $issue->status;

            DeveloperTaskHistory::create([
                'developer_task_id' => $issue->id,
                'model' => 'App\DeveloperTask',
                'attribute' => "task_status",
                'old_value' => $old_status,
                'new_value' => $request->is_resolved,
                'user_id' => Auth::id(),
            ]);

            $issue->status = $request->get('is_resolved');
            $issue->save();
        }
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function saveEstimateTime(Request $request)
    {
        // $issue = DeveloperTask::find($request->get('issue_id'));
        // //$issue = Issue::find($request->get('issue_id'));
        // $issue->estimate_time = $request->get('estimate_time');
        // $issue->save();
        $issue = DeveloperTaskHistory::where(['developer_task_id' => $request->get('issue_id'), 'attribute' => 'estimation_minute','user_id' => Auth::user()->id])->orderBy('id','DESC')->first();
        if($issue->count() > 0){
            $task_history = new DeveloperTaskHistory;
            $task_history->developer_task_id = $request->get('issue_id');
            $task_history->attribute = 'estimation_minute';
            $task_history->old_value = $issue->new_value;
            $task_history->new_value =  $request->get('estimate_time');
            $task_history->user_id = Auth::user()->id();
            $task_history->developer_task_id = $request->name;
            $task_history->model = 'App\DeveloperTask';
            $result = $task_history->save();
        }else{
            $task_history = new DeveloperTaskHistory;
            $task_history->developer_task_id = $request->get('issue_id');
            $task_history->attribute = 'estimation_minute';
            $task_history->old_value = 0;
            $task_history->new_value =  $request->get('estimate_time');
            $task_history->user_id = Auth::user()->id();
            $task_history->developer_task_id = $request->name;
            $task_history->model = 'App\DeveloperTask';
            $result = $task_history->save();
        }

        return response()->json([
            'status' => 'success', 'result' => $result
        ]);
    }

    public function approveTimeHistory(Request $request) {

       
        if(Auth::user()->isAdmin) {
            if(!$request->approve_time || $request->approve_time == "" || !$request->developer_task_id || $request->developer_task_id == '') {
                return response()->json([
                    'message' => 'Select one time first'
                ],500);
            }
            DeveloperTaskHistory::where('developer_task_id',$request->developer_task_id)->where('attribute','estimation_minute')->where('model','App\DeveloperTask')->update(['is_approved' => 0]);
            $history = DeveloperTaskHistory::find($request->approve_time);
            $history->is_approved = 1;
            $history->save();


            $task = DeveloperTask::find($request->developer_task_id);
            $time = $history->new_value !== null ? $history->new_value : $history->old_value;
            $msg = 'TIME APPROVED FOR TASK ' . '#DEVTASK-' . $task->id . '-' . $task->subject . ' - ' .  $time . ' MINS'; 
            
            $user = User::find($request->user_id);
            $admin = Auth::user(); 
            $master_user = User::find($task->master_user_id);
            $team_lead = User::find($task->team_lead_id);
            $tester = User::find($task->tester_id);

            if($user){
                if($admin->phone){
                    $chat = ChatMessage::create([
                        'number' => $admin->phone,
                        'user_id' => $user->id,
                        'customer_id' => $user->id,
                        'message' => $msg,
                        'status' => 0, 
                        'developer_task_id' => $request->developer_task_id
                    ]);
                }else if($user->phone){
                    $chat = ChatMessage::create([
                        'number' => $user->phone,
                        'user_id' => $user->id,
                        'customer_id' => $user->id,
                        'message' => $msg,
                        'status' => 0, 
                        'developer_task_id' => $request->developer_task_id
                    ]);
                }else if($master_user && $master_user->phone){
                    $chat = ChatMessage::create([
                        'number' => $master_user->phone,
                        'user_id' => $user->id,
                        'customer_id' => $user->id,
                        'message' => $msg,
                        'status' => 0, 
                        'developer_task_id' => $request->developer_task_id
                    ]);
                }else if($team_lead && $team_lead->phone){
                    $chat = ChatMessage::create([
                        'number' => $team_lead->phone,
                        'user_id' => $user->id,
                        'customer_id' => $user->id,
                        'message' => $msg,
                        'status' => 0, 
                        'developer_task_id' => $request->developer_task_id
                    ]);
                }else if($tester && $tester->phone){
                    $chat = ChatMessage::create([
                        'number' => $tester->phone,
                        'user_id' => $user->id,
                        'customer_id' => $user->id,
                        'message' => $msg,
                        'status' => 0, 
                        'developer_task_id' => $request->developer_task_id
                    ]);
                } 
                if($chat){ 
                    if($admin->phone){
                        app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($admin->phone, $admin->whatsapp_number, $msg, false, $chat->id);
                    }
                    if($user->phone){
                        app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $msg, false, $chat->id);
                    }
                    if($master_user && $master_user->phone){
                        app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($master_user->phone, $master_user->whatsapp_number, $msg, false, $chat->id);
                    }
                    if($team_lead && $team_lead->phone){
                        app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($team_lead->phone, $team_lead->whatsapp_number, $msg, false, $chat->id);
                    }
                    if($tester && $tester->phone){
                        app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($tester->phone, $tester->whatsapp_number, $msg, false, $chat->id);
                    } 
                }
            }
 

    }else{
            return response()->json([
                'message' => 'Only admin can approve'
            ],500);
        }
    }

    public function sendRemindMessage(Request $request) {
        
        $user = User::find($request->user_id);
        if($user){
            $receiver_user_phone = $user->phone;
            if($receiver_user_phone){
                $task = DeveloperTask::find($request->id);
                $msg = 'PLS ADD ESTIMATED TIME FOR TASK  ' . '#DEVTASK-' . $task->id . '-' . $task->subject ; 
                $chat = ChatMessage::create([
                    'number' => $receiver_user_phone,
                    'user_id' => $user->id,
                    'customer_id' => $user->id,
                    'message' => $msg,
                    'status' => 0, 
                    'developer_task_id' => $request->id
                ]);

                app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($receiver_user_phone, $user->whatsapp_number, $msg, false, $chat->id);

                MessageHelper::sendEmailOrWebhookNotification([$task->assigned_to, $task->team_lead_id, $task->tester_id],$msg);

            } 
        }
        return response()->json([
            'message' => 'Remind message sent successfully',
        ]);
    }

    public function sendReviseMessage(Request $request) {
        
        $user = User::find($request->user_id);
        if($user){
            $receiver_user_phone = $user->phone;
            if($receiver_user_phone){
                $task = DeveloperTask::find($request->id);
                $msg = 'TIME NOT APPROVED REVISE THE ESTIMATED TIME FOR TASK ' . '#DEVTASK-' . $task->id . '-' . $task->subject;
                $chat = ChatMessage::create([
                    'number' => $receiver_user_phone,
                    'user_id' => $user->id,
                    'customer_id' => $user->id,
                    'message' => $msg,
                    'status' => 0, 
                    'developer_task_id' => $request->id
                ]);
                app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($receiver_user_phone, $user->whatsapp_number, $msg, false, $chat->id);

                MessageHelper::sendEmailOrWebhookNotification([$task->assigned_to, $task->team_lead_id, $task->tester_id],$msg);
            } 
        }
        return response()->json([
            'message' => 'Revise message sent successfully',
        ]);
    }

    public function approveLeadTimeHistory(Request $request) {
        if(Auth::user()->isAdmin) {
            if(!$request->approve_time || $request->approve_time == "" || !$request->lead_developer_task_id || $request->lead_developer_task_id == '') {
                return response()->json([
                    'message' => 'Select one time first'
                ],500);
            }
            DeveloperTaskHistory::where('developer_task_id',$request->lead_developer_task_id)->where('attribute','estimation_minute')->update(['is_approved' => 0]);
            $history = DeveloperTaskHistory::find($request->approve_time);
            $history->is_approved = 1;
            $history->save();
            return response()->json([
                'message' => 'Success'
            ],200);
        }
        return response()->json([
            'message' => 'Only admin can approve'
        ],500);
    }

    public function approveDateHistory(Request $request) {
        if(Auth::user()->isAdmin) {

            if(!$request->approve_date || $request->approve_date == "" || !$request->developer_task_id || $request->developer_task_id == '') {
                return response()->json([
                    'message' => 'Select one time first'
                ],500);
            }
            DeveloperTaskHistory::where('developer_task_id',$request->developer_task_id)->where('attribute','estimation_minute')->where('model','App\DeveloperTask')->update(['is_approved' => 0]);
            $history = DeveloperTaskHistory::find($request->approve_date);
            $history->is_approved = 1;
            $history->save();
            return response()->json([
                'message' => 'Success'
            ],200);
        }
        return response()->json([
            'message' => 'Only admin can approve'
        ],500);
    }

    public function saveEstimateMinutes(Request $request)
    {
        $issue = DeveloperTask::find($request->get('issue_id'));

        if($issue && $request->estimate_minutes) {
            DeveloperTaskHistory::create([
                'developer_task_id' => $issue->id,
                'model' => 'App\DeveloperTask',
                'attribute' => "estimation_minute",
                'old_value' => $issue->estimate_minutes,
                'new_value' => $request->estimate_minutes,
                'user_id' => Auth::id(),
            ]);
        }
        if(Auth::user()->isAdmin()){
            $user = User::find($issue->user_id);
            $msg = 'TIME ESTIMATED BY ADMIN FOR TASK ' . '#DEVTASK-' . $issue->id . '-' .$issue->subject . ' ' .  $request->estimate_minutes . ' MINS';
        }else{ 
            $user = User::find($issue->master_user_id); 
            $msg = 'TIME ESTIMATED BY USER FOR TASK ' . '#DEVTASK-' . $issue->id . '-' .$issue->subject . ' ' .  $request->estimate_minutes . ' MINS';
        }

        if($user){

            $receiver_user_phone = $user->phone;

            if($receiver_user_phone){
                $chat = ChatMessage::create([
                    'number' => $receiver_user_phone,
                    'user_id' => $user->id,
                    'customer_id' => $user->id,
                    'message' => $msg,
                    'status' => 0, 
                    'developer_task_id' => $request->issue_id
                ]);

                app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($receiver_user_phone, $user->whatsapp_number, $msg, false, $chat->id);

                MessageHelper::sendEmailOrWebhookNotification([$issue->assigned_to, $issue->team_lead_id, $issue->tester_id],$msg);
            } 
        }

        $issue->estimate_minutes = $request->get('estimate_minutes');
        $issue->save();

        return response()->json(['status' => 'success']);
    }

    public function savePriorityNo(Request $request)
    {
        $issue = DeveloperTask::find($request->get('issue_id'));
        //$issue = Issue::find($request->get('issue_id'));

        if($issue) {
            $issue->priority_no = $request->get('priority');
            $issue->save(); 
        }

        return response()->json(['status' => 'success']);
    }

    public function saveEstimateDate(Request $request)
    {
        $issue = DeveloperTask::find($request->get('issue_id'));
        //$issue = Issue::find($request->get('issue_id'));
        $estimate_date = date("Y-m-d", strtotime($request->estimate_date));
        if($issue && $request->estimate_date) {
            DeveloperTaskHistory::create([
                'developer_task_id' => $issue->id,
                'model' => 'App\DeveloperTask',
                'attribute' => "estimate_date",
                'old_value' => $issue->estimate_date,
                'new_value' => $estimate_date,
                'user_id' => Auth::id(),
            ]);
        }

        $issue->estimate_date = $estimate_date;
        $issue->save();

        return response()->json([
            'status' => 'success'
        ]);
    }



    public function updateValues(Request $request)
    {
        $task = DeveloperTask::find($request->get('id'));
        $type = $request->get('type');
        $value = $request->get('value');
        if ($type == 'start_date') {
            $task->start_date = $request->get('value');
        } else {
            if ($type == 'end_date') {
                $task->end_date = $request->get('value');
            } else {
                if ($type == 'estimate_date') {
                    $task->estimate_date = $request->get('value');
                } else {
                    if ($type == 'cost') {
                        $task->cost = $request->get('value');
                    } else {
                        if ($type == 'module') {
                            $task->module_id = $request->get('value');
                        }
                    }
                }
            }
        }
        $task->save();
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function overview(Request $request)
    {
        // Get status
        $status = $request->get('status');
        if (empty($status)) {
            $status = 'In Progress';
        }
        $task_type = 1;
        $taskTypes = TaskTypes::all();
        $users = Helpers::getUsersByRoleName('Developer');
        if (!empty($request->get('task_type'))) {
            $task_type = $request->get('task_type');
            //$issues = $issues->where('submitted_by', $request->get('submitted_by'));
        }
        if (!empty($request->get('task_status'))) {
            $status = $request->get('task_status');
            //$issues = $issues->where('responsible_user_id', $request->get('responsible_user'));
        }
        if (!empty($request->get('task_type')) && !empty($request->get('task_status'))) {
            $status = $request->get('task_status');
            $task_type = $request->get('task_type');
        }
        return view('development.overview', [
            'taskTypes' => $taskTypes,
            'users' => $users,
            'status' => $status,
            'task_type' => $task_type,
        ]);
    }
    public function taskDetail($taskId)
    {
        // Get tasks
        $task = DeveloperTask::where('developer_tasks.id', $taskId)
            ->select('developer_tasks.*', 'task_types.name as task_type', 'users.name as username', 'u.name as reporter')
            ->leftjoin('task_types', 'task_types.id', '=', 'developer_tasks.task_type_id')
            ->leftjoin('users', 'users.id', '=', 'developer_tasks.user_id')
            ->leftjoin('users AS u', 'u.id', '=', 'developer_tasks.created_by')
            ->first();
        // Get subtasks
        $subtasks = DeveloperTask::where('developer_tasks.parent_id', $taskId)->get();
        // Get comments
        $comments = DeveloperTaskComment::where('task_id', $taskId)
            ->join('users', 'users.id', '=', 'developer_task_comments.user_id')
            ->get();
        //Get Attachments
        $attachments = TaskAttachment::where('task_id', $taskId)->get();
        $developers = Helpers::getUserArray(User::role('Developer')->get());
        // Return view
        return view('development.task_detail', [
            'task' => $task,
            'subtasks' => $subtasks,
            'comments' => $comments,
            'developers' => $developers,
            'attachments' => $attachments,
        ]);
    }
    public function taskComment(Request $request)
    {
        $response = array();
        $this->validate($request, [
            'comment' => 'required|string|min:1'
        ]);
        $data = $request->except('_token');
        $data['user_id'] = Auth::id();

        $created = DeveloperTaskComment::create($data);
        if ($created) {
            $response['status'] = 'ok';
            $response['msg'] = 'Comment stored successfully';
            echo json_encode($response);
        } else {
            $response['status'] = 'error';
            $response['msg'] = 'Error';
        }
    }
    public function changeTaskStatus(Request $request)
    {
        if (!empty($request->input('task_id'))) {
            $task = DeveloperTask::find($request->input('task_id'));
            $task->status = $request->input('status');
            $task->save();
            return response()->json(['success']);
        }
    }
    public function makeDirectory($path, $mode = 0777, $recursive = false, $force = false)
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        } else {
            return mkdir($path, $mode, $recursive);
        }
    }
    public function uploadAttachDocuments(Request $request)
    {
        $task_id = $request->input('task_id');
        $task = DeveloperTask::find($task_id);
        if ($request->hasfile('attached_document')) {
            foreach ($request->file('attached_document') as $image) {
                $name = time() . '_' . $image->getClientOriginalName();
                $new_id = floor($task_id / 1000);
                //                $path = public_path().'/developer-task' . $task_id;
                //                if (!file_exists($path)) {
                //                    $this->makeDirectory($path);
                //                }

                $dirname = public_path() . '/uploads/developer-task/' . $new_id;
                if (file_exists($dirname)) {
                    $dirname2 = public_path() . '/uploads/developer-task/' . $new_id . '/' . $task_id;
                    if (file_exists($dirname2) == false) {
                        mkdir($dirname2, 0777);
                    }
                } else {
                    mkdir($dirname, 0777);
                }
                $media = MediaUploader::fromSource($image)->toDirectory("developer-task/$new_id/$task_id")->upload();
                $task->attachMedia($media, config('constants.media_tags'));
            }
        }
        if (!empty($request->file('attached_document'))) {
            foreach ($request->file('attached_document') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/task_files/'), $name);
                $filepath[] = 'images/task_files/' . $name;
                $task_attachment = new TaskAttachment;
                $task_attachment->task_id = $task_id;
                $task_attachment->name = $name;
                $task_attachment->save();
            }
            return redirect(url("/development/task-detail/$task_id"));
        } else {
            return redirect(url("/development/task-detail/$task_id"));
        }
    }
    public function downloadFile(Request $request)
    {
        $file_name = $request->input('file_name');
        //PDF file is stored under project/public/download/info.pdf
        $file = public_path() . "/images/task_files/" . $file_name;
        $ext = substr($file_name, strrpos($file_name, '.') + 1);
        $headers = array();
        if ($ext == 'pdf') {
            $headers = array(
                'Content-Type: application/pdf',
            );
            //$download_file = $file_name.'.pdf';
        }
        return Response::download($file, $file_name, $headers);
    }

    //    public function downloadFile($path) {
    //        if (file_exists($path) && is_file($path)) {
    //            // file exist
    //            header('Content-Description: File Transfer');
    //            header('Content-Type: application/octet-stream');
    //            header('Content-Disposition: attachment; filename=' . basename($path));
    //            header('Content-Transfer-Encoding: binary');
    //            header('Expires: 0');
    //            header('Cache-Control: must-revalidate');
    //            header('Pragma: public');
    //            header('Content-Length: ' . filesize($path));
    //            set_time_limit(0);
    //            @readfile($path);//"@" is an error control operator to suppress errors
    //        } else {
    //            // file doesn't exist
    //            die('Error: The file ' . basename($path) . ' does not exist!');
    //        }
    //    }

    public function openNewTaskPopup(Request $request)
    {
        $status = "ok";
        // Get all developers
        $userlst = User::role('Developer')->orderby('name','asc')->where('is_active',1)->get();
        $users = Helpers::getUserArray($userlst);
        //$users = Helpers::getUsersByRoleName('Developer');
        // Get all task types
        $tasksTypes = TaskTypes::all();
        $moduleNames = [];
        
        // Get all modules
        $modules = DeveloperModule::orderBy('name')->get();

        // Loop over all modules and store them
        foreach ($modules as $module) {
            $moduleNames[$module->id] = $module->name;
        }

        // this is the ID for erp
        $defaultRepositoryId = 231925646;
        $respositories = GithubRepository::all();
        $statusList = \DB::table("task_statuses")
                    ->orderBy('name')
                    ->select("name")
                    ->pluck("name", "name")
                    ->toArray();

        $statusList = array_merge([
            "" => "Select Status",
        ], $statusList);

        //$html = view('development.ajax.add_new_task', compact("users", "tasksTypes", "modules", "moduleNames", "respositories", "defaultRepositoryId"))->render();
        //Get hubstaff projects
        $projects = HubstaffProject::all();

        $html = view('development.ajax.add_new_task', compact("users", "tasksTypes", "modules", "moduleNames", "respositories", "defaultRepositoryId", "projects","statusList"))->render();
        return json_encode(compact("html", "status"));
    }
    public function saveLanguage(Request $request)
    {
        $language = $request->get('language');

        if (!empty(trim($language))) {
            if (!is_numeric($language)) {
                $languageModal = \App\DeveloperLanguage::updateOrCreate(
                    ['name' => $language],
                    ['name' => $language]
                );
            }

            $issue = DeveloperTask::find($request->get('issue_id'));
            $issue->language = isset($languageModal->id) ? $languageModal->id : $language;
            $issue->save();
        }


        return response()->json([
            'status' => 'success'
        ]);
    }

    public function uploadDocument(Request $request)
    {

        $id = $request->get("developer_task_id", 0);
        $subject = $request->get("subject", null);

        $loggedUser = $request->user();

        if ($id > 0 && !empty($subject)) {

            $devTask = DeveloperTask::find($id);

            if (!empty($devTask)) {

                $devDocuments = new \App\DeveloperTaskDocument;
                $devDocuments->fill(request()->all());
                $devDocuments->created_by = \Auth::id();
                $devDocuments->save();

                if ($request->hasfile('files')) {

                    foreach ($request->file('files') as $files) {
                        $media = MediaUploader::fromSource($files)
                            ->toDirectory('developertask/' . floor($devTask->id / config('constants.image_per_folder')))
                            ->upload();
                        $devDocuments->attachMedia($media, config('constants.media_tags'));
                    }

                    $message = '[ '. $loggedUser->name .' ] - #DEVTASK-' . $devTask->id .' - ' . $devTask->subject ." \n\n" . 'New attchment(s) called ' . $subject . ' has been added. Please check and give your comment or fix it if any issue.';

                    MessageHelper::sendEmailOrWebhookNotification([$devTask->assigned_to, $devTask->team_lead_id, $devTask->tester_id], $message);
                }

                return response()->json(["code" => 200, "success" => "Done!"]);
            }

            return response()->json(["code" => 500, "error" => "Oops, There is no record in database"]);
        } else {
            return response()->json(["code" => 500, "error" => "Oops, Please fillup required fields"]);
        }
    }

    public function getDocument(Request $request)
    {
        $id = $request->get("id", 0);

        if ($id > 0) {

            $devDocuments = \App\DeveloperTaskDocument::where("developer_task_id", $id)->latest()->get();

            $html = view('development.ajax.document-list', compact("devDocuments"))->render();

            return response()->json(["code" => 200, "data" => $html]);
        } else {
            return response()->json(["code" => 500, "error" => "Oops, id is required field"]);
        }
    }

    /**
     * changeModule on  development/list/devtask
     * @ajax Request
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeModule(Request $request)
    {
        if($request->ajax()){
            $message =  array();
            $task_module = DeveloperTask::find($request->get('issue_id'));
            if($task_module) {
                $task_module->module_id = $request->get('module_id');
                if($task_module->save()){
                    $message = array('message'=>'success', 'status'=>'200');
                }else{
                    $message = array('message'=>'Error', 'status'=>'400');
                }
            }else{
                $message = array('message'=>'Error', 'status'=>'400');
            }
        }else{
            $message = array('message'=>'Error', 'status'=>'400');
        }

        return response()->json($message);
    }

    public function getTimeHistory(Request $request)
    {
        $users = User::get();
        
        $id = $request->id;
        $task_module = DeveloperTaskHistory::join('users','users.id','developer_tasks_history.user_id')->where('developer_task_id', $id)->where('model','App\DeveloperTask')->where('attribute','estimation_minute')->select('developer_tasks_history.*','users.name')->get();
        
        if($task_module) {
            return $task_module;
        }
        
        return 'error';
    }

    public function getDateHistory(Request $request)
    {
        $id = $request->id;
        $task_module = DeveloperTaskHistory::join('users','users.id','developer_tasks_history.user_id')->where('developer_task_id', $id)->where('model','App\DeveloperTask')->where('attribute','estimate_date')->select('developer_tasks_history.*','users.name')->get();
        if($task_module) {
            return $task_module;
        }
        return 'error';
    }

    
    public function getStatusHistory(Request $request)
    {
        $id = $request->id;
        $task_module = DeveloperTaskHistory::join('users','users.id','developer_tasks_history.user_id')->where('developer_task_id', $id)->where('model','App\DeveloperTask')->where('attribute','task_status')->select('developer_tasks_history.*','users.name')->get();
        if($task_module) {
            return $task_module;
        }
        return 'error';
    }

    public function getTrackedHistory(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        if($type == 'lead') {
            $task_histories = DB::select( DB::raw("SELECT hubstaff_activities.task_id,cast(hubstaff_activities.starts_at as date) as starts_at,sum(hubstaff_activities.tracked) as total_tracked,developer_tasks.master_user_id,users.name FROM `hubstaff_activities`  join developer_tasks on developer_tasks.lead_hubstaff_task_id = hubstaff_activities.task_id join users on users.id = developer_tasks.master_user_id where developer_tasks.id = ".$id." group by task_id,starts_at"));
        }
        else if($type == 'tester') {
            $task_histories = DB::select( DB::raw("SELECT hubstaff_activities.task_id,cast(hubstaff_activities.starts_at as date) as starts_at,sum(hubstaff_activities.tracked) as total_tracked,developer_tasks.tester_id,users.name FROM `hubstaff_activities`  join developer_tasks on developer_tasks.tester_hubstaff_task_id = hubstaff_activities.task_id join users on users.id = developer_tasks.tester_id where developer_tasks.id = ".$id." group by task_id,starts_at"));
        }
        else {
            $task_histories = DB::select( DB::raw("SELECT hubstaff_activities.task_id,cast(hubstaff_activities.starts_at as date) as starts_at,sum(hubstaff_activities.tracked) as total_tracked,developer_tasks.assigned_to,users.name FROM `hubstaff_activities`  join developer_tasks on developer_tasks.hubstaff_task_id = hubstaff_activities.task_id join users on users.id = developer_tasks.assigned_to where developer_tasks.id = ".$id." group by task_id,starts_at"));
        }

        return response()->json(['histories' => $task_histories]);
    }

    public function createHubstaffManualTask(Request $request) {

        $task = DeveloperTask::find($request->id);
        if($task) {
            if($request->type == 'developer') {
                $user_id = $task->assigned_to;
            }
            else  if($request->type == 'tester') {
                $user_id = $task->tester_id;
            }
            else {
                $user_id = $task->master_user_id;
            }
            // $hubstaff_project_id = getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID');
            $hubstaff_project_id = config('env.HUBSTAFF_BULK_IMPORT_PROJECT_ID');

            $assignedUser = HubstaffMember::where('user_id', $user_id)->first();

            $hubstaffUserId = null;
            if ($assignedUser) {
                $hubstaffUserId = $assignedUser->hubstaff_user_id;
            }
            $taskSummery = '#DEVTASK-' . $task->id . ' => ' . $task->subject;
            // $hubstaffUserId = 901839;
            if($hubstaffUserId) {
                $hubstaffTaskId = $this->createHubstaffTask(
                    $taskSummery,
                    $hubstaffUserId,
                    $hubstaff_project_id
                );
            }
            else {
                return response()->json([
                    'message' => 'Hubstaff member not found'
                ],500);
            }
            if($hubstaffTaskId) {
                if($request->type == 'developer') {
                    $task->hubstaff_task_id = $hubstaffTaskId;
                }
                else  if($request->type == 'tester') {
                    $task->tester_hubstaff_task_id = $hubstaffTaskId;
                }
                else {
                    $task->lead_hubstaff_task_id = $hubstaffTaskId;
                }
                $task->save();
            }
            else {
                return response()->json([
                    'message' => 'Hubstaff task not created'
                ],500);
            }
            if ($hubstaffTaskId) {
                $task = new HubstaffTask();
                $task->hubstaff_task_id = $hubstaffTaskId;
                $task->project_id = $hubstaff_project_id;
                $task->hubstaff_project_id = $hubstaff_project_id;
                $task->summary = $taskSummery;
                $task->save();
            }
            return response()->json([
                'message' => 'Successful'
            ],200);
        }
        else {
            return response()->json([
                'message' => 'Task not found'
            ],500);
        }
    }

    public function deleteBulkTasks(Request $request) {
        if(count($request->selected_tasks) > 0) {
            foreach($request->selected_tasks as $t) {
                DeveloperTask::where('id',$t)->delete();
            }
        }
        return response()->json(['message' => 'Successful']);
    }

    public function getMeetingTimings(Request $request) {
        $developerTime = 0;
        $master_devTime = 0;
        $testerTime = 0;
        $query = MeetingAndOtherTime::join('users','users.id','meeting_and_other_times.user_id')->where('model','App\DeveloperTask')->where('model_id',$request->issue_id);
        $issue = DeveloperTask::find($request->issue_id);
        if($request->type == 'admin') {
            $query = $query;
        }
        else if($request->type == 'developer') {
            $query = $query->where('user_id',$issue->assigned_to);
        }
        else if($request->type == 'lead') {
            $query = $query->where('user_id',$issue->master_user_id);
        }
        else if($request->type == 'tester') {
            $query = $query->where('user_id',$issue->tester_id);
        }
        else {
            return response()->json(['message' => 'Unauthorized access'],500);
        }
        if($request->timing_type && $request->timing_type != '') {
            $query = $query->where('type',$request->timing_type);
        }


        $timings = $query->select('meeting_and_other_times.*','users.name')->get();

        $developerTime = MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$request->issue_id)->where('user_id',$issue->assigned_to)->where('approve',1)->sum('time');

        $master_devTime = MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$request->issue_id)->where('user_id',$issue->master_user_id)->where('approve',1)->sum('time');

        $testerTime = MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$request->issue_id)->where('user_id',$issue->tester_id)->where('approve',1)->sum('time');

        return response()->json(['timings' => $timings,'issue_id' => $request->issue_id,'developerTime' => $developerTime, 'master_devTime' => $master_devTime, 'testerTime' => $testerTime],200);
    }

    public function storeMeetingTime(Request $request) {
      if(!$request->task_id || $request->task_id == '' || !$request->time || $request->time == '' || !$request->user_type || $request->user_type == '' || !$request->timing_type || $request->timing_type == '') {
        return response()->json(['message' => 'Incomplete data'],500);
      }
      $query = MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$request->task_id)->where('type',$request->timing_type);
      $user_id = Auth::user()->id;
      $issue = DeveloperTask::find($request->task_id);
       if($request->user_type == 'developer') {
            $query = $query->where('user_id',$issue->assigned_to);
            $user_id = $issue->assigned_to;
        }
        else if($request->user_type == 'lead') {
            $query = $query->where('user_id',$issue->master_user_id);
            $user_id = $issue->master_user_id;
        }
        else if($request->user_type == 'tester') {
            $query = $query->where('user_id',$issue->tester_id);
            $user_id = $issue->tester_id;
        }
        else {
            return response()->json(['message' => 'Unauthorized access'],500);
        }
      $time = $query->orderBy('id','desc')->first();
      $oldValue = 0;
      if($time) {
        $oldValue = $time->time;
      }
      $time = new MeetingAndOtherTime;
      $time->model = 'App\DeveloperTask';
      $time->model_id = $request->task_id;
      $time->user_id = $user_id;
      $time->time = $request->time;
      $time->old_time = $oldValue;
      $time->type = $request->timing_type;
      $time->note = $request->note;
      $time->updated_by = Auth::user()->name;
      $time->save();
      return response()->json(['message' => 'Successful'],200);
    }

    public function approveMeetingHistory($task_id,Request $request) {
        if(Auth::user()->isAdmin) {
            if(!$request->approve_time || $request->approve_time == "") {
                return response()->json([
                    'message' => 'Select one time first'
                ],500);
            }
            $time = MeetingAndOtherTime::find($request->approve_time);

            MeetingAndOtherTime::where('model','App\DeveloperTask')->where('model_id',$time->model_id)->where('type',$time->type)->where('user_id',$time->user_id)->update(['approve' => 0]);
            $time->approve = 1;
            $time->save();
            return response()->json([
                'message' => 'Success'
            ],200);
        }
    }

    public function getUserHistory(Request $request) {
        $users = TaskUserHistory::where('model','App\DeveloperTask')->where('model_id',$request->id)->get();
        foreach($users as $u) {
            $old_name = null;
            $new_name = null;
            if($u->old_id) {
                $old_name = User::find($u->old_id)->name;
            }
            if($u->new_id) {
                $new_name = User::find($u->new_id)->name;
            }
            $u->new_name = $new_name;
            $u->old_name = $old_name;
        }
        return response()->json([
            'users' => $users
        ],200);

    }

    public function saveLeadEstimateTime(Request $request)
    {
        $issue = DeveloperTask::find($request->get('issue_id'));
        //$issue = Issue::find($request->get('issue_id'));
        if($issue && $request->lead_estimate_minutes) {
            DeveloperTaskHistory::create([
                'developer_task_id' => $issue->id,
                'attribute' => "lead_estimation_minute",
                'old_value' => $issue->lead_estimate_minutes,
                'new_value' => $request->lead_estimate_minutes,
                'user_id' => Auth::id(),
            ]);
        }

        $issue->lead_estimate_time = $request->get('lead_estimate_minutes');
        $issue->save();

        return response()->json(['status' => 'success']);
    }

    public function getLeadTimeHistory(Request $request)
    {
        $id = $request->id;
        $task_module = DeveloperTaskHistory::join('users','users.id','developer_tasks_history.user_id')->where('developer_task_id', $id)->where('attribute','lead_estimation_minute')->select('developer_tasks_history.*','users.name')->get();
        if($task_module) {
            return $task_module;
        }
        return 'error';
    }

    public function updateDevelopmentReminder(Request $request)
    {
        // this is the changes related to developer task
        $task = DeveloperTask::find($request->get('development_id'));
        $task->frequency            = $request->get('frequency');
        $task->reminder_message     = $request->get('message');
        $task->reminder_from        = $request->get('reminder_from',"0000-00-00 00:00");
        $task->reminder_last_reply  = $request->get('reminder_last_reply',0);
        $task->last_send_reminder   = date("Y-m-d H:i:s");
        $task->save();
        
            $message = $request->get('message');
            if(optional($task->assignedUser)->phone){
                $requestData = new Request();
                $requestData->setMethod('POST');
                $requestData->request->add(['issue_id' => $task->id, 'message' => $message, 'status' => 1]);
                app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'issue');
                
                //app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($task->assignedUser->phone, $task->assignedUser->whatsapp_number, $message);
            }   
        
        return response()->json([
          'success'
        ]);
      }
}
