<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\ColdLeads;
use App\{User,UserPemfileHistory};
use App\UserRate;
use App\Role;
use App\Permission;
use App\ApiKey;
use App\Customer;
use Auth;
use App\Helpers;
use App\Task;
use PragmaRX\Tracker\Vendor\Laravel\Models\Session;
use \Carbon\Carbon;
use DateTime;
use App\Hubstaff\HubstaffActivity;
use App\Hubstaff\HubstaffPaymentAccount;
use App\Payment;
use App\PaymentMethod;
use App\Team;
use App\DeveloperTask;
use App\UserAvaibility;
use App\PermissionRequest;
use App\UserSysyemIp;
use DB;
use Hash;
use Illuminate\Support\Arr;
use App\UserFeedbackCategory;
use App\UserFeedbackStatus;


class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $req)
    {
        $title = "User management";
        $permissionRequest = PermissionRequest::count();
        $statusList = \DB::table("task_statuses")->select("name","id")->get()->toArray();


        $shell_list = shell_exec("bash " . getenv('DEPLOYMENT_SCRIPTS_PATH'). "/webaccess-firewall.sh -f list");
        $final_array = [];
        if($shell_list != ''){
            $lines=explode(PHP_EOL,$shell_list);
            $final_array = [];
            foreach($lines as $line){
                $values = [];
                $values=explode(' ',$line);
                array_push($final_array,$values);
            }
        }
        
        
        if(!empty($final_array))
        {
            foreach(array_reverse($final_array) as $values){
                
                $index   = $values[0]??0;
                $ip      = $values[1]??0;
                $comment = $values[2]??0;
                
                $where = ['ip' => $ip];

                $insert = [
                    'index_txt'       => $index??'-',
                    'ip'              => $ip??'-',
                    'notes'           => $comment??'-',
                    // 'user_id'         => Auth::id(),
                    // 'other_user_name' => $comment,
                ];
                $userips = UserSysyemIp::updateOrCreate($where,$insert);   
            }    
        }
        


        $usersystemips = UserSysyemIp::with('user')->get();
        
        $userlist = User::orderBy('name')->where('is_active',1)->get();

        // $user = new feedback_table;
        // $user->catagory=$req->input('catagory');
        // //     // $user->adminrespose=$req->input('adminrespose');
        // //     // $user->userrespose=$req->input('userrespose');
        // //     // $user->status=$req->input('status');
        // //     // $user->histry=$req->input('histry');
        // $user->save();

        return view('usermanagement::index', compact('title','permissionRequest','statusList','usersystemips','userlist'));
    }


    public function cat_name( Request $req){
        $title = "User management";
        $permissionRequest = PermissionRequest::count();
        $statusList = \DB::table("task_statuses")->select("name","id")->get()->toArray();

        $usersystemips = UserSysyemIp::with('user')->get();
        
        $userlist = User::orderBy('name')->where('is_active',1)->get();

        // $user = new feedback_table;
        // $user->catagory=$req->input('catagory');
        //     // $user->adminrespose=$req->input('adminrespose');
        //     // $user->userrespose=$req->input('userrespose');
        //     // $user->status=$req->input('status');
        //     // $user->histry=$req->input('histry');
        // $user->save();
        
  

        return view('usermanagement::index', compact('title','permissionRequest','statusList','usersystemips','userlist'));
      
    
    }

    public function permissionRequest( Request $request ){
        $history = PermissionRequest::leftjoin('users','permission_request.user_id','users.id')->orderBy("permission_request.id","desc")->get();
        return response()->json( ["code" => 200 , "data" => $history] );
    }

    public function deletePermissionRequest( Request $request ){
        $history = PermissionRequest::query()->delete();
        return response()->json( ["code" => 200 , "data" => 'Permission Deleted Successfully'] );
    }

    public function todayTaskHistory(Request $request)
    {
        $date = "'%".date('Y-m-d')."%'";   
        // $history = HubstaffActivity::select('users.name','developer_tasks.subject','developer_tasks.id as devtaskId','hubstaff_activities.starts_at' ,\DB::raw("SUM(tracked) as day_tracked"))
        //           ->join('hubstaff_members','hubstaff_activities.user_id','hubstaff_members.hubstaff_user_id')
        //           ->join('users','hubstaff_members.user_id','users.id')
        //           ->join('developer_tasks','hubstaff_activities.task_id','developer_tasks.hubstaff_task_id')
        //           ->whereDate('hubstaff_activities.starts_at',date('Y-m-d'))
        //           ->groupBy('hubstaff_activities.starts_at','hubstaff_activities.user_id');
        //           if( !empty( $request->id ) ){
        //             $history->where('users.id',$request->id);
        //           }
        // $history =  $history->orderBy("hubstaff_activities.id","desc")->get(); 
        $history = DB::select("SELECT users.name, developer_tasks.subject, developer_tasks.id as devtaskId,tasks.id as task_id,tasks.task_subject as task_subject,  hubstaff_activities.starts_at, SUM(tracked) as day_tracked 
                  FROM `users` 
                  JOIN hubstaff_members ON hubstaff_members.user_id=users.id 
                  JOIN hubstaff_activities ON hubstaff_members.hubstaff_user_id=hubstaff_activities.user_id 
                  LEFT JOIN developer_tasks ON hubstaff_activities.task_id=developer_tasks.hubstaff_task_id 
                  LEFT JOIN tasks ON hubstaff_activities.task_id=tasks.hubstaff_task_id 
                  WHERE ( (`hubstaff_activities`.`starts_at` LIKE " . $date . ") AND (developer_tasks.id is NOT NULL or tasks.id is not null) and hubstaff_activities.task_id > 0)
                    GROUP by hubstaff_activities.task_id
                    order by day_tracked desc ");
                 // WHERE (`hubstaff_activities`.`starts_at` LIKE " . $date . "  OR `hubstaff_activities`.`starts_at` is NULL ) group by users.id order by day_tracked desc ");
                 
                 //purpose : Add AND developer_tasks.id is NOT NULL in where condition ,  Remove group by users.id , Add left join task Table Old Query is Comment - DEVTASK-4256
        $filterList = [];
        if ( !empty( $history ) ) {
            foreach ($history as $key => $value) {
                $filterList[] = array(
                    'user_name' => $value->name,
                    'devtaskId' => empty($value->devtaskId) ? $value->task_id : $value->devtaskId,
                    'task'      => empty($value->devtaskId) ? $value->task_subject : $value->subject,
                    'date'      => $value->starts_at,
                    'tracked'   => number_format($value->day_tracked / 60,2,".",","),
                );
            }
        }
        return response()->json( ["code" => empty($filterList ) ? 500 : 200 , "data" => array($filterList)] );

    }
    public function taskActivity( Request $request ){
        $history = HubstaffActivity::select('users.name','developer_tasks.subject','hubstaff_activities.starts_at' ,\DB::raw("SUM(tracked) as day_tracked"))
                  ->leftjoin('hubstaff_members','hubstaff_activities.user_id','hubstaff_members.hubstaff_user_id')
                  ->leftjoin('users','hubstaff_members.user_id','users.id')
                  ->leftjoin('developer_tasks','hubstaff_activities.task_id','developer_tasks.hubstaff_task_id')
                  ->whereBetween('hubstaff_activities.starts_at', [date('Y-m-d', strtotime('-7 days')),date('Y-m-d',strtotime('+1 days'))])
                  ->groupBy('hubstaff_activities.starts_at','hubstaff_activities.user_id')
                  ->where('users.id',$request->id)
                  ->orderBy("hubstaff_activities.id","desc")->get();
        $filterList = [];
        foreach ($history as $key => $value) {
            $filterList = array(
                'user_name' => $value->name,
                'task'      => $value->subject,
                'date'      => $value->starts_at,
                'tracked'   => number_format($value->day_tracked / 60,2,".",","),
            );
        }
        return response()->json( ["code" => empty($filterList ) ? 500 : 200 , "data" => array($filterList)] );
    }

    public function modifiyPermission( Request $request )
    {   
        if ( $request->type == 'accept' ) {
            $user = User::findorfail($request->user);
            $user->permissions()->attach($request->permission);
            PermissionRequest::where('user_id',$request->user)->where('permission_id',$request->permission)->delete();

            // user need to send message
            //\App\ChatMessage::sendWithChatApi($act->phone_number, null, $userMessage);
            $permission = \App\Permission::find($request->permission);
            $permissionName = "";
            if($permission) {
                $permissionName = $permission->name;
            }

            $params = [];
            $params['user_id'] = $user->id;
            $params['message'] = "Your permission request has been approved for the permission :" .$permissionName;
            // send chat message
            $chat_message = \App\ChatMessage::create($params);
            // send
            app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'], false, $chat_message->id);


            return response()->json( ["code" => 200 , "data" => 'Permission added Successfully'] );       
        }
        if ( $request->type == 'reject' ) {
            PermissionRequest::where('user_id',$request->user)->where('permission_id',$request->permission)->delete();
            return response()->json( ["code" => 200 , "data" => 'Requets reject successfully'] );       
        }
        return response()->json( ["code" => 500, "data" => 'Something went wrong!'] );       
    }

    public function records(Request $request)
    {
        $user = new User;
        if(!Auth::user()->isAdmin()) {
            $user = $user->where('users.id',Auth::user()->id);
        }
       
        if($request->is_active == 1) {
            $user = $user->where('users.is_active',1);
        }
        if($request->is_active == 2) {
            $user = $user->where('users.is_active',0);
        }
        if($request->keyword != null) {
            $user = $user->where(function($q) use ($request) {
                $q->where("users.email","like","%".$request->keyword."%")
                ->orWhere("users.name","like","%".$request->keyword."%")
                ->orWhere("users.phone","like","%".$request->keyword."%");
            });
        }
    

        $user = $user->select(["users.*","hubstaff_activities.starts_at"])
                ->leftJoin('hubstaff_members', 'hubstaff_members.user_id', '=', 'users.id')
                ->leftJoin('hubstaff_activities', 'hubstaff_activities.user_id', '=', 'hubstaff_members.hubstaff_user_id')
                ->groupBy('users.id')
                ->orderBy('hubstaff_activities.starts_at','DESC')
                // ->orderBy('is_active','DESC')
                ->paginate(12);
        $limitchacter = 50;

        $items = [];
        $replies = null;
        if (!$user->isEmpty()) {
            foreach ($user as $u) {
                // dump($u->id);
                $currentRate = $u->latestRate;
                $team = Team::where('user_id', $u->id)->first();
                $user_in_team = 0;
                if($team) {
                    $u["team_leads"] = $team->users->count();
                    $u["team_members"] = $team->users->toArray();
                    $u["team"] = $team;
                    $user_in_team = 1;
                }

                $taskList = DB::select('
                select * from (
                    (SELECT tasks.id as task_id,tasks.task_subject as subject, tasks.task_details as details, tasks.approximate as approximate_time, tasks.due_date,tasks.deleted_at,tasks.assign_to as assign_to,tasks.status as status_falg,chat_messages.message as last_message, chat_messages.created_at as orderBytime, tasks.is_verified as cond, "TASK" as type,tasks.created_at as created_at,tasks.priority_no,tasks.is_flagged as has_flag  FROM tasks
                              LEFT JOIN
                               (SELECT MAX(id) AS max_id,
                                       task_id,
                                       message,
                                       created_at
                                FROM chat_messages
                                WHERE task_id > 0
                                GROUP BY task_id) m_max ON m_max.task_id = tasks.id
                            LEFT JOIN task_statuses ON task_statuses.id = tasks.status
                             LEFT JOIN chat_messages ON chat_messages.id = m_max.max_id
                              WHERE tasks.deleted_at IS NULL and tasks.is_statutory != 1 and tasks.is_verified is null and tasks.assign_to = '.$u->id.') 
                    
                    union  
                    
                    (
                        select developer_tasks.id as task_id, developer_tasks.subject as subject, developer_tasks.task as details, developer_tasks.estimate_minutes as approximate_time, developer_tasks.due_date as due_date,developer_tasks.deleted_at, developer_tasks.assigned_to as assign_to,developer_tasks.status as status_falg, chat_messages.message as last_message, chat_messages.created_at as orderBytime,"d" as cond, "DEVTASK" as type,developer_tasks.created_at as created_at,developer_tasks.priority_no,developer_tasks.is_flagged as has_flag from developer_tasks left join (SELECT MAX(id) as  max_id, issue_id, message,created_at  FROM  chat_messages where issue_id > 0  GROUP BY issue_id ) m_max on  m_max.issue_id = developer_tasks.id left join chat_messages on chat_messages.id = m_max.max_id where developer_tasks.status != "Done" and developer_tasks.deleted_at is null and developer_tasks.assigned_to = '.$u->id.'
                        
                        ) 
                    ) as c order by priority_no desc
                ');
 
            $pending_tasks =  0;
            $total_tasks = count($taskList);
            foreach($taskList as $t){
                if($t->has_flag != 1) $pending_tasks++;
            }
            //     $pending_tasks = Task::where('is_statutory', 0)
            // ->whereNull('is_completed')
            // ->Where('assign_to', $u->id)->count();

            $no_time_estimate = DeveloperTask::whereNull('estimate_minutes')->where('assigned_to', $u->id)->count();
            $overdue_task     = DeveloperTask::where('estimate_date', '>', date('Y-m-d'))->where('status', '!=', 'Done')->where('assigned_to', $u->id)->count();

            // $total_tasks = Task::where('is_statutory', 0)
            // ->Where('assign_to', $u->id)->count();
                $u["pending_tasks"] = $pending_tasks;
                $u["total_tasks"] = $total_tasks;
                $u['no_time_estimate'] = $no_time_estimate;
                $u['overdue_task'] = $overdue_task;

                $isMember = $u->teams()->first();
                if($isMember) {
                    $user_in_team = 1;
                }

                $u["user_in_team"] = $user_in_team;
                $u["hourly_rate"] = ($currentRate) ? $currentRate->hourly_rate : 0;
                $u["currency"]    = ($currentRate) ? $currentRate->currency : "USD";


                $u["yesterday_hrs"] = $u->yesterdayHrs();
                $u["isAdmin"] = $u->isAdmin();
                $u['is_online'] = $u->isOnline();

                if($u->approve_login == date('Y-m-d')) {
                    $u['already_approved'] = true;
                }
                else {
                    $u['already_approved'] = false;
                }

                $online_now = $u->lastOnline();
                if($online_now) {
                    $u["online_now"] =   \Carbon\Carbon::parse($online_now)->format('d-m H:i');
                }
                else {
                    $u["online_now"] = null;
                }

                $lastPaid = $u->payments()->orderBy('id','desc')->first();
                if($lastPaid) {
                    $lastPaidOn = $lastPaid->paid_upto;
                }
                else {
                    $query = HubstaffPaymentAccount::where('user_id',$u->id)->first();
                    if(!$query) {
                        $lastPaidOn = date("Y-m-d"); 
                    }
                    else {
                        $lastPaidOn =  date('Y-m-d',strtotime($query->billing_start . "-1 days"));
                    }
                }
                if($lastPaidOn) {
                    $u["previousDue"] = $u->previousDue($lastPaidOn);
                }
                else {
                    $u["previousDue"] = '';
                }
                
                $u["lastPaidOn"] = $lastPaidOn;
                
                if($u->payment_frequency == 'fornightly') {
                    $u["nextDue"] =  date('Y-m-d',strtotime($lastPaidOn . "+1 days"));
                }
                if($u->payment_frequency == 'weekly') {
                    $u["nextDue"] = date('Y-m-d',strtotime($lastPaidOn . "+7 days"));
                }
                if($u->payment_frequency == 'biweekly') {
                    $u["nextDue"] = date('Y-m-d',strtotime($lastPaidOn . "+14 days"));
                }
                if($u->payment_frequency == 'monthly') {
                    $u["nextDue"] = date('Y-m-d',strtotime($lastPaidOn . "+30 days"));
                }
                $items[] = $u;

            }

            $replies = \App\Reply::where("model", "User")->whereNull("deleted_at")->pluck("reply", "id")->toArray();
        }
        

        $isAdmin = Auth::user()->isAdmin();
        return response()->json([
            "code"       => 200,
            "data"       => $items,
            "replies" => $replies,
            'isAdmin' => $isAdmin,
            "pagination" => (string) $user->links(),
            "total"      => $user->total(),
            "page"       => $user->currentPage(),
        ]);

    }
    public function GetUserDetails($id)
    {
        $user = User::where('id',$id)->first();
      
       
       
        return response()->json([
            "code"       => 200,
            "data"       => $user,
        ]);

    }
    public function getPendingandAvalHour($id)
    {
        $u = [];
        $tasks_time = Task::where('assign_to',$id)->where('is_verified',NULL)->select(DB::raw("SUM(approximate) as approximate_time"));
        $devTasks_time = DeveloperTask::where('assigned_to',$id)->where('status','!=','Done')->select(DB::raw("SUM(estimate_minutes) as approximate_time"));
        
        $task_times = ($devTasks_time)->union($tasks_time)->get();
        $pending_tasks = 0;
        foreach($task_times as $key => $task_time){
            $pending_tasks += $task_time['approximate_time'];
        }
        $u['total_pending_hours'] = intdiv($pending_tasks, 60).':'. ($pending_tasks % 60);
        $today = date('Y-m-d');

        /** get total availablity hours */
        $avaibility = UserAvaibility::where('user_id',$id)->where('date','>=',$today)->get();
        $avaibility_hour = 0;
        foreach($avaibility as $aval_time){
            $from = $this->getTimeFormat($aval_time["from"]);
            $to = $this->getTimeFormat($aval_time["to"]);
            $avaibility_hour += round((strtotime($to) - strtotime($from))/3600, 1);
        }
        $avaibility_hour = $this->getTimeFormat($avaibility_hour);
        $u['total_avaibility_hour'] = $avaibility_hour;

        /** get today availablity hours */
        $today_avaibility = UserAvaibility::where('user_id',$id)->where('date','=',$today)->get();
        $today_avaibility_hour = 0;
        foreach($today_avaibility as $aval_time){
            $from = $this->getTimeFormat($aval_time["from"]);
            $to = $this->getTimeFormat($aval_time["to"]);
            $today_avaibility_hour += round((strtotime($to) - strtotime($from))/3600, 1);
        }
        $today_avaibility_hour = $this->getTimeFormat($today_avaibility_hour);
        $u['today_avaibility_hour'] = $today_avaibility_hour;
        return response()->json([
            "code"       => 200,
            "data"       => $u
        ]);
    }

    public function getTimeFormat($time)
    {
        $time = explode(".",$time);
        if (strlen($time[0]) <= 1) {
            $from_time = '0'.$time[0].':00:00';
        }else{
            $from_time = $time[0].':00:00';
        }
        return $from_time;
    }

    public function edit($id) {
        $user = User::find($id);
		$roles = Role::orderBy('name', 'asc')->pluck('name', 'id')->all();
		$permission = Permission::orderBy('name', 'asc')->pluck('name', 'id')->all();

		$users = User::all();
		$userRole = $user->roles->pluck('name', 'id')->all();
		$userPermission = $user->permissions->pluck('name', 'id')->all();
		$agent_roles  = array('sales' => 'Sales', 'support' => 'Support', 'queries' => 'Others');
		$user_agent_roles = explode(',', $user->agent_role);
		$api_keys = ApiKey::select('number')->get();
		$customers_all = Customer::select(['id', 'name', 'email', 'phone', 'instahandler'])->whereRaw("customers.id NOT IN (SELECT customer_id FROM user_customers WHERE user_id != $id)")->get()->toArray();

        $userRate = UserRate::getRateForUser($user->id);
        // return response()->json([
        //     "code"       => 200,
        //     "user"       => $user,
        //     "users"       => $users,
        //     "agent_roles" => $agent_roles,
        //     "api_keys"    => $api_keys,
        //     "customers_all" => $customers_all,
        //     "userRate" => $userRate,
        // ]);

        return view('usermanagement::templates.edit-user', compact('user','userRole', 'users', 'roles', 'agent_roles', 'user_agent_roles', 'api_keys', 'customers_all', 'permission', 'userPermission', 'userRate'));
    }


    public function update(Request $request, $id)
	{
		$this->validate($request, [
			'name' => 'required',
			'email' => 'required|email|unique:users,email,' . $id,
			'phone' => 'sometimes|nullable|integer|unique:users,phone,' . $id,
			'password' => 'same:confirm-password'
        ]);
		$input = $request->all();
		$hourly_rate = $input['hourly_rate'];
		$currency = $input['currency'];

		unset($input['hourly_rate']);
		unset($input['currency']);
		$input['name'] = str_replace(' ', '_', $input['name']);
		if (isset($input['agent_role'])) {
			$input['agent_role'] = implode(',', $input['agent_role']);
		} else {
			$input['agent_role'] = '';
		}


		if (!empty($input['password'])) {
			$input['password'] = Hash::make($input['password']);
		} else {
			$input = array_except($input, array('password'));
		}
        //return $input;
		$user = User::find($id);
		$user->update($input);
        if($request->customer){
    		if ($request->customer[0] != '') {
    			$user->customers()->sync($request->customer);
    		}
        }


		$user->listing_approval_rate = $request->get('listing_approval_rate') ?? '0';
		$user->listing_rejection_rate = $request->get('listing_rejection_rate') ?? '0';
		$user->save();


		$userRate = new UserRate();
		$userRate->start_date = Carbon::now();
		$userRate->hourly_rate = $hourly_rate;
		$userRate->currency = $currency;
		$userRate->user_id = $user->id;
		$userRate->save();
		return redirect()->back()
			->with('success', 'User updated successfully');
    }
    


	public function activate(Request $request, $id)
	{
		$user = User::find($id);
		if ($user->is_active == 1) {
			$user->is_active = 0;
		} else {
			$user->is_active = 1;
		}

		$user->save();

        return response()->json([
            "code"       => 200,
            "message"       => 'User sucessfully updated',
            "page"       => $request->get('page')
        ]);

    }
    public function show($id)
	{
		$user = User::find($id);

		if (Auth::id() != $id) {
			return redirect()->route('user-management.index')->withWarning("You don't have access to this page!");
		}

		$users_array = Helpers::getUserArray(User::all());
		$roles = Role::pluck('name', 'name')->all();
		$users = User::all();
		$userRole = $user->roles->pluck('name', 'name')->all();
		$agent_roles  = array('sales' => 'Sales', 'support' => 'Support', 'queries' => 'Others');
		$user_agent_roles = explode(',', $user->agent_role);
		$api_keys = ApiKey::select('number')->get();

		$pending_tasks = Task::where('is_statutory', 0)
			->whereNull('is_completed')
			->where(function ($query) use ($id) {
				return $query->orWhere('assign_from', $id)
					->orWhere('assign_to', $id);
			})->get();

		// dd($pending_tasks);
        // return response()->json(["code" => 200, "user" => $user]);

        return view('usermanagement::templates.show', 
        [
			'user'	=> $user,
			'users_array'	=> $users_array,
			'roles'	=> $roles,
			'users'	=> $users,
			'userRole'	=> $userRole,
			'agent_roles'	=> $agent_roles,
			'user_agent_roles'	=> $user_agent_roles,
			'api_keys'	=> $api_keys,
			'pending_tasks'	=> $pending_tasks,
		]);

    }
    
    public function usertrack($id)
    {
        $user = User::find($id);
        $actions = $user->actions()->orderBy('created_at', 'DESC')->get();

        $tracks = Session::where('user_id', $id)->orderBy('created_at', 'DESC')->get();

        $routeActions = [
            'users.index' => 'Viewed Users Page',
            'users.show' => 'Viewed A User',
            'customer.index' => 'Viewed Customer Page',
            'customer.show' => 'Viewed A Customer Page',
            'cold-leads.index' => 'Viewed Cold Leads Page',
            'home' => 'Landed Homepage',
            'purchase.index' => 'Viewed Purchase Page'
        ];

        $models = [
            'users.show' => new User(),
            'customer.show' => new Customer(),
            'cold-leads.show' => new ColdLeads(),
        ];

        return view('usermanagement::templates.track', 
        [
			'actions'	=> $actions,
			'tracks'	=> $tracks,
			'routeActions'	=> $routeActions,
			'models'	=> $models
		]);
    }

    
    public function userPayments($id, Request $request)
	{
		$params = $request->all();

		$date = new DateTime();

		if (isset($params['year']) && isset($params['week'])) {
			$year = $params['year'];
			$week = $params['week'];
		} else {
			$week = $date->format("W");
			$year = $date->format("Y");
		}
		$result = getStartAndEndDate($week, $year);
		$start = $result['week_start'];
		$end = $result['week_end'];

		$user = User::join('hubstaff_payment_accounts as hpa',"hpa.user_id","users.id")->where('users.id', $id)->with(['currentRate'])->first();
		$usersRatesThisWeek = UserRate::ratesForWeek($week, $year);

		$usersRatesPreviousWeek = UserRate::latestRatesForWeek($week - 1, $year);

		$activitiesForWeek = HubstaffActivity::getActivitiesForWeek($week, $year);

		$paymentsDone = Payment::getConsidatedUserPayments();

		$amountToBePaid = HubstaffPaymentAccount::getConsidatedUserAmountToBePaid();

		

        $now = now();
        $paymentMethods = array();
            if($user) {
                $user->secondsTracked = 0;
			$user->currency = '-';
			$user->total = 0;

			

            $userPaymentsDone = 0;
            
			$userPaymentsDoneModel = $paymentsDone->first(function ($value) use($user) {
				return $value->user_id == $user->id;
			});

			if($userPaymentsDoneModel){
				$userPaymentsDone = $userPaymentsDoneModel->paid;
			}

			$userPaymentsToBeDone = 0;
			$userAmountToBePaidModel = $amountToBePaid->first(function ($value) use($user){
				return $value->user_id == $user->id;
			});

			if($userAmountToBePaidModel){
				$userPaymentsToBeDone = $userAmountToBePaidModel->amount;
			}

			$user->balance = $userPaymentsToBeDone - $userPaymentsDone;

			

			//echo $user->id. ' '.$userPaymentsToBeDone. ' '. $userPaymentsDone. PHP_EOL;


			$invidualRatesPreviousWeek  = $usersRatesPreviousWeek->first(function ($value, $key) use ($user) {
				return $value->user_id == $user->id;
			});




			$weekRates = [];

			if ($invidualRatesPreviousWeek) {
				$weekRates[] = array(
					'start_date' => $start,
					'rate' => $invidualRatesPreviousWeek->hourly_rate,
					'currency' => $invidualRatesPreviousWeek->currency
				);
			}

			$rates = $usersRatesThisWeek->filter(function ($value, $key) use ($user) {
				return $value->user_id == $user->id;
			});

			if ($rates) {

				foreach ($rates as $rate) {
					$weekRates[] = array(
						'start_date' => $rate->start_date,
						'rate' => $rate->hourly_rate,
						'currency' => $rate->currency
					);
				}
			}


			usort($weekRates, function ($a, $b) {
				return strtotime($a['start_date']) - strtotime($b['start_date']);
			});


			if (sizeof($weekRates) > 0) {
				$lastEntry = $weekRates[sizeof($weekRates) - 1];

				$weekRates[] = array(
					'start_date' => $end,
					'rate' => $lastEntry['rate'],
					'currency' => $lastEntry['currency']
				);

				$user->currency = $lastEntry['currency'];
			}

			$activities = $activitiesForWeek->filter(function ($value, $key) use ($user) {
				return $value->system_user_id === $user->id;
			});

			$user->trackedActivitiesForWeek = $activities;

			foreach ($activities as $activity) {
				$user->secondsTracked += $activity->tracked;
				$i = 0;
				while ($i < sizeof($weekRates) - 1) {

					$start = $weekRates[$i];
					$end = $weekRates[$i + 1];

					if ($activity->starts_at >= $start['start_date'] && $activity->start_time < $end['start_date']) {
						// the activity needs calculation for the start rate and hence do it
						$earnings = $activity->tracked * ($start['rate'] / 60 / 60);
						$activity->rate = $start['rate'];
						$activity->earnings = $earnings;
						$user->total += $earnings;
						break;
					}
					$i++;
				}
			}
		

		//exit;
            
            foreach (PaymentMethod::all() as $paymentMethod) {
                $paymentMethods[$paymentMethod->id] = $paymentMethod->name;
            }
        }
			
        
        return view('usermanagement::templates.payments', 
        [
			'user' => $user,
			'id' => $id,
			'selectedYear' => $year,
			'selectedWeek' => $week,
			'paymentMethods' => $paymentMethods
		]);
    }
    

    public function getRoles($id) {
        $user = User::find($id);
		$roles = Role::orderBy('name', 'asc')->pluck('name', 'id')->all();
		$userRole = $user->roles->pluck('name', 'id')->all();
        
        return response()->json([
            "code"       => 200,
            "user"       => $user,
            "userRole"       => $userRole,
            "roles"       => $roles
        ]);
    }

        public function submitRoles($id, Request $request) {
            $user = User::find($id);
            if(Auth::user()->hasRole('Admin')) {
                $user->roles()->sync($request->input('roles'));
                return response()->json([
                    "code"       => 200,
                    "message"       => 'Role updated successfully'
                ]);
            }
            return response()->json([
                "code"       => 200,
                "message"       => 'Unauthorized access'
            ]);
    }

    public function getPermission($id) {
        $user = User::find($id);
		//$permission = Permission::orderBy('name', 'asc')->pluck('name', 'id')->all();
        
        $permission = Permission::leftJoin('permission_user', function($join) use ($user)
            {
                $join->on('permissions.id', '=', 'permission_user.permission_id');
                $join->where('permission_user.user_id', '=', $user->id);
            })
            ->select('permissions.name', 'permissions.id','permission_user.user_id')
            ->orderBy('permission_user.user_id','DESC')
            ->get()->toArray();

            //->pluck('name','id');

            //->pluck('permissions.name', 'permissions.id');

        $userPermission = $user->permissions->pluck('name', 'id')->all();
        
        return response()->json([
            "code"       => 200,
            "user"       => $user,
            "userPermission" => $userPermission,
            "permissions"    => $permission
        ]);
    }

    public function submitPermission($id, Request $request) {
        $user = User::find($id);
        if(Auth::user()->hasRole('Admin')) {
            $user->permissions()->sync($request->input('permissions'));
            return response()->json([
                "code"       => 200,
                "message"       => 'Permission updated successfully'
            ]);
        }
        return response()->json([
            "code"       => 200,
            "message"       => 'Unauthorized access'
        ]);
    }

    public function addNewPermission(Request $request) {
        
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'route' => 'required|unique:roles,name',
            
        ]);
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->route = $request->route;
        $permission->save();
        return response()->json([
            "code"       => 200,
            "permission"       => $permission
        ]);    
    }

    public function paymentInfo($id) {
        $user = User::find($id);
        $lastPaid = $user->payments()->orderBy('id','desc')->first();
        if($lastPaid) {
            $lastPaidOn = $lastPaid->paid_upto;
        }
        else {
            $query = HubstaffPaymentAccount::where('user_id',$id)->first();
            if(!$query) {
                return response()->json([
                    "code"       => 500,
                    "message"       => 'No data found'
                ]); 
            }
            $lastPaidOn = date('Y-m-d',strtotime($query->billing_start . "-1 days"));
        }
        $pendingPyments = HubstaffPaymentAccount::where('user_id',$id)->where('billing_start','>',$lastPaidOn)->get();
        if(!count($pendingPyments)) {
            return response()->json([
                "code"       => 500,
                "message"       => 'No data found'
            ]); 
        }
        $pendingTerms = [];
        if($user->payment_frequency == 'fornightly') {
            $totalPendingTerms = count($pendingPyments);
            $perPacket = 1;
        }
        if($user->payment_frequency == 'weekly') {
            $totalPendingTerms = floor(count($pendingPyments)/7);
            $perPacket = 7;
        }
        if($user->payment_frequency == 'biweekly') {
            $totalPendingTerms = floor(count($pendingPyments)/14);
            $perPacket = 14;
        }
        if($user->payment_frequency == 'monthly') {
            $totalPendingTerms = floor(count($pendingPyments)/30);
            $perPacket = 30;
        }
        $count = 0;
        $packetCount = 0;
        $totalAmount = 0;
        $totalAmountTobePaid = 0;
        foreach($pendingPyments as $pending) {
            if($count < $totalPendingTerms) {
                $totalAmount = $totalAmount + ($pending->hrs * $pending->rate);
                $totalAmountTobePaid = $totalAmountTobePaid + ($pending->hrs * $pending->rate * $pending->ex_rate);
                $packetCount = $packetCount + 1;
                if($packetCount == $perPacket) {
                    $packetCount = 0;
                    $count = $count + 1;
                    $array = array(
                        'totalAmount' => $totalAmount,
                        'lastPaidOn' => $pending->billing_start,
                        'currency' => $pending->currency,
                        'totalAmountTobePaid' => $totalAmountTobePaid,
                        'payment_currency' => $pending->payment_currency
                    );

                    $pendingTerms[] = $array;
                    $totalAmount = 0;
                    $totalAmountTobePaid = 0;
                }
            }
            else {
            break;
            }
        }
        $paymentMethods = PaymentMethod::all();
        return view('usermanagement::templates.add-payment', compact('user','pendingTerms','paymentMethods'));


    }

    public function addPaymentMethod(Request $request) {
        
        $paymentMethods = new PaymentMethod;
        $paymentMethods->name = $request->name;
        $paymentMethods->save();
        $paymentMethods = PaymentMethod::all();
        return view('usermanagement::templates.new-payment-methods', compact('paymentMethods'));
    }

    public function savePayments($id, Request $request) {
        
        $this->validate($request, [
			'currency' => 'required',
			'amounts' => 'required',
			'payment_method_id' => 'required',
        ]);
        

        $user = User::find($id);
        $lastPaid = $user->payments()->orderBy('id','desc')->first();
        if($lastPaid) {
            $lastPaidOn = $lastPaid->paid_upto;
        }
        else {
            $query = HubstaffPaymentAccount::where('user_id',$id)->first();
            $lastPaidOn = date('Y-m-d',strtotime($query->billing_start . "-1 days"));
        }
        $pendingPyments = HubstaffPaymentAccount::where('user_id',$id)->where('billing_start','>',$lastPaidOn)->get();
        $pendingTerms = [];
        if($user->payment_frequency == 'fornightly') {
            $perPacket = 1;
        }
        if($user->payment_frequency == 'weekly') {
            $perPacket = 7;
        }
        if($user->payment_frequency == 'biweekly') {
            $perPacket = 14;
        }
        if($user->payment_frequency == 'monthly') {
            $perPacket = 30;
        }
        $count = 1;
        $totalPendingRaws = $perPacket * count($request->amounts);
        foreach($pendingPyments as $pending) {
            if($count == $totalPendingRaws) {
                $resetLastPaidOn = $pending->billing_start;
            break;
            }
            $count++;
        }
        $totalAmount = 0;
        foreach($request->amounts as $amount) {
            $totalAmount = $totalAmount + $amount;
        }
        $payment = new Payment;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->user_id = $id;
        $payment->note = $request->note;
        $payment->amount = $totalAmount;
        $payment->currency = $request->currency;
        $payment->paid_upto = $resetLastPaidOn;
        $payment->save();
        return redirect()->back()->with('success','Payment done successfully');
    }


    public function addReply(Request $request)
    {
      $reply = $request->get("reply");
      $autoReply = [];
      // add reply from here 
      if (!empty($reply)) {
  
        $autoReply = \App\Reply::updateOrCreate(
          ['reply' => $reply, 'model' => 'User', "category_id" => 1],
          ['reply' => $reply]
        );
      }
  
      return response()->json(["code" => 200, 'data' => $autoReply]);
    }

    public function deleteReply(Request $request)
    {
      $id = $request->get("id");
  
      if ($id > 0) {
        $autoReply = \App\Reply::where("id", $id)->first();
        if ($autoReply) {
          $autoReply->delete();
        }
      }
  
      return response()->json([
        "code" => 200, "data" => \App\Reply::where("model", "User")
          ->whereNull("deleted_at")
          ->pluck("reply", "id")
          ->toArray()
      ]);
    }


    public function userTasks($id) {
        $user = User::find($id)->toArray();
            $taskList = DB::select('
                select * from (
                    (SELECT tasks.id as task_id,tasks.task_subject as subject, tasks.task_details as details, tasks.approximate as approximate_time, tasks.due_date,tasks.deleted_at,tasks.assign_to as assign_to,tasks.status as status_falg,chat_messages.message as last_message, chat_messages.created_at as orderBytime, tasks.is_verified as cond, "TASK" as type,tasks.created_at as created_at,tasks.priority_no,tasks.is_flagged as has_flag  FROM tasks
                              LEFT JOIN
                               (SELECT MAX(id) AS max_id,
                                       task_id,
                                       message,
                                       created_at
                                FROM chat_messages
                                WHERE task_id > 0
                                GROUP BY task_id) m_max ON m_max.task_id = tasks.id
                            LEFT JOIN task_statuses ON task_statuses.id = tasks.status
                             LEFT JOIN chat_messages ON chat_messages.id = m_max.max_id
                              WHERE tasks.deleted_at IS NULL and tasks.is_statutory != 1 and tasks.is_verified is null and tasks.assign_to = '.$id.') 
                    
                    union  
                    
                    (
                        select developer_tasks.id as task_id, developer_tasks.subject as subject, developer_tasks.task as details, developer_tasks.estimate_minutes as approximate_time, developer_tasks.due_date as due_date,developer_tasks.deleted_at, developer_tasks.assigned_to as assign_to,developer_tasks.status as status_falg, chat_messages.message as last_message, chat_messages.created_at as orderBytime,"d" as cond, "DEVTASK" as type,developer_tasks.created_at as created_at,developer_tasks.priority_no,developer_tasks.is_flagged as has_flag from developer_tasks left join (SELECT MAX(id) as  max_id, issue_id, message,created_at  FROM  chat_messages where issue_id > 0  GROUP BY issue_id ) m_max on  m_max.issue_id = developer_tasks.id left join chat_messages on chat_messages.id = m_max.max_id where developer_tasks.status != "Done" and developer_tasks.deleted_at is null and developer_tasks.assigned_to = '.$id.'
                        
                        ) 
                    ) as c order by has_flag desc
                '); 


        //  $tasks = Task::where('assign_to',$id)->where('is_verified',NULL)->select('id as task_id','task_subject as subject','task_details as details','approximate as approximate_time','due_date');
        //  $tasks = $tasks->addSelect(DB::raw("'TASK' as type"));
        //  $devTasks = DeveloperTask::where('assigned_to',$id)->where('status','!=','Done')->select('id as task_id','subject','task as details','estimate_minutes as approximate_time','due_date as due_date');
        //  $devTasks = $devTasks->addSelect(DB::raw("'DEVTASK' as type"));

        //  $taskList = $devTasks->union($tasks)->get();



        $u = [];
        $tasks_time = Task::where('assign_to',$id)->where('is_verified',NULL)->select(DB::raw("SUM(approximate) as approximate_time"));
        $devTasks_time = DeveloperTask::where('assigned_to',$id)->where('status','!=','Done')->select(DB::raw("SUM(estimate_minutes) as approximate_time"));
        
        $task_times = ($devTasks_time)->union($tasks_time)->get();
        $pending_tasks = 0;
        foreach($task_times as $key => $task_time){
            $pending_tasks += $task_time['approximate_time'];
        }
        $u['total_pending_hours'] = intdiv($pending_tasks, 60).':'. ($pending_tasks % 60);

        //$priority_tasks_time = Task::where('assign_to',$id)->where('is_verified',NULL)->where('is_flagged',1)->select(DB::raw("SUM(approximate) as approximate_time"))->first();
        $p_tasks_time = Task::where('assign_to',$id)->where('is_verified',NULL)->where('is_flagged',1)->select(DB::raw("SUM(approximate) as approximate_time"));
        $p_devtasks_time = DeveloperTask::where('assigned_to',$id)->where('status','!=','Done')->where('priority','!=',0)->select(DB::raw("SUM(estimate_minutes) as approximate_time"));
        $priority_tasks_time = ($p_devtasks_time)->union($p_tasks_time)->get();
        // SELECT 
        /** get availablity hours */
        $user_avaibility = UserAvaibility::where('user_id',$id)->selectRaw('minute')->orderBy('id','desc')->first();
        $available_minute = !empty($user_avaibility) ? $user_avaibility->minute : 0;

        $totalPriority = !empty($priority_tasks_time) ? $priority_tasks_time[0]->approximate_time : 0;
        $hours = 0;

        if($available_minute != 0) {
            $available_minute = $available_minute - $totalPriority;
            $hours = floor($available_minute / 60); // Get the number of whole hours
            $available_minute = $available_minute % 60; 
        }

        $u['total_priority_hours'] = intdiv($totalPriority, 60).':'. ($totalPriority % 60);
        $u['total_available_time'] = sprintf ("%d:%02d", $hours, $available_minute); 
        $today = date('Y-m-d');

        /** get total availablity hours */
        $avaibility = UserAvaibility::where('user_id',$id)->where('date','>=',$today)->get();
        $avaibility_hour = 0;
        foreach($avaibility as $aval_time){
            $from = $this->getTimeFormat($aval_time["from"]);
            $to = $this->getTimeFormat($aval_time["to"]);
            $avaibility_hour += round((strtotime($to) - strtotime($from))/3600, 1);
        }
        $avaibility_hour = $this->getTimeFormat($avaibility_hour);
        $u['total_avaibility_hour'] = $avaibility_hour;

        /** get today availablity hours */
        $today_avaibility = UserAvaibility::where('user_id',$id)->where('date','=',$today)->get();
        $today_avaibility_hour = 0;
        foreach($today_avaibility as $aval_time){
            $from = $this->getTimeFormat($aval_time["from"]);
            $to = $this->getTimeFormat($aval_time["to"]);
            $today_avaibility_hour += round((strtotime($to) - strtotime($from))/3600, 1);
        }
        $today_avaibility_hour = $this->getTimeFormat($today_avaibility_hour);
        $u['today_avaibility_hour'] = $today_avaibility_hour;

        $statusList = \DB::table("task_statuses")->select("name","id")->get()->toArray();

            return response()->json([
                "code"       => 200,
                "user"       => $user,
                "statusList" => $statusList,
                "taskList"    => $taskList,
                'userTiming'  => $u
            ]); 
    }


    public function createTeam($id) {
        $user = User::find($id);
        $users = User::where('id','!=',$id)->where('is_active',1)->get()->pluck('name', 'id');
        
        return response()->json([
            "code"       => 200,
            "user"       => $user,
            "users"       => $users
        ]);
    }

    public function submitTeam($id, Request $request) {
        $user = User::find($id);
        $isLeader = Team::where('user_id',$id)->first();
        if($isLeader) {
            return response()->json([
                "code"       => 500,
                "message"       => 'This user is already a team leader'
            ]);
        }

        $isMember = $user->teams()->first();
        if($isMember) {
            return response()->json([
                "code"       => 500,
                "message"       => 'This user is already a team member'
            ]);
        }
        $team = new Team;
        $team->name = $request->name;
        $team->user_id = $id;
        $team->save();
        if(Auth::user()->hasRole('Admin')) {
            $members = $request->input('members');
            if($members) {
                foreach($members as $key => $mem) {
                    $u = User::find($mem);
                    if($u) {
                        $isMember = $u->teams()->first();
                        $isLeader = Team::where('user_id',$mem)->first();
                        if(!$isMember && !$isLeader) {
                            $team->users()->attach($mem); 
                            $response[$key]["msg"] = $u->name." added in team successfully";
                            $response[$key]["status"] = 'success';
                        }else if($isMember){
                            $response[$key]["msg"] = $u->name." is already team member";
                            $response[$key]["status"] = 'error';
                        }else{
                            $response[$key]["msg"] = $u->name." is already team leader";
                            $response[$key]["status"] = 'error';
                        }
                    }
                }
            }
            return response()->json([
                "code"       => 200,
                "data"       => $response
            ]);
        }
        return response()->json([
            "code"       => 200,
            "message"       => 'Unauthorized access'
        ]);
    }


    public function getTeam($id) {
        $team = Team::where('user_id',$id)->first();
        $team->user;
        $team->members = $team->users()->pluck('name','id');
        $totalMembers =  $team->users()->count();
       
        $users = User::where('id','!=',$id)->where('is_active',1)->get()->pluck('name', 'id');
        return response()->json([
            "code"       => 200,
            "team"       => $team,
            "users"       => $users,
            "totalMembers"       => $totalMembers
        ]);
    }

    public function deleteTeam($id, Request $request)
    {
        $team = Team::find($id);
        if($team){
            $team->users()->detach();
            $team->delete();
            return response()->json([
                "code"       => 200,
                "data"       => "Team deleted successfully"
            ]);
        }else{
            return response()->json([
                "code"       => 200,
                "message"       => 'Unauthorized access'
            ]);
        }
    }


    public function editTeam($id, Request $request) {
        $team = Team::find($id);

        if(Auth::user()->hasRole('Admin')) {
            $team->update(['name' => $request->name]);
            $members = $request->input('members');
            if($members) {
                $team->users()->detach();
                foreach($members as $key => $mem) {
                    $u = User::find($mem);
                    if($u) {
                        $isMember = $u->teams()->first();
                        $isLeader = Team::where('user_id',$mem)->first();
                        if(!$isMember && !$isLeader) {
                            $team->users()->attach($mem); 
                            $response[$key]["msg"] = $u->name." added in team successfully";
                            $response[$key]["status"] = 'success';
                        }else if($isMember){
                            $response[$key]["msg"] = $u->name." is already team member";
                            $response[$key]["status"] = 'error';
                        }else{
                            $response[$key]["msg"] = $u->name." is already team leader";
                            $response[$key]["status"] = 'error';
                        }
                    }
                }
            }
            return response()->json([
                "code"       => 200,
                "data"       => $response
            ]);
        }
        return response()->json([
            "code"       => 200,
            "message"       => 'Unauthorized access'
        ]);
    }

    public function saveUserAvaibility(Request $request) {
        
        $rules = [
			'user_id' => 'required',
			'to' => 'required',
			'from' => 'required|lte:to',
			'day' => 'required',
			'status' => 'required',
            'availableDay' => 'required',
            'availableHour' => 'required',
            'note' => 'required_if:status,"0"',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           $errors = $validator->errors();

           $message = '';
             foreach ($errors->getMessages() as $field => $message) {
                 $message = $message[0];
             }
            return response()->json([
                "code"  => 500,
                "error" => $message
            ]);
        }

        $nextDay = 'next '.$request->day;

        UserAvaibility::updateOrCreate([
            'user_id'   => $request->user_id,
        ],[
            'date'     => date('Y-m-d', strtotime($nextDay)),
            'user_id'  => $request->user_id,
            'from'     => $request->from,
            'to'       => $request->to,
            'day'      => $request->availableDay,
            'minute'   => $request->availableHour,
            'status'   => $request->status,
            'note'     => trim($request->note)
        ]);

        // $user_avaibility = new UserAvaibility;
        // $user_avaibility->date = $day;
        // $user_avaibility->from = $request->from;
        // $user_avaibility->user_id = $request->user_id;
        // $user_avaibility->to = $request->to;
        // $user_avaibility->day = $request->availableDay;
        // $user_avaibility->minute = $request->availableHour;
        // $user_avaibility->status = $request->status;
        // $user_avaibility->note = $note;
        // $user_avaibility->save();
        
        return response()->json([
            "code"       => 200,
            "message"       => 'Successful'
        ]);
        
    }

    public function userAvaibilityForModal($id) {
        
        $avaibility = UserAvaibility::where('user_id',$id)->first();
        

        if($avaibility){
            $avaibility['weekday'] = strtolower(date('l', strtotime($avaibility['date'])));
        }

        $avaibility['user_id'] = $id;
        
        return response()->json(["code" => 200,"data" => $avaibility]);
    }

    public function userAvaibility($id) {
        $user = User::find($id);
        $today = date('Y-m-d');
        $avaibility = UserAvaibility::where('user_id',$id)->where('date','>=',$today)->get();
        foreach($avaibility as $av) {
            $av->day = date('D', strtotime($av['date']));
        }
        $avaibility = $avaibility->toArray();
        return response()->json([
            "code"       => 200,
            "user"       => $user,
            "avaibility" => $avaibility
        ]);
    }
    public function userAvaibilityUpdate($id, Request $request) {
        UserAvaibility::find($id)->update(['status' => $request->status, 'note' => $request->note]);
        
        return response()->json([
            "code"       => 200,
            "user"       => 'Success'
        ]);
    }

    public function approveUser($id) {
        $user = User::find($id);
        if($user) {
            $user->update(['approve_login' => date('Y-m-d')]);

            $params = [];
            $params['user_id'] = $user->id;
            $params['message'] = "Your activity has been approved for today's date ".date("Y-m-d");
            // send chat message
            $chat_message = \App\ChatMessage::create($params);
            // send
            app('App\Http\Controllers\WhatsAppController')
                ->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'], false, $chat_message->id);


            return response()->json(['message' => 'Successfully approved','code' => 200]);
        }
        return response()->json(['message' => 'User not found','code' => 404]);
    }


    public function getDatabase(Request $request,$id)
    {
        $database = \App\UserDatabase::where("user_id",$id)->where("database","mysql")->first();
        $tablesExisting = [];
        if($database) {
            $tablesExisting = \App\UserDatabaseTable::where("user_database_id",$database->id)->pluck('name','id')->toArray();
        }

        $user = \App\User::find($id);

        $list = [];
        $tables = \DB::select('SHOW TABLES');
        foreach($tables as $table) {
            foreach($table as $t) {
                $list[] = ["table" => $t ,"checked" => in_array($t, $tablesExisting) ? true : false];
            }
        }
        $data = [
            "user_id" => $id,
            "database" => $database,
            "tables" => $list,
            "user_name" => ($database) ? $database->username : preg_replace('/\s+/', '_', strtolower($user->name)),
            "password" => ($database) ? $database->password : "",
            "tablesExisting" => $tablesExisting,
            "connection" => 'mysql'
        ];
        return response()->json(['code' => 200 , 'data' => $data]);
    }

    public function createDatabaseUser(Request $request,$id)
    {
        $username = $request->get("username");
        $password = $request->get("password");

        $connection = $request->get("connection");

        if(empty($connection)) {
            return response()->json(["code" => 500, "message" => "Please select the database connection"]);
        }

        if(empty($username)) {
            return response()->json(["code" => 500, "message" => "Enter username"]);
        }

        if(empty($password) || strlen($password) <= 6) {
            return response()->json(["code" => 500, "message" => "Please enter password and more then 6 length"]);
        }

        $connectionInformation = config("database.connections.$connection");
        if(empty($connectionInformation)) {
            return response()->json(["code" => 500, "message" => "No , database connection is not available"]);
        }

        $user = \App\User::find($id);
        if($user) {
            $database = \App\UserDatabase::where("user_id",$user->id)->where("database",$connection)->first();
            if(!$database) {
                $cmd = "bash " . getenv('DEPLOYMENT_SCRIPTS_PATH') . "mysql_user.sh -f create -h ".$connectionInformation['host']." -d ".$connectionInformation['database']." -u ".$connectionInformation['username']." -p '".$connectionInformation['password']."' -n '".$username."' -s '".$password."' 2>&1";
                $allOutput   = array();
                $allOutput[] = $cmd;
                $result      = exec($cmd, $allOutput);
                \Log::info(print_r($allOutput,true));
                \App\UserDatabase::create([
                    "username" => $username,
                    "password" => $password,
                    "database" => $connection,
                    "user_id"  => $id
                ]);

                $params = [];
                $params['user_id'] = $user->id;
                $params['message'] = "We have created user with username : " .$username ." and password : ".$password." , you can sing in here https://erp.theluxuryunlimited.com/7WZr3fgqVfRS5ZskKfv3km2ByrVRGqyDW9F/phpMyAdmin/.";
                // send chat message
                $chat_message = \App\ChatMessage::create($params);
                // send
                app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'], false, $chat_message->id);

                return response()->json(["code" => 200, "message" => "User created successfully"]);
            }

            return response()->json(["code" => 500, "message" => "User already created"]);
        }

        return response()->json(["code" => 500, "message" => "User not found"]);
    }

    public function assignDatabaseTable(Request $request,$id)
    {
        $connection = $request->get("connection");

        if(empty($connection)) {
            return response()->json(["code" => 500, "message" => "Please select the database connection"]);
        }

        $connectionInformation = config("database.connections.$connection");
        if(empty($connectionInformation)) {
            return response()->json(["code" => 500, "message" => "No , database connection is not available"]);
        }

        $database = \App\UserDatabase::where("user_id",$id)->where("database",$connection)->first();
        $user = \App\User::find($id);
        $tables   = !empty($request->tables) ? $request->tables : [];
        $permissionType = $request->get("assign_permission","read");



        if($database) {
            
            $tablesExisting = \App\UserDatabaseTable::where("user_database_id",$database->id)->pluck('name','id')->toArray();
            if(!empty($tablesExisting)){
                $deleteTables = [];
                foreach($tablesExisting as $te) {
                    if(!in_array($te, $tables)) {
                        $deleteTables[] = $te;
                    }
                }
                if(!empty($deleteTables)) {
                    $cmd = "bash " . getenv('DEPLOYMENT_SCRIPTS_PATH') . "mysql_user.sh -f revoke -h ".$connectionInformation['host']."  -u ".$connectionInformation['username']." -p '".$connectionInformation['password']."' -d ".$connectionInformation['database']." -n '".$database->username."' -t ".implode(",",$deleteTables)." 2>&1";
                    $allOutput   = array();
                    $allOutput[] = $cmd;
                    $result      = exec($cmd, $allOutput);
                    \Log::info(print_r($allOutput,true));
                }
            }

            \App\UserDatabaseTable::where("user_database_id",$database->id)->delete();

            if(!empty($tables)) {
                foreach($tables as $t) {
                    \App\UserDatabaseTable::create([
                        'user_database_id' => $database->id,
                        'name' => $t,
                    ]);
                }
            }

            
            $cmd = "bash " . getenv('DEPLOYMENT_SCRIPTS_PATH') . "mysql_user.sh -f update  -h ".$connectionInformation['host']."  -u ".$connectionInformation['username']." -p '".$connectionInformation['password']."' -d ".$connectionInformation['database']." -n '".$database->username."' -t ".implode(",",$tables)." -m '".$permissionType."' 2>&1";
            $allOutput   = array();
            $allOutput[] = $cmd;
            $result      = exec($cmd, $allOutput);
            \Log::info(print_r($allOutput,true));

            $params = [];
            $params['user_id'] = $user->id;
            $params['message'] = "Your request for given table (" .implode(",",$tables) .")  has been approved , please verify at your end.";
            // send chat message
            $chat_message = \App\ChatMessage::create($params);
            // send
            app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'], false, $chat_message->id);

            return response()->json(["code" => 200, "message" => "Table assigned successfully"]);

        }

        return response()->json(["code" => 500, "message" => "Please create database user first"]);
    }

    public function deleteDatabaseAccess(Request $request, $id)
    {
        $connection = $request->get("connection");

        if(empty($connection)) {
            return response()->json(["code" => 500, "message" => "Please select the database connection"]);
        }

        $connectionInformation = config("database.connections.$connection");
        if(empty($connectionInformation)) {
            return response()->json(["code" => 500, "message" => "No , database connection is not available"]);
        }


        $database = \App\UserDatabase::where("user_id",$id)->where("database",$connection)->first();
        if($database) {

            $cmd = "bash " . getenv('DEPLOYMENT_SCRIPTS_PATH') . "mysql_user.sh -f delete -h ".$connectionInformation['host']."  -u ".$connectionInformation['username']." -p '".$connectionInformation['password']."' -d ".$connectionInformation['database']."  -n '".$database->username."' 2>&1";
            $allOutput   = array();
            $allOutput[] = $cmd;
            $result      = exec($cmd, $allOutput);
            \Log::info(print_r($allOutput,true));
            foreach($database->userDatabaseTables as $dbtables) {
                $dbtables->delete();
            }
            $database->delete();

            return response()->json(["code" => 200, "message" => "Database access has been removed"]);
        }

        return response()->json(["code" => 500, "message" => "Sorry we couldn't found the access for the given user"]);
    }

    public function chooseDatabase(Request $request, $id)
    {
        $connection = $request->get("connection");

        $database = \App\UserDatabase::where("database",$connection)->where("user_id",$id)->first();
        $tablesExisting = [];
        if($database) {
            $tablesExisting = \App\UserDatabaseTable::where("user_database_id",$database->id)->pluck('name','id')->toArray();
        }

        $user = \App\User::find($id);

        $list = [];
        $tables = \DB::connection($connection)->select('SHOW TABLES');
        if(!empty($tables)) {
            foreach($tables as $table) {
                foreach($table as $t) {
                    $list[] = ["table" => $t ,"checked" => in_array($t, $tablesExisting) ? true : false];
                }
            }
        }
        $data = [
            "user_id" => $id,
            "database" => $database,
            "tables" => $list,
            "user_name" => ($database) ? $database->username : preg_replace('/\s+/', '_', strtolower($user->name)),
            "password" => ($database) ? $database->password : "",
            "tablesExisting" => $tablesExisting,
            "connection" => $connection
        ];

        return response()->json(['code' => 200 , 'data' => $data]);
    }

    public function updateStatus(Request $request){
        if($request->type == "TASK"){
            //issue_id
            $status = \DB::table("task_statuses")->where('name',$request->is_resolved)->select("id")->get();
            if($status){
                Task::where('id', $request->issue_id)
                    ->update(['status' => $status[0]->id]);
                return response()->json(['code' => 200 , 'data' => 'Success']);
            }
        }
        if($request->type == "DEVTASK"){
            DeveloperTask::where('id', $request->issue_id)
              ->update(['status' => $request->is_resolved]);
            return response()->json(['code' => 200 , 'data' => 'Success']);
        }
        return response()->json(['code' => 500 , 'data' => 'Error']);
    }
    public function systemIps(Requests $request){
        $shell_list = shell_exec("bash " . getenv('DEPLOYMENT_SCRIPTS_PATH'). "/webaccess-firewall.sh -f list");
        return response()->json( ["code" => 200 , "data" => $shell_list] );
    }

    public function userGenerateStorefile(Request $request)
    {

        $server = $request->get("for_server");
        $user   = \App\User::find($request->get('userid',0));
        if(!$user) {
            return false;
        }

        $username = str_replace(" ", "_", $user->name);

        $cmd = 'bash ' . getenv('DEPLOYMENT_SCRIPTS_PATH') . 'pem-generate.sh -u '.$username.' -f add -s '.$server.' 2>&1';

        $allOutput   = array();
        $allOutput[] = $cmd;
        $result      = exec($cmd, $allOutput);

        \Log::info(print_r($allOutput,true));

        $string  = [];
        if(!empty($allOutput)) {
            $continuetoFill = false;
            foreach($allOutput as $ao) {
                if($ao == "-----BEGIN RSA PRIVATE KEY-----" || $continuetoFill) {
                   $string[] = $ao;
                   $continuetoFill = true; 
                }
            }
        }

        $content = implode("\n",$string);

        $nameF = $server.".pem";


        UserPemfileHistory::create([
            'user_id' => $request->userid, 
            'server_name' => $server,
            'username' => $username,
            'action' => 'add',
            'created_by' => $request->user()->id,
        ]);
        
        //header download
        header("Content-Disposition: attachment; filename=\"" . $nameF . "\"");
        header("Content-Type: application/force-download");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header("Content-Type: application/x-pem-file");

        echo $content;
        die;
    }

    public function userPemfileHistoryListing(Request $request)
    {
        $history = UserPemfileHistory::where('user_id',$request->userid)->latest()->get();
        return response()->json(["code" => 200, "data" => $history]);
    }

    public function deletePemFile(Request $request,$id)
    {
        $pemHistory = UserPemfileHistory::find($id);
        if($pemHistory) {

            $cmd = 'bash ' . getenv('DEPLOYMENT_SCRIPTS_PATH') . 'pem-generate.sh -u '.$pemHistory->username.' -f delete -s '.$pemHistory->server_name.' 2>&1';

            $allOutput   = array();
            $allOutput[] = $cmd;
            $result      = exec($cmd, $allOutput);
            $pemHistory->delete();

            return response()->json(["code" => 200, "data" => [], "message" => "Pem remove file request submitted successfully"]);

        }else{
            return response()->json(["code" => 500, "data" => [], "message" => "No request found"]);
        }

    }

    public function addFeedbackCategory(Request $request)
    {
        $category = new UserFeedbackCategory;
        $category->user_id=$request->user_id;
        $category->category=$request->category;
        $category->save();
        $status = UserFeedbackStatus::get();

        return view('usermanagement::user-feedback-data',compact('category','status'));
        // return response()->json(["status" => true , 'category' => $category]);
    }

    public function addFeedbackStatus(Request $request)
    {
        $status = UserFeedbackStatus::where('status',$request->status);
        if($status->count() === 0){
            $feedback_status = new UserFeedbackStatus;
            $feedback_status->status=$request->status;
            $feedback_status->save();
            // return view('usermanagement::user-feedback-data',compact('status'));
            $all_status = UserFeedbackStatus::get();
            return response()->json(["status" => true , 'feedback_status' => $all_status]);
        }
        return response()->json(["status" => false , 'status' => $status]);
    }

    public function addFeedbackTableData(Request $request)
    {
        $status = UserFeedbackStatus::get();

        $user_id = $request->user_id;
        $category = UserFeedbackCategory::where('user_id',$user_id)->groupBy('category')->get();
        return view('usermanagement::user-feedback-table',compact('category', 'status'));

    }
}
