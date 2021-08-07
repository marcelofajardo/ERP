<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificaitonContoller extends Controller
{

	public function index(Request $request){
		$notifications = Notification::getUserNotificationByRolesPaginate($request);

		$sort = $request->input('sort_by');

		return view('notification.index',compact('notifications','sort'));
	}

	public static function json(){

		$notifications = Notification::getUserNotificationByRoles();

		return $notifications;
	}

	public static function store($messsage,$forRoles,$product_id,$sale_id = null,$sent_to = null,$user_id = null){

		$notification = new Notification();

		if(!empty($forRoles)) {
			foreach ( $forRoles as $role ) {
				$notification->create( [
					'message'    => $messsage,
					'role'       => $role,
					'product_id' => $product_id,
					'user_id'    => \Auth::id() ? \Auth::id()  : $user_id,
					'sale_id'     => $sale_id,
					'task_id'    => '',
					'sent_to'    => $sent_to,
				] );
			}
		}
		else{
			$notification->create( [
				'message'    => $messsage,
				'role'       => '',
				'product_id' => '',
				'user_id'    => \Auth::id() ? \Auth::id()  : $user_id,
				'sale_id'    => $sale_id,
				'task_id'    => '',
				'sent_to'    => $sent_to,
			] );
		}
	}

	public static function storeTask($messsage,$task_id,$sent_to,$user_id = null){

		$notification = new Notification();

		$notification->create( [
			'message'    => $messsage,
			'role'       => '',
			'product_id' => '',
			'sale_id' => null,
			'user_id'    => \Auth::id() ? \Auth::id()  : $user_id,
			'task_id'    => $task_id,
			'sent_to'    => $sent_to,
		] );
	}


	public function markRead(Notification $notificaion){

		$notificaion->isread = 1;
		$notificaion->save();

		return ['msg' => 'success'];
	}

	public function salesJson(){

		$notifications = Notification::whereNotNull('sale_id')
		                             ->where('sent_to',\Auth::id())
		                             ->where('isread',0)
		                             ->whereNull('sales.deleted_at')
		                             ->join('sales', 'notifications.sale_id', '=', 'sales.id')
									 ->select(['notifications.id','notifications.message','notifications.sale_id','notifications.task_id'])
		                             ->limit(2)
		                             ->get()->toArray();

		$notificationsTask =  Notification::whereNotNull('task_id')
		                                  ->where('sent_to',\Auth::id())
		                                  ->where('isread',0)
		                                  ->whereNull('tasks.deleted_at')
		                                  ->join('tasks', 'notifications.task_id', '=', 'tasks.id')
		                                  ->select(['notifications.id','notifications.message','notifications.sale_id','notifications.task_id'])
		                                  ->limit(2)
		                                  ->get()->toArray();

		return array_merge($notifications,$notificationsTask);
	}

	public static function salesCount(){
		return Sale::where('allocated_to', Auth::id() )
				    ->where(function ($query){
				        $query->where('remark','Pending')
					        ->orWhereNull('remark');
				    })
				    ->whereNull('deleted_at')
		            ->count();
	}

	/*	public function getRoleIDs(){

			$roleNames = $this->user->getRoleNames();

			$roleIDs = [];

			foreach ($roleNames as $roleName){

				$role = Role::findByName($roleName);
				$roleIDs = $role->get('id');
			}

			return $roleIDs;
		}*/

}
