<?php

namespace App\Http\Controllers;

use App\PushNotification;
use App\NotificationQueue;
use App\Remark;
use App\Helpers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller {

	public function index(Request $request)
	{
		$data     = [];
		$term = $request->input('term');
		$data['term'] = $term;

		$lead_notifications = PushNotification::where('model_type', 'App\Leads');
		$order_notifications = PushNotification::where('model_type', 'App\Order');
		$message_notifications = PushNotification::whereIn('model_type', ['order', 'leads', 'customer']);
		$task_notifications = PushNotification::whereIn('model_type', ['App\Task', 'App\SatutoryTask', 'App\Http\Controllers\Task', 'User']);

		if ($request->user[0] != null) {
			$lead_notifications = $lead_notifications->where( function ( $query ) use ($request) {
				                       return $query->whereIn('sent_to', $request->user)
				                                    ->orWhereIn('role', \Auth::user()->getRoleNames());
			                       });

			 $order_notifications = $order_notifications->where( function ( $query ) use ($request) {
					                       return $query->whereIn('sent_to', $request->user)
					                                    ->orWhereIn('role', \Auth::user()->getRoleNames());
				                       });

			 $message_notifications = $message_notifications->where( function ( $query ) use ($request) {
					                       return $query->whereIn('sent_to', $request->user)
					                                    ->orWhereIn('role', \Auth::user()->getRoleNames());
				                       });

			 $task_notifications = $task_notifications->where( function ( $query ) use ($request) {
					                       return $query->whereIn('sent_to', $request->user)
					                                    ->orWhereIn('role', \Auth::user()->getRoleNames());
				                       });

			$user = $request->user;
		}

		if (trim($term) != '') {
			$lead_notifications = $lead_notifications->where('message', 'LIKE', "%$term%");
			$order_notifications = $order_notifications->where('message', 'LIKE', "%$term%");
			$message_notifications = $message_notifications->where('message', 'LIKE', "%$term%");
			$task_notifications = $task_notifications->where('message', 'LIKE', "%$term%");
		} else {
			if ($request->user[0] == null) {
				$lead_notifications = $lead_notifications->where( function ( $query ) {
																 return $query->where('sent_to', \Auth::id())
																							->orWhereIn('role', \Auth::user()->getRoleNames());
															 });

				 $order_notifications = $order_notifications->where( function ( $query ) {
																	 return $query->where('sent_to', \Auth::id())
																								->orWhereIn('role', \Auth::user()->getRoleNames());
																 });

				 $message_notifications = $message_notifications->where( function ( $query ) {
																	 return $query->where('sent_to', \Auth::id())
																								->orWhereIn('role', \Auth::user()->getRoleNames());
																 });

				 $task_notifications = $task_notifications->where( function ( $query ) {
																	 return $query->where('sent_to', \Auth::id())
																								->orWhereIn('role', \Auth::user()->getRoleNames());
																 });
			}
		}

		$lead_notifications = $lead_notifications->orderBy('created_at','DESC')->paginate(50, ['*'], 'lead_page');
		$order_notifications = $order_notifications->orderBy('created_at','DESC')->paginate(50, ['*'], 'order_page');
		$message_notifications = $message_notifications->orderBy('created_at','DESC')->get()->groupBy('message', 'model_type', 'model_id', 'role', 'reminder')->toArray();
		$task_notifications = $task_notifications->orderBy('created_at','DESC')->get()->groupBy('message', 'model_type', 'model_id', 'role', 'reminder')->toArray();

		// $currentPage = LengthAwarePaginator::resolveCurrentPage();
		$currentPage = $request->message_page ? $request->message_page : 1;
		$perPage = 50;
		$currentItems = array_slice($message_notifications, $perPage * ($currentPage - 1), $perPage);

		$message_notifications = new LengthAwarePaginator($currentItems, count($message_notifications), $perPage, $currentPage, [
			'path'  => LengthAwarePaginator::resolveCurrentPath()
			// 'pageName'	=> 'message_page'
		]);
		$message_notifications->setPageName('message_page');

		$currentPage = $request->task_page ? $request->task_page : 1;
		$perPage = 50;
		$currentItems = array_slice($task_notifications, $perPage * ($currentPage - 1), $perPage);

		$task_notifications = new LengthAwarePaginator($currentItems, count($task_notifications), $perPage, $currentPage, [
			'path'  => LengthAwarePaginator::resolveCurrentPath()
			// 'pageName'	=> 'message_page'
		]);
		$task_notifications->setPageName('task_page');
		// dd($message_notifications);

		return view('pushnotification.index', compact('lead_notifications', 'order_notifications', 'message_notifications', 'task_notifications', 'term', 'user'));
	}

	public function getJson() {
		$push_notifications = PushNotification::where( 'isread', 0 )
		                       ->where( function ( $query ) {
			                       return $query->where( 'sent_to', \Auth::id() )
			                                    ->orWhereIn( 'role', \Auth::user()->getRoleNames() );
		                       } )/*->limit( 3 )*/
							   ->orderBy('created_at','DESC')
		                       ->get();

		foreach ($push_notifications as $notification) {
			$notification->setUserNameAttribute($notification['user_id']);
			$notification->setClientNameAttribute($notification['model_type'], $notification['model_id']);

			if ($notification['model_type'] == 'App\Task' || $notification['model_type'] == 'App\SatutoryTask' || $notification['model_type'] == 'App\Http\Controllers\Task' || $notification['model_type'] == 'User') {
				$notification->setSubjectAttribute($notification['model_type'], $notification['model_id']);
			}
		}

		return $push_notifications->toArray();

	}

	public function markRead( PushNotification $push_notification ) {

		$push_notification->isread = 1;
		$push_notification->save();

		NotificationQueue::where('role', $push_notification->role)->where('message', $push_notification->message)->where('user_id', $push_notification->user_id)->where('sent_to', $push_notification->sent_to)->where('model_type', $push_notification->model_type)->where('model_id', $push_notification->model_id)->delete();

		return ['msg' => 'success', 'updated_at' => "$push_notification->updated_at"];
	}

	public function markReadReminder( PushNotification $push_notification ) {
		$reminders = PushNotification::where('message', $push_notification->message)
																	// ->where('sent_to', $push_notification->sent_to)
																	->where('model_type', $push_notification->model_type)
																	->where('model_id', $push_notification->model_id)->get();

		foreach ($reminders as $reminder) {
			$reminder->isread = 1;
			$reminder->save();
			$updated_at = $reminder->updated_at;
			NotificationQueue::where('role', $reminder->role)->where('message', $reminder->message)->where('user_id', $reminder->user_id)->where('sent_to', $reminder->sent_to)->where('model_type', $reminder->model_type)->where('model_id', $reminder->model_id)->delete();
		}

		return [ 'msg' => 'success', 'updated_at' => "$updated_at"];
	}

	public function changeStatus(PushNotification $push_notification,Request $request){

		$status = $request->input('status');
		$model_type = $push_notification->model_type;
		$remark = $request->input('remark');

		$model_class= new $model_type();
		$model_instance = $model_class->findOrFail($push_notification->model_id);

		$model_instance->assign_status = $status;

		if($status == 1) {
			PushNotification::create( [
				'message'    => 'Task Accepted by ' . Helpers::getUserNameById(Auth::id()),
				'model_type' => Task::class,
				'model_id'   => $push_notification->model_id,
				'user_id'    => Auth::id(),
				'sent_to'    => '',
				'role'       => 'Admin',
			] );
		}

		if($status == 3) {
			PushNotification::create( [
				'message'    => 'Task Declined by ' . Helpers::getUserNameById(Auth::id()),
				'model_type' => Task::class,
				'model_id'   => $push_notification->model_id,
				'user_id'    => Auth::id(),
				'sent_to'    => '',
				'role'       => 'Admin',
			] );
		}

		if(!empty($remark))
		{
			if($model_type == 'App\\Task') {

				if($status != 1) {

					Remark::create( [
						'remark' => $remark,
						'taskid' => $push_notification->model_id

					] );

					PushNotification::create( [
						'message'    => 'Remark added ' . $remark,
						'model_type' => Task::class,
						'model_id'   => $push_notification->model_id,
						'user_id'    => Auth::id(),
						'sent_to'    => '',
						'role'       => 'Admin',
					] );
				}

				if($status == 3)
					$model_instance->assign_to = 0;
			}
			else{
				$model_instance->remark = $remark;
			}
		}

		$message = '';

		switch ($status){
			case 1:
				$message = 'Accepted';
			break;

			case 2:
				$message = 'Postponed';
			break;

			case 3:
				$message = 'Rejected';
		}

		PushNotification::create([
			'message' => $message . ' : '. $push_notification->message,
			'role' => '',
			'user_id' => Auth::id(),
			'sent_to' => $push_notification->user_id,
			'model_type' => $push_notification->model_type,
			'model_id' => $push_notification->model_id,
		]);

		if ($status == 1) {
			NotificationQueueController::createNewNotification([
		    'message' => 'Reminder: ' . $push_notification->message,
		    'timestamps' => ['+10 minutes', '+20 minutes', '+30 minutes', '+40 minutes', '+50 minutes', '+60 minutes', '+70 minutes', '+80 minutes', '+90 minutes', '+100 minutes', '+110 minutes', '+120 minutes'],
		    'model_type' => $push_notification->model_type,
		    'model_id' =>  $push_notification->model_id,
		    'user_id' => Auth::id(),
		    'sent_to' => $push_notification->user_id,
		    'role' => '',
	    ]);
		}

		$model_instance->save();

		if($status != 2)
			$push_notification->isread = 1;

		$push_notification->save();
	}
}
