<?php

namespace App;

use Plank\Mediable\Mediable;
use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatMessage extends Model
{
    // this is guessing status since it is not declared anywhere so
    CONST MESSAGE_STATUS = [
        "11" =>  "Watson Reply",
        "5"  =>  "Read",
        "0"  =>  "Unread",
        "12" =>  "Suggested Images"
    ];

    // auto reply including chatbot as well
    CONST AUTO_REPLY_CHAT  = [
        7,8,9,10,11
    ];

    const EXECLUDE_AUTO_CHAT = [
        7,8,9,10
    ];

    CONST CHAT_AUTO_BROADCAST = 8;
    CONST CHAT_AUTO_WATSON_REPLY = 11;
    CONST CHAT_SUGGESTED_IMAGES = 12;
    CONST CHAT_MESSAGE_APPROVED = 2;

    const ERROR_STATUS_SUCCESS = 0;
    const ERROR_STATUS_ERROR = 1;

    use Mediable;
   /**
     * @var string
         * @SWG\Property(property="is_queue",type="boolean")
     * @SWG\Property(property="unique_id",type="integer")
     * @SWG\Property(property="lead_id",type="integer")
     * @SWG\Property(property="order_id",type="integer")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="supplier_id",type="integer")
     * @SWG\Property(property="ticket_id",type="integer")
     * @SWG\Property(property="task_id",type="integer")
     * @SWG\Property(property="erp_user",type="string")
     * @SWG\Property(property="assigned_to",type="string")
     * @SWG\Property(property="contact_id",type="integer")
     * @SWG\Property(property="dubbizle_id",type="integer")
     * @SWG\Property(property="is_reminder",type="boolean")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="issue_id",type="integer")
     * @SWG\Property(property="developer_task_id",type="integer")
     * @SWG\Property(property="lawyer_id",type="integer")
     * @SWG\Property(property="case_id",type="integer")
     * @SWG\Property(property="blogger_id",type="integer")
     * @SWG\Property(property="voucher_id",type="integer")
     * @SWG\Property(property="document_id",type="integer")
     * @SWG\Property(property="payment_receipt_id",type="integer")
     * @SWG\Property(property="group_id",type="integer")
     * @SWG\Property(property="old_id",type="integer")
     * @SWG\Property(property="message_application_id",type="integer")
     * @SWG\Property(property="is_chatbot",type="boolean")
     * @SWG\Property(property="sent_to_user_id",type="integer")
     * @SWG\Property(property="site_development_id",type="integer")
     * @SWG\Property(property="social_strategy_id",type="integer")
     * @SWG\Property(property="store_social_content_id",type="integer")
     * @SWG\Property(property="quoted_message_id",type="integer")
      * @SWG\Property(property="is_reviewed",type="boolean")
     * @SWG\Property(property="hubstaff_activity_summary_id",type="integer")
     * @SWG\Property(property="question_id",type="integer")
     
     */

    //Purpose - Add learning_id - DEVTASK-4020
    //Purpose : Add additional_data - DEVATSK-4236
    protected $fillable = ['is_queue', 'unique_id', 'lead_id', 'order_id', 'customer_id', 'supplier_id', 'vendor_id', 'user_id','ticket_id','task_id', 'erp_user', 'contact_id', 'dubbizle_id', 'assigned_to', 'purchase_id', 'message', 'media_url', 'number', 'approved', 'status', 'error_status', 'resent', 'is_reminder', 'created_at', 'issue_id', 'developer_task_id', 'lawyer_id', 'case_id', 'blogger_id', 'voucher_id', 'document_id', 'group_id','old_id','message_application_id','is_chatbot','sent_to_user_id','site_development_id','social_strategy_id','store_social_content_id','quoted_message_id','is_reviewed','hubstaff_activity_summary_id','question_id','is_email','payment_receipt_id','learning_id','additional_data','hubstuff_activity_user_id','user_feedback_id','user_feedback_category_id'];

    protected $table = "chat_messages";

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = array(
        "approved" => "boolean"
    );

    /**
     * Send WhatsApp message via Chat-Api
     * @param $number
     * @param null $whatsAppNumber
     * @param null $message
     * @param null $file
     * @return bool|mixed
     */
    public static function sendWithChatApi($number, $whatsAppNumber = null, $message = null, $file = null)
    {
        // Get configs
        $config = \Config::get("apiwha.instances");

        // Set instanceId and token
        if (isset($config[ $whatsAppNumber ])) {
            $instanceId = $config[ $whatsAppNumber ][ 'instance_id' ];
            $token = $config[ $whatsAppNumber ][ 'token' ];
        } else {
            $instanceId = $config[ 0 ][ 'instance_id' ];
            $token = $config[ 0 ][ 'token' ];
        }

        // Add plus to number and add to array
        $chatApiArray = [
            'phone' => '+' . $number
        ];

        if ($message != null && $file == null) {
            $chatApiArray[ 'body' ] = $message;
            $link = 'sendMessage';
        } else {
            $exploded = explode('/', $file);
            $filename = end($exploded);
            $chatApiArray[ 'body' ] = $file;
            $chatApiArray[ 'filename' ] = $filename;
            $link = 'sendFile';
            $chatApiArray[ 'caption' ] = $message;
        }

        // Init cURL
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.chat-api.com/instance$instanceId/$link?token=" . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($chatApiArray),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
            ),
        ));

        // Get response
        $response = curl_exec($curl);

        // Get possible error
        $err = curl_error($curl);

        // Close cURL
        curl_close($curl);

        // Check for errors
        if ($err) {
            // Log error
            \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") cURL Error for number " . $number . ":" . $err);
            return false;
        } else {
            // Log curl response
            \Log::channel('chatapi')->debug('cUrl:' . $response . "\nMessage: " . $message . "\nFile:" . $file . "\n");

            // Json decode response into result
            $result = json_decode($response, true);

            // Check for possible incorrect response
            if (!is_array($result) || array_key_exists('sent', $result) && !$result[ 'sent' ]) {
                // Log error
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Something was wrong with the message for number " . $number . ": " . $response);
                return false;
            } else {
                // Log successful send
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Message was sent to number " . $number . ":" . $response);
            }
        }

        return $result;
    }

    /**
     * Handle Chat-Api ACK-message
     * @param $json
     */
    public static function handleChatApiAck($json)
    {
        // Loop over ack
        if (isset($json[ 'ack' ])) {
            foreach ($json[ 'ack' ] as $chatApiAck) {
                // Find message
                $chatMessage = self::where('unique_id', $chatApiAck[ 'id' ])->first();

                // Chat Message found and status is set
                if ($chatMessage && isset($chatApiAck[ 'status' ])) {
                    // Set delivered
                    if ($chatApiAck[ 'status' ] == 'delivered') {
                        $chatMessage->is_delivered = 1;
                        $chatMessage->save();
                    }

                    // Set views
                    if ($chatApiAck[ 'status' ] == 'viewed') {
                        $chatMessage->is_delivered = 1;
                        $chatMessage->is_read = 1;
                        $chatMessage->save();
                    }
                }
            }
        }
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function lawyer()
    {
        return $this->belongsTo('App\Lawyer');
    }

    /**
     * Check if the message has received a broadcast price reply
     * @return bool
     */
    public function isSentBroadcastPrice()
    {
        // Get count
        $count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\ChatMessage')->where('type', 'broadcast-prices')->count();

        // Return true or false
        return $count > 0 ? true : false;
    }

    public static function updatedUnreadMessage($customerId, $status = 0)
    {
        // if reply is not auto reply or the suggested reply from chat then only update status
        if(!empty($status) && !in_array($status, self::AUTO_REPLY_CHAT)) {
            self::where('customer_id', $customerId)->where("status", 0)->update(['status' => 5]);
        }

    }

    public function taskUser()
    {
        return $this->hasOne("\App\User","id","user_id");
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    //START - Purpose : Add relationship - DEVTASK-4203
    public function chatmsg()
    {
        return $this->hasOne("\App\ChatMessage","user_id","user_id")->latest();
    }
    //END - DEVTASK-4203

    public function sendTaskUsername()
    {
        $name = "";

        if($this->erp_user > 0) {
            $taskUser = $this->taskUser;
            if($taskUser) {
                $name = $taskUser->name;
            }
        }

        return $name; 
    }

    public function sendername()
    {
        $name = "";

        if($this->user_id > 0) {
            $taskUser = $this->user;
            if($taskUser) {
                $name = $taskUser->name;
            }
        }

        return $name; 
    }

    public static function pendingQueueGroupList($params = [])
    {
        return self::where($params)->where("group_id",">",0)
        ->pluck("group_id","group_id")
        ->toArray();
    }
    public static function pendingQueueLeadList($params = [])
    {
        return self::where($params)->where("lead_id",">",0)
        ->pluck("lead_id","lead_id")
        ->toArray();
    }
    public static function getQueueLimit()
    {
        $limit  = \App\Setting::where("name","is_queue_sending_limit")->first();
        
        return ($limit) ? json_decode($limit->val,true) : [];
    }

    public static function getQueueTime()
    {
        $limit  = \App\Setting::where("name","is_queue_sending_time")->first();
        
        return ($limit) ? json_decode($limit->val,true) : [];
    }

    public static function getStartTime()
    {
        $limit  = \App\Setting::where("name","is_queue_send_start_time")->first();
        
        return ($limit) ? $limit->val : 0;
    }

    public static function getEndTime()
    {
        $limit  = \App\Setting::where("name","is_queue_send_end_time")->first();
        
        return ($limit) ? $limit->val : 0;
    }

    public static function getSupplierForwardTo()
    {
        $no  = \App\Setting::where("name","supplier_forward_message_no")->first();
        
        return ($no) ? $no->val : 0;
    }

    public function chatBotReply()
    {
        return $this->hasOne("\App\ChatBotReply","chat_id", "id");
    }

    public function chatBotReplychat()
    {
        return $this->hasOne(ChatbotReply::class,"replied_chat_id", "id");
    }

    public function chatBotReplychatlatest()
    {
        return $this->hasMany(ChatbotReply::class,"replied_chat_id", "id");
    }

    public function suggestion()
    {
        return $this->hasOne("App\SuggestedProduct","chat_message_id","id");
    }

    public static function getLastImgProductId($customerId)
    {
        return \App\ChatMessage::where("customer_id", $customerId)
        ->whereNull("chat_messages.number")
        ->whereNotIn("status",array_merge(self::AUTO_REPLY_CHAT,[2]))
        ->select(["chat_messages.*"])
        ->orderBy("chat_messages.created_at", "desc")
        ->first();
    }

    /**
     *  Get information by ids
     *  @param []
     *  @return Mixed
     */

    public static function getInfoByIds($ids, $fields = ["*"], $toArray = false)
    {
        $list = self::whereIn("id",$ids)->select($fields)->get();

        if($toArray) {
            $list = $list->toArray();
        }

        return $list;
    }

    /**
     *  Get information by ids
     *  @param []
     *  @return Mixed
     */

    public static function getGroupImagesByIds($ids,$toArray = false)
    {
        $list = \DB::table("mediables")
        ->where("mediable_type",self::class)
        ->whereIn("mediable_id",$ids)
        ->groupBy("mediable_id")
        ->select(["mediable_id",\DB::raw("group_concat(media_id) as image_ids")])
        ->get();

        if($toArray) {
            $list = $list->toArray();
        }

        return $list;
    }


     /**
     *  Get information by ids
     *  @param []
     *  @return Mixed
     */

    public static function getInfoByObjectIds($field, $ids, $fields = ["*"], $params = [], $toArray = false)
    {
        unset($_GET["page"]);
        $list = self::whereIn($field,$ids)->where(function ($q) {
            $q->whereNull("group_id")->orWhere("group_id", 0);
        })->whereNotIn("status", self::EXECLUDE_AUTO_CHAT);

        if(!empty($params["previous"]) && $params["previous"] == true && !empty($params["lastMsg"]) && is_numeric($params["lastMsg"])) {
            $list = $list->where("id","<",$params["lastMsg"]);            
        }

        if(!empty($params["next"]) && $params["next"] == true && !empty($params["lastMsg"])) {
            $list = $list->where("id",">",$params["lastMsg"]);            
        }

        $list =  $list->orderBy("created_at","desc")->select($fields)->paginate(10);

        if($toArray) {
            $list = $list->items();
        }

        return $list;
    }


    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }


    /**
    * Check send lead price
    * $customer object
    * customer
    **/
    public function sendLeadPrice($customer)
    {
        $media = $this->getMedia(config('constants.attach_image_tag'))->first();
        if($media) {
            \Log::channel('customer')->info("Media image found for customer id : ". $customer->id);
           $mediable = \DB::table("mediables")->where("media_id",$media->id)
           ->where("mediable_type",\App\Product::class)
           ->first();
           if(!empty($mediable)) {
            \Log::channel('customer')->info("Mediable for customer id : ". $customer->id);
                try{
                    app('App\Http\Controllers\CustomerController')->dispatchBroadSendPrice($customer, array_unique([$mediable->mediable_id]));
                }catch(\Exception $e) {
                    \Log::channel('customer')->info($e->getMessage());
                }
           }
        }
    }

    /**
    * Check send lead dimention
    * $customer object
    * customer
    **/
    public function sendLeadDimention($customer)
    {
        $media = $this->getMedia(config('constants.attach_image_tag'))->first();
        if($media) {
            \Log::channel('customer')->info("Media image found for customer id : ". $customer->id);
           $mediable = \DB::table("mediables")->where("media_id",$media->id)
           ->where("mediable_type",\App\Product::class)
           ->first();
           if(!empty($mediable)) {
            \Log::channel('customer')->info("Mediable for customer id : ". $customer->id);
                try{
                    app('App\Http\Controllers\CustomerController')->dispatchBroadSendPrice($customer, array_unique([$mediable->mediable_id]),true);
                }catch(\Exception $e) {
                    \Log::channel('customer')->info($e->getMessage());
                }
           }
        }
    }


    public function getRecieverUsername() {
        return $this->hasOne(\App\InstagramUsersList::class, 'id', 'instagram_user_id');

    }

    public function getSenderUsername() {
        return $this->hasOne(\App\Account::class, 'id', 'account_id');

    }
    


}
