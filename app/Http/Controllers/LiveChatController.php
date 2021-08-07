<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\Customer;
use App\CustomerLiveChat;
use App\Helpers\TranslationHelper;
use App\Library\Watson\Model as WatsonManager;
use App\LivechatincSetting;
use App\LiveChatUser;
use App\User;
use Illuminate\Http\Request;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Media;
use Mail;
use DB;
use App\Mails\Manual\PurchaseEmail;
use App\Tickets;
use App\TicketStatuses;
use App\Email;
use Google\Cloud\Translate\TranslateClient;

class LiveChatController extends Controller
{
    //Webhook
    public function incoming(Request $request)
    {
        \Log::channel('chatapi')->info('-- incoming >>');

        \Log::channel('chatapi')->info($request->getContent());
        $receivedJson = json_decode($request->getContent());

        if (isset($receivedJson->event_type)) {
            \Log::channel('chatapi')->info('--1111 >>');
            //When customer Starts chat
            if ($receivedJson->event_type == 'chat_started') {

                \Log::channel('chatapi')->info('-- chat_started >>');
                ///Getting the chat
                $chat = $receivedJson->chat;

                //Getting Agent
                $agent = $chat->agents;
                // name": "SoloLuxury"
                // +"login": "yogeshmordani@icloud.com"
                $chat_survey = $receivedJson->pre_chat_survey;
                $detials     = array();
                foreach ($chat_survey as $survey) {
                    $label = strtolower($survey->label);

                    if (strpos($label, 'name') !== false) {
                        array_push($detials, $survey->answer);
                    }
                    if (strpos($label, 'e-mail') !== false) {
                        array_push($detials, $survey->answer);
                    }
                    if (strpos($label, 'phone') !== false) {
                        array_push($detials, $survey->answer);
                    }
                }

                $name  = $detials[0];
                $email = $detials[1];
                $phone = $detials[2];
                //Check if customer exist

                $customer = Customer::where('email', $email)->first();

                // if($customer == '' && $customer == null && $phone != ''){
                //     //$customer = Customer::where('phone',$phone)->first();
                // }

                //Save Customer
                if ($customer == null && $customer == '') {
                    $customer        = new Customer;
                    $customer->name  = $name;
                    $customer->email = $email;
                    $customer->phone = null;
                    $customer->language = 'en';
                    $customer->save();
                }

            }
        }

        if (isset($receivedJson->action)) {
            \Log::channel('chatapi')->info('--2222 >>');
            //Incomg Event
            if ($receivedJson->action == 'incoming_event') {

                \Log::channel('chatapi')->info('-- incoming_event >>');

                //Chat Details
                $chatDetails = $receivedJson->payload;
                //Chat Details
                $chatId = $chatDetails->chat_id;

                //Check if customer which has this id
                $customerLiveChat = CustomerLiveChat::where('thread', $chatId)->first();

                //update to not seen
                if ($customerLiveChat != null) {
                    $customerLiveChat->seen   = 0;
                    $customerLiveChat->status = 1;
                    $customerLiveChat->update();
                }
                if ($chatDetails->event->type == 'message') {

                    \Log::channel('chatapi')->info('-- message >>');

                    $message   = $chatDetails->event->text;
                    $author_id = $chatDetails->event->author_id;

                    // Finding Agent
                    $agent = User::where('email', $author_id)->first();

                    if ($agent != '' && $agent != null) {
                        $userID = $agent->id;
                    } else {
                        $userID = null;
                    }

                    $customerDetails = Customer::find($customerLiveChat->customer_id);
                    $language        = $customerDetails->language;
                    if($language == null){
                        $translate = new TranslateClient([
                            // 'key' => getenv('GOOGLE_TRANSLATE_API_KEY')
                            'key' => config('env.GOOGLE_TRANSLATE_API_KEY')
                        ]);
                        $result = $translate->detectLanguage($message);
                        $customerDetails->language = $result['languageCode'];
                        $language = $result['languageCode'];
                    }
                
                    $result  = TranslationHelper::translate($language, 'en', $message);
                    // $message = $result . ' -- ' . $message;
                    $message = $message;

                    if ($author_id == 'buying@amourint.com') {
                        $messageStatus = 2;
                    } else {
                        $messageStatus = 9;
                    }

                    $message_application_id = 2;

                    $params = [
                        'unique_id'              => $chatDetails->chat_id,
                        'message'                => $message,
                        'customer_id'            => $customerLiveChat->customer_id,
                        'approved'               => 1,
                        'status'                 => $messageStatus,
                        'is_delivered'           => 1,
                        'user_id'                => $userID,
                        'message_application_id' => $message_application_id,
                    ];

                    // Create chat message
                    $chatMessage = ChatMessage::create($params);

                    //STRAT - Purpose : Add record in chatbotreplay - DEVTASK-18280
                    if($messageStatus != 2)
                    {
                        \App\ChatbotReply::create([
                            'question'        => $message,
                            'reply' => json_encode([
                                'context' => 'chatbot',
                                'issue_id' => $chatDetails->chat_id,
                                'from' => "chatbot"
                            ]),
                            'replied_chat_id' => $chatMessage->id,
                            'reply_from'      => 'chatbot',
                        ]);
                    }
                    //END - DEVTASK-18280

                    // if customer found then send reply for it
                    if (!empty($customerDetails) && $message != '') {
                        WatsonManager::sendMessage($customerDetails, $message, '', $message_application_id);
                    }

                }

                if ($chatDetails->event->type == 'file') {

                    \Log::channel('chatapi')->info('-- file >>');

                    $author_id = $chatDetails->event->author_id;

                    // Finding Agent
                    $agent = User::where('email', $author_id)->first();

                    if ($agent != null) {
                        $userID = $agent->id;
                    } else {
                        $userID = null;
                    }

                    if ($author_id == 'buying@amourint.com') {
                        $messageStatus = 2;
                    } else {
                        $messageStatus = 9;
                    }

                    //creating message
                    $params = [
                        'unique_id'              => $chatDetails->chat_id,
                        'customer_id'            => $customerLiveChat->customer_id,
                        'approved'               => 1,
                        'status'                 => $messageStatus,
                        'is_delivered'           => 1,
                        'user_id'                => $userID,
                        'message_application_id' => 2,
                    ];

                    $from = 'livechat';
                    // Create chat message
                    $chatMessage = ChatMessage::create($params);

                    $numberPath = date('d');
                    $url        = $chatDetails->event->url;
                    try {
                        $jpg      = \Image::make($url)->encode('jpg');
                        $filename = $chatDetails->event->name;
                        $media    = MediaUploader::fromString($jpg)->toDirectory('/chat-messages/' . $numberPath)->useFilename($filename)->upload();
                        $chatMessage->attachMedia($media, config('constants.media_tags'));
                    } catch (\Exception $e) {
                        $file = @file_get_contents($url);
                        if (!empty($file)) {
                            $filename = $chatDetails->event->name;
                            $media    = MediaUploader::fromString($file)->toDirectory('/chat-messages/' . $numberPath)->useFilename($filename)->upload();
                            $chatMessage->attachMedia($media, config('constants.media_tags'));
                        }
                    }

                }

                if ($chatDetails->event->type == 'system_message') {

                    $customerLiveChat = CustomerLiveChat::where('thread', $chatId)->first();
                    if ($customerLiveChat != null) {
                        $customerLiveChat->status = 0;
                        $customerLiveChat->seen   = 1;
                        $customerLiveChat->update();
                    }
                }

                // Add to chat_messages if we have a customer
            }

            if ($receivedJson->action == 'incoming_chat') {

                \Log::channel('chatapi')->info('-- incoming_chat >>');

                $chat   = $receivedJson->payload->chat;
                $chatId = $chat->id;

                //Getting user
                $userEmail = $chat->users[0]->email;
                $text = $chat->thread->events[1]->text;
                $userName  = $chat->users[0]->name;
                /*$translate = new TranslateClient([
                    'key' => getenv('GOOGLE_TRANSLATE_API_KEY')
                ]);*/
                //$result = $translate->detectLanguage($text);
                $customer_language = 'en';//$result['languageCode'];
                $websiteId = null;
                try {
                    $websiteURL = self::getDomain($chat->thread->properties->routing->start_url);
                    $website    = \App\StoreWebsite::where("website","like","%".$websiteURL."%")->first();
                    if($website) {
                       $websiteId = $website->id;
                    }
                } catch (\Exception $e) {
                    $websiteURL = '';
                }
                //dd($websiteURL);
                $customer = Customer::where('email', $userEmail);
                if($websiteId > 0) {
                    $customer = $customer->where("store_website_id",$websiteId);
                }
                $customer = $customer->first();

                if ($customer != null) {
                    //Find if its has ID
                    $chatID = CustomerLiveChat::where('customer_id', $customer->id)->where('thread', $chatId)->first();
                    if ($chatID == null) {

                        //check if only thread exist and make it null
                        $onlyThreadCheck = CustomerLiveChat::where('thread', $chatId)->first();
                        if ($onlyThreadCheck) {
                            $onlyThreadCheck->thread = null;
                            $chatID->seen            = 1;
                            $onlyThreadCheck->save();
                        }

                        $customerChatId              = new CustomerLiveChat;
                        $customerChatId->customer_id = $customer->id;
                        $customerChatId->thread      = $chatId;
                        $customerChatId->status      = 1;
                        $customerChatId->seen        = 0;
                        $customerChatId->website     = $websiteURL;
                        $customerChatId->save();
                    } else {
                        $chatID->customer_id = $customer->id;
                        $chatID->thread      = $chatId;
                        $chatID->status      = 1;
                        $chatID->website     = $websiteURL;
                        $chatID->seen        = 0;
                        $chatID->update();
                    }
                } else {

                    //check if only thread exist and make it null
                    $onlyThreadCheck = CustomerLiveChat::where('thread', $chatId)->first();
                    if ($onlyThreadCheck) {
                        $onlyThreadCheck->thread = null;
                        $chatID->seen            = 1;
                        $onlyThreadCheck->save();
                    }

                    $customer        = new Customer;
                    $customer->name  = $userName;
                    $customer->email = $userEmail;
                    $customer->language = $customer_language;
                    $customer->phone = null;
                    $customer->store_website_id = $websiteId;
                    $customer->language = 'en';
                    $customer->save();

                    //Save Customer with Chat ID
                    $customerChatId              = new CustomerLiveChat;
                    $customerChatId->customer_id = $customer->id;
                    $customerChatId->thread      = $chatId;
                    $customerChatId->status      = 1;
                    $customerChatId->seen        = 0;
                    $customerChatId->website     = $websiteURL;
                    $customerChatId->save();

                }
            }

            if ($receivedJson->action == 'thread_closed') {
                $chatId = $receivedJson->payload->chat_id;

                $customerLiveChat = CustomerLiveChat::where('thread', $chatId)->first();

                if ($customerLiveChat != null) {
                    $customerLiveChat->thread = null;
                    $customerLiveChat->status = 0;
                    $customerLiveChat->seen   = 1;
                    $customerLiveChat->update();
                }
            }
        }

    }

    public function sendMessage(Request $request)
    {

        $chatId          = $request->id;
        $message         = $request->message;
        $customerDetails = Customer::find($chatId);
        $language        = $customerDetails->language;
        if ($language != null) {
            $message = TranslationHelper::translate('en', $language, $message);
        }

        if(isset($request->messageId)){
                $chatMessages = ChatMessage::where('id', $request->messageId)->first();
                if ($chatMessages != null) {
                    $chatMessages->approved = 1;
                    $chatMessages->save();
                }
            }

        //Get Thread ID From Customer Live Chat
        $customer = CustomerLiveChat::where('customer_id', $chatId)->where("thread", "!=", "")->latest()->first();

        if ($customer != null) {
            $thread = $customer->thread;
        } else {
            return response()->json([
                'status' => 'errors',
            ]);
        }

        $post = array('chat_id' => $thread, 'event' => array('type' => 'message', 'text' => $message, 'recipients' => 'all'));
        $post = json_encode($post);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.livechatinc.com/v3.1/agent/action/send_event",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => "$post",
            CURLOPT_HTTPHEADER     => array(
                "Authorization: Bearer " . \Cache::get('key') . "",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);


        if ($err) {
            return response()->json([
                'status' => 'errors',
            ]);
        } else {
            $response = json_decode($response);
            
            if (isset($response->error)) {
                return response()->json([
                    'status' => 'errors ' . @$response->error->message,
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                ]);
            }
        }
    }

    public function setting()
    {
        $liveChatUsers = LiveChatUser::all();
        $setting       = LivechatincSetting::first();
        $users         = User::where('is_active', 1)->get();
        return view('livechat.setting', compact('users', 'liveChatUsers', 'setting'));
    }

    public function remove(Request $request)
    {

        $users = LiveChatUser::findorfail($request->id);
        $users->delete();

        return response()->json(['success' => 'success'], 200);
    }

    public function save(Request $request)
    {

        if ($request->username != '' || $request->key != '') {
            $checkIfExist = LivechatincSetting::all();
            if (count($checkIfExist) == 0) {
                $setting           = new LivechatincSetting;
                $setting->username = $request->username;
                $setting->key      = $request->key;
                $setting->save();
            } else {
                $setting           = LivechatincSetting::first();
                $setting->username = $request->username;
                $setting->key      = $request->key;
                $setting->update();
            }

        }

        if ($request->users != null && $request->users != '') {
            $users = $request->users;
            foreach ($users as $user) {

                $userCheck = LiveChatUser::where('user_id', $user)->first();
                if ($userCheck != '' && $userCheck != null) {
                    continue;
                }
                $userss          = new LiveChatUser();
                $userss->user_id = $user;
                $userss->save();

            }

        }

        return redirect()->back()->withSuccess(['msg', 'Saved']);
    }

    public function uploadFileToLiveChat($image)
    {
        //Save file to path
        //send path to Live chat
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.livechatinc.com/v3.2/agent/action/upload_file",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => array('file' => new CURLFILE('/Users/satyamtripathi/PhpstormProjects/untitled/images/1592232591.png')),
            CURLOPT_HTTPHEADER     => array(
                "Authorization: Bearer " . \Cache::get('key') . "",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function getorderdetails(Request $request)
    {
        $customer_id = $request->customer_id;

        $customer = $this->findCustomerById($customer_id);

        if ($customer){
                $orders = (new \App\Order())->newQuery()->with('customer')->leftJoin("store_website_orders as swo","swo.order_id","orders.id")
                    ->leftJoin("order_products as op","op.order_id","orders.id")
                    ->leftJoin("products as p","p.id","op.product_id")
                    ->leftJoin("brands as b","b.id","p.brand")->groupBy("orders.id")
                    ->where('customer_id',$customer->id)
                    ->select(["orders.*",\DB::raw("group_concat(b.name) as brand_name_list"),"swo.website_id"])->orderBy('created_at','desc')->get();
                list($leads_total,$leads) = $this->getLeadsInformation($customer->id);
                $exchanges_return = $customer->return_exchanges;
                if ($orders->count()){
                    foreach ($orders as &$value){
                        $value->storeWebsite = $value->storeWebsiteOrder ? ($value->storeWebsiteOrder->storeWebsite??'N/A') : 'N/A';
                        $value->order_date =  \Carbon\Carbon::parse($value->order_date)->format('d-m-y');
                        $totalBrands = explode(",",$value->brand_name_list);
                        $value->brand_name_list = (count($totalBrands) > 1) ? "Multi" : $value->brand_name_list;
                        $value->status = \App\Helpers\OrderHelper::getStatusNameById($value->order_status_id);
                    }
                }
            return [
                true,
                [
                    'orders_total'=>$orders->count(),
                    'leads_total'=>$leads_total,
                    'exchanges_return_total'=>$exchanges_return->count(),
                    'exchanges_return'=>$exchanges_return,
                    'leads'=>$leads,
                    'orders'=>$orders,
                    'customer'=>$customer
                ]
            ];
        }
        return array(FALSE, FALSE);
    }

    protected function findCustomerById($customer_id)
    {
        return Customer::where('id', '=', $customer_id)->first();
    }

    private function getLeadsInformation($id){
        $source = \App\ErpLeads::leftJoin('products', 'products.id', '=', 'erp_leads.product_id')
            ->leftJoin("customers as c","c.id","erp_leads.customer_id")
            ->leftJoin("erp_lead_status as els","els.id","erp_leads.lead_status_id")
            ->leftJoin("categories as cat","cat.id","erp_leads.category_id")
            ->leftJoin("brands as br","br.id","erp_leads.brand_id")
            ->where('erp_leads.customer_id',$id)
            ->orderBy("erp_leads.id","desc")
            ->select(["erp_leads.*","products.name as product_name","cat.title as cat_title","br.name as brand_name","els.name as status_name","c.name as customer_name","c.id as customer_id"]);


        $total = $source->count();
        $source = $source->get();

        foreach ($source as $key => $value) {
            $source[$key]->media_url = null;
            $media = $value->getMedia(config('constants.media_tags'))->first();
            if ($media) {
                $source[$key]->media_url = $media->getUrl();
            }

            if (empty($source[$key]->media_url) && $value->product_id) {
                $product = \App\Product::find($value->product_id);
                $media = $product->getMedia(config('constants.media_tags'))->first();
                if ($media) {
                    $source[$key]->media_url = $media->getUrl();
                }
            }
        }

        return [$total,$source];
    }


    public function getChats(Request $request)
    {
        $chatId = $request->id;
        $messagess = [];

        //put session
        session()->put('chat_customer_id', $chatId);

        //update chat has been seen
        $customer = CustomerLiveChat::where('customer_id', $chatId)->where("thread", "!=", "")->latest()->first();

        if ($customer != null) {
            $customer->seen = 1;
            $customer->update();
        }

        $threadId = $customer->thread;

        $messages = ChatMessage::where('customer_id', $chatId)->where('message_application_id', 2)->get();
        //getting customer name from chat
        $customer         = Customer::findorfail($chatId);
        $name             = $customer->name;
        $store_website_id = $customer->store_website_id;
        $customerInfo     = $this->getLiveChatIncCustomer($customer->email, 'raw');
        if (!$customerInfo) {
            $customerInfo = '';
        }

        $customerInital = substr($name, 0, 1);
        if (count($messages) != 0) {
            foreach ($messages as $message) {
                
                $agent       = Customer::where('id', $message->customer_id)->first();
                $agentInital = substr($agent->name, 0, 1);

                if ($message->status == 2) {
                    $type = 'end';
                } else {
                    $type = 'start';
                }

                if ($message->hasMedia(config('constants.media_tags'))) {
                    foreach ($message->getMedia(config('constants.media_tags')) as $image) {
                       

                        if(!$message->approved){
                            $vals =  '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer"><span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span><div class="d-flex mb-4"><div class="d-flex  mb-4"><input type="hidden" id="message-id" name="message-id" value="'.$chatId.'"><input type="hidden" id="message-value" name="message-value" value="'.$message->message.'"><button id="'.$message->id.'" class="btn btn-secondary quick_approve_add_live">Approve Message</button></div><div class="msg_cotainer_send"><img src="' . $image->getUrl() . '" class="rounded-circle-livechat user_img_msg"></div></div>';
                        }else{
                            $vals = '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer"><span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span></div><div class="msg_cotainer_send"><img src="' . $image->getUrl() . '" class="rounded-circle-livechat user_img_msg"></div></div>';
                        }
                        $messagess[] = $vals;

                    }
                }else
                {
                    if ($message->user_id != 0) {
                        // Finding Agent
                        $agent       = User::where('email', $message->user_id)->first();
                        $agentInital = substr($agent->name, 0, 1);
                        if(!$message->approved){
                            $vals = '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer"> ' . $message->message . '<br><span class="msg_time"> ' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . ' </span><div class="d-flex  mb-4"><input type="hidden" id="message-id" name="message-id" value="'.$chatId.'"><input type="hidden" id="message-value" name="message-value" value="'.$message->message.'"><button id="'.$message->id.'" class="btn btn-secondary quick_approve_add_live">Approve Message</button></div></div></div>';
                        }else{
                            $vals = '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer"> ' . $message->message . '<br><span class="msg_time"> ' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . ' </span> </div></div>';
                        }
                        $messagess[] = $vals;
                        //<div class="msg_cotainer_send"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div>
                    } else {
                        if(!$message->approved){
                            $vals = '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle user_inital">' . $customerInital . '</div><div class="msg_cotainer">' . $message->message . '<br><span class="msg_time"> ' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . ' </span><div class="d-flex  mb-4"><input type="hidden" id="message-id" name="message-id" value="'.$chatId.'"><input type="hidden" id="message-value" name="message-value" value="'.$message->message.'"><button id="'.$message->id.'" class="btn btn-secondary quick_approve_add_live">Approve Message</button></div></div></div>';
                        }else{
                            $vals = '<div  data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' sss mb-4"><div class="rounded-circle user_inital">' . $customerInital . '</div><div class="msg_cotainer">' . $message->message . '<br><span class="msg_time"> ' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . ' </span></div></div>';
                        //<div class="img_cont_msg"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div>
                        }
                        $messagess[] = $vals;
                    }
                }
            }

        }

        if (!isset($messagess)) {
            //$messagess[] = '<div  class="d-flex justify-content-end mb-4"><div class="rounded-circle user_inital">' . $customerInital . '</div><div class="msg_cotainer">New Customer For Chat<span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime(now()))->diffForHumans() . '</span></div></div>'; //<div class="msg_cotainer_send"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div>
        }

        $count = CustomerLiveChat::where('seen', 0)->count();

        return response()->json([
            'status' => 'success',
            'data'   => array('id' => $chatId, 'count' => $count, 'message' => $messagess, 'name' => $name, 'customerInfo' => $customerInfo, 'threadId' => $threadId, 'customerInital' => $customerInital, 'store_website_id' => $store_website_id),
        ]);
    }

    public function getChatMessagesWithoutRefresh()
    {
        $messagess = [];
        if (session()->has('chat_customer_id')) {
            $lastMessageId = request("last_msg_id");
            $chatId   = session()->get('chat_customer_id');
            $messages = ChatMessage::where('customer_id', $chatId)->where('message_application_id', 2);
            if($lastMessageId != null) {
                $messages = $messages->where('id',">", $lastMessageId);
            }
            $messages = $messages->get();

            
            //getting customer name from chat
            $customer       = Customer::findorfail($chatId);
            $name           = $customer->name;
            $customerInital = substr($name, 0, 1);
            if (count($messages) == 0) {
                if($lastMessageId == null) {
                    ////$messagess[] = '<div class="d-flex justify-content-start mb-4"><div class="rounded-circle user_inital">' . $customerInital . '</div><div class="msg_cotainer">New Chat From Customer<span class="msg_time"></span></div></div>';
                }
            } else {
                foreach ($messages as $message) {

                    if ($message->user_id != 0) {
                    // if ($message->customer_id != 0) {    
                        // Finding Agent
                        $agent       = Customer::where('id', $message->customer_id)->first();
                        // $agent       = User::where('email', $message->user_id)->first();
                        $agentInital = substr($agent->name, 0, 1);

                        if ($message->hasMedia(config('constants.media_tags'))) {
                            foreach ($message->getMedia(config('constants.media_tags')) as $image) {
                                if ($message->status == 2) {
                                    $type = 'end';
                                } else {
                                    $type = 'start';
                                }

                                if(!$message->approved){
                                    $vals =  '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer"><span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span><div class="d-flex mb-4"><div class="d-flex  mb-4"><input type="hidden" id="message-id" name="message-id" value="'.$chatId.'"><input type="hidden" id="message-value" name="message-value" value="'.$message->message.'"><button id="'.$message->id.'" class="btn btn-secondary quick_approve_add_live">Approve Message</button></div><div class="msg_cotainer_send"><img src="' . $image->getUrl() . '" class="rounded-circle-livechat user_img_msg"></div></div>';
                                }else{
                                    $vals = '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer"><span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span></div><div class="msg_cotainer_send"><img src="' . $image->getUrl() . '" class="rounded-circle-livechat user_img_msg"></div></div>';
                                }
                                $messagess[] = $vals;

                            }
                        } else {
                            if ($message->status == 2) {
                                $type = 'end';
                            } else {
                                $type = 'start';
                            }
                            if(!$message->approved){
                                $vals =  '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer">' . $message->message . '<span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span><div class="d-flex  mb-4"><input type="hidden" id="message-id" name="message-id" value="'.$chatId.'"><input type="hidden" id="message-value" name="message-value" value="'.$message->message.'"><button id="'.$message->id.'" class="btn btn-secondary quick_approve_add_live">Approve Message</button></div></div></div>';
                            }else{
                                $vals = '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer">' . $message->message . '<span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span></div></div>';
                            }
                            $messagess[] = $vals;

                              //<div class="msg_cotainer_send"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div>

                        }

                    } else {

                        if ($message->hasMedia(config('constants.media_tags'))) {
                            foreach ($message->getMedia(config('constants.media_tags')) as $image) {
                                if (strpos($image->getUrl(), 'jpeg') !== false) {
                                    $attachment = '<a href="" download><img src="' . $image->getUrl() . '" class="rounded-circle-livechat user_img_msg"></a>';
                                } else {
                                    $attachment = '<a href="" download>' . $image->filename . '</a>';
                                }
                                if ($message->status == 2) {
                                    $type = 'end';
                                } else {
                                    $type = 'start';
                                }
                                if(!$message->approved){

                                    $messagess[] = '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="msg_cotainer"><span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span><div class="d-flex  mb-4"><input type="hidden" id="message-id" name="message-id" value="'.$chatId.'"><input type="hidden" id="message-value" name="message-value" value="'.$message->message.'"><button id="'.$message->id.'" class="btn btn-secondary quick_approve_add_live">Approve Message</button></div></div><div class="msg_cotainer_send">' . $attachment . '</div></div>';                                    
                                }else{
                                    '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="msg_cotainer"><span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span></div><div class="msg_cotainer_send">' . $attachment . '</div></div>';
                                }
                            }
                        } else {
                            if ($message->status == 2) {
                                $type = 'end';
                            } else {
                                $type = 'start';
                            }
                            if(!$message->approved){
                                $messagess[] = '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle-livechat user_inital">' . $customerInital . '</div><div class="msg_cotainer">' . $message->message . '<span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span><input type="hidden" id="message-id" name="message-id" value="'.$chatId.'"><div class="d-flex  mb-4"><input type="hidden" id="message-value" name="message-value" value="'.$message->message.'"><button id="'.$message->id.'" class="btn btn-secondary quick_approve_add_live">Approve Message</button></div></div></div>';
                            }else{
                                $messagess[] = '<div data-chat-id="'.$message->id.'" class="d-flex justify-content-' . $type . ' mb-4"><div class="rounded-circle-livechat user_inital">' . $customerInital . '</div><div class="msg_cotainer">' . $message->message . '<span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() . '</span></div></div>';
                            }
                             //<div class="img_cont_msg"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div>
                        }

                    }
                }

            }

            $count = CustomerLiveChat::where('seen', 0)->count();
            return response()->json([
                'status' => 'success',
                'data'   => array('id' => $chatId, 'count' => $count, 'message' => $messagess, 'name' => $name, 'customerInital' => $customerInital),
            ]);
        } else {
            return response()->json([
                'data' => array('id' => '', 'count' => 0, 'message' => '', 'name' => '', 'customerInital' => ''),
            ]);
        }
    }

    public function getLiveChats()
    {
        if (session()->has('chat_customer_id')) {
            $chatId       = session()->get('chat_customer_id');
            $chat_message = ChatMessage::where('customer_id', $chatId)->where('message_application_id', 2)->orderBy("id","desc")->get();
            //getting customer name from chat
            $customer       = Customer::findorfail($chatId);
            $name           = $customer->name;
            $customerInital = substr($name, 0, 1);
            if (count($chat_message) == 0) {
                //$message[] = '<div class="d-flex justify-content-start mb-4"><div class="rounded-circle user_inital">' . $customerInital . '</div><div class="msg_cotainer">New Chat From Customer<span class="msg_time"></span></div></div>'; //<div class="img_cont_msg"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div>
            } else {
                foreach ($chat_message as $chat) {
                    if ($chat->user_id != 0) {
                        // Finding Agent
                        $agent       = User::where('email', $chat->user_id)->first();
                        $agentInital = substr($agent->name, 0, 1);
                        
                        if(!$chat->approved){
                            $message[] = '<div data-chat-id="'.$chat->id.'" class="d-flex justify-content-end mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer">' . $chat->message . '<span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($chat->created_at))->diffForHumans() . '</span><input type="hidden" id="message-id" name="message-id" value="'.$chatId.'"><input type="hidden" id="message-value" name="message-value" value="'.$message->message.'"><div class="d-flex  mb-4"><button id="'.$message->id.'" class="btn btn-secondary quick_approve_add_live">Approve Message</button></div></div></div>';
                        }else{
                            $message[] =  '<div data-chat-id="'.$chat->id.'" class="d-flex justify-content-end mb-4"><div class="rounded-circle user_inital">' . $agentInital . '</div><div class="msg_cotainer">' . $chat->message . '<span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($chat->created_at))->diffForHumans() . '</span></div></div>';

                        }
                          //<div class="msg_cotainer_send"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div>
                    } else {
                        $message[] = '<div data-chat-id="'.$chat->id.'" class="d-flex justify-content-start mb-4"><div class="rounded-circle user_inital">' . $customerInital . '</div><div class="msg_cotainer">' . $chat->message . '<span class="msg_time">' . \Carbon\Carbon::createFromTimeStamp(strtotime($chat->created_at))->diffForHumans() . '</span></div></div>'; //<div class="img_cont_msg"><img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg"></div>
                    }
                }
            }
            $count = CustomerLiveChat::where('seen', 0)->count();
            return view('livechat.chatMessages', compact('message', 'name', 'customerInital'));
        } else {
            $count          = 0;
            $message        = '';
            $customerInital = '';
            $name           = '';
            return view('livechat.chatMessages', compact('message', 'name', 'customerInital'));
        }
    }

    public function getUserList()
    {
        $liveChatCustomers = CustomerLiveChat::orderBy('seen', 'asc')->where("thread", "!=", "")->where("status", 1)->orderBy('status', 'desc')->get();

        foreach ($liveChatCustomers as $liveChatCustomer) {
            $customer       = Customer::where('id', $liveChatCustomer->customer_id)->first();
            $customerInital = substr($customer->name, 0, 1);
            if ($liveChatCustomer->status == 0) {
                $customers[] = '<li onclick="getChats(' . $customer->id . ')" id="user' . $customer->id . '" style="cursor: pointer;">
                                <input type="hidden" id="live_selected_customer_store" value="' . $customer->store_website_id . '" />
                                <div class="d-flex bd-highlight"><div class="img_cont"><span class="rounded-circle user_inital">' . $customerInital . '</span><span class="online_icon offline"></span>
                                </div><div class="user_info"><span>' . $customer->name . '</span><p style="margin-bottom: 0px;">' . $customer->name . ' is offline</p><p style="margin-bottom: 0px;">' . $liveChatCustomer->website . '</p></div></div></li><li>'; //<img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
            } elseif ($liveChatCustomer->status == 1 && $liveChatCustomer->seen == 0) {
                $customers[] = '<li onclick="getChats(' . $customer->id . ')" id="user' . $customer->id . '" style="cursor: pointer;">
                                <input type="hidden" id="live_selected_customer_store" value="' . $customer->store_website_id . '" />
                                <div class="d-flex bd-highlight"><div class="img_cont"><span class="rounded-circle user_inital">' . $customerInital . '</span><span class="online_icon"></span>
                                </div><div class="user_info"><span>' . $customer->name . '</span><p style="margin-bottom: 0px;">' . $customer->name . ' is online</p><p style="margin-bottom: 0px;">' . $liveChatCustomer->website . '</p></div><span class="new_message_icon"></span></div></li>'; //<img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
            } else {
                $customers[] = '<li onclick="getChats(' . $customer->id . ')" id="user' . $customer->id . '" style="cursor: pointer;">
                                <input type="hidden" id="live_selected_customer_store" value="' . $customer->store_website_id . '" />
                                <div class="d-flex bd-highlight"><div class="img_cont"><span class="rounded-circle user_inital">' . $customerInital . '</span><span class="online_icon"></span>
                                </div><div class="user_info"><span>' . $customer->name . '</span><p style="margin-bottom: 0px;">' . $customer->name . ' is online</p><p style="margin-bottom: 0px;">' . $liveChatCustomer->website . '</p></div></div></li>'; //<img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
            }
        }
        if (empty($customers)) {
            $customers[] = '<li><div class="d-flex bd-highlight"><div class="img_cont">
                                </div><div class="user_info"><span>No User Found</span><p></p></div></div></li>';
        }
        //Getting chat counts
        $count = CustomerLiveChat::where('seen', 0)->count();

        return response()->json([
            'status' => 'success',
            'data'   => array('count' => $count, 'message' => $customers),
        ]);

    }

    public function checkNewChat()
    {
        $count = CustomerLiveChat::where('seen', 0)->count();
        return response()->json([
            'status' => 'success',
            'data'   => array('count' => $count),
        ]);
    }

    /**
     * function to get customer details from livechatinc
     * https://api.livechatinc.com/v3.1/agent/action/get_customers
     *
     * @param customer's email address
     *
     * @return - response livechatinc object of customer information. If error return false
     */
    public function getLiveChatIncCustomer($email = '', $out = 'JSON')
    {
        $threadId = '';
        if ($email == '' && session()->has('chat_customer_id')) {
            $chatId   = session()->get('chat_customer_id');
            $messages = ChatMessage::where('customer_id', $chatId)->where('message_application_id', 2)->get();
            //getting customer name from chat
            $customer = Customer::findorfail($chatId);
            $email    = $customer->email;

            $liveChatCustomer = CustomerLiveChat::where('customer_id', $chatId)->first();
            $threadId         = $liveChatCustomer->thread;
        }

        $returnVal = '';
        if ($email != '') {
            $postURL = 'https://api.livechatinc.com/v3.1/agent/action/get_customers';

            $postData = array('filters' => array('email' => array('values' => array($email))));
            $postData = json_encode($postData);

            $returnVal = '';
            $result    = self::curlCall($postURL, $postData, 'application/json');
            if ($result['err']) {
                // echo "ERROR 1:<br>";
                // print_r($result['err']);
                $returnVal = false;
            } else {
                $response = json_decode($result['response']);
                if (isset($response->error)) {
                    // echo "ERROR 2:<br>";
                    // print_r($response);
                    $returnVal = false;
                } else {
                    // echo "SUCSESS:<BR>";
                    // print_r($response);
                    $returnVal = $response->customers[0];
                }
            }
        }

        if ($out == 'JSON') {
            return response()->json(['status' => 'success', 'threadId' => $threadId, 'customerInfo' => $returnVal], 200);
        } else {
            return $returnVal;
        }
    }

    /**
     * function to upload file/image to liveshatinc
     * upload file to livechatinc using their agent /action/upload_file api which will respond with livechatinc CDN url for file uploaded
     * https://api.livechatinc.com/v3.1/agent/action/upload_file
     *
     * @param request
     *
     * @return - response livechatinc CDN url for the file. If error return false
     */
    public function uploadFileToLiveChatInc(Request $request)
    {
        //To try with static file from local file, uncomment below
        //$filename = 'delete-red-cross.png';
        //$fileURL = public_path() . '/images/' . $filename;
        $uploadedFile = $request->file('file');
        $mimeType     = $uploadedFile->getMimeType();
        $filename     = $uploadedFile->getClientOriginalName();

        $postURL = 'https://api.livechatinc.com/v3.1/agent/action/upload_file';

        //echo 'File: ' . $fileURL . ', MType: ' . mime_content_type($fileURL) .'<br>';
        //$postData = array('file' => curl_file_create($fileURL, mime_content_type($fileURL), basename($fileURL)));
        //echo 'File: ' . $filename . ', MType: ' . $mimeType;

        $postData = array('file' => curl_file_create($uploadedFile, $mimeType, $filename));

        $result = self::curlCall($postURL, $postData, 'multipart/form-data');
        if ($result['err']) {
            // echo "ERROR 1:<br>";
            // print_r($result['err']);
            return false;
        } else {
            $response = json_decode($result['response']);
            if (isset($response->error)) {
                // echo "ERROR 2:<br>";
                // print_r($response);
                return false;
            } else {
                // echo "SUCSESS:<BR>";
                // print_r($response);
                return ['CDNPath' => $response->url, 'filename' => $filename];
            }
        }
    }

    public static function useAbsPathUpload($fileURL)
    {
        $filename = basename($fileURL);
        $postData = array('file' => curl_file_create($fileURL, mime_content_type($fileURL), basename($fileURL)));
        $postURL  = 'https://api.livechatinc.com/v3.1/agent/action/upload_file';
        $result   = self::curlCall($postURL, $postData, 'multipart/form-data');
        if ($result['err']) {
            return false;
        } else {
            $response = json_decode($result['response']);
            if (isset($response->error)) {
                return false;
            } else {
                return ['CDNPath' => $response->url, 'filename' => $filename];
            }
        }
    }

    /**
     * curlCall function to make a curl call
     *
     * @param
     *   URL - url that we need to access and make curl call,
     *   method - curl call method - GET, POST etc
     *   contentType - Content-Type value to set in headers
     *   data - data that has to be sent in curl call. This can be optional if GET
     * @return - response from curl call, array(response, err)
     */
    public static function curlCall($URL, $data = false, $contentType = false, $defaultAuthorization = true, $method = 'POST')
    {
        $curl = curl_init();

        $curlData = array(
            CURLOPT_URL            => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        );

        if ($method == 'POST') {
            $curlData[CURLOPT_POST] = 1;
        } else {
            $curlData[CURLOPT_CUSTOMREQUEST] = $method;
        }
        if ($contentType) {
            $curlData[CURLOPT_HTTPHEADER] = [];
            if ($defaultAuthorization) {
                array_push($curlData[CURLOPT_HTTPHEADER], "Authorization: Bearer " . \Cache::get('key') . "");
            }
            // $curlData[CURLOPT_HTTPHEADER] = array(
            //     "Authorization: Basic NTYwNzZkODktZjJiZi00NjUxLTgwMGQtNzE5YmEyNTYwOWM5OmRhbDpUQ3EwY2FZYVRrMndCTHJ3dTgtaG13",
            //     "Content-Type: " . $contentType
            // );
            array_push($curlData[CURLOPT_HTTPHEADER], "Content-Type: " . $contentType);
        }
        if ($data) {
            $curlData[CURLOPT_POSTFIELDS] = $data;
        }

        curl_setopt_array($curl, $curlData);
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);

        return array('response' => $response, 'err' => $err);
    }

    /**
     * CDN URL got after uploading file to livechatinc will expire in 24hrs unless its used in sent_event api
     * send the CDN URL to livechatinc using sent_event api to keep the CDN URL alive
     * https://developers.livechatinc.com/docs/messaging/agent-chat-api/#file
     * https://developers.livechatinc.com/docs/messaging/agent-chat-api/#send-event
     */
    public function sendFileToLiveChatInc(Request $request)
    {
        $chatId = $request->id;
        //Get Thread ID From Customer Live Chat
        $customer = CustomerLiveChat::where('customer_id', $chatId)->first();
        if ($customer != '' && $customer != null) {
            $thread = $customer->thread;
        } else {
            return response()->json(['status' => 'errors', 'errorMsg' => 'Thread not found'], 200);
        }

        $fileUploadResult = self::uploadFileToLiveChatInc($request);

        if (!$fileUploadResult) {
            //There is some error, we didn't get the CDN file path
            //return false;
            return response()->json(['status' => 'errors', 'errorMsg' => 'Error uploading file'], 200);
        } else {
            $fileCDNPath = $fileUploadResult['CDNPath'];
            $filename    = $fileUploadResult['filename'];
        }

        $postData = array('chat_id' => $thread, 'event' => array('type' => 'file', 'url' => $fileCDNPath, 'recipients' => 'all'));
        $postData = json_encode($postData);

        $postURL = 'https://api.livechatinc.com/v3.1/agent/action/send_event';

        $result = self::curlCall($postURL, $postData, 'application/json');
        if ($result['err']) {
            // echo "ERROR 1:<br>";
            // print_r($result['err']);
            //return false;
            return response()->json(['status' => 'errors', 'errorMsg' => $result['err']], 403);
        } else {
            $response = json_decode($result['response']);
            if (isset($response->error)) {
                // echo "ERROR 2:<br>";
                // print_r($response);
                return response()->json(['status' => 'errors', $response], 403);
            } else {
                // echo "SUCSESS:<BR>";
                // print_r($response);
                //return $response->url;
                return response()->json(['status' => 'success', 'filename' => $filename, 'fileCDNPath' => $fileCDNPath, 'responseData' => $response], 200);
            }
        }
    }

    public static function sendFileMessageEvent($postData)
    {
        $cdnPath  = $postData['event']['url'];
        $postData = json_encode($postData, true);
        $postURL  = 'https://api.livechatinc.com/v3.1/agent/action/send_event';
        $result   = self::curlCall($postURL, $postData, 'application/json');
        if ($result['err']) {
            return response()->json(['status' => 'errors', 'errorMsg' => $result['err']], 403);
        } else {
            $response = json_decode($result['response']);
            if (isset($response->error)) {
                return response()->json(['status' => 'errors', $response], 403);
            } else {
                return response()->json(['status' => 'success', 'filename' => $cdnPath, 'fileCDNPath' => $cdnPath, 'responseData' => $response], 200);
            }
        }
    }

    public static function getDomain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }

    public function saveToken(Request $request)
    {
        if ($request->accessToken) {
            //dd($request->accessToken);
            $storedCache = \Cache::get('key');
            if ($storedCache) {
                if ($storedCache != $request->accessToken) {
                    try {
                        \Cache::put('key', $request->accessToken, $request->seconds);
                    } catch (Exception $e) {
                        \Cache::add('key', $request->accessToken, $request->seconds);
                    }
                }
            } else {
                try {
                    \Cache::put('key', $request->accessToken, $request->seconds);
                } catch (Exception $e) {
                    \Cache::add('key', $request->accessToken, $request->seconds);
                }
            }
            //session()->put('livechat_accesstoken', $request->accessToken);
            //\Session::put('livechat_accesstoken', $request->accessToken);
            //$request->session()->put('livechat_accesstoken', $request->accessToken);
            return response()->json(['status' => 'success', 'message' => 'AccessToken saved'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'AccessToken cannot be saved'], 500);
    }

    public function attachImage(Request $request)
    {
        $customerid = $request->get("customer_id", 0);
        $livechat   = CustomerLiveChat::where("customer_id", $customerid)->where("thread", "!=", "")->first();
        
        if ($livechat) {

            if ($request->images != null) {
                $images = json_decode($request->images, true);
                $images = array_filter($images);
                if (!empty($images)) {
                    $medias = Media::whereIn("id", array_unique($images))->get();
                    if (!$medias->isEmpty()) {
                        foreach ($medias as $iimg => $media) {
                            $cdn = self::useAbsPathUpload($media->getAbsolutePath());
                            if (!$cdn == false) {
                                $postData = array(
                                    'chat_id' => $livechat->thread,
                                    'event'   => array(
                                        'type'       => 'file',
                                        'url'        => $cdn['CDNPath'],
                                        'recipients' => 'all',
                                    ),
                                );
                                $result = self::sendFileMessageEvent($postData);
                            }
                        }
                    }
                }
            }
        }

        return redirect(route('livechat.get.chats')."?open_chat=true");
    }

    /**
    * Get tickets from livechat inc and put them as unread messages
    * 
    * https://developers.livechatinc.com/docs/management/configuration-api/v2.0/#tickets
    * https://api.livechatinc.com/tickets?assigned=0
    dal:ZP6x3Uc3QMa9W-Ve4sp86A
    */
    public function getLiveChatIncTickets(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.livechatinc.com/v2/tickets",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Basic NmY0M2ZkZDUtOTkwMC00OWY4LWI4M2ItZThkYzg2ZmU3ODcyOmRhbDp0UkFQdWZUclFlLVRkQUI4Y2pFajNn"
          ),
        ));

        $response = curl_exec($curl);

        $result = json_decode($response,true);
        if(!empty($result['tickets'])) {
            return $result['tickets'];
        }else{
            return false;
        }
    }


    /**  Created By Maulik Jadvani
     * function to Get tickets list.
     *
     * @param request
     *
     * @return -all tickets list 
     */
    public function tickets(Request $request)
    {
        $title = 'tickets';

        
        $selectArray[] = 'tickets.*'; 
        $selectArray[] = 'users.name AS assigned_to_name'; 
        $query = Tickets::query();
        $query = $query->leftjoin('users','users.id', '=', 'tickets.assigned_to');

        $query = $query->select($selectArray);

        if($request->ticket_id)
        {
			$query = $query->where('ticket_id', $request->ticket_id);
        }

        if($request->users_id !='')
        {
			$query = $query->where('assigned_to', $request->users_id);
        }
        
		if($request->term !=""){

			$query = $query->where('tickets.name', 'LIKE','%'.$request->term.'%')->orWhere('tickets.email', 'LIKE', '%'.$request->term.'%');
        }

        if($request->search_country !=""){

			$query = $query->where('tickets.country', 'LIKE','%'.$request->search_country.'%');
        }

        if($request->search_order_no !=""){

			$query = $query->where('tickets.order_no', 'LIKE','%'.$request->search_order_no.'%');
        }

        if($request->search_phone_no !=""){

			$query = $query->where('tickets.phone_no', 'LIKE','%'.$request->search_phone_no.'%');
        }
        
        /* if($request->search_category !=""){

			$query = $query->where('tickets.category', 'LIKE','%'.$request->search_category.'%');
        } */

        if($request->serach_inquiry_type !=""){

			$query = $query->where('tickets.type_of_inquiry', 'LIKE','%'.$request->serach_inquiry_type.'%');
        }
        
        if($request->status_id !='')
        {
			$query = $query->where('status_id', $request->status_id);
        }

        if($request->date !='')
        {
			$query = $query->whereDate('date', $request->date);
        }

        $pageSize = 10;

        $data = $query->orderBy('date', 'DESC')->paginate($pageSize)->appends(request()->except(['page']));
        
		if ($request->ajax()) {
            return response()->json([
                'tbody' => view('livechat.partials.ticket-list', compact('data'))->with('i', ($request->input('page', 1) - 1) * $pageSize)->render(),
                'links' => (string)$data->render(),
                'count' => $data->total(),
            ], 200);
        }
       return view('livechat.tickets', compact('data'))->with('i', ($request->input('page', 1) - 1) * $pageSize);
        
    }

    public function createTickets(Request $request) {
        $data = [];
        $data['ticket_id'] = "T" . date("YmdHis");
        $customer = Customer::find($request->ticket_customer_id);
        $email = null;
        $name = null;
        if($customer) {
            $name = $customer->name;
            $email = $customer->email;
        }
        $data['date'] = date('Y-m-d H:i:s');
        $data['name'] = $name;
        $data['email'] = $email;
        $data['customer_id'] = $request->ticket_customer_id;
        $data['source_of_ticket'] = $request->source_of_ticket;
        $data['subject'] = $request->ticket_subject;
        $data['message'] = $request->ticket_message;
        $data['assigned_to'] = $request->ticket_assigned_to;
        $data['status_id'] = $request->ticket_status_id;
        $success = Tickets::create($data);
        return response()->json(['ticket created successfully', 'code' => 200, 'status' => 'success']);
    }


    public function createCredits(Request $request) {
        $data = [];
        
        $customer_id=$request->credit_customer_id;
        $credit=$request->credit;
        $customer = Customer::find($customer_id);
        if($customer->credit==null || $customer->credit==""){
            $customer->credit=0;
        }
        $calc_credit=0;
            if($credit<0){
                $type="MINUS";
                if($customer->credit==0){
                    $calc_credit=$customer->credit+($credit);
                }else{
                    $credit=str_replace('-','',$credit);
                    $calc_credit=$customer->credit-$credit;
                }
                
            }else{
                $type="PLUS";
                $calc_credit=$customer->credit+$credit;
            }
            $customer->credit=$calc_credit;
            $customer->save();
        if($customer){
            \App\CreditHistory::create(
                array(
                    'customer_id'=>$customer_id,
                    'model_id'=>$customer_id,
                    'model_type'=>Customer::class,
                    'used_credit'=>$credit,
                    'used_in'=>'MANUAL',
                    'type'=>$type
                )
            );

            $emailClass = (new \App\Mails\Manual\SendIssueCredit($customer))->build();

            $storeWebsiteOrder = $order->storeWebsiteOrder;
            $email             = Email::create([
                'model_id'         => $customer->id,
                'model_type'       => \App\Customer::class,
                'from'             => $emailClass->fromMailer,
                'to'               => $customer->email,
                'subject'          => $emailClass->subject,
                'message'          => $emailClass->render(),
                'template'         => 'issue-credit',
                'additional_data'  => '',
                'status'           => 'pre-send',
                'store_website_id' => null,
            ]);

            \App\Jobs\SendEmail::dispatch($email);

        }
        return response()->json(['credit updated successfully', 'code' => 200, 'status' => 'success']);
    }

    public function getCreditsData(Request $request) {
        $customer=Customer::find($request->customer_id);
        if($customer->credit==null || $customer->credit==""){
            $currentcredit=0;
        }else{
            $currentcredit=$customer->credit;
        }
        $credits = \App\CreditHistory::where('customer_id',$request->customer_id)->orderBy('id','desc')->get();
        return response()->json(['data' => $credits,'currentcredit'=>$currentcredit,'status' => 'success']);
    }

    public function getTicketsData(Request $request) {
        $tickets = Tickets::where('customer_id',$request->customer_id)->with('ticketStatus')->get();
        return response()->json(['data' => $tickets, 'status' => 'success']);
    }
    public function sendEmail(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|min:3|max:255',
            'message' => 'required',
           
            'cc.*' => 'nullable|email',
            'bcc.*' => 'nullable|email'
        ]);

        $tickets = Tickets::find($request->ticket_id);
        if(!isset($tickets->id))
        {
           // return false;
        }
        $ticketIdString='#'.$tickets->ticket_id;
        $fromEmail = 'buying@amourint.com';
        $fromName  =  "buying";

        if ($request->from_mail) 
        {
            $mail = \App\EmailAddress::where('id', $request->from_mail)->first();
            if ($mail) 
            {
                $fromEmail = $mail->from_address;
                $fromName  = $mail->from_name;
                $config = config("mail");
                unset($config['sendmail']);
                $configExtra = array(
                'driver'    => $mail->driver,
                'host'      => $mail->host,
                'port'      => $mail->port,
                'from'      => [
                    'address' => $mail->from_address,
                    'name' => $mail->from_name,
                ],
                'encryption'  => $mail->encryption,
                'username'    => $mail->username,
                'password'    => $mail->password
                );
                \Config::set('mail', array_merge($config, $configExtra));
                (new \Illuminate\Mail\MailServiceProvider(app()))->register();
            }
        }

        if ($tickets->email != '') 
        {
            $file_paths = [];

            if ($request->hasFile('file')) 
            {
                foreach ($request->file('file') as $file) {
                $filename = $file->getClientOriginalName();

                $file->storeAs("documents", $filename, 'files');

                $file_paths[] = "documents/$filename";
                }
            }

            $cc = $bcc = [];
            $emails[] = $tickets->email;

            if ($request->has('cc')) {
                $cc = array_values(array_filter($request->cc));
            }
            if ($request->has('bcc')) {
                $bcc = array_values(array_filter($request->bcc));
            }

            if (is_array($emails) && !empty($emails)) {
                $to = array_shift($emails);
                $cc = array_merge($emails, $cc);

                $mail = Mail::to($to);

                if ($cc) {
                $mail->cc($cc);
                }
                if ($bcc) {
                $mail->bcc($bcc);
                }

                $mail->send(new PurchaseEmail($request->subject.$ticketIdString, $request->message, $file_paths, ["from" => $fromEmail]));
            } else {
                return redirect()->back()->withErrors('Please select an email');
            }

            $params = [
                'model_id' => $tickets->id,
                'model_type' => Tickets::class,
                'from' => $fromEmail,
                'to' => $tickets->email,
                'seen' => 1,
                'subject' => $request->subject.$ticketIdString,
                'message' => $request->message,
                'template' => 'customer-simple',
                'additional_data' => json_encode(['attachment' => $file_paths]),
                'cc' => $cc ?: null,
                'bcc' => $bcc ?: null
            ];

            Email::create($params);

            return redirect()->back()->withSuccess('You have successfully sent an email!');
        }
    }

    public function AssignTicket(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'users_id' =>'required|numeric',
           
        ]);

        $id = $request->id;
        $users_id = $request->users_id;

        $tickets = Tickets::find($request->id);
        if(isset($tickets->id) && $tickets->id > 0)
        {
            $tickets->assigned_to = $users_id;
            $tickets->save();

            return redirect()->back()->withSuccess('Tickets has been successfully Assigned.');

        }else
        {
            return redirect()->back()->withErrors('something wrong please try to again Assigned Tickets.');

        }

    }


    public function TicketStatus(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);


        $name = $request->name;
        $TicketStatusObj = TicketStatuses::where(['name'=> $name])->first();
        if(isset($TicketStatusObj->id) && $TicketStatusObj->id > 0)
        {

        }else
        {
            TicketStatuses::create(['name'=> $name]);
        }

        return redirect()->back()->withSuccess('Ticket Status has been successfully Added.');
    }


    public function ChangeStatus(Request $request)
    {
        if($request->status !='' && $request->id !='') 
        {
            $tickets = Tickets::find($request->id);
            if(isset($tickets->id) && $tickets->id > 0)
            {
                $tickets->status_id = $request->status;
                $tickets->save();

            }

           
        }else
        {

        }

        return response()->json([
            'status' => 'success'
        ]);
    }


    
}