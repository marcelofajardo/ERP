<?php

namespace App\Http\Controllers;

use App\Account;
use App\Brand;
use App\ColdLeads;
use App\Customer;
use App\InstagramDirectMessages;
use App\InstagramThread;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use InstagramAPI\Instagram;
use InstagramAPI\Media\Photo\InstagramPhoto;

Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class ColdLeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        if (!$request->isXmlHttpRequest()) {
            if(isset($request->via)){
                $via = $request->via;
            }else{
                $via = '';
            }
            return view('cold_leads.index',compact('via'));
        }

        $this->validate($request, [
            'pagination' => 'required|integer',
        ]);

        if (strlen($request->get('query')) >= 4) {
            $query = $request->get('query');
            $leads = ColdLeads::where('status', '>', 0)
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                    $q->where('username', 'LIKE', "%$query%");
                });
        } else {
            $leads = ColdLeads::where('status', '>', 0);
        }

        if ($request->get('gender') == 'm' || $request->get('gender') == 'f' || $request->get('gender') == 'o') {
            $leads = $leads->where('gender', $request->get('gender'));
        }

        if ($request->get('acc') > 0) {
            $leads = $leads->where('account_id', $request->get('acc'));
        }

        $leads = $leads->orderBy('updated_at', 'DESC')->with('account')->paginate($request->get('pagination'));

        $accounts = Account::where('platform', 'instagram')->where('broadcast', 1)->get();

        return response()->json([
            'leads' => $leads,
            'accounts' => $accounts
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ColdLeads  $coldLeads
     * @return \Illuminate\Http\Response
     */
    public function show(ColdLeads $coldLeads)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ColdLeads  $coldLeads
     * @return \Illuminate\Http\Response
     */
    public function edit(ColdLeads $coldLeads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ColdLeads  $coldLeads
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ColdLeads $coldLeads)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     */
    public function destroy($id)
    {
        $lead = ColdLeads::find($id);

        if ($lead) {
            $lead->delete();
        }

        return redirect()->back()->with('message', 'Cold lead deleted successfully!');
    }

    public function sendMessage($leadId, Request $request) {
        
        // $this->validate($request, [
        //     'account_id' => 'required'
        // ]);

        $lead = ColdLeads::find($leadId);

        //$account = Account::find($request->get('account_id'));

        //Commenting to sending from other accounts now will only be sending from admin
        //$senderUsername = $account->last_name;
        //$password = $account->password;
        
        $senderUsername = env('IG_USERNAME');
        $password = env('IG_PASSWORD');

        
        $receiverId = $lead->platform_id;
        
        $message = $request->get('message');

        if (strlen($receiverId) < 5) {
            $receiverId = $lead->username;
        }

        $messageType = 1;
        if ($request->has('image')) {
            $status = $this->sendFileToInstagramUser($senderUsername, $password, $receiverId, $request->file('image'), $lead);
            $messageType = 2;
        } else {
            $status = $this->sendMessageToInstagramUser($senderUsername, $password, $receiverId, $message, $lead);
        }

        if ($status === false) {
            return response()->json([
                'error'
            ], 413);
        }
        $accountId = 1;
        $thread = InstagramThread::where('account_id', $accountId)->where('cold_lead_id', $leadId)->first();
        if (!$thread) {
            $thread = new InstagramThread();
            $thread->account_id = $accountId;
            $thread->cold_lead_id = $leadId;
        }
        $thread->last_message = $message;
        $thread->save();

        $dm = new InstagramDirectMessages();
        $dm->instagram_thread_id = $thread->id;
        $dm->message_type = $messageType;
        $dm->sender_id = $status[1];
        $dm->message = $status[2];
        $dm->receiver_id = $receiverId;
        $dm->status = 1;
        $dm->save();

        return response()->json([
            'status' => 'success',
            'receiver_id' => $receiverId,
            'sender_id' => $status[1],
            'message' => $status[2]
        ]);

    }

    private function sendFileToInstagramUser($sender, $password, $receiver, $file, $lead) {
        $i = new Instagram();

        try {
            $i->login($sender, $password);
        } catch (\Exception $exception) {
            return false;
        }

        if (!is_scalar($receiver)) {
            $receiver = $i->people->getUserIdForName($receiver);
            $lead->platform_id = $receiver;
            $lead->save();
        }


        $fileName = Storage::disk('uploads')->putFile('', $file);

        $photo = new InstagramPhoto($file);
        $i->direct->sendPhoto([
            'users' => [
                $receiver
            ]
        ], $photo->getFile());

        return [true, $i->account_id, $fileName];


    }

    private function sendMessageToInstagramUser($sender, $password,  $receiver, $message, $lead) {

        $i = new Instagram();

        try {
            $i->login($sender, $password);
        } catch (\Exception $exception) {
            return false;
        }

        if (!is_numeric($receiver)) {
            $receiver = $i->people->getUserIdForName($receiver);
            $lead->platform_id = $receiver;
            $lead->save();
        }

        try {
            $i->direct->sendText([
                'users' => [
                    $receiver
                ]
            ], $message);
        } catch (\Exception $exception) {
            dd($exception);
        }
        return [true, $i->account_id, $message];

    }

    public function getMessageThread($id) {
        $instagramThread = InstagramThread::where('cold_lead_id', $id)->get();
        $processedThread = [];

        foreach ($instagramThread as $item) {
            $messages = $item->conversation;
            $coldLeadPlatformId = $item->lead->platform_id;

            $processedMessages = [];
            foreach ($messages as $message) {
                $processedMessages[] = [
                    'message' => $message->message,
                    'type' => $message->message_type,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'created_at' => $message->created_at->format('Y-m-d')
                ];
            }

            $processedThread[] = [
                'messages' => $processedMessages,
                'lead_instagram_id' => $coldLeadPlatformId,
                'account_id' => $item->account_id
            ];
        }

        return response()->json($processedThread);
    }

    public function addToCustomer($leadId) {

    }

    public function deleteColdLead(Request $request) {
        $leadId = $request->get('lead_id');
        $dl = ColdLeads::findOrFail($leadId);
//        try {
//            $dl->threads->conversation()->delete();
//        } catch (\Exception $exception) {
//        }
//        try {
//            $dl->threads()->deleadslete();
//        } catch (\Exception $exception) {
//        }

        $dl->forceDelete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function showImportedColdLeads(Request $request) {
        $leads = ColdLeads::where('is_imported', 1);

        if ($request->get('address') !== '') {
            $leads = $leads->where(function($query) use($request) {
                $query->where('address', 'LIKE', $request->get('address'))
                    ->orWhere('name', 'LIKE', $request->get('address'))
                    ->orWhere('username', 'LIKE', $request->get('address'))
                    ->orWhere('platform_id', 'LIKE', $request->get('address'));
            });
        }

        $query = $request->get('address');

        $leads = $leads->paginate(200);

        return view('leads.imported_index', compact('leads', 'query'));
    }

    public function addLeadToCustomer(Request $request) {
        $this->validate($request, [
            'cold_lead_id' => 'required'
        ]);

        $lead = ColdLeads::find($request->get('cold_lead_id'));

        if ($lead) {
            $customer = new Customer();
            $customer->name = $lead->name;
            $customer->phone = $lead->platform_id;
            $customer->whatsapp_number = $lead->platform_id;
            $customer->city = $lead->address;
            $customer->country = 'IN';
            $customer->save();

            $lead->customer_id = $customer->id;
            $lead->save();
        }


        return response()->json([
            'status' => 'success'
        ]);

    }

    public function home(Request $request)
    {
            if (strlen($request->get('query')) >= 4) {
            $query = $request->get('query');
            $leads = ColdLeads::where('status', '>', 0)
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                    $q->where('username', 'LIKE', "%$query%");
                });
        } else {
            $leads = ColdLeads::where('status', '>', 0);
        }

        if ($request->get('gender') == 'm' || $request->get('gender') == 'f' || $request->get('gender') == 'o') {
            $leads = $leads->where('gender', $request->get('gender'));
        }

        if ($request->get('acc') > 0) {
            $leads = $leads->where('account_id', $request->get('acc'));
        }

        $leads = $leads->orderBy('updated_at', 'DESC')->with('account')->paginate($request->get('pagination'));

        $accounts = Account::where('platform', 'instagram')->where('broadcast', 1)->get();

        return view('instagram.direct-message.index',compact('leads','accounts'));
    
    }

}
