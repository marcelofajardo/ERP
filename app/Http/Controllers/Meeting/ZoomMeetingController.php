<?php
/**
 * Class ZoomMeetingController | app/Http/Controllers/Meeting/ZoomMeetingController.php
 * Zoom Meetings integration for video call purpose using LaravelZoom's REST API
 *
 * @package  Zoom
 * @subpackage Jwt Token
 * @filesource required php 7 as this file contains tokenizer extension which was not stable prior to this version
 * @see https://github.com/saineshmamgain/laravel-zoom
 * @see ZoomMeetings
 * @author   sololux <sololux@gmail.com>
 */
namespace App\Http\Controllers\Meeting;

use App\Meetings\ZoomMeetings;
use Auth;
use Cache;
use Validator;
use Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use seo2websites\LaravelZoom\LaravelZoom;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
/**
 * Class ZoomMeetingController - active record
 *
 * A zoom class used to create meetings
 * This class is used to interact with zoom interface.
 *
 * @package  LaravelZoom
 * @subpackage Jwt Token
 */
class ZoomMeetingController extends Controller
{
    /**
     * Constructor of class
     * Calling env variables and adding in scope
     *
     */
    public function __construct()
    {
        // $this->zoomkey = env('ZOOM_API_KEY');
        // $this->zoomsecret = env('ZOOM_API_SECRET');
        // $this->zoomuser = env('ZOOM_USER');
        $this->zoomkey = config('env.ZOOM_API_KEY');
        $this->zoomsecret = config('env.ZOOM_API_SECRET');
        $this->zoomuser = config('env.ZOOM_USER');
    }

    /**
     * Create a meeting with zoom based on the params send through form
     * @param Request $request Request
     * @return \Illuminate\Http\Response
     * @Rest\Post("twilio/token")
     *
     * @uses Auth
     * @uses ClientToken
     */
   public function createMeeting( Request $request )
    {
        $this->validate( $request, [
            'meeting_topic' => 'required|min:3|max:255',
            //'start_date_time' => 'required',
            //'meeting_duration' => 'required',
            //'timezone' => 'required'
        ]);

        $input = $request->all();
        
        $startDate      = strtotime(new Carbon($request->get("start_date_time",date("Y-m-d H:i",strtotime("+5 minutes")))));
        $currentDate    = strtotime(Carbon::now());

        if($startDate < $currentDate){
            $data = ['msg' => 'Start date time should not be less than current date time.'];
            return Response::json(array(
                'success' => false,
                'data'   => $data
            ));
        }

        $userId = $this->zoomuser;
        // Default settings for zoommeeting
         $settings = [
            'join_before_host' => true,
            'host_video' => true,
            'participant_video' => true,
            'mute_upon_entry' => false,
            'enforce_login' => false,
            'auto_recording' => 'cloud'
        ];

        // add default setting in meeting
        $input['start_date_time']  = date("Y-m-d H:i",$startDate);
        $input['meeting_duration'] = $request->get("meeting_duration",5);
        $input['timezone']         = $request->get("timezone","Asia/Dubai");
        $input['meeting_agenda']   = $request->get("agenda","");
        // gethering all data to pass to model function
        $data = [
            'user_id'   => $userId,
            'topic'     => $input['meeting_topic'],
            'agenda'    => $input['meeting_agenda'],
            'settings'  => $settings,
            'startTime' => new Carbon($input['start_date_time']),
            'duration'  => $input['meeting_duration'],
            'timezone'  => $input['timezone'],
        ];
        // Calling model calss
        $meetings       = new ZoomMeetings();
        $zoomKey        = $this->zoomkey;
        $zoomSecret     = $this->zoomsecret;
        $createMeeting  = $meetings->createMeeting($zoomKey,$zoomSecret, $data);

        if($createMeeting){
         $input[ 'meeting_id' ] = empty( $createMeeting[ 'body' ]['id'] ) ? "" : $createMeeting[ 'body' ]['id'];
         $input[ 'host_zoom_id' ] = $this->zoomuser;
         $input[ 'meeting_type' ] = 'scheduled';
         $input[ 'join_meeting_url' ] = empty( $createMeeting[ 'body' ]['join_url'] ) ? "" : $createMeeting[ 'body' ]['join_url'];
         $input[ 'start_meeting_url' ] = empty( $createMeeting[ 'body' ]['start_url'] ) ? "" : $createMeeting[ 'body' ]['start_url'];
         // saving data in db
         $createMeeting = ZoomMeetings::create( $input );
         if($createMeeting){
             $getUserDetails =  $meetings->getUserDetails($input[ 'user_id' ], $input[ 'user_type' ]);
             if(!empty($getUserDetails)){
              $phonenumber  = isset($getUserDetails->number) ? $getUserDetails->number : $getUserDetails->phone;
              $msg = "New meeting has been scheduled for you. Kindly find below the link to join the meeting. ".$input[ 'join_meeting_url' ];
             $html = "New meeting has been scheduled for you. Kindly find below the link to join the meeting. <br><br> <a href='".$input[ 'join_meeting_url' ]."' target='_blank'>".$input[ 'join_meeting_url' ]."</a>";
             if(!empty($phonenumber)){
             $message = app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($phonenumber, $getUserDetails->whatsapp_number, $msg);
             }
             $email = $getUserDetails->email;
             if(!empty($email)){
                if('supplier' == $input[ 'user_type' ]){
                   $name =  $getUserDetails->supplier;
                }else{
                  $name =  $getUserDetails->name;
                }
             $data = array('name'=>"SoloLuxury");
             }
             }
             $data = ['msg' => 'New Meeting added successfully.', 'meeting_link' => $input[ 'join_meeting_url' ], 'start_meeting' => $input[ 'start_meeting_url' ]];
            return Response::json(array(
                'success' => true,
                'data'   => $data
              ));
         }else{
            $data = ['msg' => 'Token is expired. Please try to add the meeting again.'];
            return Response::json(array(
                'success' => false,
                'data'   => $data
              ));
         }
        }else{
            $data = ['msg' => 'Meeting not added.'];
            return Response::json(array(
                'success' => false,
                'data'   => $data
              ));
        }
    }

    public function getMeetings()
    {
        $zoomKey =  $this->zoomkey;
        $zoomSecret = $this->zoomsecret;
        $zoom = new LaravelZoom($zoomKey,$zoomSecret);
        $meeting1 = $zoom->getJWTToken(time() + 7200);
        $meeting = $zoom->getUsers('active',10);
        $user_id = '-ISK-roPRUyC3-3N5-AT_g';
        $topic = 'Test meeting using erp';
        $agenda = "Communication with team";
        $startTime = Carbon::tomorrow();
        $duration = 40;
        $timezone = 'Asia/Kolkata';
        $settings = [
            'join_before_host' => true,
            'host_video' => true,
            'participant_video' => true,
            'mute_upon_entry' => false,
            'enforce_login' => false,
            'auto_recording' => 'local'
        ];

        $data = ['user_id' => $user_id,'topic' => $topic, 'agenda' => $agenda, 'settings' => $settings, 'startTime' => $startTime, 'duration' => $duration, 'timezone' => $timezone, 'type' => 'all'];
        $meetings = new ZoomMeetings();
        //$createMeet = $meetings->getMeetings($zoomKey,$zoomSecret, $data);
        $createMeet = $meetings->createMeeting($zoomKey,$zoomSecret, $data);
        echo "hello"; echo "<pre>"; print_r($createMeet); die; die;

    }

    public function showData(Request $request){
    $type = $request->get('type');
    $meetings = new ZoomMeetings();
    $curDate = Carbon::now();
    $upcomingMeetings = $meetings->upcomingMeetings($type, $curDate);
    $pastMeetings = $meetings->pastMeetings($type, $curDate);
    return view('zoom-meetings.showdata', [
            'upcomingMeetings' => $upcomingMeetings,
            'pastMeetings' => $pastMeetings,
            'type' => $type
        ]);
    }

    public function show(){
    $type = "";
    $upcomingMeetings = [];
    $pastMeetings = [];
    return view('zoom-meetings.showdata', [
            'upcomingMeetings' => $upcomingMeetings,
            'pastMeetings' => $pastMeetings,
            'type' => $type
        ]);
    }
}
