<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use App\ChatMessage;
use App\Customer;
use App\ImQueue;
use App\Marketing\WhatsappConfig;
use Propaganistas\LaravelPhone\PhoneNumber;
use Carbon\Carbon;
use App\Setting;

class InstantMessagingHelper
{

    /**
     * Save Messages For Send Whats App
     *
     * @param $numberTo , $text , $image , $priority, $numberFrom , $client , sendAfter
     * @return void
     * @static
     */
    public static function sendInstantMessage($numberTo, $text = null, $image = null, $priority = null, $numberFrom = null, $client = null, $sendAfter = null)
    {
        // Check for image and text
        if ($image != null || $text != null) {
            // Check if there is a number
            if ($numberTo == '' || $numberTo == null) {
                return redirect()->back()->withErrors('Please provide a number to send the message to');
            }

            //default number for send message
            if ($numberFrom == null) {
                $numberFrom = env('DEFAULT_SEND_NUMBER');
            }

            //setting default client name
            if ($client == null) {
                $client = 'whatsapp';
            }

            //saving queue
            $queue = new ImQueue();
            $queue->im_client = $client;
            $queue->number_to = $numberTo;
            $queue->number_from = $numberFrom;

            //getting image or text
            if ($image != null && $text != null) {
                $queue->image = self::encodeImage($text, $image);
            } elseif ($image != null) {
                $queue->image = self::encodeImage('', $image);
            } else {
                $queue->text = $text;
            }

            //setting priority
            if ($priority == null) {
                $queue->priority = 10;
            } else {
                $queue->priority = $priority;
            }

            //setting send after
            $queue->send_after = $sendAfter;
            $queue->save();

            //returning response
            return redirect()->back()->withSuccess('Mesage Saved');
        } else {
            //returning error in response
            return redirect()->back()->withErrors('Please Provide with image link or message');
        }
    }

    public static function scheduleMessage($numberTo, $numberFrom, $message = null, $image = null, $priority = 1, $sendAfter = null , $broadcastId = null)
    {

        // Check last message to this number - TODO: This works for now, but not once we start scheduling messages from the system
        $maxTime = ImQueue::select(DB::raw('IF(MAX(send_after)>MAX(sent_at), MAX(send_after), MAX(sent_at)) AS maxTime'))->where('number_from', $numberFrom)->first();

        //Getting WhatsApp Config
        $whatappConfig = WhatsappConfig::where('number', $numberFrom)->first();
        if ($whatappConfig == '' && $whatappConfig == null) {
            return false;
        }

        $numberTo = self::customerPhoneCheck($numberTo,1);
        if($numberTo == false){
            return false;
        }

        // Convert maxTime to unixtime
        $maxTime = strtotime($maxTime->maxTime);

        // Add interval
        $maxTime = $maxTime + (3600 / $whatappConfig->frequency);
        
        // Check if it's in the future
        if ($maxTime < time()) {
            $maxTime = time();
        }

        if (empty($sendAfter)) {
            // Check for decent times
            if (date('H', $maxTime) < $whatappConfig->send_start) {
                $sendAfter = date('Y-m-d 0' . $whatappConfig->send_start . ':00:00', $maxTime);
            } elseif (date('H', $maxTime) > $whatappConfig->send_end) {
                $sendAfter = date('Y-m-d 0' . $whatappConfig->send_start . ':00:00', $maxTime + 86400);
            } else {
                $sendAfter = date('Y-m-d H:i:s', $maxTime);
            }
        }

        // Insert message into queue
        $imQueue = new ImQueue();
        $imQueue->im_client = 'whatsapp';
        $imQueue->number_to = $numberTo;
        $imQueue->number_from = $numberFrom;
        $imQueue->text = $message;
        $imQueue->image = $image;
        $imQueue->priority = $priority;
        $imQueue->send_after = $sendAfter;
        $imQueue->broadcast_id = $broadcastId;
        $imQueue->marketing_message_type_id = 2;
        return $imQueue->save();
    }


    /**
     * Return Json Encode URL
     *
     * @param $text , $image
     * @return jsonencoded image
     */
    public function encodeImage($text = null, $image)
    {
        // Get filename from image URL
        $filename = basename($image);

        // Get caption from text
        if ($text == null) {
            $image = array('body' => $image, 'filename' => $filename, 'caption' => '');
        } else {
            $image = array('body' => $image, 'filename' => $filename, 'caption' => $text);
        }

        // Return json encoded array
        return json_encode($image);
    }

    public static function replaceTags(Customer $customer, $message)
    {
        // Set tags to replace
        $fields = [
            '[[NAME]]' => $customer->name,
            '[[CITY]]' => $customer->city,
            '[[EMAIL]]' => $customer->email,
            '[[PHONE]]' => $customer->phone,
            '[[PINCODE]]' => $customer->pincode,
            '[[WHATSAPP_NUMBER]]' => $customer->whatsapp_number,
            '[[SHOESIZE]]' => $customer->shoe_size,
            '[[CLOTHINGSIZE]]' => $customer->clothing_size
        ];

        // Get replacement tags from message
        preg_match_all("/\[[^\]]*\]]/", $message, $matches);
        $values = $matches[ 0 ];

        // Replace all tags
        foreach ($values as $value) {
            if (isset($fields[ $value ])) {
                $message = str_replace($value, $fields[ $value ], $message);
            }
        }

        // Return message
        return $message;
    }
    /**
     * Check if the number is correct
     *
     * @var int
     */
    public static function customerPhoneCheck($phone , $type)
    {
        $customer = Customer::where('phone',$phone)->first();
        
        //Check customer country code is null and update it By IN
        if($customer->country == null){
            $customer->country = 'IN';
            $customer->update();
        }
        //Check if customer code is INDIA update it by IN
        $country = strtolower($customer->country);
        if($country == 'india'){
            $customer->country = 'IN';
            $customer->update();
        }

        //Check if phone is empty
        if($customer->phone == null){
            return false;
        }
        
        
        try{
            $countries = \Config('countries');
            $country = $countries[$customer->country];
            $code = $country['code'];
            
            //getting first 3 digit from number 
            $length = strlen($country['code']);
            $result = substr($customer->phone, 0, $length);
            
            if($result != $code){
                if($type == 1){
                    $customer->broadcast_number = NULL;
                    if($customer->phone != 0){
                    $customer->phone = (int)$customer->phone * -1;
                    }
                    $customer->update();
                    \Log::channel('customer')->debug('Customer Name :' . $customer->name . "\n Customer ID: " . $customer->id . "\nPhone Number Not Valid:" . $customer->phone . "\n");
                }   
                    return false;
                
            }

        }catch(\Exception $e){
                if($type == 1){
                $customer->broadcast_number = NULL;
                if($customer->phone != 0){
                    $customer->phone = (int)$customer->phone * -1;
                }
                $customer->update();
                \Log::channel('customer')->debug('Customer Name :' . $customer->name . "\n Customer ID: " . $customer->id . "\nPhone Number Not Valid:" . $customer->phone . "\n");
                }
                return false;
        }
        
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $swissNumberProto = $phoneUtil->parse($customer->phone, $customer->country);
            $isValid = $phoneUtil->isValidNumber($swissNumberProto);
            if($isValid == false){
                if($type == 1){
                $customer->broadcast_number = NULL;
                if($customer->phone != 0){
                    $customer->phone = (int)$customer->phone * -1;
                }
                $customer->update();
                \Log::channel('customer')->debug('Customer Name :' . $customer->name . "\n Customer ID: " . $customer->id . "\nPhone Number Not Valid:" . $customer->phone . "\n");
                }
                return false;
            }
        } catch (\libphonenumber\NumberParseException $e) {
            if($type == 1){
            $customer->broadcast_number = NULL;
            if($customer->phone != 0){
                $customer->phone = (int)$customer->phone * -1;
            }
            $customer->update();
            \Log::channel('customer')->debug('Customer Name :' . $customer->name . "\n Customer ID: " . $customer->id . "\nPhone Number Not Valid:" . $customer->phone . "\n");
            }
            return false;
        }

        return $customer->phone;
    }

    public static function broadcastSendingTimeCheck($time){
        
        $now = $time;

        //Getting Start and end time from setting
        // $startTime = Setting::where('name','start_time')->first();
        // $endTime = Setting::where('name','end_time')->first();
        
        $morning = Carbon::create($now->year, $now->month, $now->day, 8, 0, 0);
        $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
        
        if (!$now->between($morning, $evening, true)) {
            $now->addDay();
            $now = Carbon::create($now->year, $now->month, $now->day, 8, 0, 0);
        }
        
        return $now;
    }
}