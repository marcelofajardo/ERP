<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\Learning;
use App\DailyActivitiesHistories;
use App\UserEvent\UserEvent;
use App\UserEvent\UserEventAttendee;
use App\UserEvent\UserEventParticipant;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;


class UserEventController extends Controller
{

    function index()
    {
        $userId = Auth::user()->id;
        $link = base64_encode('soloerp:' . $userId);
        return view(
            'user-event.index',
            [
                'link' => $link
            ]
        );
    }



    /**
     * list of user events as json
     */
    function list(Request $request)
    {

        $userId = Auth::user()->id;

        if (!$userId) {
            return response()->json(
                [
                    'message' => 'Not allowed'
                ],
                401
            );
        }

        $start = explode('T', $request->get('start'))[0];
        $end = explode('T', $request->get('end'))[0];

        $events = UserEvent::with(['attendees'])
            ->where('start', '>=', $start)
            ->where('end', '<', $end)
            ->where('user_id', $userId)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'subject' => $event->subject,
                    'title' => $event->subject,
                    'description' => $event->description,
                    'date' => $event->date,
                    'start' => $event->start,
                    'end' => $event->end,
                    'attendees' => $event->attendees
                ];
            });


        return response()->json($events);
    }

    /**
     * edit event
     */
    function editEvent(Request $request, int $id)
    {
        $userId = Auth::user()->id;

        if (!$userId) {
            return response()->json(
                [
                    'message' => 'Not allowed'
                ],
                401
            );
        }

        $start = $request->get('start');
        $end = $request->get('end');

        $userEvent = UserEvent::find($id);

        if (!$userEvent) {
            return response()->json(
                [
                    'message' => 'Event not found'
                ],
                404
            );
        }

        if ($userEvent->user_id != $userId) {
            return response()->json(
                [
                    'message' => 'Not allowed to edit event'
                ],
                401
            );
        }

        $userEvent->start = $start;
        $userEvent->end = $end;
        $userEvent->save();

        // once user event has been stored create the event in daily planner
        $dailyActivities = new \App\DailyActivity; 
        if($userEvent->daily_activity_id > 0) {
            $dailyActivities  = \App\DailyActivity::find($userEvent->daily_activity_id);
            if(empty($dailyActivities)) {
                $dailyActivities = new \App\DailyActivity;
            }
        }

        $dailyActivities->time_slot = date("h:00 a",strtotime($userEvent->start)) . " - " .date("h:00 a",strtotime($userEvent->end));
        $dailyActivities->activity  = $userEvent->subject;
        $dailyActivities->user_id   = $userId;
        $dailyActivities->for_date  = $date;
        
        if($dailyActivities->save()) {
           $userEvent->daily_activity_id =  $dailyActivities->id;
           $userEvent->save();
        }

        // check first and vendors
        $vendors = $request->get("vendors",[]);
        UserEventParticipant::where("user_event_id",$userEvent->id)->delete();
        if(!empty($vendors) && is_array($vendors)) {
            foreach($vendors as $vendor) {
                $userEventParticipant = new UserEventParticipant;
                $userEventParticipant->user_event_id = $userEvent->id;
                $userEventParticipant->object = \App\Vendor::class;
                $userEventParticipant->object_id = $vendor;
                $userEventParticipant->save();
            }
        }

        return response()->json([
            'message' => 'Event updated',
            'event' => [
                'id' =>  $userEvent->id,
                'title' =>  $userEvent->title,
                'start' =>  $userEvent->start,
                'end' => $userEvent->end
            ]
        ]);
    }

    public function GetEditEvent( Request $request, int $id ){
        $id = $request->id;
        if ( empty( $id ) ) {
            return response()->json([
                    'message' => 'Not allowed'
            ],401);
        }

        $edit = UserEvent::where('daily_activity_id', $id)->with('attendees')->first();
        if( empty( $edit ) ){
            return redirect()->back()->with('error','Not record found');
        }

        $vendor = UserEventParticipant::where('user_event_id',$edit->id)->pluck('object_id')->toArray();
        return view('dailyplanner.edit-event',compact('edit','vendor'));
    }

    public function UpdateEvent( Request $request ){

        $validated = $request->validate([
            'date'    => 'required',
            'time'    => 'required',
            'subject' => 'required',
        ]);
        
        $date           = $request->get('date');
        $time           = $request->get('time');
        $subject        = $request->get('subject');
        $description    = $request->get('description');
        $contactsString = $request->get('contacts');
        
        $start = $date . ' ' . $time;
        $end = strtotime($start . ' + 1 hour');
        $start = strtotime($start);


        $userEvent = UserEvent::findorFail( $request->edit_id );
        $userEvent->subject = $subject;
        $userEvent->description = ($description) ? $description : "";
        $userEvent->date = $date;

        if (isset($time)) {
            $start = strtotime($date . ' ' . $time);
            $end   = strtotime($date . ' ' . $time . ' + 1 hour');
            $userEvent->start = date('Y-m-d H:i:s', $start);
            $userEvent->end = date('Y-m-d H:i:s', $end);
        }

        $userEvent->save();

        $dailyActivities = \App\DailyActivity::findorFail( $request->daily_activity_id );
        $dailyActivities->time_slot = date("h:00a",strtotime($userEvent->start)) . " - " .date("h:00a",strtotime($userEvent->end));
        $dailyActivities->activity  = $userEvent->subject;
        $dailyActivities->for_date  = $date;
        $dailyActivities->for_datetime  = $date . ' ' . $time;
        $dailyActivities->save();
        
        if( request('edit_next_recurring') == '1' ){
            
            $update = [
                'activity'  => $userEvent->subject,
                'time_slot' => $dailyActivities->time_slot,
            ];

            $now_str      = now()->format('Y-m-d');
            $future_event = \App\DailyActivity::where('parent_row',$request->daily_activity_id )->where('for_date', '>', $now_str)->update( $update );

        }
        
        $vendors = $request->get("vendors",[]);
        if(!empty($vendors) && is_array($vendors)) {
            UserEventParticipant::where('user_event_id', $userEvent->id)->delete();
            foreach($vendors as $vendor) {
                $userEventParticipant = new UserEventParticipant;
                $userEventParticipant->user_event_id = $userEvent->id;
                $userEventParticipant->object = \App\Vendor::class;
                $userEventParticipant->object_id = $vendor;
                $userEventParticipant->save();
            }
        }
        $history = [
            'daily_activities_id' => $request->daily_activity_id,
            'title'               => 'Event Edit',
            'description'         => 'Event edit by '.Auth::user()->name,
        ];
        DailyActivitiesHistories::insert( $history );
        return redirect()->back()->with('success','success');
    }

    /**
     * Stop notification
     */
    function stopEvent( Request $request ){
        
        $id = $request->parent_id;
        if (!$id) {
            return response()->json(
                [
                    'message' => 'Not allowed'
                ],
                401
            );
        }

        try {
            \App\DailyActivity::where('id', $id)->update(['status' => 'stop']);
            \App\DailyActivity::where('parent_row', $id)->where('for_date' , '>=' , Carbon::now()->toDateTimeString())->delete();
            $history = [
                'daily_activities_id' => $id,
                'title'               => 'Event Stop',
                'description'         => 'Event Stop by '.Auth::user()->name,
            ];
            DailyActivitiesHistories::insert( $history );
            return response()->json([
                "code"    => 200, 
                'message' => 'Event stop successfully',
            ]);
        } catch (\Throwable $th) {
            $history = [
                'daily_activities_id' => $id,
                'title'               => 'Event Stop failed',
                'description'         => $th->getMessage(),
            ];
            DailyActivitiesHistories::insert( $history );
            return response()->json([
                "code"    => 500, 
                'message' => $th->getMessage(),
            ]);
        }

    }

    /**
     * Create a new event
     */
    function createEvent(Request $request)
    {
        $userId = Auth::user()->id;

        if (!$userId) {
            return response()->json(
                [
                    'message' => 'Not allowed'
                ],
                401
            );
        }



        $date = $request->get('date');
        $time = $request->get('time');
        $subject = $request->get('subject');
        $description = $request->get('description');
        $contactsString = $request->get('contacts');

        $errors = array();

        // date validations
        if (!$date) {
            $errors['date'][] = 'Date is missing';
        } else if (!preg_match('/^[0-9]{4}-((0[1-9])|(1[0|1|2]))-(0|1|2|3)[0-9]$/', $date)) {
            $errors['date'][] = 'Invalid date format';
        } else if (!validateDate($date)) {
            $errors['date'][] = 'Invalid date';
        }

        if (isset($time)) {
            if (!preg_match('/^(([0-1][0-9])|(2[0-3])):[0-5][0-9]$/', $time)) {
                $errors['time'] = 'Invalid time format';
            }
        }

        if (empty(trim($subject))) {
            $errors['subject'][] = 'Subject is required';
        }

        if ($request->type =='learning' && empty($request->users)) {
            $errors['vendor'][] = 'Please select user';
        }

        if ($request->type=='learning' && $request->has('users') && ( count($request->users) > 1))  {
            $errors['vendor'][] = 'Please select one user';
        }


        if (!empty($errors)) {
            return response()->json($errors, 400);
        }


        $start = $date . ' ' . $time;
        $for_datetime = $start;
        $end = strtotime($start . ' + 1 hour');
        $start = strtotime($start);

        if($request->type == 'event')
        {
            $userEvent = new UserEvent;
            $userEvent->user_id = $userId;
            $userEvent->subject = $subject;
            $userEvent->description = ($description) ? $description : "";
            $userEvent->date = $date;

            if (isset($time)) {
                $start = strtotime($date . ' ' . $time);
                $end = strtotime($date . ' ' . $time . ' + 1 hour');
                $userEvent->start = date('Y-m-d H:i:s', $start);
                $userEvent->end = date('Y-m-d H:i:s', $end);
            }

            $userEvent->save();

            // once user event has been stored create the event in daily planner
            $dailyActivities = new \App\DailyActivity;
            $dailyActivities->time_slot = date("h:00a",strtotime($userEvent->start)) . " - " .date("h:00a",strtotime($userEvent->end));
            $dailyActivities->activity  = $userEvent->subject;
            $dailyActivities->user_id   = $userId;
            $dailyActivities->for_date  = $date;
            $dailyActivities->for_datetime    = $for_datetime;
            $dailyActivities->repeat_type     = $request->repeat;
            $dailyActivities->repeat_on       = $request->repeat_on;
            $dailyActivities->repeat_end      = $request->ends_on;
            $dailyActivities->repeat_end_date = $request->repeat_end_date;
            $dailyActivities->timezone        = $request->timezone;
            $dailyActivities->type            = 'event';
            $dailyActivities->type_table_id   = $userEvent->id;
            
            if($dailyActivities->save()) {
                $dailyActivities->parent_row = $dailyActivities->id;
                $dailyActivities->save();
               $userEvent->daily_activity_id =  $dailyActivities->id;
               $userEvent->save();
            }

            // save the attendees
            $attendees = explode(',', $contactsString);

            $attendeesResponse = [];

            foreach ($attendees as $attendee) {
                $attendeeDb = new UserEventAttendee;
                $attendeeDb->user_event_id = $userEvent->id;
                $attendeeDb->contact = $attendee;
                $attendeeDb->save();

                $attendeesResponse[] = $attendeeDb->toArray();
            }

            $vendors = $request->get("vendors",[]);
            if(!empty($vendors) && is_array($vendors)) {
                foreach($vendors as $vendor) {
                    $userEventParticipant = new UserEventParticipant;
                    $userEventParticipant->user_event_id = $userEvent->id;
                    $userEventParticipant->object = \App\Vendor::class;
                    $userEventParticipant->object_id = $vendor;
                    $userEventParticipant->save();
                }
            }
            $history = [
                'daily_activities_id' => $dailyActivities->id,
                'title'               => 'Event create',
                'description'         => "Event created by ".Auth::user()->name,
            ];
            DailyActivitiesHistories::insert( $history );

            \Log::error( 'Daily activities ::',DailyActivitiesHistories::where( 'daily_activities_id', $dailyActivities->id )->get()->toArray() );
            return response()->json([
                "code"    => 200, 
                'message' => 'Event added successfully',
                'event' => $userEvent->toArray(),
                'attendees' => $attendeesResponse
            ]);
        
        }else{

            $data['learning_user']       = Auth::id();
            $data['learning_vendor']     = $request->users[0];
            $data['learning_subject']    = $subject;
            $data['learning_assignment'] = $description;
            $data['learning_duedate']    = $request->date;

            $learning = Learning::create($data);

            $start = strtotime($date . ' ' . $time);
            $end = strtotime($date . ' ' . $time . ' + 1 hour');

            $dailyActivities = new \App\DailyActivity;
            $dailyActivities->time_slot = date("h:00a",strtotime($start)) . " - " .date("h:00a",strtotime($end));
            $dailyActivities->activity  = $learning->subject;
            $dailyActivities->user_id   = $userId;
            $dailyActivities->for_date  = $date;
            $dailyActivities->for_datetime    = $for_datetime;
            $dailyActivities->repeat_type     = $request->repeat;
            $dailyActivities->repeat_on       = $request->repeat_on;
            $dailyActivities->repeat_end      = $request->ends_on;
            $dailyActivities->repeat_end_date = $request->repeat_end_date;
            $dailyActivities->timezone        = $request->timezone;
            $dailyActivities->type            = 'learning';
            $dailyActivities->type_table_id   = $learning->id;

            if($dailyActivities->save()) {
                $dailyActivities->parent_row = $dailyActivities->id;
                $dailyActivities->save();
            }

            return response()->json([
                "code"    => 200, 
                'message' => 'Learning added successfully',
            ]);
        }    
    }

    function removeEvent(Request $request, $id)
    {
        $userId = Auth::user()->id;

        if (!$userId) {
            return response()->json(
                [
                    'message' => 'Not allowed'
                ],
                401
            );
        }

        $result = UserEvent::where('id', $id)->where('user_id', $userId)->first();
        if($result) {
            $result->attendees()->delete();
            $result->delete();
            return response()->json([
                'message' => 'Event deleted:' . $result
            ]);
        }

        return response()->json([
            'message' => 'Failed to deleted',
            404
        ]);
    }

    /*
             ____    _   _   ____    _       ___    ____ 
            |  _ \  | | | | | __ )  | |     |_ _|  / ___|
            | |_) | | | | | |  _ \  | |      | |  | |    
            |  __/  | |_| | | |_) | | |___   | |  | |___ 
            |_|      \___/  |____/  |_____| |___|  \____|
                                                            
    */

    /**
     * show public calendar
     */
    function publicCalendar($id)
    {
        $calendarId = base64_decode($id);
        $calendarUserId = explode(':', $calendarId)[1];

        $user = User::find($calendarUserId, ['name']);

        return view(
            'user-event.public-calendar',
            [
                'calendarId' => $id,
                'user' => $user
            ]
        );
    }

    /**
     * events of the user without auth
     */
    function publicEvents(Request $request, $id)
    {
        $text = base64_decode($id);
        $calendarUserId = explode(':', $text)[1];

        $start = explode('T', $request->get('start'))[0];
        $end = explode('T', $request->get('end'))[0];

        $events = UserEvent::with(['attendees'])
            ->where('start', '>=', $start)
            ->where('end', '<', $end)
            ->where('user_id', $calendarUserId)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'subject' => $event->subject,
                    'title' => $event->subject,
                    'description' => $event->description,
                    'date' => $event->date,
                    'start' => $event->start,
                    'end' => $event->end,
                    'attendees' => $event->attendees
                ];
            });
        return response()->json($events);
    }

    /**
     * suggest timing for the invitation view
     */
    function suggestInvitationTiming($invitationId)
    {

        $attendee = UserEventAttendee::with('event')->find($invitationId);

        return view(
            'user-event.public-calendar-time-suggestion',
            [
                'attendee' => $attendee,
                'invitationId' => $invitationId
            ]
        );
    }

    /**
     * save suggested timing
     */
    function saveSuggestedInvitationTiming(Request $request, $invitationId)
    {
        UserEventAttendee::where('id', '=', $invitationId)
        ->update([
            'suggested_time' => $request->get('time')
        ]);

        return redirect('/calendar/public/event/suggest-time/'.$invitationId)->with([
            'message' => 'Saved data'
        ]);
    }
}
