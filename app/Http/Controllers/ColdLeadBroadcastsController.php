<?php

namespace App\Http\Controllers;

use App\ColdLeadBroadcasts;
use App\ColdLeads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use InstagramAPI\Instagram;
use Carbon\Carbon;
use App\Account;
use App\ImQueue;
use App\CompetitorPage;
use App\Marketing\InstagramConfig;

class ColdLeadBroadcastsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return view('cold_leads.broadcasts.index');
        }

        $this->validate($request, [
            'pagination' => 'required|integer',
        ]);

        if (strlen($request->get('query')) >= 4) {
            $query = $request->get('query');
            $leads = ColdLeadBroadcasts::where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                });
        } else {
            $leads = new ColdLeadBroadcasts;
        }

        $leads = $leads->orderBy('updated_at', 'DESC')->paginate($request->get('pagination'));
        $competitors = CompetitorPage::select('id','name')->where('platform', 'instagram')->get();

        

        return response()->json([
            'leads' => $leads,
            'competitors' => $competitors,
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'number_of_users' => 'required',
            'frequency' => 'required',
            'message' => 'required',
            'started_at' => 'required',
            'status' => 'required',
        ]);
        
        $broadcast = new ColdLeadBroadcasts();
        $broadcast->name = $request->get('name');
        $broadcast->number_of_users = $request->get('number_of_users');
        $broadcast->frequency = $request->get('frequency');
        $broadcast->message = $request->get('message');
        $broadcast->started_at = $request->get('started_at');
        $broadcast->status = $request->get('status');
        $broadcast->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Storage::disk('uploads')->putFile('', $file);
            $broadcast->image = $fileName;
            $broadcast->save();
        }

        $limit = $request->get('number_of_users');

        $query = ColdLeads::query();
        $competitor = $request->competitor;

        if(!empty($competitor)){
            $comp = CompetitorPage::find($competitor);
            $query = $query->where('because_of','LIKE','%via '.$comp->name.'%');
        }

        if(!empty($request->gender)){
            $query = $query->where('gender', $request->gender);   
        }

        $coldleads = $query->where('status', 1)->where('messages_sent', '<', 5)->take($limit)->orderBy('messages_sent', 'ASC')->orderBy('id', 'ASC')->get();
        
        
        $count = 0;
        $leads = [];

        $now = $request->started_at ? Carbon::parse($request->started_at) : Carbon::now();
                $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);

                if (!$now->between($morning, $evening, true)) {
                    if (Carbon::parse($now->format('Y-m-d'))->diffInWeekDays(Carbon::parse($morning->format('Y-m-d')), false) == 0) {
                        // add day
                        $now->addDay();
                        $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                    } else {
                        // dont add day
                        $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                    }
                }
        $sendingTime = ''; 

        foreach ($coldleads as $coldlead) {
            $count++;

            // Convert maxTime to unixtime
            if(empty($sendingTime)){
                $maxTime = strtotime($now);
            }else{
                $now = $sendingTime ? Carbon::parse($sendingTime) : Carbon::now();
                $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);

                if (!$now->between($morning, $evening, true)) {
                    if (Carbon::parse($now->format('Y-m-d'))->diffInWeekDays(Carbon::parse($morning->format('Y-m-d')), false) == 0) {
                        // add day
                        $now->addDay();
                        $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                    } else {
                        // dont add day
                        $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                    }
                }
                $sendingTime = $now;
                $maxTime = strtotime($sendingTime);
            }
            

            // Add interval
            $maxTime = $maxTime + (3600 / $request->frequency);
            
            // Check if it's in the future
            if ($maxTime < time()) {
                $maxTime = time();
            }

            $sendAfter = date('Y-m-d H:i:s', $maxTime);
            $sendingTime = $sendAfter;

            //Giving BroadCast to Least Count
            $count = [];

            if($request->account_id){
                $instagramAccount = InstagramConfig::find($request->account_id);
                    
                if($instagramAccount){
                    $queue = new ImQueue();
                    $queue->im_client = 'instagram';
                    $queue->number_to = $coldlead->platform_id;
                    $queue->number_from = $instagramAccount->username;
                    $queue->text = $request->message;
                    $queue->priority = null;
                    $queue->marketing_message_type_id = 1;
                    $queue->broadcast_id = $broadcast->id;
                    $queue->send_after = $sendAfter;
                    $queue->save();
                }
                

            }else{

                $instagramAccounts = InstagramConfig::where('status','1')->get();
                foreach ($instagramAccounts  as $instagramAccount) {
                    $count[] = array($instagramAccount->imQueueBroadcast->count() => $instagramAccount->username);
                }
            
                ksort($count);
                
                if(isset($count[0][key($count[0])])){
                    $username = $count[0][key($count[0])];
                    $queue = new ImQueue();
                    $queue->im_client = 'instagram';
                    $queue->number_to = $coldlead->platform_id;
                    $queue->number_from = $username;
                    $queue->text = $request->message;
                    $queue->priority = null;
                    $queue->marketing_message_type_id = 1;
                    $queue->broadcast_id = $broadcast->id;
                    $queue->send_after = $sendAfter;
                    $queue->save();
                }

            }
            
            
            $coldlead->status = 2;
            $coldlead->save();
        }

        
        return redirect()->back();
       

        // $coldleads = ColdLeads::whereNotIn('status', [0])->whereNotIn('id', $leads)->where('messages_sent', '<', 5)->take($limit-$count)->orderBy('messages_sent', 'ASC')->orderBy('id', 'ASC')->get();

        // $count = 0;
        // foreach ($coldleads as $coldlead) {
        //     $count++;

        //     $broadcast->lead()->attach($coldlead->id, [
        //         'status' => 0
        //     ]);

        //     $coldlead->status = 2;
        //     $coldlead->save();
        // }

        // return response()->json([
        //     'status' => 'success'
        // ]);





    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ColdLeadBroadcasts  $coldLeadBroadcasts
     * @return \Illuminate\Http\Response
     */
    public function show(ColdLeadBroadcasts $coldLeadBroadcasts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ColdLeadBroadcasts  $coldLeadBroadcasts
     * @return \Illuminate\Http\Response
     */
    public function edit(ColdLeadBroadcasts $coldLeadBroadcasts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ColdLeadBroadcasts  $coldLeadBroadcasts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ColdLeadBroadcasts $coldLeadBroadcasts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ColdLeadBroadcasts  $coldLeadBroadcasts
     * @return \Illuminate\Http\Response
     */
    public function destroy(ColdLeadBroadcasts $coldLeadBroadcasts)
    {
        //
    }
}
