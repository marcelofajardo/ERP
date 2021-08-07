<?php

namespace App\Exports;

use App\Customer;
use App\DeveloperTask;
use App\User;
use App\Task;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\DeveloperTaskHistory;

class HubstaffActivityReport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents
{
  protected $user;

  public function __construct(array $user)
  {
    $this->user = $user;

  }

  	public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // $event->sheet->getDelegate()->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);           
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('G')->setAutoSize(true);
            }
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
      $new_customers = [];
      // dd( $this->user );
      $totalApproved = 0;
      $totalDiff = 0;
      $totalTrack = 0;
      $estimatedTime = 0;
    //   dd( $this->user );

    //START - Purpose : Comment Code - DEVTASK-4300
    //   foreach ($this->user as $key => $user) {
        
    //     // foreach($user['tasks'] as $key =>  $ut) {
	      	
    //         $userDev = User::find($user['user_id']);
    //         // dump($userDev->id);
	//       	// @list($taskid,$devtask,$taskName,$estimation,$status,$devTaskId) = explode("||",$ut);
    //         if ($user['is_manual']) {
    //             $task = DeveloperTask::where('id', $user['task_id'])->first();
    //             if ($task) {
    //                 $taskSubject = '#DEVTASK-' . $task->id . '-' . $task->subject;
    //             } else {
    //                 $task = Task::where('id', $ar->task_id)->first();
    //                 if ($task) {
    //                     // $estMinutes = ($task->estimate_minutes && $task->estimate_minutes > 0) ? $task->estimate_minutes : "N/A";
    //                     // $taskSubject = $ar->task_id . '||#TASK-' . $task->id . '-' . $task->task_subject."||#TASK-$task->id||$estMinutes||$task->status||$task->id";
    //                     $taskSubject = '#TASK-' . $task->id . '-' . $task->task_subject;
    //                 }
    //             }
    //         } else {
    //             $task = DeveloperTask::where('hubstaff_task_id', $user['task_id'])->orWhere('lead_hubstaff_task_id', $user['task_id'])->first();
    //             if ($task && empty( $task_id )) {
    //                 // $estMinutes = ($task->estimate_minutes && $task->estimate_minutes > 0) ? $task->estimate_minutes : "N/A";
    //                 $taskSubject = '#DEVTASK-' . $task->id . '-' . $task->subject;
    //             } else {
    //                 $task = Task::where('hubstaff_task_id', $user['task_id'])->orWhere('lead_hubstaff_task_id', $user['task_id'])->first();
    //                 if ($task && empty( $developer_task_id )) {
    //                     // $estMinutes = ($task->estimate_minutes && $task->estimate_minutes > 0) ? $task->estimate_minutes : "N/A";
    //                     $taskSubject = '#TASK-' . $task->id . '-' . $task->task_subject;
    //                 }
    //             }
    //         }
    //         $devTask = $task;
    //         // $devTask = DeveloperTask::where('hubstaff_task_id', $user['task_id'])->first();
    //         if(isset($user['type'])){

    //             $est = DeveloperTaskHistory::where('developer_task_id', $user['task_id'])->latest()->first();
    //             $new_customers[$key]['User'] = $userDev->name ?? null;
    //             $new_customers[$key]['date'] = \Carbon\Carbon::parse($user['date'])->format('d-m');
    //             $new_customers[$key]['TaskId'] = $taskSubject ?? 'N/A';
    //             $new_customers[$key]['TimeAppr'] = $est_time ?? 'N/A';
    //             $new_customers[$key]['TimeDiff'] = $diff ?? 'N/A';
    //             $new_customers[$key]['TimeTracked'] =  ( isset($trackedTime)  && isset($devTask->subject)) ? number_format($trackedTime / 60,2,".",",") : 'N/A';
    //             $new_customers[$key]['estimated_time'] = !empty($est) ? $est->new_value ?? 'N/A' : 'N/A';
    //             $new_customers[$key]['status'] = $devTask->status ?? 'N/A';
    
    //             // array_push($new_customers);
    
    //         }
    //         if( empty( $devTask ) ){
    //             continue;
    //         }
    //         // $task = \App\Hubstaff\HubstaffActivity::where('task_id', $user['task_id'])->first();
    //         // dd( $devTask );
	//       	$trackedTime = \App\Hubstaff\HubstaffActivity::where('task_id', $user['task_id'])->sum('tracked');
	//       	$time_history = \App\DeveloperTaskHistory::where('developer_task_id',$devTask->id)->where('attribute','estimation_minute')->where('is_approved',1)->first();

	//       	if($time_history) {
    //             $est_time = $time_history->new_value;
    //         }
    //         else {
    //             $est_time = 'N/A';
    //         }

    //         if (is_numeric($devTask->estimate_minutes) && $trackedTime && $devTask->subject){
    //             $diff =  $devTask->estimate_minutes - number_format($trackedTime / 60,2,".",",");
    //         }else{
    //             $diff = 'N/A';
    //         }

    //         if( $devTask ){
    //             $est = DeveloperTaskHistory::where('developer_task_id', $user['task_id'])->latest()->first();
    // 	        $new_customers[$key]['User'] = $userDev->name ?? null;
    //             $new_customers[$key]['date'] = \Carbon\Carbon::parse($user['date'])->format('d-m');
    // 	        $new_customers[$key]['TaskId'] = $taskSubject;
    // 	        $new_customers[$key]['TimeAppr'] = $est_time;
    // 	        $new_customers[$key]['TimeDiff'] = $diff;
    // 	        $new_customers[$key]['TimeTracked'] =  ( $trackedTime && $devTask->subject) ? number_format($trackedTime / 60,2,".",",") : 'N/A';
    //             $new_customers[$key]['estimated_time'] = !empty($est) ? $est->new_value ?? 'N/A' : 'N/A';
    // 	        $new_customers[$key]['estimated_time'] = $user['estimated_time'];
    // 	        $new_customers[$key]['status'] = $devTask->status;

    // 	        if (is_numeric($est_time) && $devTask->subject) {
    // 	        	$totalApproved += $est_time ?? 0;
    // 	        }

    // 	        if (is_numeric($diff) && $devTask->subject) {
    // 	        	$totalDiff += $diff;
    // 	        }
    // 	        if ($trackedTime && $devTask->subject) {
    // 	        	 $totalTrack += $trackedTime;
    // 	        }
    //             // array_push($new_customers);
    //             // dump([$key, $new_customers, 'out']);

    //         } 

            
    //         // if ($user['estimated_time'] !== null) {
    //         //     $estimatedTime += $user['estimated_time'];
    //         // }

    //   	// }
    //   }

    //END - DEVTASK-4300
    
    //START - Purpose : Genrate Excel Sheet - DEVTASK-4300
    $index = 0;
    $total_time_tracked = 0;
    $total_time_approved = 0;
    $total_time_panding = 0;
    $total_user_requested = 0;
    $total_panding_payment_type = 0;

    foreach ($this->user as $key => $user) {
        foreach($user as $kkk => $vvv) {

            $index = $index+1;
            
            $new_customers[$index]['Date'] = $vvv['date'];
            $new_customers[$index]['User'] = $vvv['userName'];
            $new_customers[$index]['Time_tracked_1'] = number_format($vvv['total_tracked'] / 60,2,".",",");

            $total_time_tracked += number_format($vvv['total_tracked'] / 60,2,".",",");

            
            
            foreach($vvv['tasks'] as $kk => $vv) {
                    @list($taskid,$devtask,$taskName,$estimation,$status,$devTaskId) = explode("||",$vv);

                    $trackedTime = \App\Hubstaff\HubstaffActivity::where('task_id', $taskid)->sum('tracked');
                    $time_history = \App\DeveloperTaskHistory::where('developer_task_id',$devTaskId)->where('attribute','estimation_minute')->where('is_approved',1)->first();
                    if($time_history) {
                        $est_time = $time_history->new_value;
                    }
                    else {
                        $est_time = 0;
                    }

                   
                    $old_key_index= $index;
                    if($taskid)
                    {
                        if (array_key_exists($index,$new_customers))
                        {
                            $old_key = $index;
                            $new_customers[$index]['Tasks'] = $devtask;

                            if ($taskName)
                                $Time_tracked_2 = (isset($trackedTime) && $devtask ) ? number_format($trackedTime / 60,2,".",",") : 'N/A';
                            else
                                $Time_tracked_2 = 'N/A';
                            
                            $new_customers[$index]['Time_tracked_2'] = $Time_tracked_2;


                            $new_customers[$index]['Time_estimation'] = (isset($taskName) ? $estimation : 'N/A');

                            if ($taskName)
                            {
                                if (is_numeric($estimation) && $trackedTime && $taskName)
                                {
                                    $trackedTime = ($trackedTime / 60);
                                    $Time_diff = number_format($estimation - $trackedTime,2,".",",");
                                    //$Time_diff = $estimation . '-' . number_format($trackedTime / 60,2,".",",");
                                }
                                else
                                    $Time_diff = 'N/A';
                            }else{
                                $Time_diff = 'N/A';
                            }   
                            
                            $new_customers[$index]['Time_diff'] = $Time_diff;

                            if ( $taskName )
                                $Status_1 = $status ? $status : 'N/A';
                            else
                                $Status_1 = 'N/A';

                            $new_customers[$index]['Status'] = $Status_1;

                            $new_customers[$index]['Time_app'] = $est_time;
                        }
                        else
                        {
                            $new_customers[$index]['Date'] = '';
                            $new_customers[$index]['User'] = '';
                            $new_customers[$index]['Time_tracked_1'] = '';

                            $new_customers[$index]['Tasks'] = $devtask;

                            if ($taskName)
                                $Time_tracked_2 = (isset($trackedTime) && $devtask ) ? number_format($trackedTime / 60,2,".",",") : 'N/A';
                            else
                                $Time_tracked_2 = 'N/A';
                            
                            $new_customers[$index]['Time_tracked_2'] = $Time_tracked_2;


                            $new_customers[$index]['Time_estimation'] = (isset($taskName) ? $estimation : 'N/A');

                            if ($taskName)
                            {
                                if (is_numeric($estimation) && $trackedTime && $taskName){
                                    $trackedTime = ($trackedTime / 60);
                                    $Time_diff = number_format($estimation - $trackedTime,2,".",",");
                                    //$Time_diff = $estimation . '-' . number_format($trackedTime / 60,2,".",",");
                                }
                                else
                                    $Time_diff = 'N/A';
                            }else{
                                $Time_diff = 'N/A';
                            }   
                            
                            $new_customers[$index]['Time_diff'] = $Time_diff;

                            if ( $taskName )
                                $Status_1 = $status ? $status : 'N/A';
                            else
                                $Status_1 = 'N/A';

                            $new_customers[$index]['Status'] = $Status_1;

                            $new_customers[$index]['Time_app'] = $est_time;

                            $new_customers[$index]['Time_approved'] = '';
                            $new_customers[$index]['Time_pending'] = '';
                            $new_customers[$index]['User_requested'] = '';
                            $new_customers[$index]['Pending_payment_time'] = '';
                            $new_customers[$index]['Status_2'] = '';
                            $new_customers[$index]['Note'] = '';
                        }
                        $index++;
                    }
                    else{
                        
                        if(!isset($old_key))
                        {
                            $old_key = $index;
                            $new_customers[$old_key]['Tasks'] = '';
                            $new_customers[$old_key]['Time_tracked_2'] = '';
                            $new_customers[$old_key]['Time_estimation'] =  '';
                            $new_customers[$old_key]['Time_diff'] = '';
                            $new_customers[$old_key]['status'] = '';
                            $new_customers[$old_key]['Time_app'] = '';
                        }
                    }
            }
            
           
            $new_customers[$old_key]['Time_approved'] = number_format($vvv['totalApproved'] / 60,2,".",",");
            $total_time_approved += number_format($vvv['totalApproved'] / 60,2,".",",");

            $new_customers[$old_key]['Time_pending'] = number_format($vvv['totalPending'] / 60,2,".",",");
            $total_time_panding += number_format($vvv['totalPending'] / 60,2,".",",");

            $new_customers[$old_key]['User_requested'] = number_format($vvv['totalUserRequest'] / 60,2,".",",");
            $total_user_requested += number_format($vvv['totalUserRequest'] / 60,2,".",",");

            $new_customers[$old_key]['Pending_payment_time'] = number_format($vvv['totalNotPaid'] / 60,2,".",",");
            $total_panding_payment_type += number_format($vvv['totalNotPaid'] / 60,2,".",",");

            $new_customers[$old_key]['Status_2'] = $vvv['status'];
            $new_customers[$old_key]['Note'] = $vvv['note'];
        }

        $new_customers[$index+4]['Date'] = 'Total';
        $new_customers[$index+4]['User'] = '';
        $new_customers[$index+4]['Time_tracked_1'] = ($total_time_tracked ? $total_time_tracked : '0');
        $new_customers[$index+4]['Tasks'] = '';
        $new_customers[$index+4]['Time_tracked_2'] = '';
        $new_customers[$index+4]['Time_estimation'] =  '';
        $new_customers[$index+4]['Time_diff'] = '';
        $new_customers[$index+4]['status'] = '';
        $new_customers[$index+4]['Time_app'] = '';
        $new_customers[$index+4]['Time_approved'] = ($total_time_approved ? $total_time_approved : '0');
        $new_customers[$index+4]['Time_pending'] = ($total_time_panding ? $total_time_panding : '0' );
        $new_customers[$index+4]['User_requested'] = ($total_user_requested ? $total_user_requested : '0' );
        $new_customers[$index+4]['Pending_payment_time'] = ($total_panding_payment_type ? $total_panding_payment_type : '0' );
        $new_customers[$index+4]['Status_2'] = '';
        $new_customers[$index+4]['Note'] = '';

    }
    // dd($new_customers);
    // dd("stop");
    //END - DEVTASK-4300
 
    //   dd($new_customers);
     // array_push($new_customers, ['Total ',null,null,$totalApproved,$totalDiff, null, $estimatedTime, number_format($totalTrack / 60,2,".",",")]);//Purpose : Comment code - DEVATSK-4300
     
      return $new_customers;
    }

    public function headings() : array
    {
        // return ["User", "Date", "Task", "Time approved", "Time Diff", "Time tracked", "Estimated Time", "Status"]; //Purpose : Comment code - DEVATSK-4300
        return ["Date", "User", "Time tracked (Minutes)", "Tasks", "Time tracked (Minutes)", "Time Estimation (Minutes)", "Time Diff. (Minutes)", "Status","Time app.","Time approved","Time Pending","User Requested","Pending payment time","Status","Note"];//Purpose : Add Header - DEVATSK-4300
    }
}
