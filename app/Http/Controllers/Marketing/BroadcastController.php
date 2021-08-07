<?php

namespace App\Http\Controllers\Marketing;

use App\Customer;
use App\Helpers\InstantMessagingHelper;
use App\Http\Controllers\Controller;
use App\CustomerMarketingPlatform;
use Illuminate\Http\Request;
use App\Setting;
use Auth;
use Validator;
use Response;
use App\Order;
use App\ApiKey;
use App\ErpLeads;
use App\ImQueue;
use App\Marketing\WhatsappConfig;
use App\ColdLeadBroadcasts;
use App\CompetitorPage;

class BroadcastController extends Controller
{
    /**
     * Getting BroadCast Page with Ajax Search.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Customer  id , term , date , number , broadcast , manual , remark , name
     * @return \Illuminate\Http\View And Ajax
     */
    public function index(Request $request)
    {




        if ($request->term || $request->total || $request->date || $request->number || $request->broadcast || $request->manual || $request->remark || $request->name || $request->customrange || $request->dnd || $request->whats_number || $request->lastBroadcast || $request->notDelivered || $request->broadcastSend || $request->manualApproval) {
            $terms =  $request->terms;
            $total = $request->total;
            
            $query = Customer::query();

            //Total Result 
            if (request('total') != null){
                
                //search with date
                if(request('total') == 1 && request('customrange') != null){
                    $range = explode(' - ', request('customrange'));
                    if($range[0] == end($range)){
                        $query->whereHas('customerMarketingPlatformActive', function ($qu) use ($range) {
                            $qu->whereDate('created_at', end($range))->where('active', 1);
                        })->where('do_not_disturb',0);
                    }else{
                        $query->whereHas('customerMarketingPlatformActive', function ($qu) use ($range) {
                        $qu->whereBetween('created_at', [$range[0], end($range)])->where('active', 1);
                        })->where('do_not_disturb',0);
                    }
                }
                
                elseif(request('total') == 1){
                    $query->whereHas('customerMarketingPlatformActive', function ($qu) use ($request) {
                        $qu->where('active', 1);
                    })->where('do_not_disturb',0);
                }
                
                 if(request('total') == 2 && request('customrange') != null){
                    $range = explode(' - ', request('customrange'));
                    if($range[0] == end($range)){
                         $query->doesntHave('customerMarketingPlatformActive')->whereDate('created_at',end($range))->where('do_not_disturb',0);
                    }else{
                         $query->doesntHave('customerMarketingPlatformActive')->whereBetween('created_at', [$range[0], end($range)])->where('do_not_disturb',0);
                    }
                }

                if(request('total') == 2){
                    $query->doesntHave('customerMarketingPlatformActive')->where('do_not_disturb',0);
                }
               
                if(request('total') == 3 && request('customrange') != null){
                    $range = explode(' - ', request('customrange'));
                    if($range[0] == end($range)){
                        $query->where('do_not_disturb', 1)->whereDate('updated_at',end($range));
                    }else{
                        $query->where('do_not_disturb', 1)->whereBetween('updated_at', [$range[0], end($range)]);
                    }
                }

                elseif(request('total') == 3){
                    $query->where('do_not_disturb', 1);
                }

                if(request('total') == 4 && request('customrange') != null){
                    $range = explode(' - ', request('customrange'));
                    if($range[0] == end($range)){
                        
                        $query->whereHas('leads', function ($qu) use ($range) {
                            $qu->whereDate('created_at', end($range));
                        });

                    }else{
                        $query->whereHas('leads', function ($qu) use ($range) {
                            $qu->whereBetween('created_at', [$range[0], end($range)]);
                        });
                    }
                }

                elseif(request('total') == 4){
                    $query->whereHas('leads');
                }

                if(request('total') == 5 && request('customrange') != null){
                    $range = explode(' - ', request('customrange'));
                    if($range[0] == end($range)){
                        
                        $query->whereHas('orders', function ($qu) use ($range) {
                            $qu->whereDate('created_at', end($range));
                        });

                    }else{
                        $query->whereHas('orders', function ($qu) use ($range) {
                            $qu->whereBetween('created_at', [$range[0], end($range)]);
                        });
                    }
                }

                elseif(request('total') == 5){
                    $query->whereHas('orders');
                }

                if(request('total') == 6 && request('customrange') != null){
                    $range = explode(' - ', request('customrange'));
                    if($range[0] == end($range)){
                        
                        $query->whereHas('customerMarketingPlatformActive', function ($qu) use ($range) {
                            $qu->where('active', 1);
                        })->where('broadcast_number',null)->whereDate('created_at', end($range));;

                    }else{
                        $query->whereHas('customerMarketingPlatformActive', function ($qu) use ($range) {
                           $qu->where('active', 1);
                        })->where('broadcast_number',null)->whereBetween('created_at', [$range[0], end($range)]);
                    }
                }

                elseif(request('total') == 6){
                    $query->whereHas('customerMarketingPlatformActive', function ($qu) use ($request) {
                        $qu->where('active', 1);
                    })->where('broadcast_number',null)->where('do_not_disturb',0);
                }

                if(request('total') == 7 && request('customrange') != null){
                    $range = explode(' - ', request('customrange'));
                    if($range[0] == end($range)){
                        $query->whereHas('notDelieveredImQueueMessage', function ($qu) use ($range) {
                            $qu->whereDate('send_after', end($range));
                        });
                    }else{
                        $query->whereHas('notDelieveredImQueueMessage', function ($qu) use ($range) {
                           $qu->whereBetween('send_after', [$range[0], end($range)]);
                        });
                    }
                }

                elseif(request('total') == 7){
                    $query->whereHas('notDelieveredImQueueMessage');
                }

            }     

            // global search term
            if (request('term') != null) {
                $query->where('whatsapp_number', 'LIKE', "%{$request->term}%")
                    ->orWhere('name', 'LIKE', "%{$request->term}%")
                    ->orWhereHas('broadcastLatest', function ($qu) use ($request) {
                        $qu->where('group_id', 'LIKE', "%{$request->term}%");
                    })
                    ->orWhere('broadcast_number','LIKE', "%{$request->term}%");
                    
            }

            //if number is not null
            if (request('number') != null) {
                $query->where('phone', 'LIKE', '%' . request('number') . '%');
            }

             //if number is not null
            if (request('whats_number') != null) {
                $query->where('broadcast_number', 'LIKE', '%' . request('whats_number') . '%');
            }
            
            //if number is not null
            if (request('name') != null) {
                $query->where('name', 'LIKE', '%' . request('name') . '%');
            }

            //getting customer with DND
            if (request('dnd') != null) {
                $query->where('do_not_disturb', request('dnd'));
            }

            if (request('broadcast') != null) {
                $query->whereHas('broadcastLatest', function ($qu) use ($request) {
                    $qu->where('group_id', 'LIKE', '%' . request('broadcast') . '%');
                });
            }

            if (request('manual') != null) {
                $query->whereHas('customerMarketingPlatformActive', function ($qu) use ($request) {
                   $qu->where('active', request('manual'));
                });
            }

            if (request('remark') != null) {
                $query->whereHas('customerMarketingPlatformRemark', function ($qu) use ($request) {
                    $qu->where('remark', 'LIKE', '%' . request('remark') . '%');
                });
            }

            

            
            $customers = $query->select('id', 'name','phone','broadcast_number','do_not_disturb','is_blocked' ,'whatsapp_number')->paginate(Setting::get('pagination'))->appends(request()->except(['page']));
           
        } else {
            //Order List
            $orders = Order::select('customer_id')->whereNotNull('customer_id')->get();
            foreach ($orders as $order) {
                $orderArray[] = $order->customer_id;
            }
            $orderList = implode(",", $orderArray);

            //Leads List
            $leads = ErpLeads::select('customer_id')->whereNotNull('customer_id')->get();
/*            dd($leads);*/
            foreach ($leads as $lead) {
                $leadArray[] = $lead->customer_id;
            }
            $leadList = implode(",", $leadArray);
            
            $marketings = CustomerMarketingPlatform::select('customer_id')->whereNull('remark')->get();
/*            dd($marketings);*/
             foreach ($marketings as $marketing) {
                $marketingArray[] = $marketing->customer_id;
            }
            $marketingList = implode(",", $marketingArray);

            $dndNumbers = Customer::select('id')->where('do_not_disturb',1)->get();
/*            dd($dndNumbers);*/
            foreach ($dndNumbers as $dndNumber) {
                $dndCustomerNumberArray[] = $dndNumber->id;
            }
            $dndCustomerNumbersArray = implode(",", $dndCustomerNumberArray);
            
            $customers = Customer::select('id', 'name','phone','broadcast_number','do_not_disturb','is_blocked' ,'whatsapp_number',\DB::raw('IF(id IN ('.$orderList.') , 1 , 0) AS priority_order , IF(id IN ('.$orderList.') , 1 , 0) AS priority_lead , IF(id IN ('.$marketingList.') , 1 , 0) AS priority_marketing , IF(id IN ('.$orderList.') , 1 , 0) AS priority_lead , IF(id IN ('.$dndCustomerNumbersArray.') , 1 , 0) AS priority_dnd '))->orderby('priority_order','desc')->orderby('priority_lead','desc')->orderby('priority_marketing','asc')->orderby('priority_dnd','asc')->paginate(Setting::get('pagination'));

        }

        //Filter For WhatsApp Number
        if($request->phone_term || $request->phone_date || $request->phone_customrange){

            $query = WhatsappConfig::query();

            //global search term
            if (request('phone_term') != null) {
                $query->where('number', 'LIKE', "%{$request->phone_term}%");
            }

            if (request('phone_date') != null) {
                $date = request('phone_date');
            }else{
                $date = '';
            }

            if (request('phone_customrange') != null) {
                $range = explode(' - ', request('phone_customrange'));
                $startDate = $range[0];
                $endDate = end($range);
            }else{
                $startDate = '';
                $endDate = '';
            }

            $numbers = $query->get();

            if ($request->ajax()) {
            return response()->json([
                'tbody' => view('marketing.broadcasts.partials.phone-data', compact('numbers','date','startDate','endDate'))->render(),
                'links' => (string)$customers->render()
            ], 200);
            }

        }else{
            $numbers = WhatsappConfig::get();
            $date = '';
            $startDate = '';
            $endDate = '';
        }
        
        if(isset($request->total)){
            $total = $request->total;
            $customrange = $request->customrange;
            
        }else{
            $total = '';
            $customrange = '';
        }
        
        $customerBroadcastSend = Customer::whereNotNull('broadcast_number')->count();
        $customerBroadcastPending = Customer::whereNull('broadcast_number')->count();
        $countDNDCustomers = Customer::where('do_not_disturb','1')->count();
        $totalCustomers = Customer::count();

        $apiKeys = ApiKey::all();
        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('marketing.broadcasts.partials.data', compact('customers', 'apiKeys', 'numbers','customerBroadcastSend','customerBroadcastPending','countDNDCustomers','totalCustomers','total','customrange'))->render(),
                'links' => (string)$customers->render(),
                'count' => $customers->total(),
            ], 200);
        }

        return view('marketing.broadcasts.index', [
            'customers' => $customers,
            'apiKeys' => $apiKeys,
            'numbers' => $numbers,
            'customerBroadcastSend' => $customerBroadcastSend,
            'customerBroadcastPending' => $customerBroadcastPending,
            'countDNDCustomers' => $countDNDCustomers,
            'totalCustomers' => $totalCustomers,
            'date' => $date,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'total' => $total,
            'customrange' => $customrange,
        ]);

    }

    /**
     * Update Customer TO DND .
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Customer  id is $request->id
     * @return \Illuminate\Http\Response
     */
    public function addToDND(Request $request)
    {

        $id = $request->id;
        $customer = Customer::findOrFail($id);
        $customer->do_not_disturb = $request->type;
        $customer->update();
        \Log::channel('customerDnd')->debug("(Customer ID " . $customer->id . " line " . $customer->name. " " . $customer->number . ": Added To DND");
        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * Getting Remark From CustomerMarketingPlatform table.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\CustomerMarketingPlatform  customer_id = $request->id
     * @return \Illuminate\Http\Response
     */

    public function getBroadCastRemark(Request $request)
    {
        $id = $request->input('id');

        $remark = CustomerMarketingPlatform::where('customer_id', $id)->whereNotNull('remark')->get();

        return response()->json($remark, 200);
    }

    /**
     * Adding Remark to CustomerMarketingPlatform table.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\CustomerMarketingPlatform  id and remark
     * @return \Illuminate\Http\Response
     */
    public function addRemark(Request $request)
    {

        $remark = $request->input('remark');
        $id = $request->input('id');
        CustomerMarketingPlatform::create([
            'customer_id' => $id,
            'remark' => $remark,
            'marketing_platform_id' => '1',
            'user_name' => Auth::user()->name,
        ]);
        return response()->json(['remark' => $remark], 200);

    }

    /**
     * Adding Customer to CustomerMarketingPlatform table.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Customer $request ->id
     * @return \Illuminate\Http\Response
     */
    public function addManual(Request $request)
    {
        // Set customer ID and try to find customer
        $customerId = $request->id;
        $customer = Customer::findOrFail($customerId);

        // Do we have a customer?
        if ($customer != null && $request->type == 1) {
            // Set welcome message
            $welcomeMessage = InstantMessagingHelper::replaceTags($customer, Setting::get('welcome_message'));

            // Set empty number with count
            $numberWithCount = [];

            // Get Whatsapp number with lowest customer count
            $whatsappConfigs = WhatsappConfig::where('is_customer_support', 0)->where('status',1)->get();
            
            // Check if we have results
            if ($whatsappConfigs != null && count($whatsappConfigs) > 0) {
                // Set temp minimum value
                $tmpMinValue = 1000000;

                // Set number with least customers
                $numberWithLeastCustomers = null;

                // Loop over numbers
                foreach ($whatsappConfigs as $whatsappConfig) {
                    // Check if number is already set
                    if ($customer->broadcast_number == $whatsappConfig->number) {
                        $numberWithLeastCustomers = $customer->broadcast_number;
                        break;
                    }

                    // Check for lower count
                    if ($whatsappConfig->customer->count() < $tmpMinValue) {
                        // Set new tmp minimum value
                        $tmpMinValue = $whatsappConfig->customer->count();

                        // Set new number with least customers
                        $numberWithLeastCustomers = $whatsappConfig->number;
                    }
                }

                // Update customer with new number
                $customer->broadcast_number = $numberWithLeastCustomers;
                $customer->update();

                // Send the welcome message
                InstantMessagingHelper::scheduleMessage($customer->phone, $numberWithLeastCustomers, $welcomeMessage);
            }
        }

        //Add Customer to Customer Marketing Table
        $remark = CustomerMarketingPlatform::where('customer_id', $customerId)->whereNull('remark')->first();
        if ($remark == null) {
            CustomerMarketingPlatform::create([
                'customer_id' => $customerId,
                'marketing_platform_id' => '1', // WhatsApp
                'active' => 1,
                'user_name' => Auth::user()->name,
            ]);

        } else {
            $customer->broadcast_number = '';
            $customer->save();
            $remark->active = 0;
            $remark->update();
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * Update the customer number.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Customer $request ->id
     * @return \Illuminate\Http\Response
     */
    public function updateWhatsAppNumber(Request $request)
    {
        //Updating Customer WhatsAppNumber
        $id = $request->id;
        $number = $request->number;

        $customer = Customer::findOrFail($id);
        $customer->broadcast_number = $number;
        $customer->update();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function broadCastSendMessage(Request $request)
    {

        $messages = ImQueue::where('number_from',$request->number)->whereDate('created_at',$request->date)->get();
        //dd($messages);
        foreach ($messages as $message) {
            $messageArray[] = '<tr><td>'.$message->id.'</td><td>'.$message->text.'</td><td>'.$message->number_to.'</td><td>'.$message->created_at.'</td><td>'.$message->send_after.'</td></tr>';
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $messageArray,
        ]);
    }

    public function getCustomerBroadcastList(Request $request)
    {
        $id = $request->id;
        $customer = Customer::findOrFail($id);
        $broadcasts = $customer->broadcastAll;

        foreach ($broadcasts as $broadcast) {
            $broadcastArray[] = $broadcast->group_id .' '.$broadcast->created_at->format('d-m-Y').'<br>';
        }

         return response()->json([
            'status' => 'success',
            'data' => $broadcastArray,
        ]);
    }


    public function saveGlobalValues(Request $request)
    {

        $numbers = WhatsappConfig::where('is_customer_support',0)->get();
        foreach ($numbers as $number) {
         if($request->frequency != null){
           $number->frequency = $request->frequency;
           $number->update();
        }

        if($request->send_start != null){
           $number->send_start = $request->send_start;
           $number->update();
        }

        if($request->send_end != null){
           $number->send_end = $request->send_end;
           $number->update();
        }
        }

        return redirect()->back()->with('message', 'Values Updated Globally');
        
    }

    public function getCustomerCountEnable()
    {
         $query = CustomerMarketingPlatform::query();

        if (request('custom_date') != null) {
            $range = explode(' - ', request('custom_date'));
            if($range[0] == end($range)){
                $query->whereDate('created_at', $range[0])->where('active', 1);
            }else{
                $query->whereBetween('created_at', [$range[0], end($range)])->where('active', 1);
            }
            
        }
        
        $count = $query->whereNull('remark')->count();

        return response()->json([
            'status' => 'success',
            'data' => $count,
        ]);
    }

    public function switchBroadcast(Request $request)
    {
        $id = $request->id;
        if($id == $request->newId){
            return redirect()->back()->with('message', 'Both Number Are Same');
        }
        $whatsAppNew = WhatsappConfig::find($request->newId);
        $whatsAppOld = WhatsappConfig::find($id);
        
        $messages = ImQueue::where('number_from',$whatsAppOld->number)->get();
        foreach ($messages as $message) {
            $message->number_from = $whatsAppNew->number; 
            $message->update();  
        }

        return redirect()->back()->with('message', 'Broadcast Switch To Another Number');
    }


    public function instagram(Request $request)
    {
        if ($request->get('date')) {
            $leads = ColdLeadBroadcasts::whereDate('created_at',$request->get('date'));
        } else {
            $leads = new ColdLeadBroadcasts;
        }

        $leads = $leads->orderBy('updated_at', 'DESC')->paginate($request->get('pagination'));
        $competitors = CompetitorPage::select('id','name')->where('platform', 'instagram')->get();

        return view('marketing.broadcasts.instagram.index',compact('leads','competitors'));
    }

    
    
    
}