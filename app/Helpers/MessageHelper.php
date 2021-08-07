<?php

namespace App\Helpers;

use App\ChatMessagesQuickData;
use App\Library\Watson\Model as WatsonManager;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\ChatMessage;
use \App\Product;
use \App\KeywordAutoGenratedMessageLog; //Purpose : Add KeywordAutoGenratedMessageLog - DEVTASK-4233
use App\User;

class MessageHelper
{
    const TXT_ARTICLES = [
        "a",
        "an",
        "the",
    ];

    const AUTO_LEAD_SEND_PRICE = 281;
    const AUTO_DIMENSION_SEND  = 7;

    const TXT_PREPOSITIONS = [
        "aboard",
        "about",
        "above",
        "across",
        "after",
        "against",
        "along",
        "amid",
        "among",
        "anti",
        "around",
        "as",
        "at",
        "before",
        "behind",
        "below",
        "beneath",
        "beside",
        "besides",
        "between",
        "beyond",
        "but",
        "by",
        "concerning",
        "considering",
        "despite",
        "down",
        "during",
        "except",
        "excepting",
        "excluding",
        "following",
        "for",
        "from",
        "in",
        "inside",
        "into",
        "like",
        "minus",
        "near",
        "of",
        "off",
        "on",
        "onto",
        "opposite",
        "outside",
        "over",
        "past",
        "per",
        "plus",
        "regarding",
        "round",
        "save",
        "since",
        "than",
        "through",
        "to",
        "toward",
        "towards",
        "under",
        "underneath",
        "unlike",
        "until",
        "up",
        "upon",
        "versus",
        "via",
        "with",
        "within",
        "without",
    ];

    public static function getMostUsedWords()
    {
        $chatMessages = ChatMessage::where("customer_id", ">", 0)
            ->where("message", "!=", "")
            ->whereNotNull("number")
            ->select("message", "id")->groupBy("message")->get()->pluck("message", "id");

        //rows here should be replaced by the SQL result
        $wordTotals = [];
        $phrases    = [];
        foreach ($chatMessages as $id => $row) {
            $words = explode(" ", $row);
            foreach ($words as $word) {
                if (!in_array($word, self::TXT_ARTICLES + self::TXT_PREPOSITIONS) && $word != "") {
                    $phrases[$word][] = ["txt" => $row, "id" => $id];
                    if (isset($wordTotals[$word])) {
                        $wordTotals[$word]++;
                        continue;
                    }

                    $wordTotals[$word] = 1;
                }
            }
        }

        arsort($wordTotals);

        $records = [];
        foreach ($wordTotals as $word => $count) {
            $records['words'][$word] = [
                "word"  => $word,
                "total" => $count,
            ];

            $records['phrases'][$word] = [
                "phrases" => isset($phrases[$word]) ? $phrases[$word] : [],
            ];

        }
        return $records;
    }

    /**
     * Send whatsApp Message
     *
     * @param $customer [ object ]
     * @param $message  [ string ]
     * @return mixed
     */
    public static function whatsAppSend($customer = null, $message = null, $sendMsg = null, $messageModel = null, $isEmail = null, $parentMessage = null)
    {
        if ($customer) {

            //START - Purpose : Add Data in array - DEVTASK-4233
            $log_comment = 'whatsAppSend : ';
            if (!empty($messageModel)) {
                $temp_log_params['model']     = $messageModel->getTable();
                $temp_log_params['model_id']     = $messageModel->id;
            }
            //END - DEVTASK-4233

            // $exp_mesaages = explode(" ", $message);
            $exp_mesaages = explode(" ", $message);

            $temp_log_params['keyword']  =  implode(", ",$exp_mesaages);//Purpose : Add keyword in array - DEVTASK-4233
          
            for ($i = 0; $i < count($exp_mesaages); $i++) {
                $keywordassign = DB::table('keywordassigns')->select('*')
                    ->whereRaw('FIND_IN_SET(?,keyword)', [strtolower($exp_mesaages[$i])])
                    ->get();

                if (count($keywordassign) > 0) {
                    $log_comment = $log_comment.' Keyword is '.$exp_mesaages[$i];
                    break;
                }
            }

            \Log::info("Keyword assign found" . count($keywordassign));

            if (count($keywordassign) > 0) {

                //START - Purpose : Log Comment - DEVTASK-4233
                $log_comment = $log_comment.' and Keyword match Description is '.$keywordassign[0]->task_description.', ';

                $temp_log_params['keyword_match']     = $keywordassign[0]->task_description;
                //END - DEVTASK-4233

                $task_array = array(
                    "category"     => 42,
                    "is_statutory" => 0,
                    "task_subject" => "#" . $customer->id . "-" . $keywordassign[0]->task_description,
                    "task_details" => $keywordassign[0]->task_description,
                    "assign_from"  => \App\User::USER_ADMIN_ID,
                    "assign_to"    => $keywordassign[0]->assign_to,
                    "customer_id"  => $customer->id,
                    "created_at"   => date("Y-m-d H:i:s"),
                    "updated_at"   => date("Y-m-d H:i:s"),
                );
                DB::table('tasks')->insert($task_array);
                $taskid           = DB::getPdo()->lastInsertId();
                $task_users_array = array(
                    "task_id" => $taskid,
                    "user_id" => $keywordassign[0]->assign_to,
                    "type"    => "App\User",
                );
                DB::table('task_users')->insert($task_users_array);

                // check that match if this the assign to is auto user
                // then send price and deal
                \Log::channel('whatsapp')->info("Price Lead section started for customer id : " . $customer->id);
                
                if ($keywordassign[0]->assign_to == self::AUTO_LEAD_SEND_PRICE) {

                    \Log::channel('whatsapp')->info("Auto section started for customer id : " . $customer->id);
                    
                    if (!empty($parentMessage)) {
                        \Log::channel('whatsapp')->info("Auto section parent message  lead pricefound started for customer id : " . $customer->id);

                        $log_comment = $log_comment.' .Auto section parent message lead price found started for customer id : '.$customer->id;//Purpose : Log Comment - DEVTASK-4233

                        $parentMessage->sendLeadPrice($customer);
                    }

                }elseif ($keywordassign[0]->assign_to == self::AUTO_DIMENSION_SEND) {
                    \Log::channel('whatsapp')->info("Auto section started for customer id : " . $customer->id);
                    if (!empty($parentMessage)) {
                        
                        \Log::channel('whatsapp')->info("Auto section parent message found started for customer id : " . $customer->id);

                        $log_comment = $log_comment.' Auto section parent message found started for customer id : '.$customer->id.' >> ';//Purpose : Log Comment - DEVTASK-4233

                        $products = DB::table('leads')
                        ->select('*')
                        ->where('id', '=',$parentMessage->lead_id)
                        ->get();
                        if(!empty($products[0]->selected_product)){
                            $requestData = new Request();
                            $requestData->setMethod('POST');
                            $requestData->request->add(['customer_id' => $customer->id, 'dimension' => true, 'selected_product' => $products[0]->selected_product]);

                            app('App\Http\Controllers\LeadsController')->sendPrices($requestData, new GuzzleClient);
                        }

                    }
                }

                //START CODE Task message to send message in whatsapp

                $task_info = DB::table('tasks')
                    ->select('*')
                    ->where('id', '=', $taskid)
                    ->get();

                $users_info = DB::table('users')
                    ->select('*')
                    ->where('id', '=', $task_info[0]->assign_to)
                    ->get();

                if (count($users_info) > 0) {
                    if ($users_info[0]->phone != "") {

                        //START - Purpose : Log Comment - DEVTASK-4233
                        $log_comment = $log_comment.' User Info id : '.$users_info[0]->id.' and ';
                        $log_comment = $log_comment.' User Info phone : '.$users_info[0]->phone.' Send Whatsapp Message ';
                        //END - DEVTASK-4233

                        $params_task = [
                            'number'            => null,
                            'user_id'           => $users_info[0]->id,
                            'approved'          => 1,
                            'status'            => 2,
                            'task_id'           => $taskid,
                            'message'           => $task_info[0]->task_details,
                            'quoted_message_id' => ($messageModel) ? $messageModel->quoted_message_id : null,
                        ];

                        if ($sendMsg === true) {
                            app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($users_info[0]->phone, $users_info[0]->whatsapp_number, $task_info[0]->task_details);
                        }


                        $chat_message = \App\ChatMessage::create($params_task);
                        \App\ChatMessagesQuickData::updateOrCreate([
                            'model'    => \App\Task::class,
                            'model_id' => $taskid,
                        ], [
                            'last_communicated_message'    => $task_info[0]->task_details,
                            'last_communicated_message_at' => $chat_message->created_at,
                            'last_communicated_message_id' => ($chat_message) ? $chat_message->id : null,
                        ]);

                        $myRequest = new Request();
                        $myRequest->setMethod('POST');
                        $myRequest->request->add(['messageId' => $chat_message->id]);//Purpose : add messageid in array - DEVTASK-4233

                        $log_comment = $log_comment.' and Create new Entry In ChatMessage , ChatMessage id is '.$chat_message->id;

                        $temp_log_params['message_sent_id']     = $chat_message->id;

                        if ($sendMsg === true) {
                            app('App\Http\Controllers\WhatsAppController')->approveMessage('task', $myRequest);
                        }
                    }
                }
                //END CODE Task message to send message in whatsapp
            }

            //START - Purpose : Log Comment , add data - DEVTASK-4233
            $log_comment = $log_comment.' . ';
            $temp_log_params['comment']  =  $log_comment;

            if ( !empty($temp_log_params['keyword_match']) && $temp_log_params['keyword_match'] != '')
            {
                $add_keyword = KeywordAutoGenratedMessageLog::create($temp_log_params);
            }
            //END - DEVTASK-4233

        }
    }

    /**
     * Send watson reply
     *
     * @param $Custmoer [ Object ]
     * @param $message [ string ]
     * @return mixed
     */
    public static function sendwatson($customer = null, $message = null, $sendMsg = null, $messageModel = null, $params = [], $isEmail = null, $userType = null)
    {
        //START - Purpose : add data in array - DEVTASK-4233
        $log_comment = 'sendwatson : ';
        if (!empty($messageModel)) {
            $temp_log_params['model']     = $messageModel->getTable();
            $temp_log_params['model_id']     = $messageModel->id;
        }
        //END - DEVTASK-4233

        $isReplied = 0;
        if ($userType !== 'vendor') {
            $log_comment = $log_comment.' User Type is Vendor ';//Purpose : Log Comment - DEVTASK-4233
            \Log::info("#2 Price for customer vendor condition passed");
            if ((preg_match("/price/i", $message) || preg_match("/you photo/i", $message) || preg_match("/pp/i", $message) || preg_match("/how much/i", $message) || preg_match("/cost/i", $message) || preg_match("/rate/i", $message))) {

                //START - Purpose : Log Comment , get task discription - DEVTASK-4233
                // $log_comment = $log_comment.' Keyword Match >> ';

                $exp_mesaages = explode(" ", $message);
                $temp_log_params['keyword']  =  implode(", ",$exp_mesaages);
            
                for ($i = 0; $i < count($exp_mesaages); $i++) {
                    $keywordassign = DB::table('keywordassigns')->select('*')
                        ->whereRaw('FIND_IN_SET(?,keyword)', [strtolower($exp_mesaages[$i])])
                        ->get();
                    if (count($keywordassign) > 0) {
                        $log_comment = $log_comment.' Keyword is '.$exp_mesaages[$i];
                        break;
                    }
                }

                if (count($keywordassign) > 0) {

                    // $log_comment = $log_comment.' Keyword assign found >> ';
                    $log_comment = $log_comment.' and Keyword match Description is '.$keywordassign[0]->task_description.', ';
    
                    $temp_log_params['keyword_match']     = $keywordassign[0]->task_description;
                }
                //END - DEVTASK-4233
                
                \Log::info("#3 Price for customer message condition passed");
                if ($customer) {
                    \Log::info("#4 Price for customer model passed");

                    $log_comment = $log_comment.' Customerid is '.$customer->id;//Purpose : Log Comment - DEVTASK-4233
                    // send price from meessage queue
                    $messageSentLast = \App\MessageQueue::where("customer_id", $customer->id)->where("sent", 1)->orderBy("sending_time", "desc")->first();
                    // if message found then start
                    $selected_products = [];
                    if ($messageSentLast) {
                        $mqProducts = $messageSentLast->getImagesWithProducts();
                        if (!empty($mqProducts)) {
                            foreach ($mqProducts as $mq) {
                                if (!empty($mq["products"])) {
                                    foreach ($mq["products"] as $productId) {
                                        $selected_products[] = $productId;
                                    }
                                }
                            }
                        }
                    }

                    // check the last message send for price
                    $lastChatMessage = \App\ChatMessage::getLastImgProductId($customer->id);
                    if ($lastChatMessage) {
                        \Log::info("#5 last message condition found" . $lastChatMessage->id);

                        $log_comment = $log_comment.' and get Last message id from ChatMessage id is '.$lastChatMessage->id.' ';//Purpose : Log Comment - DEVTASK-4233

                        if ($lastChatMessage->hasMedia(config('constants.attach_image_tag'))) {
                            \Log::info("#6 last message has media found");
                            $lastImg = $lastChatMessage->getMedia(config('constants.attach_image_tag'))->sortByDesc('id')->first();
                            \Log::info("#7 last message get media found");
                            if ($lastImg) {
                                \Log::info("#8 last message media found " . $lastImg->id);

                                $log_comment = $log_comment.' Last Message Media Found : '.$lastImg->id.' ,';//Purpose : Log Comment - DEVTASK-4233

                                $mediable = \DB::table("mediables")->where("media_id", $lastImg->id)->where('mediable_type', Product::class)->first();
                                if (!empty($mediable)) {
                                    \Log::info("#9 last message mediable found");

                                    // $log_comment = $log_comment.' Mediable Found  >> ';//Purpose : Log Comment - DEVTASK-4233
                                    
                                    $product = \App\Product::find($mediable->mediable_id);
                                    if (!empty($product)) {
                                        \Log::info("#9 last message product found");
                                        $priceO                       = ($product->price_inr_special > 0) ? $product->price_inr_special : $product->price_inr;
                                        $selected_products[]          = $product->id;
                                        $temp_img_params              = $params;
                                        $temp_img_params['message']   = "Price : " . $priceO;
                                        $temp_img_params['media_url'] = null;
                                        $temp_img_params['status']    = 2;
                                        $temp_img_params['is_email']  = ($isEmail == 1) ? 1 : 0;
                                        $temp_img_params['is_draft']  = ($isEmail == 1) ? 1 : 0;
                                        // Create new message
                                        \App\ChatMessage::create($temp_img_params);
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($selected_products) && $messageSentLast) {
                        foreach ($selected_products as $pid) {
                            $product    = \App\Product::where("id", $pid)->first();
                            $quick_lead = \App\ErpLeads::create([
                                'customer_id'    => $customer->id,
                                //'rating' => 1,
                                'lead_status_id' => 3,
                                //'assigned_user' => 6,
                                'product_id'     => $pid,
                                'brand_id'       => $product ? $product->brand : null,
                                'category_id'    => $product ? $product->category : null,
                                'brand_segment'  => $product && $product->brands ? $product->brands->brand_segment : null,
                                'color'          => $customer->color,
                                'size'           => $customer->size,
                                'created_at'     => Carbon::now(),
                            ]);

                            $log_comment = $log_comment.' Create ERP lead lead id is '.$quick_lead->id;//Purpose : Log Comment - DEVTASK-4233
                        }

                        $requestData = new Request();
                        $requestData->setMethod('POST');
                        $requestData->request->add(['customer_id' => $customer->id, 'lead_id' => $quick_lead->id, 'selected_product' => $selected_products]);

                        app('App\Http\Controllers\LeadsController')->sendPrices($requestData, new GuzzleClient);

                        \App\CommunicationHistory::create([
                            'model_id'   => $messageSentLast->id,
                            'model_type' => \App\MessageQueue::class,
                            'type'       => 'broadcast-prices',
                            'method'     => 'email',
                        ]);
                    }

                    \App\Instruction::create([
                        'customer_id'   => $customer->id,
                        'instruction'   => 'Please send the prices',
                        'category_id'   => 1,
                        'assigned_to'   => 7,
                        'assigned_from' => 6,
                    ]);
                }
            }
        }

        if (!empty($message)) {

            // auto mated reply here
            $auto_replies = \App\AutoReply::where('is_active', 1)->get();
            if (!$auto_replies->isEmpty()) {
                foreach ($auto_replies as $auto_reply) {
                    if (preg_match("/{$auto_reply->keyword}/i", strtolower(trim($message))) && $auto_reply->reply) {
                        $temp_params              = $params;
                        $temp_params['message']   = $auto_reply->reply;
                        $temp_params['media_url'] = null;
                        $temp_params['status']    = 2;
                        $temp_params['approved']  = 1;
                        // Create new message
                        $messageModel = ChatMessage::create($temp_params);

                        $log_comment = $log_comment.' , If Empty message then Create Auto Mated replay in ChatMessage Table and id is '.$messageModel->id;//Purpose : Log Comment - DEVTASK-4233
                    }
                }
            }

            $replies = \App\ChatbotQuestion::join('chatbot_question_examples', 'chatbot_questions.id', 'chatbot_question_examples.chatbot_question_id')
                ->join('chatbot_questions_reply', 'chatbot_questions.id', 'chatbot_questions_reply.chatbot_question_id')
                ->where('chatbot_questions_reply.store_website_id', ($customer->store_website_id) ? $customer->store_website_id : 1)
                ->select('chatbot_questions.value', 'chatbot_questions.keyword_or_question', 'chatbot_questions.erp_or_watson', 'chatbot_questions.auto_approve', 'chatbot_question_examples.question', 'chatbot_questions_reply.suggested_reply')
                ->where('chatbot_questions.erp_or_watson', 'erp')
                ->get();

            if ($messageModel) {
                $chatbotReply = \App\ChatbotReply::create([
                    "question"        => $message,
                    "replied_chat_id" => $messageModel->id,
                ]);

                //START - Purpose : Log Comment , Add message sent id in array - DEVTASK-4233
                $temp_log_params['message_sent_id']     = $messageModel->id;

                // $log_comment = $log_comment.' Chat Message Create : '.$messageModel->id.'  >> ';
                //END - DEVTASK-4233

                foreach ($replies as $reply) {
                    if ($message != '' && $customer) {
                        $keyword = $reply->question;
                        if (($keyword == $message || strpos(strtolower(trim($keyword)), strtolower(trim($message))) !== false) && $reply->suggested_reply) {
                            /*if($reply->auto_approve) {
                            $status = 2;
                            }
                            else {
                            $status = 8;
                            }*/
                            $status                     = ChatMessage::CHAT_AUTO_WATSON_REPLY;
                            $temp_params                = $params;
                            $temp_params['message']     = $reply->suggested_reply;
                            $temp_params['media_url']   = null;
                            $temp_params['status']      = $status;
                            $temp_params['question_id'] = $reply->id;

                            // Create new message
                            $message = ChatMessage::create($temp_params);

                            if ($message->status == ChatMessage::CHAT_AUTO_WATSON_REPLY) {
                                $chatbotReply->chat_id    = $message->id;
                                $chatbotReply->answer     = $reply->suggested_reply;
                                $chatbotReply->reply      = '{"output":{"database":[{"response_type":"text","text":"' . $reply->suggested_reply . '"}]}}';
                                $chatbotReply->reply_from = 'erp';
                                $chatbotReply->save();
                            }

                            // Send message if all required data is set
                            if ($temp_params['message'] || $temp_params['media_url']) {
                                if ($status == 2) {
                                    $sendResult = app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($customer->phone, isset($instanceNumber) ? $instanceNumber : null, $temp_params['message'], $temp_params['media_url']);
                                    if ($sendResult) {
                                        $message->unique_id = $sendResult['id'] ?? '';
                                        $message->save();
                                    }
                                }
                                $isReplied = 1;
                                break;
                            }
                        }
                    }
                }
            }

            // assigned the first storewebsite to default erp customer
            $customer->store_website_id = ($customer->store_website_id > 0) ? $customer->store_website_id : 1;
            if (!$isReplied && $customer->store_website_id) {
                WatsonManager::sendMessage($customer, $message, false, null, $messageModel, $userType);
            }
        }

        //START - Purpose : Log Comment ,Add Data - DEVTASK-4233
        $log_comment = $log_comment.' . ';
        $temp_log_params['comment']  =  $log_comment;
        
        if ( !empty($temp_log_params['keyword_match']) && $temp_log_params['keyword_match'] != '')
        {
            $add_keyword = KeywordAutoGenratedMessageLog::create($temp_log_params);
        }
        //END - DEVTASK-4233
    }

    public static function sendEmailOrWebhookNotification($toUsers, $message){
        
        try{

            foreach($toUsers as $user_id){

                $user = User::with('webhookNotification')->find($user_id);

                if(!$user){
                    continue;
                }
                
                $webhookNotification = $user->webhookNotification;
                
                    $webhookClient = new GuzzleClient();

                    $webhookClient->{$webhookNotification->method}($webhookNotification->url, [
                        'body' => str_replace('[MESSAGE]', $message, $webhookNotification->payload),
                        'connect_timeout' => 3,
                        'headers' => ['Content-Type' => $webhookNotification->content_type ],
                    ]);
            }

        }catch(\Exception $e){
            \Log::channel('webhook')->debug($e->getMessage(). ' | Line no: ' . $e->getLine() .' | ' . $e->getFile());
        }

    }

}
