<?php

namespace App\Http\Controllers;

use App\DeveloperTask;
use App\HubstaffTaskEfficiency;
use App\Hubstaff\HubstaffActivity;
use App\Hubstaff\HubstaffActivitySummary;
use App\HubstaffActivityByPaymentFrequency;
use App\Hubstaff\HubstaffMember; 
use App\Hubstaff\HubstaffTaskNotes;
use App\PaymentReceipt;
use App\Task;
use App\Team;
use App\User;
use App\UserRate;
use Artisan;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\hubstaffTrait;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HubstaffActivityReport;
use App\DeveloperTaskHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class HubstaffActivitiesController extends Controller
{
    use hubstaffTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $title = "Hubstaff Activities";

        return view("hubstaff.activities.index", compact('title'));

    }

    public function notification()
    {
        $title = "Hubstaff Notification";

        return view("hubstaff.activities.notification.index", compact('title'));
    }

    public function notificationRecords(Request $request)
    {
        $records = \App\Hubstaff\HubstaffActivityNotification::join("users as u", "hubstaff_activity_notifications.user_id", "u.id");
        
        $records->leftJoin("user_avaibilities as av", "hubstaff_activity_notifications.user_id", "av.user_id");
        
        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("u.name", "LIKE", "%$keyword%");
            });
        }

        if ($request->start_date != null) {
            $records = $records->whereDate("start_date", ">=", $request->start_date . " 00:00:00");
        }

        if ($request->end_date != null) {
            $records = $records->whereDate("start_date", "<=", $request->end_date . " 23:59:59");
        }

        $records = $records->select([
            "hubstaff_activity_notifications.*", 
            "u.name as user_name",
            "av.minute as daily_working_hour",
            "u.name as total_working_hour",
        ])
        ->orderBy('total_track','desc')->get();

         $recordsArr = []; 
       foreach($records as $row){

            $dwork = $row->daily_working_hour ? number_format($row->daily_working_hour,2,".","") : 0;

            $thours = floor($row->total_track / 3600);
            $tminutes = floor(($row->total_track / 60) % 60);
            $twork = $thours.':'.sprintf("%02d", $tminutes);

            $difference = ( ($row->daily_working_hour * 60 * 60 ) - $row->total_track);

            $sing = '';
            if($difference > 0){
              $sign = '-';
            }
            elseif($difference < 0){
              $sign = '+';
            }else{
                $sign = '';
            }
                $admin = null;
            if (\Auth::user()->hasRole('Admin')) {
                $admin = 1;
            }

            $hours = floor(abs($difference) / 3600);
            $minutes = sprintf("%02d", floor((abs($difference) / 60) % 60));

            $latest_message = \App\ChatMessage::where('user_id',$row->id)->latest('message')->first();
            $latest_msg = null;
            if($latest_message){
                $latest_msg = $latest_message->message;
                if(strlen($latest_message->message) > 20){
                    $latest_msg = substr($latest_message->message, 0, 20).'...';
                }
            }
            $recordsArr[] = [

                'id' => $row->id,
                'user_name' => $row->user_name,
                'user_id' => $row->user_id,
                'start_date' =>  Carbon::parse($row->start_date)->format('Y-m-d'),
                'daily_working_hour' => $dwork,
                'total_working_hour' => $twork,
                'different' => $sign.$hours.':'.$minutes,
                'min_percentage' => $row->min_percentage,
                'actual_percentage' => $row->actual_percentage,
                'reason' => $row->reason,
                'status' => $row->status,
                'is_admin' => $admin,
                'is_hod_crm' => "user",
                'latest_message' => $latest_msg,
                
            ];
       }   

        return response()->json(["code" => 200, "data" => $recordsArr, "total" => count($records)]);
    }

    public function downloadNotification(Request $request){

        $records = \App\Hubstaff\HubstaffActivityNotification::join("users as u", "hubstaff_activity_notifications.user_id", "u.id");

        $records->leftJoin("user_avaibilities as av", "hubstaff_activity_notifications.user_id", "av.user_id");

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("u.name", "LIKE", "%$keyword%");
            });
        }

        if ($request->start_date != null) {
            $records = $records->whereDate("start_date", ">=", $request->start_date . " 00:00:00");
        }

        if ($request->end_date != null) {
            $records = $records->whereDate("start_date", "<=", $request->end_date . " 23:59:59");
        }

        $records = $records->select([
            "hubstaff_activity_notifications.*", 
            "u.name as user_name",
            "av.minute as daily_working_hour",
            "u.name as total_working_hour",
        ])
        ->latest()->get();

        $recordsArr = []; 
       foreach($records as $row){

            $dwork = $row->daily_working_hour ? number_format($row->daily_working_hour,2,".","") : 0;

            $thours = floor($row->total_track / 3600);
            $tminutes = floor(($row->total_track / 60) % 60);
            $twork = $thours.':'.sprintf("%02d", $tminutes);

            $difference = ( ($row->daily_working_hour * 60 * 60 ) - $row->total_track);

            $sing = '';
            if($difference > 0){
              $sign = '-';
            }
            elseif($difference < 0){
              $sign = '+';
            }else{
                $sign = '';
            }



            $hours = floor(abs($difference) / 3600);
            $minutes = sprintf("%02d", floor((abs($difference) / 60) % 60));



            $recordsArr[] = [
                'user_name' => $row->user_name,
                'start_date' =>  Carbon::parse($row->start_date)->format('Y-m-d'),
                'daily_working_hour' => $dwork,
                'total_working_hour' => $twork,
                'different' => $sign.$hours.':'.$minutes,
                'min_percentage' => $row->min_percentage,
                'actual_percentage' => $row->actual_percentage,
                'reason' => $row->reason,
                'status' => $row->status,

            ];
       }


        $filename = 'Report-'.request('start_date').'-To-'.request('end_date').'.csv';
        return Excel::download(new HubstaffNotificationReport($recordsArr),$filename);
    }

    public function notificationReasonSave(Request $request)
    {
        if ($request->id != null) {
            $hnotification = \App\Hubstaff\HubstaffActivityNotification::find($request->id);
            if ($hnotification != null) {
                $hnotification->reason = $request->reason;
                $hnotification->save();
                return response()->json(["code" => 200, "data" => [], "message" => "Added succesfully"]);
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Requested id is not in database"]);
    }

    public function changeStatus(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(["code" => 500, "data" => [], "message" => "only admin can change status."]);
        }
        if ($request->id != null) {
            $hnotification = \App\Hubstaff\HubstaffActivityNotification::find($request->id);
            if ($hnotification != null) {
                $hnotification->status = $request->status;
                $hnotification->save();
                return response()->json(["code" => 200, "data" => [], "message" => "changed succesfully"]);
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Requested id is not in database"]);
    }

    public function getActivityUsers(Request $request, $params = null)
    {   
        Log::channel('hubstaff_activity_command')->info(' get activity user controller starts');

        if($params !== null){
            $params = $params->request->all();
             
            $request->activity_command = $params['activity_command']; 
            $request->user_id = $params['user_id']; 
            $request->user = $params['user']; 
            $request->developer_task_id = $params['developer_task_id']; 
            $request->task_id = $params['task_id']; 
            $request->task_status = $params['task_status']; 
            $request->start_date = $params['start_date']; 
            $request->end_date = $params['end_date']; 
            $request->status = $params['status'];
            $request->submit = $params['submit']; 
            Auth::login($request->user);
        }
        Log::channel('hubstaff_activity_command')->info(' check parmenters');

        //START - Purpose : Comment code - DEVATSK-4300
        // if( request('submit') ==  'report_download'){
        //    return $this->downloadExcelReport();

        // }
        //END - DEVATSK-4300

        $title      = "Hubstaff Activities";
        $start_date = $request->start_date ? $request->start_date : date('Y-m-d', strtotime("-1 days"));
        $end_date   = $request->end_date ? $request->end_date : date('Y-m-d', strtotime("-1 days"));
        $user_id    = $request->user_id ? $request->user_id : null;
        $task_id    = $request->task_id ? $request->task_id : null;
        $task_status    = $request->task_status ? $request->task_status : null;
        $developer_task_id    = $request->developer_task_id ? $request->developer_task_id : null;

        $taskIds = [];
        if(!empty($developer_task_id)) {
            
            $developer_tasks    = \App\DeveloperTask::find($developer_task_id);
            Log::channel('hubstaff_activity_command')->info('find devloper task' .$developer_tasks);
            if(!empty($developer_tasks)) {
                if(!empty($developer_tasks->hubstaff_task_id)) {
                    $taskIds[] = $developer_tasks->hubstaff_task_id;
                }
                if(!empty($developer_tasks->lead_hubstaff_task_id)) {
                    $taskIds[] = $developer_tasks->lead_hubstaff_task_id;
                }
                if(!empty($developer_tasks->team_lead_hubstaff_task_id)) {
                    $taskIds[] = $developer_tasks->team_lead_hubstaff_task_id;
                }
                if(!empty($developer_tasks->tester_hubstaff_task_id)) {
                    $taskIds[] = $developer_tasks->tester_hubstaff_task_id;
                }
            }
        }
        Log::channel('hubstaff_activity_command')->info('task id array' .$task_id);

        if( !empty( $task_status ) ){
            $developer_tasks = \App\DeveloperTask::where('status',$task_status)->where('hubstaff_task_id','!=',0)->pluck('hubstaff_task_id');
            if(!empty($developer_tasks)) {
                 $taskIds = $developer_tasks;
            }
            Log::channel('hubstaff_activity_command')->info('devloper task' .$developer_tasks);

        }

        if(!empty($task_id)) {
            $developer_tasks    = \App\Task::find($task_id);
            Log::channel('hubstaff_activity_command')->info(' task' .$developer_tasks);

            if(!empty($developer_tasks)) {
                if(!empty($developer_tasks->hubstaff_task_id)) {
                    $taskIds[] = $developer_tasks->hubstaff_task_id;
                }
                if(!empty($developer_tasks->lead_hubstaff_task_id)) {
                    $taskIds[] = $developer_tasks->lead_hubstaff_task_id;
                }
            }
        }
        Log::channel('hubstaff_activity_command')->info('task ids' .json_encode($taskIds));

        if (!empty($taskIds) || !empty($task_id) || !empty($developer_task_id)) {

            $query = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->whereIn('hubstaff_activities.task_id', $taskIds)->whereDate('hubstaff_activities.starts_at', '>=', $start_date)->whereDate('hubstaff_activities.starts_at', '<=', $end_date);
            Log::channel('hubstaff_activity_command')->info('!empty($taskIds) || !empty($task_id) || !empty($developer_task_id)',$query);

        } else {
            //START - Purpose : Add Date Temporary Remove this code - DEVATSK-4300
            // $start_date = '2020-09-01';
            // $end_date = '2020-09-02';
            //END - DEVATSK-4300
            $query = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->whereDate('hubstaff_activities.starts_at', '>=', $start_date)->whereDate('hubstaff_activities.starts_at', '<=', $end_date);
            Log::channel('hubstaff_activity_command')->info('!not if queary'.json_encode($query));


        }

        if (Auth::user()->isAdmin()) {
            Log::channel('hubstaff_activity_command')->info(' check : user is admin ');

            $query = $query;
            $users = User::all()->pluck('name', 'id')->toArray();
        } else {
            Log::channel('hubstaff_activity_command')->info(' check : if  user is not admin ');


            $members = Team::join('team_user', 'team_user.team_id', 'teams.id')->where('teams.user_id', Auth::user()->id)->distinct()->pluck('team_user.user_id');

            Log::channel('hubstaff_activity_command')->info(' check : if  user is not admin '.json_encode($members));

            if (!count($members)) {
                $members = [Auth::user()->id];
            } else {
                $members[] = Auth::user()->id;
            }
            $query = $query->whereIn('hubstaff_members.user_id', $members);
            Log::channel('hubstaff_activity_command')->info(' queary'.json_encode($query));

            $users = User::whereIn('id', $members)->pluck('name', 'id')->toArray();
            Log::channel('hubstaff_activity_command')->info(' users'.json_encode($users));

        }

        if ($request->user_id) {
            $query = $query->where('hubstaff_members.user_id', $request->user_id);
        }

        $activities = $query->select(DB::raw("
        hubstaff_activities.user_id,
        SUM(hubstaff_activities.tracked) as total_tracked,DATE(hubstaff_activities.starts_at) as date,hubstaff_members.user_id as system_user_id")
        )->groupBy('date', 'user_id')->orderBy('date', 'desc')->get();
        $activityUsers = collect([]);


        foreach ($activities as $activity) {
            $a = [];

            $efficiencyObj = HubstaffTaskEfficiency::where('user_id', $activity->user_id)->first();
            Log::channel('hubstaff_activity_command')->info(' activuies'. json_encode($activity));

            // all activities

            if (isset($efficiencyObj->id) && $efficiencyObj->id > 0) {
                $a['admin_efficiency'] = $efficiencyObj->admin_input;
                $a['user_efficiency']  = $efficiencyObj->user_input;
                $a['efficiency']       = (Auth::user()->isAdmin()) ? $efficiencyObj->admin_input : $efficiencyObj->user_input;

                Log::channel('hubstaff_activity_command')->info('check: hubstaff activity id > 0'.json_encode($efficiencyObj->id));


            } else {
                $a['admin_efficiency'] = "";
                $a['user_efficiency']  = "";

                $a['efficiency'] = "";
                Log::channel('hubstaff_activity_command')->info('check: hubstaff activity id is < 0'.json_encode($efficiencyObj->id));


            }

            if ($activity->system_user_id) {
                $user = User::find($activity->system_user_id);
                if ($user) {
                    $activity->userName = $user->name;
                } else {
                    $activity->userName = '';
                }
                Log::channel('hubstaff_activity_command')->info('check: system id of activity'. json_encode($activity->userName));

            } else {
                $activity->userName = '';
            }

            // send hubstaff activities
            $ac            = DB::select(DB::raw("SELECT hubstaff_activities.* FROM hubstaff_activities where DATE(starts_at) = '" . $activity->date . "' and hubstaff_activities.user_id = " . $activity->user_id));
            Log::channel('hubstaff_activity_command')->info('check: hubstaff_activities'.json_encode($ac));

            $totalApproved = 0;
            $totalPending = 0;
            $isAllSelected = 0;
            $a['tasks']    = [];
            $lsTask        = [];
            foreach ($ac as $ar) {
                $taskSubject = '';
                if ($ar->task_id) {
                    if ($ar->is_manual) {
                        $task = DeveloperTask::where('id', $ar->task_id)->first();
                        Log::channel('hubstaff_activity_command')->info('check: if $ar->manual is true ',$task);

                        if ($task) {
                            $estMinutes = ($task->estimate_minutes && $task->estimate_minutes > 0) ? $task->estimate_minutes : "N/A";
                            $taskSubject = $ar->task_id . '||#DEVTASK-' . $task->id . '-' . $task->subject."||#DEVTASK-$task->id||$estMinutes||$task->status||$task->id";
                            Log::channel('hubstaff_activity_command')->info('task true ');

                        } else {
                            $task = Task::where('id', $ar->task_id)->first();
                            Log::channel('hubstaff_activity_command')->info('check: if not task find'.json_encode($task));

                            if ($task) {
                                $estMinutes = ($task->estimate_minutes && $task->estimate_minutes > 0) ? $task->estimate_minutes : "N/A";
                                $taskSubject = $ar->task_id . '||#TASK-' . $task->id . '-' . $task->task_subject."||#TASK-$task->id||$estMinutes||$task->status||$task->id";
                                Log::channel('hubstaff_activity_command')->info('check:find task from else condition'.json_encode($task));

                            }
                        }
                    } else {
                        Log::channel('hubstaff_activity_command')->info('check: if $ar->manual is fakse ');

                        $tracked = $ar->tracked;
                        $task = DeveloperTask::where('hubstaff_task_id', $ar->task_id)->orWhere('lead_hubstaff_task_id', $ar->task_id)->first();
                        if ($task && empty( $task_id )) {
                            Log::channel('hubstaff_activity_command')->info('check: hubstaff task id and lead hubstaff task id is true:' .$task);

                            $estMinutes = ($task->estimate_minutes && $task->estimate_minutes > 0) ? $task->estimate_minutes : "N/A";
                            $taskSubject = $ar->task_id . '||#DEVTASK-' . $task->id . '-' . $task->subject."||#DEVTASK-$task->id||$estMinutes||$task->status||$task->id";
                        } else {
                            Log::channel('hubstaff_activity_command')->info('check: if $ar->manual is fakse ');

                            $task = Task::where('hubstaff_task_id', $ar->task_id)->orWhere('lead_hubstaff_task_id', $ar->task_id)->first();
                            if ($task && empty( $developer_task_id )) {
                                $estMinutes = ($task->estimate_minutes && $task->estimate_minutes > 0) ? $task->estimate_minutes : "N/A";
                                $taskSubject = $ar->task_id . '||#TASK-' . $task->id . '-' . $task->task_subject."||#TASK-$task->id||$estMinutes||$task->status||$task->id";
                            }
                        }
                    }
                }
                $lsTask[] = $taskSubject;
            }
            Log::channel('hubstaff_activity_command')->info('ls task array'.json_encode($lsTask[]));

            $a['tasks'] = array_unique($lsTask);
            $hubActivitySummery = HubstaffActivitySummary::where('date', $activity->date)->where('user_id', $activity->system_user_id)->orderBy('created_at', 'desc')->first();
            if ($request->status == 'approved') {
                Log::channel('hubstaff_activity_command')->info('requrest approved');

                if ($hubActivitySummery && $hubActivitySummery->final_approval == 1) {
                    Log::channel('hubstaff_activity_command')->info('$hubActivitySummery && $hubActivitySummery->final_approval == 1');

                    if ($hubActivitySummery->forworded_person == 'admin') {
                        Log::channel('hubstaff_activity_command')->info(' is fowaeded person is admin ');

                        $status         = 'Approved by admin';
                        $totalApproved  = $hubActivitySummery->accepted;
                        $totalPending  = $hubActivitySummery->pending;
                        $totalUserRequest  = $hubActivitySummery->user_requested;
                        $totalNotPaid   = HubstaffActivity::whereDate('starts_at', $activity->date)->where('user_id', $activity->user_id)->where('status', 1)->where('paid', 0)->sum('tracked');
                        Log::channel('hubstaff_activity_command')->info(' totle paid '.json_encode($totalNotPaid));

                        $forworded_to   = $hubActivitySummery->receiver;
                        $final_approval = 1;

                        $a['system_user_id'] = $activity->system_user_id;
                        $a['user_id']        = $activity->user_id;
                        $a['total_tracked']  = $activity->total_tracked;
                        $a['date']           = $activity->date;
                        $a['userName']       = $activity->userName;
                        $a['forworded_to']   = $forworded_to;
                        $a['status']         = $status;
                        $a['totalApproved']  = $totalApproved;
                        $a['totalPending']  = $totalPending;
                        $a['totalUserRequest']   = $totalUserRequest;
                        $a['totalNotPaid']   = $totalNotPaid;
                        $a['final_approval'] = $final_approval;
                        $a['note']           = $hubActivitySummery->rejection_note;
                        $activityUsers->push($a);
                        Log::channel('hubstaff_activity_command')->info('end admin condition if forwarded');

                    }
                }
            } else if ($request->status == 'pending') {
                Log::channel('hubstaff_activity_command')->info('status pending');

                if ($hubActivitySummery && $hubActivitySummery->final_approval == 1) {

                    Log::channel('hubstaff_activity_command')->info('final_approval is 1');

                    if ($hubActivitySummery->forworded_person == 'admin') {
                        $status         = 'Pending by admin';
                        $totalApproved  = $hubActivitySummery->accepted;
                        $totalPending  = $hubActivitySummery->pending;
                        $totalUserRequest  = $hubActivitySummery->user_requested;
                        $totalNotPaid   = HubstaffActivity::whereDate('starts_at', $activity->date)->where('user_id', $activity->user_id)->where('status', 2)->where('paid', 0)->sum('tracked');
                        Log::channel('hubstaff_activity_command')->info('total not paid'. json_encode($totalNotPaid));

                        $forworded_to   = $hubActivitySummery->receiver;
                        $final_approval = 1;

                        $a['system_user_id'] = $activity->system_user_id;
                        $a['user_id']        = $activity->user_id;
                        $a['total_tracked']  = $activity->total_tracked;
                        $a['date']           = $activity->date;
                        $a['userName']       = $activity->userName;
                        $a['forworded_to']   = $forworded_to;
                        $a['status']         = $status;
                        $a['totalApproved']  = $totalApproved;
                        $a['totalPending']  = $totalPending;
                        $a['totalUserRequest']   = $totalUserRequest;
                        $a['totalNotPaid']   = $totalNotPaid;
                        $a['final_approval'] = $final_approval;
                        $a['note']           = $hubActivitySummery->rejection_note;
                        $activityUsers->push($a);
                    }
                    
                }
                Log::channel('hubstaff_activity_command')->info('end pending condition');
            } else if ($request->status == 'pending') {
                Log::channel('hubstaff_activity_command')->info('pending condition');

                if ($hubActivitySummery && $hubActivitySummery->final_approval == 1) {
                    Log::channel('hubstaff_activity_command')->info('final approval is one');

                    if ($hubActivitySummery->forworded_person == 'admin') {
                        Log::channel('hubstaff_activity_command')->info('is forwarded person is is admin');

                        $status         = 'Pending by admin';
                        $totalApproved  = $hubActivitySummery->accepted;
                        $totalUserRequest  = $hubActivitySummery->user_requested;
                        $totalNotPaid   = HubstaffActivity::whereDate('starts_at', $activity->date)->where('user_id', $activity->user_id)->where('status', 2)->where('paid', 0)->sum('tracked');
                        $forworded_to   = $hubActivitySummery->receiver;
                        $final_approval = 1;

                        $a['system_user_id'] = $activity->system_user_id;
                        $a['user_id']        = $activity->user_id;
                        $a['total_tracked']  = $activity->total_tracked;
                        $a['date']           = $activity->date;
                        $a['userName']       = $activity->userName;
                        $a['forworded_to']   = $forworded_to;
                        $a['status']         = $status;
                        $a['totalApproved']  = $totalApproved;
                        $a['totalUserRequest']   = $totalUserRequest;
                        $a['totalNotPaid']   = $totalNotPaid;
                        $a['final_approval'] = $final_approval;
                        $a['note']           = $hubActivitySummery->rejection_note;
                        $activityUsers->push($a);
                    }
                    
                }
                Log::channel('hubstaff_activity_command')->info('pending condition end');
            } else if ($request->status == 'forwarded_to_lead') {
                Log::channel('hubstaff_activity_command')->info('forwareded to lead');

                if ($hubActivitySummery) {
                    if ($hubActivitySummery->forworded_person == 'team_lead' && $hubActivitySummery->final_approval == 0) {
                        Log::channel('hubstaff_activity_command')->info('final approval is zero');

                        $status         = 'Pending for team lead approval';
                        $totalApproved  = $hubActivitySummery->accepted;
                        $totalPending  = $hubActivitySummery->pending;
                        $totalUserRequest  = $hubActivitySummery->user_requested;
                        $totalNotPaid   = HubstaffActivity::whereDate('starts_at', $activity->date)->where('user_id', $activity->user_id)->where('status', 1)->where('paid', 0)->sum('tracked');
                        Log::channel('hubstaff_activity_command')->info('total nor paid'.json_encode($totalNotPaid));

                        $forworded_to   = $hubActivitySummery->receiver;
                        $final_approval = 0;

                        $a['system_user_id'] = $activity->system_user_id;
                        $a['user_id']        = $activity->user_id;
                        $a['total_tracked']  = $activity->total_tracked;
                        $a['date']           = $activity->date;
                        $a['userName']       = $activity->userName;
                        $a['forworded_to']   = $forworded_to;
                        $a['status']         = $status;
                        $a['totalApproved']  = $totalApproved;
                        $a['totalPending']  = $totalPending;
                        $a['totalUserRequest']   = $totalUserRequest;
                        $a['totalNotPaid']   = $totalNotPaid;
                        $a['final_approval'] = $final_approval;
                        $a['note']           = $hubActivitySummery->rejection_note;
                        $activityUsers->push($a);
                    }
                }
                Log::channel('hubstaff_activity_command')->info('pending condition end');

            } else if ($request->status == 'forwarded_to_admin') {
                Log::channel('hubstaff_activity_command')->info('forwarded to admin');

                if ($hubActivitySummery) {
                    if ($hubActivitySummery->forworded_person == 'admin' && $hubActivitySummery->final_approval == 0) {
                        Log::channel('hubstaff_activity_command')->info('final approval is zero');

                        $status         = 'Pending for admin approval';
                        $totalApproved  = $hubActivitySummery->accepted;
                        $totalPending  = $hubActivitySummery->pending;
                        $totalUserRequest  = $hubActivitySummery->user_requested;
                        $totalNotPaid   = HubstaffActivity::whereDate('starts_at', $activity->date)->where('user_id', $activity->user_id)->where('status', 1)->where('paid', 0)->sum('tracked');
                        Log::channel('hubstaff_activity_command')->info('total nor paid'.json_encode($totalNotPaid));

                        $forworded_to   = $hubActivitySummery->receiver;
                        $final_approval = 0;

                        $a['system_user_id'] = $activity->system_user_id;
                        $a['user_id']        = $activity->user_id;
                        $a['total_tracked']  = $activity->total_tracked;
                        $a['date']           = $activity->date;
                        $a['userName']       = $activity->userName;
                        $a['forworded_to']   = $forworded_to;
                        $a['status']         = $status;
                        $a['totalApproved']  = $totalApproved;
                        $a['totalPending']  = $totalPending;
                        $a['totalUserRequest']   = $totalUserRequest;
                        $a['totalNotPaid']   = $totalNotPaid;
                        $a['final_approval'] = $final_approval;
                        $a['note']           = $hubActivitySummery->rejection_note;
                        $activityUsers->push($a);
                    }
                }
                Log::channel('hubstaff_activity_command')->info('forward to admin is end');

            } else if ($request->status == 'new') {
                Log::channel('hubstaff_activity_command')->info('status is new');

                if (!$hubActivitySummery) {
                    $status         = 'New';
                    $totalApproved  = 0;
                    $totalPending  = 0;
                    $totalNotPaid   = 0;
                    $totalUserRequest   = 0;
                    $forworded_to   = Auth::user()->id;
                    $final_approval = 0;

                    $a['system_user_id'] = $activity->system_user_id;
                    $a['user_id']        = $activity->user_id;
                    $a['total_tracked']  = $activity->total_tracked;
                    $a['date']           = $activity->date;
                    $a['userName']       = $activity->userName;
                    $a['forworded_to']   = $forworded_to;
                    $a['status']         = $status;
                    $a['totalApproved']  = $totalApproved;
                    $a['totalPending']  = $totalPending;
                    $a['totalUserRequest'] = $totalUserRequest;
                    $a['totalNotPaid']   = $totalNotPaid;
                    $a['final_approval'] = $final_approval;
                    $a['note']           = '';
                    $activityUsers->push($a);
                }
                Log::channel('hubstaff_activity_command')->info('end status new condition');

            } else {
                Log::channel('hubstaff_activity_command')->info('final else condition after elseif elseif...');

                if ($hubActivitySummery) {
                    if ($hubActivitySummery->forworded_person == 'admin') {
                        if ($hubActivitySummery->final_approval == 1) {
                            $status = 'Approved by admin';
                        } else {
                            $status = 'Pending for admin approval';
                        }
                    }
                    if ($hubActivitySummery->forworded_person == 'team_lead') {
                        $status = 'Pending for team lead approval';
                    }
                    if ($hubActivitySummery->forworded_person == 'user') {
                        $status = 'Pending for approval';
                    }

                    $totalApproved = $hubActivitySummery->accepted;
                    $totalPending = $hubActivitySummery->pending;
                    $totalUserRequest  = $hubActivitySummery->user_requested;
                    $totalNotPaid  = HubstaffActivity::whereDate('starts_at', $activity->date)->where('user_id', $activity->user_id)->where('status', 1)->where('paid', 0)->sum('tracked');

                    Log::channel('hubstaff_activity_command')->info('total nor paid'.$totalNotPaid);

                    $forworded_to  = $hubActivitySummery->receiver;
                    if ($hubActivitySummery->final_approval) {
                        $final_approval = 1;
                    } else {
                        $final_approval = 0;
                    }
                    $note = $hubActivitySummery->rejection_note;

                    Log::channel('hubstaff_activity_command')->info('hub staff activity summerny');

                } else {
                    Log::channel('hubstaff_activity_command')->info('end hub staff activity summerny');

                    $forworded_to   = Auth::user()->id;
                    $status         = 'New';
                    $totalApproved  = 0;
                    $totalPending  = 0;
                    $totalNotPaid   = 0;
                    $totalUserRequest  = 0;
                    $final_approval = 0;
                    $note           = null;
                }
                $a['system_user_id'] = $activity->system_user_id;
                $a['user_id']        = $activity->user_id;
                $a['total_tracked']  = $activity->total_tracked;
                $a['date']           = $activity->date;
                $a['userName']       = $activity->userName;
                $a['forworded_to']   = $forworded_to;
                $a['status']         = $status;
                $a['totalApproved']  = $totalApproved;
                $a['totalPending']  = $totalPending;
                $a['totalUserRequest'] = $totalUserRequest;
                $a['totalNotPaid']   = $totalNotPaid;
                $a['final_approval'] = $final_approval;
                $a['note']           = $note;
                $activityUsers->push($a);
                Log::channel('hubstaff_activity_command')->info('end foreach condition');

            }
        }
        //START - Purpose : set data for download  - DEVATSK-4300
        if( $request->submit ==  'report_download' ){
            Log::channel('hubstaff_activity_command')->info('reqeust has "report download" condition and return download excel report');

           return $this->downloadExcelReport($activityUsers);

        }
        //END - DEVATSK-4300
        
        Log::channel('hubstaff_activity_command')->info('before return page');

        $status = $request->status;
        return view("hubstaff.activities.activity-users", compact('title', 'status', 'activityUsers', 'start_date', 'end_date', 'users', 'user_id', 'task_id'));
    }

    //Purpose : Add activityUsers parameter - DEVATSK-4300
     public function downloadExcelReport($activityUsers){

        // $query = HubstaffActivity::join('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->whereDate('hubstaff_activities.starts_at', '>=', request('start_date'))->whereDate('hubstaff_activities.starts_at', '<=', request('end_date'));
        
        // $query->leftJoin('developer_tasks','hubstaff_activities.task_id','developer_tasks.hubstaff_task_id');
        
        // $query = $query->where('hubstaff_members.user_id', request('user_id'));
        
        //  $activities = $query->select(DB::raw("
        //  SUM(developer_tasks.estimate_minutes) as estimated_time, hubstaff_members.user_id,hubstaff_activities.task_id,hubstaff_activities.is_manual,
        //         SUM(hubstaff_activities.tracked) as total_tracked,DATE(hubstaff_activities.starts_at) as date,hubstaff_members.user_id as system_user_id")
        // )->groupBy('task_id')->orderBy('date', 'desc')->get();

        // if(request('user_id')){
        //     $user = User::where('id', request('user_id'))->first();
        // }else{
        //     $user = User::where('id', Auth::user()->id)->first();
        // }

        // $userid = Auth::id();
        // $userquery = ' AND (assign_from = ' . $userid . ' OR  master_user_id = ' . $userid . ' OR  id IN (SELECT task_id FROM task_users WHERE user_id = ' . $userid . ' AND type LIKE "%User%")) ';
        // $test = DB::Select(
        // '
		// 	SELECT tasks.*

		// 	FROM (
		// 	  SELECT * FROM tasks
		// 	  LEFT JOIN (
		// 		  SELECT 
		// 		  chat_messages.id as message_id, 
		// 		  chat_messages.task_id, 
		// 		  chat_messages.message, 
		// 		  chat_messages.status as message_status, 
		// 		  chat_messages.sent as message_type, 
		// 		  chat_messages.created_at as message_created_at, 
		// 		  chat_messages.is_reminder AS message_is_reminder,
		// 		  chat_messages.user_id AS message_user_id
		// 		  FROM chat_messages join chat_messages_quick_datas on chat_messages_quick_datas.last_communicated_message_id = chat_messages.id WHERE chat_messages.status not in(7,8,9) and chat_messages_quick_datas.model="App\\\\Task"
		// 	  ) as chat_messages  ON chat_messages.task_id = tasks.id
		// 	) AS tasks
		// 	WHERE (deleted_at IS NULL) AND (id IS NOT NULL) AND is_statutory != 1 '.$userquery  
        // );
        // // dd($test);
        // $activities = $activities->toArray();
        // foreach($test as $t){
        //     // dump($t);
        //     $a["type"] = "task";
        //     $a["estimated_time"] = "N/A";
        //     $a["user_id"] = $userid;
        //     $a["task_id"] = $t->task_id;
        //     $a["is_manual"] = 0;
        //     $a["total_tracked"] = 0;
        //     $a["date"] = $t->message_created_at;
        //     $a["system_user_id"] = 'N/A';
        //     $id = $t->id;
		// 	$task_module = DeveloperTaskHistory::where('developer_task_id', $id)->select('developer_tasks_history.*')->latest()->first();
		// 	if($task_module) {
        //         $a["estimated_time"] = $task_module->estimate_minutes ?? 'N/A';
        //     }
        //     $activities[] = $a;
        // }

        //START - Purpose : Get User Data - DEVATSK-4300 
        if(request('user_id')){
            $user = User::where('id', request('user_id'))->first();
        }else{
            $user = User::where('id', Auth::user()->id)->first();
        }
        $activities[] = $activityUsers;
        //END - DEVATSK-4300
        return Excel::download(new HubstaffActivityReport($activities), $user->name.'-'.request('start_date').'-To-'.request('end_date').'.xlsx');
    }
    public function downloadExcelReportOld($activityUsers, $users)
    {   
        if(request('user_id')){
            $user = User::where('id', request('user_id'))->first();
        }else{
            $user = User::where('id', Auth::user()->id)->first();
        }
        
        return Excel::download(new HubstaffActivityReport($activityUsers->toArray()), $user->name.'-'.request('start_date').'-To-'.request('end_date').'.xlsx');
    }

    public function approveTime(Request $request)
    {
        $activityrecords = DB::select(DB::raw("SELECT CAST(starts_at as date) AS OnDate,  SUM(tracked) AS total_tracked, hour( starts_at ) as onHour, status
        FROM hubstaff_activities where DATE(starts_at) = '" . $request->date . "' and user_id = " . $request->user_id . "
        GROUP BY hour( starts_at ) , day( starts_at )"));

        $appArr = [];
        
        foreach ($activityrecords as $record) {
            $activities = DB::select(DB::raw("SELECT hubstaff_activities.*
            FROM hubstaff_activities where DATE(starts_at) = '" . $request->date . "' and user_id = " . $request->user_id . " and hour(starts_at) = " . $record->onHour . ""));

            foreach ($activities as $value) {
                array_push($appArr,$value->id);
            }
        }

        if ( !empty($appArr) ) {
                $myRequest = new Request();
                $myRequest->setMethod('POST');
                $myRequest->request->add([
                    'user_id' => $request->user_id,
                    'activities' => $appArr,
                    'status' => '1',
                    'date' => $request->date,
                ]);
             return app('App\Http\Controllers\HubstaffActivitiesController')->finalSubmit( $myRequest );
        }
        
    }

    public function getActivityDetails(Request $request)
    {

        if (!$request->user_id || !$request->date || $request->user_id == "" || $request->date == "") {
            return response()->json(['message' => '']);
        }

        $activityrecords = DB::select(DB::raw("SELECT CAST(starts_at as date) AS OnDate,  SUM(tracked) AS total_tracked, hour( starts_at ) as onHour, status
        FROM hubstaff_activities where DATE(starts_at) = '" . $request->date . "' and user_id = " . $request->user_id . "
        GROUP BY hour( starts_at ) , day( starts_at )"));
        // $activityrecords  = HubstaffActivity::whereDate('hubstaff_activities.starts_at',$request->date)->where('hubstaff_activities.user_id',$request->user_id)->select('hubstaff_activities.*')->get();

        $admins = User::join('role_user', 'role_user.user_id', 'users.id')->join('roles', 'roles.id', 'role_user.role_id')
            ->where('roles.name', 'Admin')->select('users.name', 'users.id')->get();

        $teamLeaders = [];

        $users = User::select('name', 'id')->get();

        $hubstaff_member    = HubstaffMember::where('hubstaff_user_id', $request->user_id)->first();
        $hubActivitySummery = null;
        if ($hubstaff_member) {
            $system_user_id     = $hubstaff_member->user_id;
            $hubActivitySummery = HubstaffActivitySummary::where('date', $request->date)->where('user_id', $system_user_id)->orderBy('created_at', 'DESC')->first();
            $teamLeaders = User::join('teams', 'teams.user_id', 'users.id')->join('team_user', 'team_user.team_id', 'teams.id')->where('team_user.user_id', $system_user_id)->distinct()->select('users.name', 'users.id')->get();
        }
        $approved_ids = [0];
        $pending_ids = [0];
        if ($hubActivitySummery) {
            if ($hubActivitySummery->approved_ids) {
                $approved_ids = json_decode($hubActivitySummery->approved_ids);
            }
            if ($hubActivitySummery->pending_ids) {
                $pending_ids = json_decode($hubActivitySummery->pending_ids);
            }

            if ($hubActivitySummery->final_approval) {
                if (!Auth::user()->isAdmin()) {
                    return response()->json([
                        'message' => 'Already approved',
                    ], 500);
                }
            }
        }

        foreach ($activityrecords as $record) {
            $activities = DB::select(DB::raw("SELECT hubstaff_activities.*
            FROM hubstaff_activities where DATE(starts_at) = '" . $request->date . "' and user_id = " . $request->user_id . " and hour(starts_at) = " . $record->onHour . ""));
            $totalApproved = 0;
            $totalPending = 0;
            $isAllSelected = 0;
            foreach ($activities as $a) {
                if (in_array($a->id, $approved_ids)) {
                    $isAllSelected = $isAllSelected + 1;
                    $a->status     = 1;
                    $hubAct        = HubstaffActivity::where('id', $a->id)->first();
                    if ($hubAct) {
                        $totalApproved = $totalApproved + $a->tracked;
                    }
                    $a->totalApproved = $a->tracked;
                } else {
                    $a->status        = 0;
                    $a->totalApproved = 0;
                }

                if (in_array($a->id, $pending_ids)) {
                    $isAllSelected = $isAllSelected + 1;
                    $a->status     = 2;
                    $hubAct        = HubstaffActivity::where('id', $a->id)->first();
                    if ($hubAct) {
                        $totalPending = $totalPending + $a->tracked;
                    }
                    $a->totalPending = $a->tracked;
                } else {
                    $a->status        = 0;
                    $a->totalPending = 0;
                }
                $taskSubject = '';
                if ($a->task_id) {
                    if ($a->is_manual) {
                        $task = DeveloperTask::where('id', $a->task_id)->first();
                        if ($task) {
                            $taskSubject = '#DEVTASK-' . $task->id . '-' . $task->subject;
                        } else {
                            $task = Task::where('id', $a->task_id)->first();
                            if ($task) {
                                $taskSubject = '#TASK-' . $task->id . '-' . $task->task_subject;
                            }
                        }
                        $taskStatus = $task->status ?? null;
                    } else {
                        $task = DeveloperTask::where('hubstaff_task_id', $a->task_id)->orWhere('lead_hubstaff_task_id', $a->task_id)->first();
                        if ($task) {
                            $taskSubject = '#DEVTASK-' . $task->id . '-' . $task->subject;
                        } else {
                            $task = Task::where('hubstaff_task_id', $a->task_id)->orWhere('lead_hubstaff_task_id', $a->task_id)->first();
                            if ($task) {
                                $taskSubject = '#TASK-' . $task->id . '-' . $task->task_subject;
                            }
                        }
                        $taskStatus = $task->status ?? null;
                    }
                }

                $a->taskSubject = $taskSubject;
                $a->taskStatus = $taskStatus ?? null;
            }
            if ($isAllSelected == count($activities)) {
                $record->sample = 1;
            } else {
                $record->sample = 0;
            }
            $record->activities    = $activities;
            $record->totalApproved = $totalApproved;
            $record->totalPending = $totalPending;
        }
        $user_id = $request->user_id;
        $isAdmin = false;
        if (Auth::user()->isAdmin()) {
            $isAdmin = true;
        }
        $isTeamLeader = false;
        $isLeader     = Team::where('user_id', Auth::user()->id)->first();
        if ($isLeader) {
            $isTeamLeader = true;
        }
        $taskOwner = false;
        if (!$isAdmin && !$isTeamLeader) {
            $taskOwner = true;
        }
        $date = $request->date;

        $member = HubstaffMember::where('hubstaff_user_id', $request->user_id)->first();
        return view("hubstaff.activities.activity-records", compact('activityrecords', 'user_id', 'date', 'hubActivitySummery', 'teamLeaders', 'admins', 'users', 'isAdmin', 'isTeamLeader', 'taskOwner', 'member'));
    }

    public function approveActivity(Request $request)
    {
        if (!$request->forworded_person) {
            return response()->json([
                'message' => 'Please forword someone',
            ], 500);
        }
        if ($request->forworded_person == 'admin') {
            $forword_to = $request->forword_to_admin;
        }
        if ($request->forworded_person == 'team_lead') {
            $forword_to = $request->forword_to_team_leader;
        }
        if ($request->forworded_person == 'user') {
            $forword_to = $request->forword_to_user;
        }

        $approvedArr = [];
        $rejectedArr = [];
        if ($request->activities && count($request->activities) > 0) {
            $approved = 0;
            foreach ($request->activities as $id) {
                $hubActivity = HubstaffActivity::where('id', $id)->first();
                //    $hubActivity->update(['status' => 1]);
                $approved      = $approved + $hubActivity->tracked;
                $approvedArr[] = $id;
            }
            $query = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->whereDate('hubstaff_activities.starts_at', $request->date)->where('hubstaff_activities.user_id', $request->user_id);

            $totalTracked = $query->sum('tracked');
            $activity     = $query->select('hubstaff_members.user_id')->first();
            $user_id      = $activity->user_id;
            $rejected     = $totalTracked - $approved;
            $rejectedArr  = $query  = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->whereDate('hubstaff_activities.starts_at', $request->date)->where('hubstaff_activities.user_id', $request->user_id)->whereNotIn('hubstaff_activities.id', $approvedArr)->pluck('hubstaff_activities.id')->toArray();

            $approvedJson = json_encode($approvedArr);
            if (count($rejectedArr) > 0) {
                $rejectedJson = json_encode($rejectedArr);
            } else {
                $rejectedJson = null;
            }
            if (!$request->rejection_note) {
                $request->rejection_note = '';
            } else {
                $request->rejection_note = $request->previous_remarks . ' || ' . $request->rejection_note . ' ( ' . Auth::user()->name . ' ) ';
            }

            $hubActivitySummery                   = new HubstaffActivitySummary;
            $hubActivitySummery->user_id          = $user_id;
            $hubActivitySummery->date             = $request->date;
            $hubActivitySummery->tracked          = $totalTracked;
            $hubActivitySummery->user_requested   = $approved;
            $hubActivitySummery->accepted         = $approved;
            $hubActivitySummery->rejected         = $rejected;
            $hubActivitySummery->approved_ids     = $approvedJson;
            $hubActivitySummery->rejected_ids     = $rejectedJson;
            $hubActivitySummery->sender           = Auth::user()->id;
            $hubActivitySummery->receiver         = $forword_to;
            $hubActivitySummery->forworded_person = $request->forworded_person;
            $hubActivitySummery->rejection_note   = $request->rejection_note;
            $hubActivitySummery->save();

            // $hubActivitySummery = HubstaffActivitySummary::where('date',$request->date)->where('user_id',$user_id)->first();
            // if($hubActivitySummery) {
            //     $hubActivitySummery->tracked = $totalTracked;
            //     $hubActivitySummery->accepted = $approved;
            //     $hubActivitySummery->rejected = $rejected;
            //     $hubActivitySummery->rejection_note = $request->rejection_note;
            //     $hubActivitySummery->save();
            // }
            // else {
            //     $hubActivitySummery = new HubstaffActivitySummary;
            //     $hubActivitySummery->user_id = $user_id;
            //     $hubActivitySummery->date =  $request->date;
            //     $hubActivitySummery->tracked = $totalTracked;
            //     $hubActivitySummery->accepted = $approved;
            //     $hubActivitySummery->rejected = $rejected;
            //     $hubActivitySummery->rejection_note = $request->rejection_note;
            //     $hubActivitySummery->save();
            // }

            return response()->json([
                'totalApproved' => $approved,
            ], 200);
        }
        return response()->json([
            'message' => 'Can not update data',
        ], 500);
    }

    public function NotesHistory( Request $request ){
        $history = HubstaffTaskNotes::orderBy("id","desc")->where('task_id',request('id'))->get();
        return response()->json( ["code" => 200 , "data" => $history] );
    }

    public function saveNotes( Request $request ){
        if( $request->notes_field ){
            $notesArr = [];
            foreach ($request->notes_field as $key => $value) {
                $notesArr[] = array(
                    'task_id' => $key,
                    'notes' => $value,
                    'date' => date('Y-m-d'),
                );    
            }
            HubstaffTaskNotes::insert( $notesArr );
        }

        return response()->json( ["code" => 200 , "message" => 'success'] );
    }

    public function finalSubmit(Request $request)
    {
        $approvedArr = [];
        $rejectedArr = [];
        $pendingArr = [];
        $approved    = 0;
        $pending    = 0;
        $member      = HubstaffMember::where('hubstaff_user_id', $request->user_id)->first();

        if (!$member) {
            return response()->json([
                'message' => 'Hubstaff member not mapped with erp',
            ], 500);
        }
        if (!$member->user_id) {
            return response()->json([
                'message' => 'Hubstaff member not mapped with erp',
            ], 500);
        }

        if ( empty($request->activities)) {
            return response()->json([
                'message' => 'Please choose at least one record',
            ], 500);
        }

        
        if( $request->notes_field ){
            $notesArr = [];
            foreach ($request->notes_field as $key => $value) {
                $notesArr[] = array(
                    'task_id' => $key,
                    'notes' => $value,
                    'date' => date('Y-m-d'),
                );    
            }
            HubstaffTaskNotes::insert( $notesArr );
        }


        $rejection_note = '';
        $prev           = '';
        if ($request->previous_remarks) {
            $prev = $request->previous_remarks . ' || ';
        }

        $rejection_note = $prev . $request->rejection_note;
        if ($rejection_note != '') {
            $rejection_note = $rejection_note . ' ( ' . Auth::user()->name . ' ) ';
        }

        if ($request->activities && count($request->activities) > 0) {

            $dateWise = [];
            foreach ($request->activities as $id) {
                $hubActivity = HubstaffActivity::where('id', $id)->first();
                $hubActivity->update(['status' => $request->status]);

                if( $request->status == '2' ){
                    $pending      = $pending + $hubActivity->tracked;
                    $pendingArr[] = $id;
                }else{
                    $approved               = $approved + $hubActivity->tracked;
                    $approvedArr[]          = $id;
                }
                
                if($request->isTaskWise) {
                    $superDate              = date("Y-m-d", strtotime($hubActivity->starts_at));
                    $dateWise[$superDate][] = $hubActivity;
                }
            }

            // started to check date wiser
            if (!empty($dateWise)) {
                $totalApproved = 0;
                $totalPending = 0;
                foreach ($dateWise as $dk => $dateW) {
                    if (!empty($dateW)) {
                        $approvedArr = [];
                        $pendingArr = [];
                        $approved    = 0;
                        $pending    = 0;
                        $totalTracked    = 0;

                        $query = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')
                            ->whereDate('hubstaff_activities.starts_at', $dk)
                            ->where('hubstaff_activities.user_id', $request->user_id);

                        $totalTracked = $query->sum('tracked');
                        $activity     = $query->select('hubstaff_members.user_id')->first();
                        $user_id      = $activity->user_id;


                        $hubActivitySummery = HubstaffActivitySummary::where('user_id', $user_id)->where('date', $dk)->first();
                        $approveIDs = [];
                        $rejectedIds = [];
                        $pendingIds = [];
                        if($hubActivitySummery) {
                            $approveIDs = json_decode($hubActivitySummery->approved_ids);
                            $rejectedIds = json_decode($hubActivitySummery->rejected_ids);
                            $pendingIds = json_decode($hubActivitySummery->pending_ids);
                            if(empty($pendingIds)) {
                                $pendingIds = [];
                            }
                            if(empty($rejectedIds)) {
                                $rejectedIds = [];
                            }
                            if(empty($approveIDs)) {
                                $approveIDs = [];
                            }
                        }

                        foreach ($dateW as $dw) {
                            if(!in_array($dw->id, $approveIDs) && !in_array($dw->id, $rejectedIds) && !in_array($dw->id, $pendingIds)) {
                                $dw->update(['status' => $request->status]);
                                if( $request->status == '2' ){
                                    $pending      = $pending + $dw->tracked;
                                    $pendingArr[] = $dw->id;
                                }else{
                                    $approved      = $approved + $dw->tracked;
                                    $approvedArr[] = $dw->id;
                                }
                            }
                        }

                        $totalApproved += $approved;
                        $totalPending += $pending;

                        $approvedJson = null;
                        $pendingJson = null;
                        if (count($approvedArr) > 0) {
                            $approvedJson = json_encode($approvedArr);
                        }
                        if (count($pendingArr) > 0) {
                            $pendingJson = json_encode($pendingArr);
                        }

                        

                        if ($hubActivitySummery) {

                            $aprids = array_merge($approveIDs, $approvedArr);
                            $pendids = array_merge($pendingIds, $pendingArr);

                            $hubActivitySummery->tracked      = $totalTracked;
                            $hubActivitySummery->accepted     = $hubActivitySummery->accepted + $approved;
                            $hubActivitySummery->pending      = $hubActivitySummery->pending + $pending;
                            $hubActivitySummery->approved_ids = json_encode($aprids);
                            $hubActivitySummery->pending_ids  = json_encode($pendids);
                            $hubActivitySummery->sender       = Auth::user()->id;
                            $hubActivitySummery->receiver     = Auth::user()->id;
                            $hubActivitySummery->rejection_note = $rejection_note.PHP_EOL.$hubActivitySummery->rejection_note;
                            $hubActivitySummery->save();
                        } else {
                            $hubActivitySummery                   = new HubstaffActivitySummary;
                            $hubActivitySummery->user_id          = $user_id;
                            $hubActivitySummery->date             = $dk;
                            $hubActivitySummery->tracked          = $totalTracked;
                            $hubActivitySummery->user_requested   = $approved;
                            $hubActivitySummery->accepted         = $approved;
                            $hubActivitySummery->pending          = $pending;
                            $hubActivitySummery->approved_ids     = $approvedJson;
                            $hubActivitySummery->pending_ids      = $pendingJson;
                            $hubActivitySummery->sender           = Auth::user()->id;
                            $hubActivitySummery->receiver         = Auth::user()->id;
                            $hubActivitySummery->forworded_person = 'admin';
                            $hubActivitySummery->final_approval   = 1;
                            $hubActivitySummery->rejection_note = $rejection_note;
                            $hubActivitySummery->save();
                        }
                    }
                }

                return response()->json([
                    'totalApproved' => (float)$totalApproved / 60,
                ], 200);
            } else {
                $query = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->whereDate('hubstaff_activities.starts_at', $request->date)->where('hubstaff_activities.user_id', $request->user_id);

                $totalTracked = $query->sum('tracked');
                $activity     = $query->select('hubstaff_members.user_id')->first();
                $user_id      = $activity->user_id;
                $rejected     = $totalTracked;
                $rejectedArr  = $query  = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->whereDate('hubstaff_activities.starts_at', $request->date)->where('hubstaff_activities.user_id', $request->user_id)->pluck('hubstaff_activities.id')->toArray();
            }

        } else {
            $query = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')
                ->whereDate('hubstaff_activities.starts_at', $request->date)
                ->where('hubstaff_activities.user_id', $request->user_id);

            $totalTracked = $query->sum('tracked');
            $activity     = $query->select('hubstaff_members.user_id')->first();
            $user_id      = $activity->user_id;
            $rejected     = $totalTracked;
            $rejectedArr  = $query  = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')
                ->whereDate('hubstaff_activities.starts_at', $request->date)
                ->where('hubstaff_activities.user_id', $request->user_id)
                ->pluck('hubstaff_activities.id')
                ->toArray();
        }

        if (count($approvedArr) > 0) {
            $approvedJson = json_encode($approvedArr);
        } else {
            $approvedJson = null;
        }

        if (count($rejectedArr) > 0) {
            $rejectedJson = json_encode($rejectedArr);
        } else {
            $rejectedJson = null;
        }

        if (count($pendingArr) > 0) {
            $pendingJson = json_encode($pendingArr);
        } else {
            $pendingJson = null;
        }

        $hubActivitySummery = HubstaffActivitySummary::where('user_id', $user_id)->where('date', $request->date)->first();
        $unApproved = 0;
        $unPending  = 0;
        
        foreach ($request->activities as $index => $id) {
            $hubActivity = HubstaffActivity::where('id', $id)->first();
            
            if( $request->status == '2' ){
                if($hubActivitySummery) {
                    $approved = $hubActivitySummery->accepted;
                    if( $hubActivitySummery->accepted > 0 && $hubActivitySummery->approved_ids ){
                        $arrayIds = json_decode($hubActivitySummery->approved_ids);
                        if( in_array( $id, $arrayIds ) ){
                            $unApproved = $unApproved + $hubActivity->tracked;
                        }
                    }
                }
            }
            if( $request->status == '1' ){
                if($hubActivitySummery) {
                    $pending = $hubActivitySummery->pending;
                    if( $hubActivitySummery->pending > 0 && $hubActivitySummery->pending_ids ){
                        $arrayIds = json_decode($hubActivitySummery->pending_ids);
                        if(  in_array( $id, $arrayIds ) ){
                            if($index == 0){
                                $unPending = $hubActivitySummery->pending;
                            }
                            $unPending = $unPending + $hubActivity->tracked;
                        }
                    }
                }
            }

        }

        if( $unApproved > 0){
            $approved = $approved - $unApproved;
            $approved = ( $approved < 0 ) ? 0 : $approved ;
        }
        
        if( $unPending > 0){
            $pending = $pending - $unPending;
            $pending = ( $pending < 0 ) ? 0 : $pending; 
        }
        
       
        if ($hubActivitySummery) {
            // if( $request->status = '2' ){  
                $approved_ids = json_decode( $hubActivitySummery->approved_ids );
                if( $approved_ids && $pendingArr ){
                    $approvedJson = json_encode( array_values($this->array_except( $approved_ids, json_decode($pendingJson) ) ) );
                }
            // }else{
                $pending_ids = json_decode( $hubActivitySummery->pending_ids );
                if( $pending_ids && $approvedArr){
                    $pendingJson = json_encode( array_values( $this->array_except( $pending_ids, json_decode($approvedJson) ) ) );
                }
            // }
            
            $hubActivitySummery->tracked        = $totalTracked;
            $hubActivitySummery->accepted       = $approved;
            $hubActivitySummery->rejected       = $rejected;
            $hubActivitySummery->pending        = $pending;
            $hubActivitySummery->approved_ids   = $approvedJson;
            $hubActivitySummery->rejected_ids   = $rejectedJson;
            $hubActivitySummery->pending_ids    = $pendingJson;
            $hubActivitySummery->sender         = Auth::user()->id;
            $hubActivitySummery->receiver       = Auth::user()->id;
            $hubActivitySummery->rejection_note = $rejection_note;
            $hubActivitySummery->save();
        } else {
            $hubActivitySummery                   = new HubstaffActivitySummary;
            $hubActivitySummery->user_id          = $user_id;
            $hubActivitySummery->date             = $request->date;
            $hubActivitySummery->tracked          = $totalTracked;
            $hubActivitySummery->user_requested   = $approved;
            $hubActivitySummery->accepted         = $approved;
            $hubActivitySummery->rejected         = $rejected;
            $hubActivitySummery->pending          = $pending;
            $hubActivitySummery->approved_ids     = $approvedJson;
            $hubActivitySummery->rejected_ids     = $rejectedJson;
            $hubActivitySummery->pending_ids      = $pendingJson;
            $hubActivitySummery->sender           = Auth::user()->id;
            $hubActivitySummery->receiver         = Auth::user()->id;
            $hubActivitySummery->forworded_person = 'admin';
            $hubActivitySummery->final_approval   = 1;
            $hubActivitySummery->rejection_note   = $rejection_note;
            $hubActivitySummery->save();
        }

        $requestData = new Request();
        $requestData->setMethod('POST');
        $min     = $approved / 60;
        $min     = number_format($min, 2);
        $message = 'Hi, your time for ' . $request->date . ' has been approved. Total approved time is ' . $min . ' minutes.';
        $requestData->request->add(['summery_id' => $hubActivitySummery->id, 'message' => $message, 'status' => 1]);
        app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'time_approval');

        return response()->json([
            'totalApproved' => $approved,
        ], 200);
        return response()->json([
            'message' => 'Can not update data',
        ], 500);
    }

    private function array_except($array, $keys){
        foreach($array as $key => $value){
            if( in_array( $value , $keys) ){
                unset($array[$key]);
            }
        }
        return $array;
    }

    public function approvedPendingPayments(Request $request)
    {
        $title      = "Approved pending payments";
        $start_date = $request->start_date ? $request->start_date : date("Y-m-d");
        $end_date   = $request->end_date ? $request->end_date : date("Y-m-d");
        $user_id    = $request->user_id ? $request->user_id : null;
        if ($user_id) {
            $activityUsers = DB::select(DB::raw("select system_user_id, sum(tracked) as total_tracked,starts_at from (select a.* from (SELECT hubstaff_activities.id,hubstaff_activities.user_id,cast(hubstaff_activities.starts_at as date) as starts_at,hubstaff_activities.status,hubstaff_activities.paid,hubstaff_members.user_id as system_user_id,hubstaff_activities.tracked FROM `hubstaff_activities` left outer join hubstaff_members on hubstaff_members.hubstaff_user_id = hubstaff_activities.user_id where hubstaff_activities.status = 1 and hubstaff_activities.paid = 0 and hubstaff_members.user_id = " . $user_id . ") as a left outer join payment_receipts on a.system_user_id = payment_receipts.user_id where a.starts_at <= payment_receipts.date) as b group by starts_at,system_user_id"));
        } else {
            $activityUsers = DB::select(DB::raw("select system_user_id, sum(tracked) as total_tracked,starts_at from (select a.* from (SELECT hubstaff_activities.id,hubstaff_activities.user_id,cast(hubstaff_activities.starts_at as date) as starts_at,hubstaff_activities.status,hubstaff_activities.paid,hubstaff_members.user_id as system_user_id,hubstaff_activities.tracked FROM `hubstaff_activities` left outer join hubstaff_members on hubstaff_members.hubstaff_user_id = hubstaff_activities.user_id where hubstaff_activities.status = 1 and hubstaff_activities.paid = 0) as a left outer join payment_receipts on a.system_user_id = payment_receipts.user_id where a.starts_at <= payment_receipts.date) as b group by starts_at,system_user_id"));
        }

        foreach ($activityUsers as $activity) {
            $user              = User::find($activity->system_user_id);
            $latestRatesOnDate = UserRate::latestRatesOnDate($activity->starts_at, $user->id);
            if ($activity->total_tracked > 0 && $latestRatesOnDate && $latestRatesOnDate->hourly_rate > 0) {
                $total            = ($activity->total_tracked / 60) / 60 * $latestRatesOnDate->hourly_rate;
                $activity->amount = number_format($total, 2);
            } else {
                $activity->amount = 0;
            }
            $activity->userName = $user->name;
        }
        $users = User::all()->pluck('name', 'id')->toArray();
        return view("hubstaff.activities.approved-pending-payments", compact('title', 'activityUsers', 'start_date', 'end_date', 'users', 'user_id'));
    }

    public function submitPaymentRequest(Request $request)
    {
        $this->validate($request, [
            'amount'    => 'required',
            'user_id'   => 'required',
            'starts_at' => 'required',
        ]);

        $payment_receipt                 = new PaymentReceipt;
        $payment_receipt->date           = date('Y-m-d');
        $payment_receipt->rate_estimated = $request->amount;
        $payment_receipt->status         = 'Pending';
        $payment_receipt->user_id        = $request->user_id;
        $payment_receipt->remarks        = $request->note;
        $payment_receipt->save();

        $hubstaff_user_id = HubstaffMember::where('user_id', $request->user_id)->first()->hubstaff_user_id;

        HubstaffActivity::whereDate('starts_at', $request->starts_at)->where('user_id', $hubstaff_user_id)->where('status', 1)->where('paid', 0)->update(['paid' => 1]);
        return redirect()->back()->with('success', 'Successfully submitted');
    }

    public function submitManualRecords(Request $request)
    {
        if ($request->starts_at && $request->starts_at != '' && $request->total_time > 0 && $request->task_id > 0) {
            $member = HubstaffMember::where('user_id', Auth::user()->id)->first();
            if ($member) {
                $firstId = HubstaffActivity::orderBy('id', 'asc')->first();
                if ($firstId) {
                    $previd = $firstId->id - 1;
                } else {
                    $previd = 1;
                }
                // if($request->task_type == 'devtask') {
                //     $devtask = DeveloperTask::find($request->task_id);
                //     if($devtask) {
                //         if($request->role == 'developer') {
                //             $devtask->hubstaff_task_id = $request->task_id;
                //         }
                //         else if($request->role == 'lead') {
                //             $devtask->lead_hubstaff_task_id = $request->task_id;
                //         }
                //         else if($request->role == 'tester') {
                //             $devtask->tester_hubstaff_task_id = $request->task_id;
                //         }
                //         else {
                //             $devtask->hubstaff_task_id = $request->task_id;
                //         }
                //         $devtask->save();
                //     }
                // }

                // if($request->task_type == 'devtask') {
                //     $task = Task::find($request->task_id);
                //     if($task) {
                //         if($request->role == 'developer') {
                //             $task->hubstaff_task_id = $request->task_id;
                //         }
                //         else if($request->role == 'lead') {
                //             $task->lead_hubstaff_task_id = $request->task_id;
                //         }
                //         else if($request->role == 'tester') {
                //             $task->tester_hubstaff_task_id = $request->task_id;
                //         }
                //         else {
                //             $task->hubstaff_task_id = $request->task_id;
                //         }
                //         $task->save();
                //     }
                // }

                if (!$request->user_notes) {
                    $request->user_notes = '';
                }
                $activity             = new HubstaffActivity;
                $activity->id         = $previd;
                $activity->task_id    = $request->task_id;
                $activity->user_id    = $member->hubstaff_user_id;
                $activity->starts_at  = $request->starts_at;
                $activity->tracked    = $request->total_time * 60;
                $activity->keyboard   = 0;
                $activity->mouse      = 0;
                $activity->overall    = 0;
                $activity->status     = 0;
                $activity->is_manual  = 1;
                $activity->user_notes = $request->user_notes;
                $activity->save();
                return response()->json(["message" => 'Successful'], 200);
            }
            return response()->json(["message" => 'Hubstaff member not found'], 500);
        } else {
            return response()->json(["message" => 'Fill all the data first'], 500);
        }
    }
    public function fetchActivitiesFromHubstaff(Request $request)
    {
        if (!$request->hub_staff_start_date || $request->hub_staff_start_date == '' || !$request->hub_staff_end_date || $request->hub_staff_end_date == '' ) {
            return response()->json(['message' => 'Select date'], 500);
        }
        
        $starts_at  = $request->hub_staff_start_date;
        $ends_at    = $request->hub_staff_end_date;
        $userID     = $request->get("fetch_user_id",Auth::user()->id);
        $member     = $hubstaff_user_id    = HubstaffMember::where('user_id', $userID)->first();

        if ($member) {
            $hubstaff_user_id = $member->hubstaff_user_id;
        } else {
            return response()->json(['message' => 'Hubstaff member not found'], 500);
        }
        $timeReceived = 0;
        try {
            $this->init(getenv('HUBSTAFF_SEED_PERSONAL_TOKEN'));

            $now = time();

            $startString = $starts_at;
            $endString   = $ends_at;
            $userIds     = $hubstaff_user_id;
            $userIds     = explode(",", $userIds);
            $userIds     = array_filter($userIds);

            $start = strtotime($startString." 00:00:00" . ' UTC');
            $now   = strtotime($endString." 23:59:59" . ' UTC');
            
           $diff = $now - $start;
           $dayDiff = round($diff / 86400);
           if($dayDiff > 7 ) {
              return response()->json(['message' => 'Can not fetch activities more then week'], 500);  
           }

            $activities = $this->getActivitiesBetween(gmdate('c', $start), gmdate('c', $now), 0, [], $userIds);
            if($activities == false) {
               return response()->json(['message' => 'Can not fetch activities as no activities found'], 500);   
            }
            if(!empty($activities)) {
                foreach ($activities as $id => $data) {
                    HubstaffActivity::updateOrCreate(['id' => $id,],
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
                    $timeReceived += $data['tracked'];
                }
            }
            
        } catch (\Exception $e) {
           return response()->json(['message' => $e->getMessage()], 500);
        }

        $timeReceived = number_format(($timeReceived / 60),2,'.','');

        return response()->json(['message' => 'Fetched activities total time : '.$timeReceived], 200);
    }

    /*
     * process to Add Efficiency
     *
     *@params Request $request
     *@return
     */
    public function AddEfficiency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'efficiency' => 'required',
            'user_id'    => 'required',
            'type'       => 'required',
            'date'       => 'required',
            'hour'       => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 500);

        } else {
            // $requestArr = $request->all();

            // if(Auth::user()->isAdmin())
            // {
            //     $admin_input = (isset($requestArr['efficiency'])) ? $requestArr['efficiency'] : '';
            //     $user_input =  '';

            // }else
            // {
            //     $admin_input = "";
            //     $user_input = (isset($requestArr['efficiency'])) ? $requestArr['efficiency'] : '';

            // }

            // $user_id = (isset($requestArr['user_id'])) ? $requestArr['user_id'] : '';
            $admin_input = null;
            $user_input  = null;
            if ($request->type == 'admin') {
                $admin_input = $request->efficiency;
            } else {
                $user_input = $request->efficiency;
            }
            $insert_array = array(
                'user_id'     => $request->user_id,
                'admin_input' => $admin_input,
                'user_input'  => $user_input,
                'date'        => $request->date,
                'time'        => $request->hour,
            );

            $userObj = HubstaffTaskEfficiency::where('user_id', $request->user_id)->where('date', $request->date)->where('time', $request->hour)->first();
            if ($userObj) {
                if ($request->type == 'admin') {
                    $user_input = $userObj->user_input;
                } else {
                    $admin_input = $userObj->admin_input;
                }
                $userObj->update(['admin_input' => $admin_input, 'user_input' => $user_input]);
            } else {
                HubstaffTaskEfficiency::create($insert_array);
            }
        }

        return response()->json(['message' => 'Successful'], 200);
    }

    public function taskActivity(Request $request)
    {
        $task_id = $request->task_id;
        $user_id = $request->user_id;

        /*$query = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->where('hubstaff_activities.task_id', '=',$task_id);

        $activities  = $query->select(DB::raw("
        hubstaff_activities.user_id,
        SUM(hubstaff_activities.tracked) as total_tracked,
        DATE(hubstaff_activities.starts_at) as date,
        hubstaff_members.user_id as system_user_id
        ")
        )->where("task_id",$task_id)
        ->where("hubstaff_activities.user_id",$user_id)
        ->groupBy('task_id')
        ->orderBy('date','desc')
        ->get();*/

        // check the task created date
        $task = \App\DeveloperTask::where(function ($q) use ($task_id) {
            $q->orWhere("hubstaff_task_id", $task_id)->orWhere("lead_hubstaff_task_id", $task_id)->orWhere("team_lead_hubstaff_task_id", $task_id)->orWhere("tester_hubstaff_task_id", $task_id);
        })->first();

        if (!$task) {
            $task = \App\Task::where(function ($q) use ($task_id) {
                $q->orWhere("hubstaff_task_id", $task_id)->orWhere("lead_hubstaff_task_id", $task_id);
            })->first();
        }

        $date = ($task) ? $task->created_at : date("1998-02-02");

        $activityrecords = DB::select(DB::raw("SELECT CAST(starts_at as date) AS OnDate,  SUM(tracked) AS total_tracked, hour( starts_at ) as onHour,status
        FROM hubstaff_activities where task_id = '" . $task_id . "' and user_id = " . $user_id . "
        GROUP BY hour( starts_at ) , day( starts_at ) order by OnDate desc"));
        // $activityrecords  = HubstaffActivity::whereDate('hubstaff_activities.starts_at',$request->date)->where('hubstaff_activities.user_id',$request->user_id)->select('hubstaff_activities.*')->get();

        $admins = User::join('role_user', 'role_user.user_id', 'users.id')->join('roles', 'roles.id', 'role_user.role_id')
            ->where('roles.name', 'Admin')->select('users.name', 'users.id')->get();

        $teamLeaders = [];

        $users = User::select('name', 'id')->get();

        $hubstaff_member    = HubstaffMember::where('hubstaff_user_id', $user_id)->first();
        $hubActivitySummery = null;
        if ($hubstaff_member) {
            $system_user_id     = $hubstaff_member->user_id;
            $hubActivitySummery = HubstaffActivitySummary::whereDate('date', ">=", $date)->where('user_id', $system_user_id)->orderBy('created_at', 'DESC')->get();
            $teamLeaders        = User::join('teams', 'teams.user_id', 'users.id')->join('team_user', 'team_user.team_id', 'teams.id')->where('team_user.user_id', $system_user_id)->distinct()->select('users.name', 'users.id')->get();
        }

        $approved_ids = [0];
        $pending_ids = [0];
        if ($hubActivitySummery) {
            if (!$hubActivitySummery->isEmpty()) {
                foreach ($hubActivitySummery as $hubA) {
                    if (isset($hubA->approved_ids)) {
                        $approved_idsArr = json_decode($hubA->approved_ids);
                        if (!empty($approved_idsArr) && is_array($approved_idsArr)) {
                            $approved_ids = array_merge($approved_ids, $approved_idsArr);
                        }
                    }
                    if ($hubA->pending_ids) {
                        $pending_ids = json_decode($hubA->pending_ids);
                    }
                }
            }
        }

        foreach ($activityrecords as $record) {

            $activities = DB::select(DB::raw("SELECT hubstaff_activities.* FROM hubstaff_activities where task_id = " . $task_id . " and DATE(starts_at) = '" . $record->OnDate . "' and user_id = " . $user_id . " and hour(starts_at) = " . $record->onHour . ""));

            $totalApproved = 0;
            $isAllSelected = 0;
            $totalPending = 0;

            foreach ($activities as $a) {

                if (in_array($a->id, $approved_ids)) {

                    $isAllSelected = $isAllSelected + 1;
                    $a->status     = 1;

                    $hubAct = HubstaffActivity::where('id', $a->id)->first();
                    if ($hubAct) {
                        $totalApproved = $totalApproved + $a->tracked;
                    }

                    $a->totalApproved = $a->tracked;
                } else {
                    $a->status        = 0;
                    $a->totalApproved = 0;
                }

                if (in_array($a->id, $pending_ids)) {
                    $isAllSelected = $isAllSelected + 1;
                    $a->status     = 2;
                    $hubAct        = HubstaffActivity::where('id', $a->id)->first();
                    if ($hubAct) {
                        $totalPending = $totalPending + $a->tracked;
                    }
                    $a->totalPending = $a->tracked;
                } else {
                    $a->status        = 0;
                    $a->totalPending = 0;
                }

                $taskSubject = '';
                if ($a->task_id) {
                    if ($a->is_manual) {
                        $task = DeveloperTask::where('id', $a->task_id)->first();
                        if ($task) {
                            $taskSubject = '#DEVTASK-' . $task->id . '-' . $task->subject;
                        } else {
                            $task = Task::where('id', $a->task_id)->first();
                            if ($task) {
                                $taskSubject = '#TASK-' . $task->id . '-' . $task->task_subject;
                            }
                        }
                        $taskStatus = $task->status ?? null;
                    } else {
                        $task = DeveloperTask::where('hubstaff_task_id', $a->task_id)->orWhere('lead_hubstaff_task_id', $a->task_id)->first();
                        if ($task) {
                            $taskSubject = '#DEVTASK-' . $task->id . '-' . $task->subject;
                        } else {
                            $task = Task::where('hubstaff_task_id', $a->task_id)->orWhere('lead_hubstaff_task_id', $a->task_id)->first();
                            if ($task) {
                                $taskSubject = '#TASK-' . $task->id . '-' . $task->task_subject;
                            }
                        }
                        $taskStatus = $task->status ?? null;
                    }

                }

                $a->taskSubject = $taskSubject;
                $a->taskStatus = $taskStatus ?? null;
            }
            if ($isAllSelected == count($activities)) {
                $record->sample = 1;
            } else {
                $record->sample = 0;
            }
            $record->activities    = $activities;
            $record->totalApproved = $totalApproved;
            $record->totalPending = $totalPending;
        }
        $user_id = $request->user_id;
        $isAdmin = false;
        if (Auth::user()->isAdmin()) {
            $isAdmin = true;
        }
        $isTeamLeader = false;
        $isLeader     = Team::where('user_id', Auth::user()->id)->first();
        if ($isLeader) {
            $isTeamLeader = true;
        }
        $taskOwner = false;
        if (!$isAdmin && !$isTeamLeader) {
            $taskOwner = true;
        }
        //$date = $request->date;

        $member = HubstaffMember::where('hubstaff_user_id', $request->user_id)->first();
        $isTaskWise = true;
        return view("hubstaff.activities.activity-records", compact('activityrecords', 'user_id', 'date', 'hubActivitySummery', 'teamLeaders', 'admins', 'users', 'isAdmin', 'isTeamLeader', 'taskOwner', 'member','isTaskWise'));

    }
    
    public function activityReport(Request $request)
    {
        $user_id = $request->user_id;
        $activity = HubstaffActivityByPaymentFrequency::where('user_id',$user_id)->get();
        return response()->json(['status' => true, 'data' => $activity]);
    
    }
    public function activityReportDownload(Request $request)
    {
        $file_path = storage_path($request->file);
        return response()->download($file_path);
    }
}
