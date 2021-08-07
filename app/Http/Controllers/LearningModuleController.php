<?php

namespace App\Http\Controllers;

use App\PushNotification;
use App\SatutoryTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers;
use App\User;
use App\Task;
use App\{Learning,LearningStatusHistory,LearningDueDateHistory};
use App\LearningModule;
use App\TaskStatus;
use App\Contact;
use App\Setting;
use App\Remark;
use App\DocumentRemark;
use App\DeveloperTask;
use App\NotificationQueue;
use App\ChatMessage;
use App\DeveloperTaskHistory;
use App\ScheduledMessage;
use App\WhatsAppGroup;
use App\WhatsAppGroupNumber;
use App\PaymentReceipt;
use App\ChatMessagesQuickData;
use App\Hubstaff\HubstaffMember;
use App\Hubstaff\HubstaffTask;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Storage;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use App\Helpers\HubstaffTrait;


class LearningModuleController extends Controller {

	use hubstaffTrait;

	public function __construct() {
		// $this->init(getenv('HUBSTAFF_SEED_PERSONAL_TOKEN'));
		$this->init(config('env.HUBSTAFF_SEED_PERSONAL_TOKEN'));
	}

	public function index( Request $request ) {
		if ( $request->input( 'selected_user' ) == '' ) {
			$userid = Auth::id();
			$userquery = ' AND (assign_from = ' . $userid . ' OR  master_user_id = ' . $userid . ' OR  id IN (SELECT task_id FROM task_users WHERE user_id = ' . $userid . ' AND type LIKE "%User%")) ';
		} else {
			$userid = $request->input( 'selected_user' );
			$userquery = ' AND (master_user_id = ' . $userid . ' OR  id IN (SELECT task_id FROM task_users WHERE user_id = ' . $userid . ' AND type LIKE "%User%")) ';
		}
		
		if ( !$request->input( 'type' ) || $request->input( 'type' ) == '' ) {
			$type = 'pending';
		} else {
			$type = $request->input( 'type' );
		}
		$activeCategories = LearningModule::where('is_active',1)->pluck('id')->all();

		$categoryWhereClause = '';
		$category = '';
		$request->category = $request->category ? $request->category : 1;
		if ($request->category != '') {
			if ($request->category != 1) {
				$categoryWhereClause = "AND category = $request->category";
				$category = $request->category;
			} else {
				$category_condition  = implode(',', $activeCategories);
				if ($category_condition != '' || $category_condition != null) {
					$category_condition = '( ' . $category_condition . ' )';
					$categoryWhereClause = "AND category in " . $category_condition;
				} else {
					$categoryWhereClause = "";
				}
			}
		}

		$term = $request->term ?? "";
		$searchWhereClause = '';

		if ($request->term != '') {
			$searchWhereClause = ' AND (id LIKE "%' . $term . '%" OR category IN (SELECT id FROM task_categories WHERE title LIKE "%' . $term . '%") OR task_subject LIKE "%' . $term . '%" OR task_details LIKE "%' . $term . '%" OR assign_from IN (SELECT id FROM users WHERE name LIKE "%' . $term . '%") OR id IN (SELECT task_id FROM task_users WHERE user_id IN (SELECT id FROM users WHERE name LIKE "%' . $term . '%")))';
		}
		// if ($request->get('is_statutory_query') != '' && $request->get('is_statutory_query') != null) {
		//     $searchWhereClause .= ' AND is_statutory = ' . $request->get('is_statutory_query');
		// }
		// else {
		// 	$searchWhereClause .= ' AND is_statutory != 3';
		// }
		$orderByClause = ' ORDER BY';
		if($request->sort_by == 1) {
			$orderByClause .= ' learnings.created_at desc,';
		}
		else if($request->sort_by == 2) {
			$orderByClause .= ' learnings.created_at asc,';
		}
		$data['task'] = [];

		$search_term_suggestions = [];
		$search_suggestions = [];
		$assign_from_arr = array(0);
		$special_task_arr = array(0);
		$assign_to_arr = array(0);
		$data['task']['pending'] = [];
		$data['task']['statutory_not_completed'] = [];
		$data['task']['completed'] = [];
		if($type == 'pending') {
			$paginate = 50;
    		$page = $request->get('page', 1);
			$offSet = ($page * $paginate) - $paginate; 
			
			$orderByClause .= ' is_flagged DESC, message_created_at DESC';
			$isCompleteWhereClose = ' AND is_verified IS NULL ';

			if(!Auth::user()->isAdmin()) {
				$isCompleteWhereClose = ' AND is_verified IS NULL ';
			}
			if($request->filter_by == 1) {
				$isCompleteWhereClose = ' AND is_completed IS NULL ';
			}
			if($request->filter_by == 2) {
				$isCompleteWhereClose = ' AND is_completed IS NOT NULL AND is_verified IS NULL ';
			}

			$data['task']['pending'] = DB::select('
			SELECT learnings.*

			FROM (
			  SELECT * FROM learnings
			  LEFT JOIN (
				  SELECT 
				  chat_messages.id as message_id, 
				  chat_messages.task_id, 
				  chat_messages.message, 
				  chat_messages.status as message_status, 
				  chat_messages.sent as message_type, 
				  chat_messages.created_at as message_created_at, 
				  chat_messages.is_reminder AS message_is_reminder,
				  chat_messages.user_id AS message_user_id
				  FROM chat_messages join chat_messages_quick_datas on chat_messages_quick_datas.last_communicated_message_id = chat_messages.id WHERE chat_messages.status not in(7,8,9) and chat_messages_quick_datas.model="App\\Task"
			  ) as chat_messages  ON chat_messages.task_id = learnings.id
			) AS learnings
			WHERE (id IS NOT NULL) AND is_statutory != 1 '.$isCompleteWhereClose.$userquery. $categoryWhereClause . $searchWhereClause .$orderByClause.' limit '.$paginate.' offset '.$offSet.'; ');


			foreach ($data['task']['pending'] as $task) {
				array_push($assign_to_arr, $task->assign_to);
				array_push($assign_from_arr, $task->assign_from);
				array_push($special_task_arr, $task->id);
			}
			
			$user_ids_from = array_unique($assign_from_arr);
			$user_ids_to = array_unique($assign_to_arr);
		
			foreach ($data['task']['pending'] as $task) {
				$search_suggestions[] = "#" . $task->id . " " . $task->task_subject . ' ' . $task->task_details;
				$from_exist = in_array($task->assign_from, $user_ids_from);
				if($from_exist) {
					$from_user = User::find($task->assign_from);
					if($from_user) {
						$search_term_suggestions[] = $from_user->name;
					}
				}

				$to_exist = in_array($task->assign_to, $user_ids_to);
				if($to_exist) {
					$to_user = User::find($task->assign_to);
					if($to_user) {
						$search_term_suggestions[] = $to_user->name;
					}
				}			
				$search_term_suggestions[] = "$task->id";
				$search_term_suggestions[] = $task->task_subject;
				$search_term_suggestions[] = $task->task_details;
			}
		}
		else if($type == 'completed') {
			$paginate = 50;
    		$page = $request->get('page', 1);
			$offSet = ($page * $paginate) - $paginate; 
			$orderByClause .= ' last_communicated_at DESC';
			$data['task']['completed'] = DB::select('
                SELECT *,
 				message_id,
                message,
                message_status,
                message_type,
                message_created_At as last_communicated_at
                FROM (
                  SELECT * FROM learnings
                 LEFT JOIN (
					SELECT 
					chat_messages.id as message_id, 
					chat_messages.task_id, 
					chat_messages.message, 
					chat_messages.status as message_status, 
					chat_messages.sent as message_type, 
					chat_messages.created_at as message_created_at, 
					chat_messages.is_reminder AS message_is_reminder,
					chat_messages.user_id AS message_user_id
					FROM chat_messages join chat_messages_quick_datas on chat_messages_quick_datas.last_communicated_message_id = chat_messages.id WHERE chat_messages.status not in(7,8,9) and chat_messages_quick_datas.model="App\\\\Task"
                 ) AS chat_messages ON chat_messages.task_id = learnings.id
                ) AS learnings
                WHERE (id IS NOT NULL) AND is_statutory != 1 AND is_verified IS NOT NULL '.$userquery . $categoryWhereClause . $searchWhereClause .$orderByClause.' limit '.$paginate.' offset '.$offSet.';');
				

				foreach ($data['task']['completed'] as $task) {
					array_push($assign_to_arr, $task->assign_to);
					array_push($assign_from_arr, $task->assign_from);
					array_push($special_task_arr, $task->id);
				}
				
				$user_ids_from = array_unique($assign_from_arr);
				$user_ids_to = array_unique($assign_to_arr);
			
				foreach ($data['task']['completed'] as $task) {
					$search_suggestions[] = "#" . $task->id . " " . $task->task_subject . ' ' . $task->task_details;
					$from_exist = in_array($task->assign_from, $user_ids_from);
					if($from_exist) {
						$from_user = User::find($task->assign_from);
						if($from_user) {
							$search_term_suggestions[] = $from_user->name;
						}
					}
	
					$to_exist = in_array($task->assign_to, $user_ids_to);
					if($to_exist) {
						$to_user = User::find($task->assign_to);
						if($to_user) {
							$search_term_suggestions[] = $to_user->name;
						}
					}			
					$search_term_suggestions[] = "$task->id";
					$search_term_suggestions[] = $task->task_subject;
					$search_term_suggestions[] = $task->task_details;
				}
		}
		else if($type == 'statutory_not_completed') {
			$paginate = 50;
    		$page = $request->get('page', 1);
			$offSet = ($page * $paginate) - $paginate; 
			$orderByClause .= ' last_communicated_at DESC';
			$data['task']['statutory_not_completed'] = DB::select('
	               SELECT *,
				   message_id,
	               message,
	               message_status,
	               message_type,
	               message_created_At as last_communicated_at

	               FROM (
	                 SELECT * FROM learnings
	                 LEFT JOIN (
							SELECT 
							chat_messages.id as message_id, 
							chat_messages.task_id, 
							chat_messages.message, 
							chat_messages.status as message_status, 
							chat_messages.sent as message_type, 
							chat_messages.created_at as message_created_at, 
							chat_messages.is_reminder AS message_is_reminder,
							chat_messages.user_id AS message_user_id
							FROM chat_messages join chat_messages_quick_datas on chat_messages_quick_datas.last_communicated_message_id = chat_messages.id WHERE chat_messages.status not in(7,8,9) and chat_messages_quick_datas.model="App\\\\Task"
	                 ) AS chat_messages ON chat_messages.task_id = learnings.id

	               ) AS learnings
				   WHERE (id IS NOT NULL) AND is_statutory = 1 AND is_verified IS NULL '.$userquery . $categoryWhereClause . $orderByClause .' limit '.$paginate.' offset '.$offSet.';');
				   
				   foreach ($data['task']['statutory_not_completed'] as $task) {
					array_push($assign_to_arr, $task->assign_to);
					array_push($assign_from_arr, $task->assign_from);
					array_push($special_task_arr, $task->id);
				}
				
				$user_ids_from = array_unique($assign_from_arr);
				$user_ids_to = array_unique($assign_to_arr);
			
				foreach ($data['task']['statutory_not_completed'] as $task) {
					$search_suggestions[] = "#" . $task->id . " " . $task->task_subject . ' ' . $task->task_details;
					$from_exist = in_array($task->assign_from, $user_ids_from);
					if($from_exist) {
						$from_user = User::find($task->assign_from);
						if($from_user) {
							$search_term_suggestions[] = $from_user->name;
						}
					}
	
					$to_exist = in_array($task->assign_to, $user_ids_to);
					if($to_exist) {
						$to_user = User::find($task->assign_to);
						if($to_user) {
							$search_term_suggestions[] = $to_user->name;
						}
					}			
					$search_term_suggestions[] = "$task->id";
					$search_term_suggestions[] = $task->task_subject;
					$search_term_suggestions[] = $task->task_details;
				}
		}
		else {
			//return;
		}

					
		$subjectList = Learning::select('learning_subject')->distinct()->pluck('learning_subject');

		$users                     = User::oldest()->get()->toArray();
		$data['users']             = $users;
		$data['daily_activity_date'] = $request->daily_activity_date ? $request->daily_activity_date : date('Y-m-d');

		
		// $category = '';
		//My code start
		$selected_user = $request->input( 'selected_user' );
		$users         = Helpers::getUserArray( User::orderby('name')->get() );
		$task_categories = LearningModule::where('parent_id', 0)->get();
		$learning_module_dropdown = nestable(LearningModule::where('is_approved', 1)->where('parent_id', 0)->get()->toArray())->attr(['name' => 'learning_module','class' => 'form-control input-sm parent-module'])
		->selected($request->category)
		->renderAsDropdown();

		$learning_submodule_dropdown = LearningModule::where('is_approved', 1)->where('parent_id', '1')->get();

		$categories = [];
		foreach (LearningModule::all() as $category) {
			$categories[$category->id] = $category->title;
		}
		if ( ! empty( $selected_user ) && ! Helpers::getadminorsupervisor() ) {
			return response()->json( [ 'user not allowed' ], 405 );
		}
		//My code end
		$tasks_view = [];
		$priority  = \App\ErpPriority::where('model_type', '=', Learning::class)->pluck('model_id')->toArray();

		$openTask = \App\Learning::join("users as u","u.id","learnings.assign_to")
		->whereNull("learnings.is_completed")
		->groupBy("learnings.assign_to")
		->select(\DB::raw("count(u.id) as total"),"u.name as person")
		->pluck("total","person");

		if($request->is_statutory_query == 3) {
			$title = 'Discussion learnings';
		}
		else {
			$title = 'Learning & Activity';
		}

	    $task_statuses=TaskStatus::all();

		if ($request->ajax()) {
			if($type == 'pending') {
				return view( 'learning-module.partials.pending-row-ajax', compact('data', 'users', 'selected_user','category', 'term', 'search_suggestions', 'search_term_suggestions', 'tasks_view', 'categories', 'task_categories', 'task_categories_dropdown', 'priority','openTask','type','title','task_statuses'));
			}
			else if( $type == 'statutory_not_completed') {
				return view( 'learning-module.partials.statutory-row-ajax', compact('data', 'users', 'selected_user','category', 'term', 'search_suggestions', 'search_term_suggestions', 'tasks_view', 'categories', 'task_categories', 'task_categories_dropdown', 'priority','openTask','type','title','task_statuses'));
			}
			else if( $type == 'completed') {
				return view( 'learning-module.partials.completed-row-ajax', compact('data', 'users', 'selected_user','category', 'term', 'search_suggestions', 'search_term_suggestions', 'tasks_view', 'categories', 'task_categories', 'task_categories_dropdown', 'priority','openTask','type','title','task_statuses'));
			}
			else {
				return view( 'learning-module.partials.pending-row-ajax', compact('data', 'users', 'selected_user','category', 'term', 'search_suggestions', 'search_term_suggestions', 'tasks_view', 'categories', 'task_categories', 'task_categories_dropdown', 'priority','openTask','type','title','task_statuses'));
			}
		}

		if($request->is_statutory_query == 3) {
			return view( 'learning-module.discussion-tasks', compact('data', 'users', 'selected_user','category', 'term', 'search_suggestions', 'search_term_suggestions', 'tasks_view', 'categories', 'task_categories', 'learning_module_dropdown', 'learning_submodule_dropdown', 'priority','openTask','type','title','task_statuses'));
		}
		else {

			$statusList = \DB::table("task_statuses")->orderBy('name','asc')->pluck("name", "id")->toArray();

			$learningsListing = Learning::query();

			if(!empty($request->get('user_id'))){
				$learningsListing->where('learning_user',$request->get('user_id'));
			}

			if(!empty($request->get('subject'))){
				$subject = $request->get('subject');
				$learningsListing->where('learning_subject','LIKE', "%$subject%");
			}

			if (!empty($request->get('task_status'))) {
	            $learningsListing->whereIn('learning_status', $request->get('task_status'));
	        }

      if (!empty($request->get('overduedate'))) {
          $learningsListing->whereDate('learning_duedate','<',$request->get('overduedate'));
      }

      if (!empty($request->get('module'))) {
          $learningsListing->where('learning_module',$request->get('module'));
      }

      if (!empty($request->get('submodule'))) {
          $learningsListing->where('learning_submodule',$request->get('submodule'));
      }

			$learningsListing = $learningsListing->latest()->get();


			$last_record_learning = Learning::with('learningUser')->latest()->first();

			// echo "<pre>";
			// print_r($last_record_learning->toArray());
			// exit;


			return view( 'learning-module.show', compact('data', 'users', 'selected_user','category', 'term', 'search_suggestions', 'search_term_suggestions', 'tasks_view', 'categories', 'task_categories', 'learning_module_dropdown', 'learning_submodule_dropdown', 'priority','openTask','type','title','task_statuses','learningsListing','statusList','subjectList','last_record_learning'));
		}
	}


	// public function createTask() {
	// 	$users                     = User::oldest()->get()->toArray();
	// 	$data['users']             = $users;
	// 	$task_categories = TaskCategory::where('parent_id', 0)->get();
	// 	$task_categories_dropdown = nestable(TaskCategory::where('is_approved', 1)->get()->toArray())->attr(['name' => 'category','class' => 'form-control input-sm'])
	// 	                                        ->renderAsDropdown();

	// 	$categories = [];
	// 	foreach (TaskCategory::all() as $category) {
	// 		$categories[$category->id] = $category->title;
	// 	}
	// 	return view( 'learning-module.create-task',compact('data','task_categories','task_categories_dropdown','categories'));
	// }

	public function updateCost(Request $request) {
		$task = Learning::find($request->task_id);

		// if($task && $request->approximate) {
        //     DeveloperTaskHistory::create([
		// 		'developer_task_id' => $task->id,
		// 		'model' => 'App\Task',
        //         'attribute' => "estimation_minute",
        //         'old_value' => $task->approximate,
        //         'new_value' => $request->approximate,
        //         'user_id' => auth()->id(),
        //     ]);
		// }
		if(Auth::user()->isAdmin()) {
			$task->cost = $request->cost;
			$task->save();
			return response()->json(['msg' => 'success']);
		}
		else {
			return response()->json(['msg' => 'Not authorized user to update'],500);
		}
		
	}




	public function saveMilestone(Request $request)
    {
		$task = Learning::find($request->task_id);
        if(!$task->is_milestone) {
            return;
        }
        $total = $request->total;
        if($task->milestone_completed) {
            if($total <= $task->milestone_completed) {
                return response()->json([
                    'message' => 'Milestone no can\'t be reduced'
                ],500);
            }
        }

        if($total > $task->no_of_milestone) {
            return response()->json([
                'message' => 'Estimated milestone exceeded'
            ],500);
        }
        if(!$task->cost || $task->cost == '') {
            return response()->json([
                'message' => 'Please provide cost first'
            ],500);
        }

        $newCompleted = $total - $task->milestone_completed;
        $individualPrice = $task->cost / $task->no_of_milestone;
        $totalCost = $individualPrice * $newCompleted;

        $task->milestone_completed = $total;
        $task->save();
        $payment_receipt = new PaymentReceipt;
        $payment_receipt->date = date( 'Y-m-d' );
        $payment_receipt->worked_minutes = $task->approximate;
        $payment_receipt->rate_estimated = $totalCost;
        $payment_receipt->status = 'Pending';
        $payment_receipt->task_id = $task->id;
        $payment_receipt->user_id = $task->assign_to;
		$payment_receipt->save();
		
        return response()->json([
            'status' => 'success'
        ]);
    }

	public function updateApproximate(Request $request) {
		$task = Learning::find($request->task_id);

		if(Auth::user()->id == $task->assign_to || Auth::user()->isAdmin()) {
			if($task && $request->approximate) {
				DeveloperTaskHistory::create([
					'developer_task_id' => $task->id,
					'model' => 'App\Task',
					'attribute' => "estimation_minute",
					'old_value' => $task->approximate,
					'new_value' => $request->approximate,
					'user_id' => auth()->id(),
				]);
			}
	
			$task->approximate = $request->approximate;
			$task->save();
			return response()->json(['msg' => 'success']);
		}
		else {
			return response()->json(['msg' => 'Unauthorized access'],500);
		}
		
	}

	public function updatePriorityNo(Request $request) {
		$task = Learning::find($request->task_id);

		if(Auth::user()->id == $task->assign_to || Auth::user()->isAdmin()) {
			$task->priority_no = $request->priority;
			$task->save();
			return response()->json(['msg' => 'success']);
		}
		else {
			return response()->json(['msg' => 'Unauthorized access'],500);
		}
		
	}

	public function learningListByUserId(Request $request)
    {
        $user_id = $request->get('user_id' , 0);
        $selected_issue = $request->get('selected_issue' , []);

        $issues = Learning::select('learnings.id', 'learnings.task_subject', 'learnings.task_details', 'learnings.assign_from')
                        ->leftJoin('erp_priorities', function($query){
                            $query->on('erp_priorities.model_id', '=', 'learnings.id');
                            $query->where('erp_priorities.model_type', '=', Learning::class);
                        })->whereNull('is_verified');

        if (auth()->user()->isAdmin()) {
            $issues = $issues->where(function($q) use ($selected_issue, $user_id) {
            	$user_id = is_null($user_id) ? 0 : $user_id;
            	$q->whereIn('learnings.id', $selected_issue)->orWhere("erp_priorities.user_id", $user_id);
            });
        } else {
            $issues = $issues->whereNotNull('erp_priorities.id');
        }

        $issues = $issues->groupBy('learnings.id')->orderBy('erp_priorities.id')->get();

        foreach ($issues as &$value) {
            $value->created_by = User::where('id', $value->assign_from)->value('name');
        }
        unset($value);
        
        return response()->json($issues);
    }

    public function setTaskPriority(Request $request)
    {
        $priority = $request->get('priority', null);
        $user_id = $request->get('user_id', 0);
        //get all user task
        //$developerTask = Learning::where('assign_to', $user_id)->pluck('id')->toArray();
        
        //delete old priority
        \App\ErpPriority::where('user_id', $user_id)->where('model_type', '=', Learning::class)->delete();
        
        if (!empty($priority)) {
            foreach ((array)$priority as $model_id) {
                \App\ErpPriority::create([
                    'model_id' => $model_id, 
                    'model_type' => Learning::class,
                    'user_id' => $user_id
                ]);
            }

            $developerTask = Learning::select('learnings.id', 'learnings.task_subject', 'learnings.task_details', 'learnings.assign_from')
			                        ->join('erp_priorities', function($query) use ($user_id){
			                        	$user_id = is_null($user_id) ? 0 : $user_id;
			                            $query->on('erp_priorities.model_id', '=', 'learnings.id');
			                            $query->where('erp_priorities.model_type', '=', Learning::class);
			                            $query->where('user_id', $user_id);
			                        })
			                        ->whereNull('is_verified')
			                        ->orderBy('erp_priorities.id')
			                        ->get();                      

            $message = "";
            $i = 1;
            
            foreach ($developerTask as $value) {
                $message .= $i ." : #Task-" . $value->id . "-" . $value->task_subject."\n";
                $i++;
            }

            if (!empty($message)) {
                $requestData = new Request();
                $requestData->setMethod('POST');
                $params = [];
                $params['user_id'] = $user_id;

                $string = "";

                if(!empty($request->get('global_remarkes', null))) {
                    $string .= $request->get('global_remarkes')."\n";
                }

                $string .= "Task Priority is : \n".$message;

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

	public function store( Request $request ) {
		dd("We are not using this function anymore, If you reach here, that means that we have to change this.");
		$this->validate($request, [
			'task_subject'	=> 'required',
			'task_details'	=> 'required',
			'assign_to' => 'required_without:assign_to_contacts'
		]);
		$data = $request->except( '_token' );
		$data['assign_from'] = Auth::id();

		if ($request->task_type == 'quick_task') {
			$data['is_statutory'] = 0;
			$data['category'] = 6;
			$data['model_type'] = $request->model_type;
			$data['model_id'] = $request->model_id;
		}

		if ($request->task_type == 'note-task') {
			$main_task = Learning::find($request->task_id);
		} else {
			if ($request->assign_to) {
				$data['assign_to'] = $request->assign_to[0];
			} else {
				$data['assign_to'] = $request->assign_to_contacts[0];
			}
		}
		

			$task = Learning::create($data);

			// dd($request->all());
			if ($request->is_statutory == 3) {
				foreach ($request->note as $note) {
					if ($note != null) {
						Remark::create([
							'taskid'	=> $task->id,
							'remark'	=> $note,
							'module_type'	=> 'task-note'
						]);
					}
				}
			}

			if ($request->task_type != 'note-task') {
				if ($request->assign_to) {
					foreach ($request->assign_to as $user_id) {
						$task->users()->attach([$user_id => ['type' => User::class]]);
					}
				}

				if ($request->assign_to_contacts) {
					foreach ($request->assign_to_contacts as $contact_id) {
						$task->users()->attach([$contact_id => ['type' => Contact::class]]);
					}
				}
			}

			if ($task->is_statutory != 1) {
				$message = "#" . $task->id . ". " . $task->task_subject . ". " . $task->task_details;
			} else {
				$message = $task->task_subject . ". " . $task->task_details;
			}

			$params = [
			 'number'       => NULL,
			 'user_id'      => Auth::id(),
			 'approved'     => 1,
			 'status'       => 2,
			 'task_id'			=> $task->id,
			 'message'      => $message
		    ];
		 if (count($task->users) > 0) {
			 if ($task->assign_from == Auth::id()) {
				 foreach ($task->users as $key => $user) {
					 if ($key == 0) {
						 $params['erp_user'] = $user->id;
					 } else {
						 app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message']);
					 }
				 }
			 } else {
				 foreach ($task->users as $key => $user) {
					 if ($key == 0) {
						 $params['erp_user'] = $task->assign_from;
					 } else {
						 if ($user->id != Auth::id()) {
							 app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message']);
						 }
					 }
				 }
			 }
		 }

		 if (count($task->contacts) > 0) {
			 foreach ($task->contacts as $key => $contact) {
				 if ($key == 0) {
					 $params['contact_id'] = $task->assign_to;
				 } else {
					 app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($contact->phone, NULL, $params['message']);
				 }
			 }
		 }

			$chat_message = ChatMessage::create($params);
			ChatMessagesQuickData::updateOrCreate([
                'model' => \App\Learning::class,
                'model_id' => $params['task_id']
                ], [
                'last_communicated_message' => @$params['message'],
                'last_communicated_message_at' => $chat_message->created_at,
                'last_communicated_message_id' => ($chat_message) ? $chat_message->id : null,
            ]);
			 

			$myRequest = new Request();
      		$myRequest->setMethod('POST');
      		$myRequest->request->add(['messageId' => $chat_message->id]);

			  app('App\Http\Controllers\WhatsAppController')->approveMessage('task', $myRequest);
			  
			//   $hubstaff_project_id = getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID');
			  $hubstaff_project_id = config('env.HUBSTAFF_BULK_IMPORT_PROJECT_ID');

			  $assignedUser = HubstaffMember::where('user_id', $request->input('assign_to'))->first();
			  // $hubstaffProject = HubstaffProject::find($request->input('hubstaff_project'));
	  
			  $hubstaffUserId = null;
			  if ($assignedUser) {
				  $hubstaffUserId = $assignedUser->hubstaff_user_id;
			  }
			  $taskSummery = substr($message, 0, 200);
			  
	  
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
				  $task->summary = $message;
				  $task->save();
			  }

			$task_statuses=TaskStatus::all();  

			if ($request->ajax()) {
				$hasRender = request("has_render", false);
				
				if(!empty($hasRender)) {
					
					$users      = Helpers::getUserArray( User::all() );
					$priority  	= \App\ErpPriority::where('model_type', '=', Learning::class)->pluck('model_id')->toArray();

					
					if($task->is_statutory == 1) {
						$mode = "learning-module.partials.statutory-row";
					}
					else if($task->is_statutory == 3) {
						$mode = "learning-module.partials.discussion-pending-raw";
					}
					else {
						$mode = "learning-module.partials.pending-row";
					}

					$view = (string)view($mode,compact('task','priority','users','task_statuses'));
					return response()->json(["code" => 200, "statutory" => $task->is_statutory , "raw" => $view]);	

				}

				return response('success');
			}

			return redirect()->back()->with( 'success', 'Task created successfully.' );
	}

			// echo "<pre>";
			// print_r($last_record_learning->toArray());
			// exit;

	private function createHubstaffTask(string $taskSummary, ?int $hubstaffUserId, int $projectId, bool $shouldRetry = true)
    {
        $tokens = $this->getTokens();
       // echo '<pre>';print_r($tokens);

        

       

        $url = 'https://api.hubstaff.com/v2/projects/' . $projectId . '/learnings';

        
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
                else
                {

                }
        	}
        }
       
        return false;
	}

	
	public function flag(Request $request)
	{
		$task = Learning::find($request->task_id);

		if ($task->is_flagged == 0) {
			$task->is_flagged = 1;
		} else {
			$task->is_flagged = 0;
		}

		$task->save();

		return response()->json(['is_flagged' => $task->is_flagged]);
	}

	public function remarkFlag(Request $request)
	{
		$remark = Remark::find($request->remark_id);

		if ($remark->is_flagged == 0) {
			$remark->is_flagged = 1;
		} else {
			$remark->is_flagged = 0;
		}

		$remark->save();

		return response()->json(['is_flagged' => $remark->is_flagged]);
	}

	public function plan(Request $request, $id)
	{
		$task = Learning::find($id);
		$task->time_slot = $request->time_slot;
		$task->planned_at = $request->planned_at;
		$task->general_category_id = $request->get("general_category_id",null);
		$task->save();

		return response()->json([
			'task'	=> $task
		]);
	}

	public function loadView(Request $request)
	{
		$tasks = Learning::whereIn('id', $request->selected_tasks)->get();
		$users = Helpers::getUserArray(User::all());
		$view = view('learning-module.partials.learning-view', [
			'tasks_view' => $tasks,
			'users'			=> $users
			])->render();

		return response()->json([
			'view'	=> $view
		]);
	}

	public function assignMessages(Request $request)
	{
		$messages_ids = json_decode($request->selected_messages, true);

		foreach ($messages_ids as $message_id) {
			$message = ChatMessage::find($message_id);
			$message->task_id = $request->task_id;
			$message->save();
		}

		return redirect()->back()->withSuccess('You have successfully assign messages');
	}

	public function messageReminder(Request $request)
	{
		$this->validate($request, [
			'message_id'		=> 'required|numeric',
			'reminder_date'	=> 'required'
		]);

		$message = ChatMessage::find($request->message_id);

		$additional_params = [
			'user_id'	=> $message->user_id,
			'task_id'	=> $message->task_id,
			'erp_user'	=> $message->erp_user,
			'contact_id'	=> $message->contact_id,
		];

		$params = [
			'user_id'       => Auth::id(),
			'message'       => "Reminder - " . $message->message,
			'type'					=> 'task',
			'data'					=> json_encode($additional_params),
			'sending_time'  => $request->reminder_date
		];

		ScheduledMessage::create($params);

		return redirect()->back()->withSuccess('You have successfully set a reminder!');
	}

	public function convertTask(Request $request, $id)
	{
		$task = Learning::find($id);

		$task->is_statutory = 3;
		$task->save();

		return response('success', 200);
	}

	public function updateSubject(Request $request, $id)
	{
		$task = Learning::find($id);
		$task->task_subject = $request->subject;
		$task->save();

		return response('success', 200);
	}

	public function addNote(Request $request, $id)
	{
		Remark::create([
			'taskid'	=> $id,
			'remark'	=> $request->note,
			'module_type'	=> 'task-note'
		]);

		return response('success', 200);
	}

	public function addSubnote(Request $request, $id)
	{
		$remark = Remark::create([
			'taskid'	=> $id,
			'remark'	=> $request->note,
			'module_type'	=> 'task-note-subnote'
		]);

		$id = $remark->id;

		return response(['success' => $id], 200);
	}

	public function updateCategory(Request $request, $id)
	{
		$task = Learning::find($id);
		$task->category = $request->category;
		$task->save();

		return response('success', 200);
	}

	public function show($id)
	{
		$task = Learning::find($id);
		$chatMessages = ChatMessage::where('task_id',$id)->get();
		if ((!$task->users->contains(Auth::id()) && $task->is_private == 1) || ($task->assign_from != Auth::id() && $task->contacts()->count() > 0) || (!$task->users->contains(Auth::id()) && $task->assign_from != Auth::id() && Auth::id() != 6)) {
			return redirect()->back()->withErrors("This Learning is private!");
		}

		$users = User::all();
		$users_array = Helpers::getUserArray(User::all());
		$categories = LearningModule::attr(['title' => 'category','class' => 'form-control input-sm', 'placeholder' => 'Select a Category', 'id' => 'task_category'])
																						->selected($task->category)
												->renderAsDropdown();
		
		if (request()->has('keyword')) {
			$taskNotes = $task->notes()->orderBy('is_flagged')->where('is_hide', 0)->where('remark', 'like', '%' . request()->keyword . '%')->paginate(20);
		} else {
			$taskNotes = $task->notes()->orderBy('is_flagged')->where('is_hide', 0)->paginate(20);
		}
		
		$hiddenRemarks = $task->notes()->where('is_hide', 1)->get();
		return view('learning-module.learning-show', [
			'task'	=> $task,
			'users'	=> $users,
			'users_array'	=> $users_array,
			'categories'	=> $categories,
			'taskNotes'	=> $taskNotes,
			'hiddenRemarks'	=> $hiddenRemarks,
			'chatMessages'	=> $chatMessages,
		]);
	}

	public function update(Request $request, $id) {
		$this->validate($request, [
			'assign_to.*'		=> 'required_without:assign_to_contacts',
			'sending_time'	=> 'sometimes|nullable|date'
		]);

		$task = Learning::find($id);
		$task->users()->detach();
		$task->contacts()->detach();

		if ($request->assign_to) {
			foreach ($request->assign_to as $user_id) {
				$task->users()->attach([$user_id => ['type' => User::class]]);
			}

			$task->assign_to = $request->assign_to[0];
		}

		if ($request->assign_to_contacts) {
			foreach ($request->assign_to_contacts as $contact_id) {
				$task->users()->attach([$contact_id => ['type' => Contact::class]]);
			}

			$task->assign_to = $request->assign_to_contacts[0];
		}

		if ($request->sending_time) {
			$task->sending_time = $request->sending_time;
		}

		$task->save();

		return redirect()->route('task.show', $id)->withSuccess('You have successfully reassigned users!');
	}

	public function makePrivate(Request $request, $id)
	{
		$task = Learning::find($id);

		if ($task->is_private == 1) {
			$task->is_private = 0;
		} else {
			$task->is_private = 1;
		}

		$task->save();

		return response()->json([
			'task'	=> $task
		]);
	}

	public function isWatched(Request $request, $id)
	{
		$task = Learning::find($id);

		if ($task->is_watched == 1) {
			$task->is_watched = 0;
		} else {
			$task->is_watched = 1;
		}

		$task->save();

		return response()->json([
			'task'	=> $task
		]);
	}

	public function complete(Request $request, $taskid ) {

		$task  = Learning::find( $taskid );
		// $task->is_completed = date( 'Y-m-d H:i:s' );
//		$task->deleted_at = null;

		// if ( $task->assign_to == Auth::id() ) {
		// 	$task->save();
		// }

		// $tasks = Learning::where('category', $task->category)->where('assign_from', $task->assign_from)->where('is_statutory', $task->is_statutory)->where('task_details', $task->task_details)->where('task_subject', $task->task_subject)->get();
		//
		// foreach ($tasks as $item) {
		// 	if ($request->type == 'complete') {
		// 		if ($item->is_completed == '') {
		// 			$item->is_completed = date( 'Y-m-d H:i:s' );
		// 		} else if ($item->is_verified == '') {
		// 			$item->is_verified = date( 'Y-m-d H:i:s' );
		// 		}
		// 	} else if ($request->type == 'clear') {
		// 		$item->is_completed = NULL;
		// 		$item->is_verified = NULL;
		// 	}
		//
		// 	$item->save();
		// }
		if ($request->type == 'complete') {
			if (is_null($task->is_completed)) {
				$task->is_completed = date( 'Y-m-d H:i:s' );
			} else if (is_null($task->is_verified)) {
				if($task->assignedTo) {
					if($task->assignedTo->fixed_price_user_or_job == 1) {
						// Fixed price task.
						if($task->cost == null) {
							if ($request->ajax()) {
								return response()->json([
									'message'	=> 'Please provide cost for fixed price task.'
								],500);
							}
					
							return redirect()->back()
											 ->with( 'error', 'Please provide cost for fixed price task.' );
						}
						if(!$task->is_milestone) {
							$payment_receipt = new PaymentReceipt;
							$payment_receipt->date = date( 'Y-m-d' );
							$payment_receipt->worked_minutes = $task->approximate;
							$payment_receipt->rate_estimated = $task->cost;
							$payment_receipt->status = 'Pending';
							$payment_receipt->task_id = $task->id;
							$payment_receipt->user_id = $task->assign_to;
							$payment_receipt->save();
						}
					}
				}
				$task->is_verified = date( 'Y-m-d H:i:s' );
			}
		} else if ($request->type == 'clear') {
			$task->is_completed = NULL;
			$task->is_verified = NULL;
		}
		$task->save();

		// if($task->is_statutory == 0)
		// 	$message = 'Task Completed: ' . $task->task_details;
		// else
		// 	$message = 'Recurring Task Completed: ' . $task->task_details;

		// PushNotification::create( [
		// 	'message'    => $message,
		// 	'model_type' => Learning::class,
		// 	'model_id'   => $task->id,
		// 	'user_id'    => Auth::id(),
		// 	'sent_to'    => $task->assign_from,
		// 	'role'       => '',
		// ] );
		//
		// PushNotification::create( [
		// 	'message'    => $message,
		// 	'model_type' => Learning::class,
		// 	'model_id'   => $task->id,
		// 	'user_id'    => Auth::id(),
		// 	'sent_to'    => '',
		// 	'role'       => 'Admin',
		// ] );

		// $notification_queues = NotificationQueue::where('model_id', $task->id)->where('model_type', 'App\Task')->delete();

		if ($request->ajax()) {
			return response()->json([
				'task'	=> $task
			]);
		}

		return redirect()->back()
		                 ->with( 'success', 'Task marked as completed.' );
	}

	public function start(Request $request, $taskid ) {

		$task               = Learning::find( $taskid );

		$task->actual_start_date = date( 'Y-m-d H:i:s' );
		$task->save();

		if ($request->ajax()) {
			return response()->json([
				'task'	=> $task
			]);
		}

		return redirect()->back()->with( 'success', 'Task started.' );
	}

	public function statutoryComplete( $taskid ) {

		$task               = SatutoryTask::find( $taskid );
		$task->completion_date = date( 'Y-m-d H:i:s' );
//		$task->deleted_at = null;

		if ( $task->assign_to == Auth::id() ) {
			$task->save();
		}

		$message = 'Statutory Task Completed: ' . $task->task_details;

		// $notification_queues = NotificationQueue::where('model_id', $task->id)->where('model_type', 'App\StatutoryTask')->delete();

		// PushNotification::create( [
		// 	'message'    => $message,
		// 	'model_type' => SatutoryLearning::class,
		// 	'model_id'   => $task->id,
		// 	'user_id'    => Auth::id(),
		// 	'sent_to'    => $task->assign_from,
		// 	'role'       => '',
		// ] );

		return redirect()->back()
		                 ->with( 'success', 'Statutory Task marked as completed.' );
	}

	public function addRemark( Request $request ) {

		$remark       = $request->input( 'remark' );
		$id           = $request->input( 'id' );
		$created_at = date('Y-m-d H:i:s');
		$update_at = date('Y-m-d H:i:s');
		if($request->module_type=="document"){
			$remark_entry = DocumentRemark::create([
				'document_id'	=> $id,
				'remark'	=> $remark,
				'module_type'	=> $request->module_type,
				'user_name'	=> $request->user_name ? $request->user_name : Auth::user()->name
			]);
		}
		else{
			$remark_entry = Remark::create([
				'taskid'	=> $id,
				'remark'	=> $remark,
				'module_type'	=> $request->module_type,
				'user_name'	=> $request->user_name ? $request->user_name : Auth::user()->name
			]);
		}

		if ($request->module_type == 'task-discussion') {
			// NotificationQueueController::createNewNotification([
			// 	'message' => 'Remark for Developer Task',
			// 	'timestamps' => ['+0 minutes'],
			// 	'model_type' => DeveloperLearning::class,
			// 	'model_id' =>  $id,
			// 	'user_id' => Auth::id(),
			// 	'sent_to' => $request->user == Auth::id() ? 6 : $request->user,
			// 	'role' => '',
			// ]);

			// NotificationQueueController::createNewNotification([
			// 	'message' => 'Remark for Developer Task',
			// 	'timestamps' => ['+0 minutes'],
			// 	'model_type' => DeveloperLearning::class,
			// 	'model_id' =>  $id,
			// 	'user_id' => Auth::id(),
			// 	'sent_to' => 56,
			// 	'role' => '',
			// ]);
		}

		// if ($request->module_type == 'developer') {
		// 	$task = DeveloperTask::find($id);
		//
		// 	if ($task->user->id == Auth::id()) {
		// 		NotificationQueueController::createNewNotification([
		// 			'message' => 'New Task Remark',
		// 			'timestamps' => ['+0 minutes'],
		// 			'model_type' => DeveloperLearning::class,
		// 			'model_id' =>  $task->id,
		// 			'user_id' => Auth::id(),
		// 			'sent_to' => 6,
		// 			'role' => '',
		// 		]);
		//
		// 		NotificationQueueController::createNewNotification([
		// 			'message' => 'New Task Remark',
		// 			'timestamps' => ['+0 minutes'],
		// 			'model_type' => DeveloperLearning::class,
		// 			'model_id' =>  $task->id,
		// 			'user_id' => Auth::id(),
		// 			'sent_to' => 56,
		// 			'role' => '',
		// 		]);
		// 	} else {
		// 		NotificationQueueController::createNewNotification([
		// 			'message' => 'New Task Remark',
		// 			'timestamps' => ['+0 minutes'],
		// 			'model_type' => DeveloperLearning::class,
		// 			'model_id' =>  $task->id,
		// 			'user_id' => Auth::id(),
		// 			'sent_to' => $task->user_id,
		// 			'role' => '',
		// 		]);
		// 	}
		// }
		// $remark_entry = DB::insert('insert into remarks (taskid, remark, created_at, updated_at) values (?, ?, ?, ?)', [$id  ,$remark , $created_at, $update_at]);

		// if (is_null($request->module_type)) {
		// 	$task = Learning::find($remark_entry->taskid);
		//
		// 	PushNotification::create( [
		// 		'message'    => 'Remark added: ' . $remark,
		// 		'model_type' => Learning::class,
		// 		'model_id'   => $task->id,
		// 		'user_id'    => Auth::id(),
		// 		'sent_to'    => $task->assign_from,
		// 		'role'       => '',
		// 	] );
		//
		// 	PushNotification::create( [
		// 		'message'    => 'Remark added: ' . $remark,
		// 		'model_type' => Learning::class,
		// 		'model_id'   => $task->id,
		// 		'user_id'    => Auth::id(),
		// 		'sent_to'    => '',
		// 		'role'       => 'Admin',
		// 	] );
		// }


		return response()->json(['remark' => $remark ],200);
	}

	public function list(Request $request)
	{
		$pending_tasks = Learning::where('is_statutory', 0)->whereNull('is_completed')->where('assign_from', Auth::id());
		$completed_tasks = Learning::where('is_statutory', 0)->whereNotNull('is_completed')->where('assign_from', Auth::id());

		if (is_array($request->user) && $request->user[0] != null) {
			$pending_tasks = $pending_tasks->whereIn('assign_to', $request->user);
			$completed_tasks = $completed_tasks->whereIn('assign_to', $request->user);
		}

		if ($request->date != null) {
			$pending_tasks = $pending_tasks->where('created_at', 'LIKE', "%$request->date%");
			$completed_tasks = $completed_tasks->where('created_at', 'LIKE', "%$request->date%");
		}

		$pending_tasks = $pending_tasks->oldest()->paginate(Setting::get('pagination'));
		$completed_tasks = $completed_tasks->orderBy('is_completed', 'DESC')->paginate(Setting::get('pagination'), ['*'], 'completed-page');

		$users = Helpers::getUserArray(User::all());
		$user = $request->user ?? [];
		$date = $request->date ?? '';

		return view('learning-module.list', [
			'pending_tasks'		=> $pending_tasks,
			'completed_tasks'	=> $completed_tasks,
			'users'						=> $users,
			'user'						=> $user,
			'date'						=> $date
		]);
	}

	public function getremark( Request $request ) {

		$id   = $request->input( 'id' );

		$task = Learning::find( $id );

		echo $task->remark;
	}


	public function deleteTask(Request $request){

		$id   = $request->input( 'id' );
		$task = Learning::find( $id );
		
		if($task ) {
			
			$task->remark = $request->input( 'comment' );
			$task->save();

			$task->delete();

		}


		if ($request->ajax()) {
			return response()->json(["code" => 200]);
		}

	}

	public function archiveTask($id)
	{
		$task = Learning::find($id);

		$task->delete();
		
		if ($request->ajax()) {
			return response('success');
		}
		return redirect('/');
	}

	public function archiveTaskRemark($id)
	{
		$task = Remark::find($id);
		$remark  = $task->remark;
		$task->delete_at = now();
		$task->update();
		
		return response(['success' => $remark],200);
	}

	public function deleteStatutoryTask(Request $request){

		$id   = $request->input( 'id' );
		$task = SatutoryTask::find( $id );
		$task->delete();

		return redirect()->back();
	}

	public function exportTask(Request $request){

		$users = $request->input('selected_user');
		$from = $request->input( 'range_start' ) . " 00:00:00.000000";
		$to   = $request->input( 'range_end' ) . " 23:59:59.000000";

		$tasks = (new Task())->newQuery()->withTrashed()->whereBetween('created_at',[$from,$to])->where('assign_from', '!=', 0)->where('assign_to', '!=', 0);

		if( !empty($users) ){
			$tasks = $tasks->whereIn('assign_to',$users);
		}

		$tasks_list =  $tasks->get()->toArray();
		$tasks_csv = [];
		$userList = Helpers::getUserArray( User::all() );

		for ($i = 0 ; $i < sizeof($tasks_list) ; $i++){

			$task_csv = [];
			$task_csv['id'] = $tasks_list[$i]['id'];
			$task_csv['SrNo'] = $i+1;
			$task_csv['assign_from'] = $userList[$tasks_list[$i]['assign_from']];
			$task_csv['assign_to'] = $userList[$tasks_list[$i]['assign_to']];
			$task_csv['type'] = $tasks_list[$i]['is_statutory'] == 1 ? 'Statutory' : 'Other';
			$task_csv['task_subject'] = $tasks_list[$i]['task_subject'];
			$task_csv['task_details'] = $tasks_list[$i]['task_details'];
			$task_csv['completion_date'] = $tasks_list[$i]['completion_date'];
			$task_csv['remark'] = $tasks_list[$i]['remark'];
			$task_csv['completed_on'] = $tasks_list[$i]['is_completed'];
			$task_csv['created_on'] = $tasks_list[$i]['created_at'];

			array_push($tasks_csv,$task_csv);
		}

		// $this->outputCsv('tasks.csv', $tasks_csv);
		return view('learning-module.export')->withTasks($tasks_csv);
	}

	public function outputCsv($fileName, $assocDataArray)
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


	public static function getClasses($task){

		$classes = ' ';
		// dump($task);
		$classes .= ' '. ( (empty($task) && $task->assign_from == Auth::user()->id) ? 'mytask' : '' ) . ' ';
		$classes .= ' '.( (empty($task) && time() > strtotime( $task->completion_date. ' 23:59:59'  ))  ? 'isOverdue' : '').' ';


		$task_status = empty($task) ? Helpers::statusClass($task->assign_status) : '';

		$classes .= $task_status;

		return $classes;
	}

	public function recurringTask(){

		$statutory_tasks = SatutoryTask::all()->toArray();

		foreach ($statutory_tasks as $statutory_task){

			switch ( $statutory_task['recurring_type'] ){

				case 'EveryDay':
					self::createTasksFromSatutary($statutory_task);
				break;

				case 'EveryWeek':
					if( $statutory_task['recurring_day'] == date('D') )
					self::createTasksFromSatutary($statutory_task);
				break;

				case 'EveryMonth':
					if( $statutory_task['recurring_day'] == date('d') )
					self::createTasksFromSatutary($statutory_task);
				break;

				case 'EveryYear':
					$dayNdate  = date('d-n',strtotime($statutory_task['recurring_day']));
					if( $dayNdate == date('d-n') )
					self::createTasksFromSatutary($statutory_task);
				break;
			}
		}
	}

	public static function createTasksFromSatutary($statutory_task){

		$statutory_task['is_statutory'] = 1;
		$statutory_task['statutory_id'] = $statutory_task['id'];
		$task = Learning::create( $statutory_task );

		// PushNotification::create([
		// 	'message'    => 'Recurring Task: ' . $statutory_task['task_details'],
		// 	'role'       => '',
		// 	'model_type' => Learning::class,
		// 	'model_id'   => $task->id,
		// 	'user_id'    => Auth::id(),
		// 	'sent_to'    => $statutory_task['assign_to'],
		// ]);
	}

	public function getTaskRemark(Request $request){

		$id   = $request->input( 'id' );

		if (is_null($request->module_type)) {
			$remark = \App\Learning::getremarks($id);
		} else {
			$remark = Remark::where('module_type', $request->module_type)->where('taskid', $id)->get();
		}

		return response()->json($remark,200);
	}

	public function addWhatsAppGroup(Request $request)
	{

		$whatsapp_number = '971562744570';
		$task = Learning::findorfail($request->id);

		// Yogesh Sir Number
		$admin_number = User::findorfail(6);
		$assigned_from = Helpers::getUserArray( User::where('id',$task->assign_from)->get() );
		$assigned_to = Helpers::getUserArray( User::where('id',$task->assign_to)->get() );
		$task_id = $task->id;

		//Check if task id is present in Whats App Group
		$group = WhatsAppGroup::where('task_id',$task_id)->first();

		if($group == null){
		//First Create Group Using Admin id
		$phone = $admin_number->phone;
		$result = app('App\Http\Controllers\WhatsAppController')->createGroup($task_id ,'', $phone ,'', $whatsapp_number);
 		if(isset($result['chatId']) && $result['chatId'] != null){
             $task_id = $task_id;
             $chatId = $result['chatId'];
             //Create Group
			 $group = new WhatsAppGroup;
             $group->task_id = $task_id;
             $group->group_id = $chatId;
             $group->save();
             //Save Whats App Group With Reference To Group ID
             $group_number = new WhatsAppGroupNumber;
		     $group_number->group_id = $group->id;
		     $group_number->user_id = $admin_number->id;
		     $group_number->save();
		     //Chat Message
			 $params['task_id'] = $task_id;
             $params['group_id'] = $group->id;
			 ChatMessage::create($params);
		}else{
			$group = new WhatsAppGroup;
             $group->task_id = $task_id;
             $group->group_id = null;
             $group->save();

             $group_number = new WhatsAppGroupNumber;
		     $group_number->group_id = $group->id;
		     $group_number->user_id = $admin_number->id;
		     $group_number->save();

             $params['task_id'] = $task_id;
             $params['group_id'] = $group->id;
             $params['error_status'] = 1;
			 ChatMessage::create($params);

			}
		}

		//iF assigned from is different from Yogesh Sir
		if($admin_number->id != array_keys($assigned_from)[0]){
		$request->request->add(['group_id' => $group->id, 'user_id' => array_keys($assigned_from),'task_id' => $task->id,'whatsapp_number'=>$whatsapp_number]);

		 $this->addGroupParticipant(request());
		}

		//Add Assigned To Into Whats App Group
		if(array_keys($assigned_to)[0] != null){
		$request->request->add(['group_id' => $group->id, 'user_id' => array_keys($assigned_to),'task_id' => $task->id,'whatsapp_number'=>$whatsapp_number]);

		 $this->addGroupParticipant(request());
		}
		return response()->json(['group_id' => $group->id]);

	}

	public function addGroupParticipant(Request $request)
			{

				$whatsapp_number = '971562744570';
				//Now Add Participant In the Group

				foreach ($request->user_id as $key => $value) {

					$check = WhatsAppGroupNumber::where('group_id',$request->group_id)->where('user_id',$value)->first();
					if($check == null){
						$user = User::findorfail($value);
						$group = WhatsAppGroup::where('task_id',$request->task_id)->first();
						$phone = $user->phone;
						$result = app('App\Http\Controllers\WhatsAppController')->createGroup('' , $group->group_id, $phone ,'', $whatsapp_number);
						if(isset($result['add']) && $result['add'] != null){
							 $task_id = $request->task_id;

							 $group_number = new WhatsAppGroupNumber;
				             $group_number->group_id = $request->group_id;
				             $group_number->user_id = $user->id;
				             $group_number->save();
				             $params['user_id'] = $user->id;
				             $params['task_id'] = $task_id;
				             $params['group_id'] = $request->group_id;
							 ChatMessage::create($params);

						}else{
							$task_id = $request->task_id;

							 $group_number = new WhatsAppGroupNumber;
				             $group_number->group_id = $request->group_id;
				             $group_number->user_id = $user->id;
				             $group_number->save();
				             $params['user_id'] = $user->id;
				             $params['task_id'] = $task_id;
				             $params['group_id'] = $request->group_id;
				             $params['error_status'] = 1;
							 ChatMessage::create($params);
						}

					}

			}

			return redirect()->back()->with('message', 'Participants Added To Group');
		}

	public function getDetails(Request $request)
	{
		
		$task = \App\Learning::where("id", $request->get("task_id",0))->first();

		if($task) {
			return response()->json(["code" => 200 , "data" => $task]);
		}

		return response()->json(["code" => 500 , "message" => "Sorry, no task found"]);

	}

	public function saveNotes(Request $request)
	{
		
		$task = \App\Learning::where("id", $request->get("task_id",0))->first();

		if($task) {

			if ($task->is_statutory == 3) {
				foreach ($request->note as $note) {
					if ($note != null) {
						Remark::create([
							'taskid'	=> $task->id,
							'remark'	=> $note,
							'module_type'	=> 'task-note'
						]);
					}
				}
			}

			return response()->json(["code" => 200 , "data" => $task , "message" => "Note added!"]);
		}

		return response()->json(["code" => 500 , "message" => "Sorry, no task found"]);

	}

	public function createLearningFromSortcut(Request $request)
	{
		
		$created = 0;
		$message = '';
		$assignedUserId = 0;
		$data = $request->except( '_token' );
		// //print_r($data); die;
		// $this->validate($request, [
		// 	'task_subject'	=> 'required',
		// 	'task_detail'	=> 'required',
		// 	'category'	=> 'required',
		// 	'submodule'	=> 'required',
		// 	'task_asssigned_to' => 'required_without:assign_to_contacts',
		// 	'cost'=>'sometimes|integer'
		// ]);
		// $data['assign_from'] = Auth::id();
		
		// $taskType = $request->get("task_type");


		// $data = [];

		// $data["learning_user"] 			= $request->learning_user;
		// $data["learning_vendor"] 		= $request->learning_vendor;
		// $data["learning_subject"] 		= $request->learning_subject;
		// $data["learning_module"] 		= $request->learning_module;
		// $data["learning_submodule"] 	= $request->learning_submodule;
		// $data["learning_assignment"] 	= $request->learning_assignment;
		// $data["learning_duedate"] 		= $request->learning_duedate;
		// $data["learning_status"] 		= $request->learning_status;
		
		// $task = Learning::create($data);

		Learning::create([
			'learning_user'			=> $request->learning_user,
			'learning_vendor'		=> $request->learning_vendor,
			'learning_subject'		=> $request->learning_subject,
			'learning_module'		=> $request->learning_module,
			'learning_submodule'	=> $request->learning_submodule,
			'learning_assignment'	=> $request->learning_assignment,
			'learning_duedate'		=> $request->learning_duedate,
			'learning_status'		=> $request->learning_status,
		]);

		$created = 1;
		// $message = '#DEVTASK-' . $task->id . ' => ' . $task->subject;
		// $assignedUserId = $task->assigned_to;
		// $requestData = new Request();
        // $requestData->setMethod('POST');
        // $requestData->request->add(['issue_id' => $task->id, 'message' => $request->get("task_detail"), 'status' => 1]);
		// app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'issue');


		// if($created) {
		// 	$hubstaff_project_id = getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID');
		// 	$assignedUser = HubstaffMember::where('user_id', $assignedUserId)->first();
	  
		// 	  $hubstaffUserId = null;
		// 	  $hubstaffTaskId = null;
		// 	  if ($assignedUser) {
		// 		  $hubstaffUserId = $assignedUser->hubstaff_user_id;
		// 	  }
		// 	  $taskSummery = substr($message, 0, 200);
		// 	  if($hubstaffUserId) {
		// 		$hubstaffTaskId = $this->createHubstaffTask(
		// 			$taskSummery,
		// 			$hubstaffUserId,
		// 			$hubstaff_project_id
		// 		);
		// 	  }
			 
	  
		// 	  if($hubstaffTaskId) {
		// 		  $task->hubstaff_task_id = $hubstaffTaskId;
		// 		  $task->save();
		// 	  }
		// 	  if ($hubstaffTaskId) {

			  	 

		// 		  $hubtask = new HubstaffTask();
		// 		  $hubtask->hubstaff_task_id = $hubstaffTaskId;
		// 		  $hubtask->project_id = $hubstaff_project_id;
		// 		  $hubtask->hubstaff_project_id = $hubstaff_project_id;
		// 		  $hubtask->summary = $message;
		// 		  $hubtask->save();
		// 	  }
		//   }

		// if ($request->ajax() && $request->from == 'task-page') {
		// 	$hasRender = request("has_render", false);

		// 	$task_statuses=TaskStatus::all();
			
		// 	if(!empty($hasRender)) {
				
		// 		$users      = Helpers::getUserArray( User::all() );
		// 		$priority  	= \App\ErpPriority::where('model_type', '=', Learning::class)->pluck('model_id')->toArray();

		// 		if($task->is_statutory == 1) {
		// 			$mode = "learning-module.partials.statutory-row";
		// 		}
		// 		// else if($task->is_statutory == 3) {
		// 		// 	$mode = "learning-module.partials.discussion-pending-raw";
		// 		// }
		// 		else {
		// 			$mode = "learning-module.partials.pending-row";
		// 		}
		// 		//return $users;
		// 		$view = (string)view($mode,compact('task','priority','users','task_statuses'));
		// 		return response()->json(["code" => 200, "statutory" => $task->is_statutory , "raw" => $view]);	

		// 	}
		// 	return response('success');
		// }

		return redirect()->route('learning.index');
		// return response()->json(["code" => 200, "data" => [], "message" => "Your Lerning Task created!"]);

	}

	public function getDiscussionSubjects() {
		$discussion_subjects = Learning::where('is_statutory',3)->where('is_verified',NULL)->pluck('task_subject','id')->toArray();
		return response()->json(["code" => 200, "discussion_subjects" => $discussion_subjects]);
	}

	/***
	 * Delete task note
	 */
	public function deleteTaskNote(Request $request)
	{
		$task = Remark::whereId($request->note_id)->delete();
		session()->flash('success', 'Deleted successfully.');
		return response(['success' => "Deleted"],200);
	}

	/**
	 * Hide task note from list
	 */
	public function hideTaskRemark(Request $request)
	{
		$task = Remark::whereId($request->note_id)->update(['is_hide' => 1]);
		session()->flash('success', 'Hide successfully.');
		return response(['success' => "Hidden"],200);
	}

	public function assignMasterUser(Request $request)
    {
        $masterUserId = $request->get("master_user_id");
        $issue = Learning::find($request->get('issue_id'));

        $user = User::find($masterUserId);

        if(!$user) {
            return response()->json([
                'status' => 'success', 'message' =>'user not found'
            ],500);
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
		$message = "#" . $issue->id . ". " . $issue->task_subject . ". " . $issue->task_details;
        $summary = substr($message, 0, 200);

        

        $hubstaffTaskId = $this->createHubstaffTask(
            $summary,
            $hubstaffUserId,
            $hubstaff_project_id
        );
        if($hubstaffTaskId) {
            $issue->lead_hubstaff_task_id = $hubstaffTaskId;
            $issue->save();
        }
        if ($hubstaffTaskId) {
            $task = new HubstaffTask();
            $task->hubstaff_task_id = $hubstaffTaskId;
            $task->project_id = $hubstaff_project_id;
            $task->hubstaff_project_id = $hubstaff_project_id;
            $task->summary = $message;
            $task->save();
		}
        return response()->json([
            'status' => 'success'
        ]);
	}
	
	public function uploadDocuments(Request $request)
	{
		$path = storage_path('tmp/uploads');

		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}

		$file = $request->file('file');

		$name = uniqid() . '_' . trim($file->getClientOriginalName());

		$file->move($path, $name);

		return response()->json([
			'name'          => $name,
			'original_name' => $file->getClientOriginalName(),
		]);
	}


	public function saveDocuments(Request $request)
	{
		if(!$request->learning_id || $request->learning_id == '') {
			return response()->json(["code" => 500, "data" => [], "message" => "Select one learning"]);
		}
		$documents = $request->input('document', []);
		$learning = Learning::find($request->learning_id);
		if (!empty($documents)) {
			$count = 0;
			foreach ($request->input('document', []) as $file) {
				$path  = storage_path('tmp/uploads/' . $file);
				$media = MediaUploader::fromSource($path)
					->toDirectory('learning-files/' . floor($learning->id / config('constants.image_per_folder')))
					->upload();
				$learning->attachMedia($media, config('constants.media_tags'));
				$count++;
			}

			return response()->json(["code" => 200, "data" => [], "message" => "Done!"]);
		} else {
			return response()->json(["code" => 500, "data" => [], "message" => "No documents for upload"]);
		}

	}

	public function previewTaskImage($id) {

        $task = Learning::find($id);
        $records = [];
            if ($task) {
            	$userList = User::pluck('name','id')->all();
				// $usrSelectBox = "";
				// if (!empty($userList)) {
				// 	$usrSelectBox = (string) \Form::select("send_message_to", $userList, null, ["class" => "form-control send-message-to-id"]);
				// }
                if ($task->hasMedia(config('constants.attach_image_tag'))) {
                    foreach ($task->getMedia(config('constants.attach_image_tag')) as $media) {

						$imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
						$explodeImage = explode('.', $media->getUrl());
						$extension = end($explodeImage);

						if(in_array($extension, $imageExtensions))
						{
							$isImage = true;
						}else
						{
							$isImage = false;
						}
                        $records[] = [
                            "id"        => $media->id,
                            'url'       => $media->getUrl(),
							'task_id'   => $task->id,
							'isImage'   => $isImage,
							'userList'  => $userList,
							'created_at'  => $media->created_at
						];
                    }
                }
            }

        $records = array_reverse($records);
        $title = 'Preview images';
        return view('learning-module.partials.preview-task-images', compact('title','records'));
	}
	
	public function approveTimeHistory(Request $request) {
        if(Auth::user()->isAdmin) {
            if(!$request->approve_time || $request->approve_time == "" || !$request->developer_task_id || $request->developer_task_id == '') {
                return response()->json([
                    'message' => 'Select one time first'
                ],500);
            }
            DeveloperTaskHistory::where('developer_task_id',$request->developer_task_id)->where('attribute','estimation_minute')->where('model','App\Task')->update(['is_approved' => 0]);
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
	
	public function getTrackedHistory(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        if($type == 'lead') {
            $task_histories = DB::select( DB::raw("SELECT hubstaff_activities.task_id,cast(hubstaff_activities.starts_at as date) as starts_at_date,sum(hubstaff_activities.tracked) as total_tracked,learnings.master_user_id,users.name FROM `hubstaff_activities`  join learnings on learnings.lead_hubstaff_task_id = hubstaff_activities.task_id join users on users.id = learnings.master_user_id where learnings.id = ".$id." group by starts_at_date"));
        }
        else {
            $task_histories = DB::select( DB::raw("SELECT hubstaff_activities.task_id,cast(hubstaff_activities.starts_at as date) as starts_at_date,sum(hubstaff_activities.tracked) as total_tracked,learnings.assign_to,users.name FROM `hubstaff_activities`  join learnings on learnings.hubstaff_task_id = hubstaff_activities.task_id join users on users.id = learnings.assign_to where learnings.id = ".$id." group by starts_at_date"));
        }
       
        return response()->json(['histories' => $task_histories]);
	}
	
	public function updateTaskDueDate(Request $request) {
		
		
		if($request->type == 'TASK'){
			$task = Learning::find($request->task_id);
			if($request->date) {
				$task->update(['due_date' => $request->date]);
			}
		}else{
			if($request->date) {
				DeveloperTask::where('id',$request->task_id)
					->update(['due_date' => $request->date]);
			}
		}
		
		return response()->json([
            'message' => 'Successfully updated'
        ],200);
	}

	public function createHubstaffManualTask(Request $request) {
		$task = Learning::find($request->id);
		if($task) {
			if($request->type == 'developer') {
				$user_id = $task->assign_to;
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
			$taskSummery = "#" . $task->id . ". " . $task->task_subject;
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

		public function getTaskCategories() {
			$categories = LearningModule::where('is_approved',1)->get();
			return view( 'learning-module.partials.all-task-category', compact('categories'));
		}

		public function completeBulkTasks(Request $request) {
			if(count($request->selected_tasks) > 0) {
				foreach($request->selected_tasks as $t) {
					$task = Learning::find($t);
					$task->is_completed = date( 'Y-m-d H:i:s' );
					$task->is_verified = date( 'Y-m-d H:i:s' );
					if($task->assignedTo) {
						if($task->assignedTo->fixed_price_user_or_job == 1) {
								// Fixed price task.
								continue;
						}
					}
					$task->save();
				}
			}
			return response()->json(['message' => 'Successful']);
		}


		public function deleteBulkTasks(Request $request) {
			if(count($request->selected_tasks) > 0) {
				foreach($request->selected_tasks as $t) {
					$task = Learning::where('id',$t)->delete();
				}
			}
			return response()->json(['message' => 'Successful']);
		}

		public function getTimeHistory(Request $request)
		{
			$id = $request->id;
			$task_module = DeveloperTaskHistory::join('users','users.id','developer_tasks_history.user_id')->where('developer_task_id', $id)->where('model','App\Task')->where('attribute','estimation_minute')->select('developer_tasks_history.*','users.name')->get();
			if($task_module) {
				return $task_module;
			}
			return 'error';
		}


		public function sendDocument(Request $request)
		{
			if ($request->id != null && $request->user_id != null) {
				$media        = \Plank\Mediable\Media::find($request->id);
				$user         = \App\User::find($request->user_id);
				if ($user) {
					if ($media) {
						\App\ChatMessage::sendWithChatApi(
							$user->phone,
							null,
							"Please find attached file",
							$media->getUrl()
						);
						return response()->json(["message" => "Document send succesfully"],200);
					}
				}else{
					return response()->json(["message" => "User  not available"],500);
				}
			}
	
			return response()->json(["message" => "Sorry required fields is missing like id , userid"],500);
		}

   /* update task status 
    */

   public function updateStatus(Request $request)
   {
   	    try {

   	    	$task=Learning::find($request->task_id);

   	    	$task->status=$request->status;

   	    	$task->save();

   	    	return response()->json([
                'status' => 'success', 'message' =>'The task status updated.'
            ],200);


   	    	
   	    } catch (Exception $e) {

   	    	return response()->json([
                'status' => 'error', 'message' =>'The task status not updated.'
            ],500);
   	    	
   	    }
   }

   /* create new task status */

   public function createStatus(Request $request)
   {
   	  $this->validate($request,['task_status'=>'required']);

   	  try {

   	  	TaskStatus::create(['name'=>$request->task_status]);

   	  	return redirect()->back()->with( 'success', 'The task status created successfully.' );
   	  	
   	  } catch (Exception $e) {
   	  	
   	  	return redirect()->back()->with('error',$e->getMessage());
   	  }



   }

   public function learningModuleUpdate(Request $request){
		$id = $request->id;
		$learning = Learning::find($id);
		if($request->user_id){
			$learning->learning_user = $request->user_id;
			$learning->save();
			return response()->json(["message" => "User Updated Successfully"]);
		}

		if($request->provider_id){
			$learning->learning_vendor = $request->provider_id;
			$learning->save();
			return response()->json(["message" => "provider Updated Successfully"]);
		}

		if($request->subject){
			$learning->learning_subject = $request->subject;
			$learning->save();
			return response()->json(["message" => "Subject Updated Successfully"]);
		}

		if($request->module_id){
			$learning->learning_module = $request->module_id;
			$learning->learning_submodule = null;
			$learning->save();
			$submodule = LearningModule::where('parent_id',$learning->learning_module)->get();
			return response()->json(["message" => "Module Updated Successfully","learning_id" => $learning->id,"submodule" => $submodule]);
		}

		if($request->submodule_id){
			$learning->learning_submodule = $request->submodule_id;
			$learning->save();
			return response()->json(["message" => "Submodule Updated Successfully"]);
		}

		if($request->assignment){
			$learning->learning_assignment = $request->assignment;
			$learning->save();
			return response()->json(["message" => "Assignment Updated Successfully"]);
		}

		if($request->status_id){
			
			LearningStatusHistory::create([
   	    		'learning_id' => $learning->id,
   	    		'old_status'  => $learning->learning_status??0,
   	    		'new_status'  => $request->status_id,
   	    		'update_by'   => $request->user()->id
   	    	]);

			$learning->learning_status = $request->status_id;
			$learning->save();
			return response()->json(["message" => "Status Updated Successfully"]);
		}
   }

   public function getStatusHistory(Request $request)
    {
        $learningid = $request->learningid;
        
        $records = LearningStatusHistory::with('oldstatus','newstatus','user')
        		->where('learning_id', $learningid)
        		->latest()
        		->get();
        
        if($records){		
	        $response = [];
	        foreach ($records as $row) {
	        	$response [] = [
	        		'created_date'=> $row->created_at->format('Y-m-d'),
	        		'old_status'  => optional($row->oldstatus)->name??'-',
	        		'new_status'  => optional($row->newstatus)->name??'-',
	        		'update_by'   => $row->user->name,
	        	];
	        }
	        return $response;
        }
        return 'error';
    }

    public function saveDueDateUpdate(Request $request)
    {
        $learning = Learning::find($request->get('learningid'));
        $due_date = date("Y-m-d", strtotime($request->due_date));
        if($learning && $request->due_date) {
            LearningDueDateHistory::create([
	   	    		'learning_id' => $learning->id,
	   	    		'old_duedate'  => $learning->learning_duedate??0,
	   	    		'new_duedate'  => $due_date,
	   	    		'update_by'   => $request->user()->id
	   	    	]);
        }

        $learning->learning_duedate = $due_date;
        $learning->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getDueDateHistory(Request $request)
    {
        $learningid = $request->learningid;
        
        $records = LearningDueDateHistory::with('user')
        		->where('learning_id', $learningid)
        		->latest()
        		->get();
        
        if($records){		
	        $response = [];
	        foreach ($records as $row) {
	        	$response [] = [
	        		'created_date'=> $row->created_at->format('Y-m-d'),
	        		'old_duedate'  => $row->old_duedate??'-',
	        		'new_duedate'  => $row->new_duedate??'-',
	        		'update_by'   => $row->user->name,
	        	];
	        }
	        return $response;
        }
        return 'error';
    }
}