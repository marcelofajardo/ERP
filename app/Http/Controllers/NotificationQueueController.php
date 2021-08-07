<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Leads;
use App\Message;
use App\NotificationQueue;
use App\Order;
use App\Sale;
use App\Task;
use App\PushNotification;
use App\User;
use App\Instruction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationQueueController extends Controller
{
	private  $notificationQueueArray = [
		"type",
		'message',
		'timestamps', //Array
		'model_type',
		'model_id',
		'user_id',
		'sent_to',
		'message_id',
		'role',
	];

	private $notificationFillable = [
		"type",
		'message',
		'role',
		'user_id',
		'sent_to',
		'model_type',
		'model_id',
		'message_id',
	];

	public static function createNewNotification( $notificationArray ){

		$startTime = date("Y-m-d H:i:s");
		$notificationArray['user_id'] = $notificationArray['user_id'] ?? \Auth::id();

		if (!empty($notificationArray['sent_to'])) {
			$user = User::find($notificationArray['sent_to']);

			if (!$user->isOnline()) {
				if (!empty($user->responsible_user)) {
					$responsible_user = User::find($user->responsible_user);

					if ($responsible_user->isOnline()) {
						$notificationArray['sent_to'] = $responsible_user->id;
					}
				}
			}
		}

		// if ($notificationArray['sent_to'] == 6 || $notificationArray['sent_to'] == 56 || (array_key_exists('role', $notificationArray) && $notificationArray['role'] == 'Admin')) {
		if (($notificationArray['sent_to'] == 6 && $notificationArray['model_type'] == Instruction::class) || ($notificationArray['sent_to'] == 3 && $notificationArray['model_type'] == Instruction::class) || ($notificationArray['sent_to'] == 23 && $notificationArray['model_type'] == Instruction::class)) {
			// TEMP SOLUTION TO TURN OFF NOTIFICATIONS FOR ADMINS
		} else {
			foreach ($notificationArray['timestamps'] as $time){

				$data = $notificationArray;
				$data['time_to_add'] = date('Y-m-d H:i:s',strtotime($time,strtotime($startTime)));

				NotificationQueue::create($data);
			}
		}


	}

	public static function deQueueNotficationNew(){

		$nArray = NotificationQueue::orderBy('time_to_add', 'ASC')->take(20)->get()->toArray();

		foreach($nArray  as $item){

			if( time() >= strtotime( $item['time_to_add']) )
			{

				switch ($item['model_type']){

					case Sale::class :

						$sale_instance = Sale::find( $item['model_id'] );

						if ( ! empty( $sale_instance ) ) {

							if ( $sale_instance->selected_product == 'null' ) {

								PushNotification::create($item);
							}
						}

					break;

					case Task::class :

						$task_instance = Task::find( $item['model_id'] );

						if ( ! empty( $task_instance ) ) {

							if ( $task_instance->assign_status != '1' ) {
								PushNotification::create($item);
							}
						}

					break;

					case Leads::class :
						$lead_instance = Leads::find( $item['model_id'] );

						if ( ! empty( $lead_instance ) ) {

							if ( $lead_instance->status == '1' && $lead_instance->assign_status == null ) {

								PushNotification::create($item);
							}
						}

					break;

					case Order::class :

						$order_instance = Order::find( $item['model_id'] );

						if ( ! empty( $order_instance ) ) {
//							if ( $order_instance->status == '' ) {}
							if ( $order_instance->assign_status == null ) {
								PushNotification::create($item);
							}
						}

					break;

					case 'leads':
						PushNotification::create($item);
					break;

					case 'order':
						PushNotification::create($item);
					break;

					case 'customer':
						PushNotification::create($item);
					break;

					case 'App\Instruction':
						PushNotification::create($item);
					break;

					case 'App\DeveloperTask':
						PushNotification::create($item);
					break;

					case 'MasterControl':
						PushNotification::create($item);
					break;
				}

				NotificationQueueController::destroy( $item['id'] );
			}
		}

	}

	public function perHourActivityNotification(){

		$is_correct_hr = intval(date('H',time()));

		if( $is_correct_hr >= 10 && $is_correct_hr <= 19 ) {

			$user_ids = Helpers::getAllUserIdsWithoutRole();

			$hrs = date( 'h a', strtotime( '-1 hours', time() ) ) . ' - ' . date( 'h a', time() );

			foreach ( $user_ids as $id ) {

				PushNotification::create( [
					'message'    => 'Input Activity for ' . $hrs,
					'role'       => '',
					'user_id'    => $id,
					'sent_to'    => $id,
					'model_type' => 'User',
					'model_id'   => $id,
				] );
			}
		}
	}

	public static function destroy($notificaiton_queue_id){

		DB::table("notification_queues")->where('id',$notificaiton_queue_id)->delete();
	}


}
