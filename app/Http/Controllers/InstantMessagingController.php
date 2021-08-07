<?php

namespace App\Http\Controllers;

use \Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\ChatMessage;
use App\Customer;
use App\ImQueue;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;

class InstantMessagingController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/{client}/{numberFrom}/get-im",
     *   tags={"Instant Messaging"},
     *   summary="Get Instant Message",
     *   operationId="get-instant-msg",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    /**
     * Send Message Queue Result For API Call
     *
     * @param $client
     * @param $numberFrom
     * @return void
     */
    public function getMessage($client, $numberFrom, Request $request)
    {
        if($client == 'whatsapp'){
            // Get client class
            $clientClass = '\\App\\Marketing\\' . ucfirst($client) . 'Config';

            // Check credentials
            $whatsappConfig = $clientClass::where('last_name', $numberFrom)->first();
        }else{
            $clientClass = '\\App\\Account';

            // Check credentials
            $whatsappConfig = $clientClass::where('last_name', $numberFrom)->first();
            
        }

        

        //Nothing found
         if ($whatsappConfig == null || Crypt::decrypt($whatsappConfig->password) != $request->token) {
             $message = ['error' => 'Invalid token'];
             return json_encode($message, 400);
         }

        // Hard coded 15 minute gap
        $sentLast = ImQueue::where('number_from', $numberFrom)->max('sent_at');
        if ($sentLast != null) {
            $sentLast = strtotime($sentLast);
        }
        

        if ( $sentLast > time() - (3600 / $whatsappConfig->frequency) ) {
            $message = ['error' => 'Awaiting forced time gap'];
            return json_encode($message, 400);
        }

        //Check if send time and end time is not equal to 0 or null
        if($whatsappConfig->send_start != '' || $whatsappConfig->send_end != ''){
            $send_start = $whatsappConfig->send_start;
            $send_end = $whatsappConfig->send_end;
        }else{
            $send_start = 4;
            $send_end = 19;
        }


        // Only send at certain times
        if ((date('H') < $send_start || date('H') > $send_end) && $numberFrom != '971504752911') {
            $message = ['error' => 'Sending at this hour is not allowed'];
            return json_encode($message, 400);
        }

        // Get next messsage from queue
        $queue = ImQueue::select('id', 'text', 'image', 'number_to')
            ->where('im_client', $client)
            ->where('number_from', $numberFrom)
            ->whereNull('sent_at')
            ->where(function ($query) {
                $query->where('send_after', '<', Carbon::now())
                    ->orWhereNull('send_after');
            })
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->first();

        // Return error if no message is found
        if ($queue == null && $numberFrom != '971504752911') {
            $message = ['error' => 'The queue is empty'];
            return json_encode($message, 400);
        } elseif ($queue == null && $numberFrom == '971504752911') {
            $queue = new \stdClass();
            $queue->id = rand(1000000, 9999999);
            $queue->number_to = '31629987287';
            $queue->text = 'This is a random message id ' . rand(1000000, 9999999);
            $queue->image = null;
            $queue->filename = null;
        }

        // Set output
        if (isset($queue->image) && $queue->image != null) {
            $output = ['queueNumber' => $queue->id, 'phone' => $queue->number_to, 'body' => $queue->image, 'filename' => urlencode(substr($queue->image, strrpos($queue->image, '/') + 1)), 'caption' => $queue->text];
//            $output = ['queueNumber' => $queue->id, 'phone' => '31629987287', 'body' => $queue->image, 'filename' => urlencode(substr($queue->image, strrpos($queue->image, '/') + 1)), 'caption' => $queue->text];
        } else {
            $output = ['queueNumber' => $queue->id, 'phone' => $queue->number_to, 'body' => $queue->text];
//            $output = ['queueNumber' => $queue->id, 'phone' => '31629987287', 'body' => $queue->text];
        }

        // Return output
        if (isset($output)) {
            return json_encode($output, 200);
        } else {
            return json_encode(['error' => 'The queue is empty'], 400);
        }
    }

    /**
     * @SWG\Post(
     *   path="/{client}/{numberFrom}/webhook",
     *   tags={"Instant Messaging"},
     *   summary="post process Webhook",
     *   operationId="post-process-webhook",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function processWebhook(Request $request)
    {
        // Get raw JSON
        $receivedJson = json_decode($request->getContent());

        // Valid json?
        if ($receivedJson !== null && is_object($receivedJson)) {

            if(isset($receivedJson->queueNumber)){
                // Get message from queue
                $imQueue = ImQueue::where(['id' => $receivedJson->queueNumber])->first();

            // message found in the queue
            //if ($imQueue !== null && empty($imQueue->sent_at)) {
                if ($imQueue !== null) {
                // Update status in im_queues
                    $imQueue->sent_at = $receivedJson->sent == true ? date('Y-m-d H:i:s', Carbon::now()->timestamp) : '2002-02-02 02:02:02';
                    $imQueue->save();

                // Find customer for this number
                    $customer = Customer::where('phone', '=', $imQueue->number_to)->first();

                // Number times -1 if sent is false
                    if ($receivedJson->sent == false) {
                        $customer->phone = (int)$customer->phone * -1;
                        $customer->save();
                    }

                // Add to chat_messages if we have a customer
                    $params = [
                        'unique_id' => $receivedJson->id,
                        'message' => $imQueue->text,
                        'customer_id' => $customer != null ? $customer->id : null,
                        'approved' => 1,
                        'status' => 8,
                        'is_delivered' => $receivedJson->sent == true ? 1 : 0
                    ];

                // Create chat message
                    $chatMessage = ChatMessage::create($params);

                // TODO: Attach images to chatMessage
                }
            }else{
                try {
                    if(is_array($receivedJson->messages)){
                        
                        //Message 
                        $detials = $receivedJson->messages[0];
                        
                        //Getting Number 
                        $receivedMessageFrom = $detials->chatId;
                        
                        //Remove @c.us
                        $receivedMessageFrom = str_replace('@c.us', '', $receivedMessageFrom);
                        
                        //Getting Customer
                        $customer = Customer::where('phone',$receivedMessageFrom)->first();

                        //Getting Last Im queue message
                        $imQueue = ImQueue::where('number_to',$customer->phone)->whereNotNull('sent_at')->latest()->first();

                        $body = $detials->body[0];
                        
                        //Message Body
                        $messages = $body->details;

                        foreach ($messages as $message) {
                            
                            if($message->Images == ''){
                                // Add to chat_messages if we have a customer
                                $params = [
                                    'unique_id' => $detials->chatId,
                                    'message' => $message->text,
                                    'customer_id' => $customer != null ? $customer->id : null,
                                    'approved' => 1,
                                    'status' => 3,
                                ];
                                
                                // Create chat message
                                $chatMessage = ChatMessage::create($params);
                                
                            }else{
                                //Getting Image IN BASE64 Encoded
                                $image = $message->Images;  
                                $image = str_replace('data:image/png;base64,', '', $image);
                                $image = str_replace(' ', '+', $image);
                                $imageName = str_random(10).'.'.'png';
                                //Image
                                $image = base64_decode($image);
                                
                                $params = [
                                    'unique_id' => $detials->chatId,
                                    'message' => '',
                                    'customer_id' => $customer != null ? $customer->id : null,
                                    'approved' => 1,
                                    'status' => 3,
                                ];

                                // Create chat message
                                $chatMessage = ChatMessage::create($params);
                                
                                // Upload media
                                $media = MediaUploader::fromString($image)->useFilename(uniqid(true, true))->toDisk('uploads')->toDirectory('chat-messages/' . floor($chatMessage->id / config('constants.image_per_folder')))->upload();
                                $chatMessage->attachMedia($media, config('constants.media_tags'));
                            }
                            

                        }


                    }
                    

                } catch (\Exception $e) {
                    
                }
                
            }
            
        }

        // Return json ack
        return json_encode('ack', 200);
    }

    /**
     * @SWG\Get(
     *   path="/{client}/{numberFrom}/im-status-update",
     *   tags={"Instant Messaging"},
     *   summary="update phone Status",
     *   operationId="update-phone-status",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function updatePhoneStatus($client, $numberFrom, Request $request)
    {
        // Get client class
        $clientClass = '\\App\\Marketing\\' . ucfirst($client) . 'Config';

        // Check credentials
        $whatsappConfig = $clientClass::where('number', $numberFrom)->first();


        // // Nothing found
        if ($whatsappConfig == null || $whatsappConfig->token != $request->token) {
            $message = ['error' => 'Invalid token'];
            return json_encode($message, 400);
        }

        //Adding Last Login
        $whatsappConfig->last_online = Carbon::now();
        if($request->status == 1){
            $whatsappConfig->is_connected = 1;
        }

        if($request->status == 0){
            $whatsappConfig->is_connected = 0;
        }


        $whatsappConfig->status = $request->status;


        //Updating Whats App Config details
        $whatsappConfig->update();

        $output = ['phone' => $numberFrom, 'body' => 'SuccesFully Updated Status'];

        return json_encode($output, 200);

    }


}
