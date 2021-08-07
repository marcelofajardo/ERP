<?php

namespace App\Http\Controllers;

use App\BulkCustomerRepliesKeyword;
use App\Customer;
use App\CustomerBulkMessageDND;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers;

class BulkCustomerRepliesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        set_time_limit(0);
        $keywords = BulkCustomerRepliesKeyword::where('is_manual', 1)->get();
        $autoKeywords = BulkCustomerRepliesKeyword::where('count', '>', 10)
            ->whereNotIn('value', [
                'test', 'have', 'sent', 'the', 'please', 'pls', 'through', 'using', 'solo', 'that',
                'comes', 'message', 'sending', 'Yogesh', 'Greetings', 'this', 'numbers', 'maam', 'from',
                'changed', 'them', 'with' , '0008000401700', 'WhatsApp', 'send', 'Auto', 'based', 'suggestion',
                'Will', 'your', 'number', 'number,', 'messages', 'also', 'meanwhile'
            ])
            ->take(50)
            ->orderBy('count', 'DESC')
            ->get();
        $searchedKeyword = null;

        if ($request->get('keyword_filter')) {
            $keyword = $request->get('keyword_filter');

//            $searchedKeyword = BulkCustomerRepliesKeyword::with(['customers' => function($q)use($request){
//                $q->leftJoin(\DB::raw('(SELECT MAX(chat_messages.id) as  max_id,whatsapp_number, customer_id ,message as matched_message  FROM `chat_messages` join customers as c on c.id = chat_messages.customer_id  GROUP BY customer_id ) m_max'), 'm_max.customer_id', '=', 'customers.id')
//                ->groupBy('customers.id')
//                ->orderBy('max_id','desc');
//
//                if($request->dnd_enabled !== 'all'){
//                    $q->doesntHave('dnd');
//                }else{
//                    $q->has('dnd');
//                }
//
//            }])->with('customers.dnd')
//            ->where('value', $keyword)
//            ->first();
//dd($searchedKeyword);
            $searchedKeyword = BulkCustomerRepliesKeyword::where('value', $keyword)->first();

            $customerids = Customer::whereHas('bulkMessagesKeywords', function($q) use($keyword){
                $q->where('value', $keyword);
            });

            if($request->dnd_enabled === '0'){

                $customerids = $customerids->whereHas('dnd');
            }else if ($request->dnd_enabled === '1'){
                $customerids = $customerids->whereDoesntHave('dnd');
            }else{

            }
            $customerids = $customerids->pluck('id')->toArray();

            $customers = Customer::leftJoin(\DB::raw('(SELECT MAX(chat_messages.id) as  max_id,whatsapp_number, customer_id ,message as matched_message  FROM `chat_messages` join customers as c on c.id = chat_messages.customer_id  GROUP BY customer_id ) m_max'), 'm_max.customer_id', '=', 'customers.id')
                ->groupBy('customers.id')
                ->whereIn('id', $customerids);



            $customers = $customers->orderBy('max_id','desc')->paginate(20);



//dd($customers);
//            $searchedKeyword = BulkCustomerRepliesKeyword::with(['customers' => function($q)use($request){
//
//                $q->leftJoin(\DB::raw('(SELECT MAX(chat_messages.id) as  max_id,whatsapp_number, customer_id ,message as matched_message  FROM `chat_messages` join customers as c on c.id = chat_messages.customer_id  GROUP BY customer_id ) m_max'), 'm_max.customer_id', '=', 'customers.id')
//                    ->groupBy('customers.id')
//                    ->orderBy('max_id','desc');
//                if($request->dnd_enabled !== 'all'){
//
//                    $q->whereHas('dnd');
//                }else{
//                    $q->whereDoesntHave('dnd');
//                }
//
//
//            }, 'customers.dnd'])
//                ->where('value', $keyword)
//                ->first();

//            dd($searchedKeyword);

        }

        $groups           = \App\QuickSellGroup::select('id', 'name', 'group')->orderby('id', 'DESC')->get();
        $pdfList = [];
        $nextActionArr = DB::table('customer_next_actions')->pluck('name', 'id');
        $reply_categories = \App\ReplyCategory::with('approval_leads')->orderby('name')->get();
        $settingShortCuts = [
            "image_shortcut"      => \App\Setting::get('image_shortcut'),
            "price_shortcut"      => \App\Setting::get('price_shortcut'),
            "call_shortcut"       => \App\Setting::get('call_shortcut'),
            "screenshot_shortcut" => \App\Setting::get('screenshot_shortcut'),
            "details_shortcut"    => \App\Setting::get('details_shortcut'),
            "purchase_shortcut"   => \App\Setting::get('purchase_shortcut'),
        ];
        $users_array      = Helpers::getUserArray(\App\User::all());

        $whatsappNos = getInstanceNo();
        $chatbotKeywords = \App\ChatbotKeyword::all();
        // dd($chatbotKeywords);
// dd($searchedKeyword);
        return view('bulk-customer-replies.index', compact('customers','keywords','autoKeywords', 'searchedKeyword', 'nextActionArr','groups','pdfList','reply_categories','settingShortCuts','users_array','whatsappNos','chatbotKeywords'));
    }

    public function updateWhatsappNo(Request $request)
    {
        $no = $request->get("whatsapp_no");
        $customers = explode(",",$request->get("customers",""));
        $total = 0;
        if(!empty($no) && is_array(array_filter($customers))) {
            $lCustomer  = array_filter($customers);
            $total      = count($lCustomer);
            $customers  = \App\Customer::whereIn("id",$lCustomer)->update(["whatsapp_number" => $no]);
        }
        return response()->json(["code" => 200, "total" => $total]);

    }

    public function storeKeyword(Request $request) {
        $this->validate($request, [
            'keyword' => 'required'
        ]);

        $type = 'keyword';
        $numOfSpaces = count(explode(' ', $request->get('keyword')));
        if ($numOfSpaces > 1 && $numOfSpaces < 4) {
            $type = 'phrase';
        } else if ($numOfSpaces >= 4) {
            $type = 'sentence';
        }

        $keyword = new BulkCustomerRepliesKeyword();
        $keyword->value = $request->get('keyword');
        $keyword->text_type = $type;
        $keyword->is_manual = 1;
        $keyword->count = 0;
        $keyword->save();

        return redirect()->back()->with('message', title_case($type) . ' added successfully!');
    }

    public function sendMessagesByKeyword(Request $request) {
        $customer_id_array = $request->get('customers');
        // $this->validate($request, [
        //     'message' => 'required',
        //     'customers' => 'required'
        // ]);

        foreach ($request->get('customers') as $customer) {
            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add([
                'message' => $request->get('message_bulk'),
                'customer_id' => $customer,
                'status' => 1
            ]);

            app('App\Http\Controllers\WhatsAppController')->sendMessage($myRequest, 'customer');

            // DB::table('bulk_customer_replies_keyword_customer')->where('customer_id', $customer)->where("keyword_id",$request->get("keyword_id",0))->delete();
        }
            return response()->json(['message' => 'Messages sent successfully!','c_id' => $customer_id_array]);
        // return redirect()->back()->with('message', 'Messages sent successfully!');

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function addToDND(Request $request){

        $exist = CustomerBulkMessageDND::where('customer_id', $request->customer_id)->where('filter', $request->filter['keyword_filter'])->first(); 

        if($exist == null){
            CustomerBulkMessageDND::create([
                'customer_id' => $request->customer_id,
                'filter' => $request->filter ? $request->filter['keyword_filter'] : null
            ]);
        }
        

        return response()->json(true);

    }

    public function removeFromDND(Request $request){

        $dnd = CustomerBulkMessageDND::where('customer_id', $request->customer_id)->where('filter', $request->filter['keyword_filter'])->delete();

        return response()->json(true);

    }
}
