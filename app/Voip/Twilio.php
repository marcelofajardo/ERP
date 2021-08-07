<?php
/**
 * Class Twilio Model | app/Voip/Twilio.php
 * Twilio integration for VOIP purpose using Twilio's Voice REST API
 *
 * @package  Twillio
 * @subpackage Jwt Token
 * @filesource required php 7 as this file contains tokenizer extension which was not stable prior to this version
 * @see https://www.twilio.com/docs/voice/quickstart/php
 * @author   sololux <sololux@gmail.com>
 */

namespace App\Voip;

use Twilio\Jwt\ClientToken;
use Twilio\Twiml;
use Twilio\Rest\Client;
use App\Setting;
use App\CallBusyMessage;
use App\Customer;
use Illuminate\Database\Eloquent\Model;

class Twilio extends Model
{
    /**
     * Getting call records
     * @return void
     * @Rest\Post("twilio/missedCallStatus")
     *
     * @uses Config
     * @uses Twilio
     * @uses Customer
     * @uses CallBusyMessage
     */
    public function missedCallStatus()
    {
        $sid = \Config::get("twilio.account_sid");
        $token = \Config::get("twilio.auth_token");
        $twilio = new Client($sid, $token);
        //Get the total number of records from CallBusyMessage which already saved
        $totalSavedCallLogs = CallBusyMessage::count();
        // Set the number of records need to fetch. Total record + 100
        $noOfRecords = $totalSavedCallLogs + 50;
        // Getting all the call records
        $calls = $twilio->calls->read(array(), $noOfRecords);
        $data = [];
        $i = 0;
        foreach ($calls as $record) {
            // Check if Sid id already there
            $checkSidExists = CallBusyMessage::checkSidAlreadyExist($record->sid);
            if ($checkSidExists == null) {
                // Api call to get the recordings with caller sid
                $recordings = $twilio->recordings
                    ->read(array(
                        "callSid" => $record->sid
                    ),
                        1
                    );
                $apiVersion = $record->apiVersion;
                // If recording array is not empty then get the recording url
                if (!empty($recordings)) {
                    foreach ($recordings as $recording) {
                        $recordingId = $recording->sid;
                        $recordingLink = "https://api.twilio.com/" . $apiVersion . "/Accounts/" . $sid . "/Recordings/" . $recordingId . ".mp3";
                        $data[ $i ][ 'recording_url' ] = $recordingLink;
                    }
                } else {
                    $data[ $i ][ 'recording_url' ] = "";
                }
                // Getting the message based on call nos and date
                $messages = $twilio->messages
                    ->read(array(
                        "dateSent" => $record->dateCreated,
                        "from" => $record->from,
                        "to" => $record->to
                    ),
                        1
                    );
                // Getting the message recording id
                if (!empty($messages)) {
                    foreach ($messages as $msg) {
                        $data[ $i ][ 'message' ] = $msg->sid;
                    }
                } else {
                    $data[ $i ][ 'message' ] = "";
                }
                $data[ $i ][ 'twilio_call_sid' ] = $record->from;
                $data[ $i ][ 'caller_sid' ] = $record->sid;
                $data[ $i ][ 'created_at' ] = $record->startTime;
                $data[ $i ][ 'updated_at' ] = $record->endTime;
                // Checking the status if call is completed or no-answer
                if ('completed' == $record->status) {
                    $data[ $i ][ 'status' ] = 1;
                } else {
                    $data[ $i ][ 'status' ] = 0;
                }
                $data[ $i ][ 'lead_id' ] = "";
                // Get the lead id from phone number in customer table
                if (($record->from)) {
                    # Removing the country code from phone number
                    $formatted_phone = str_replace('+91', '', $record->from);
                    // Getting customer data based on phone no.
                    $customerData = Customer::where('phone', 'LIKE', "%$formatted_phone%")->get()->toArray();
                    if (!empty($customerData)) {
                        $customerId = $customerData[ 0 ][ 'id' ];
                        $customerName = $customerData[ 0 ][ 'name' ];
                        if (!empty($customerData[ 0 ][ 'lead' ])) {
                            $leadId = $customerData[ 0 ][ 'lead' ][ 'id' ];
                            $data[ $i ][ 'lead_id' ] = $leadId;
                        }
                    }
                }
                $i++;
            }
        }
        // Saving the data in CallBusyMessage
        $insertData = CallBusyMessage::bulkInsert($data);
        exit('This data inserted in db..Now, you can check missed calls screen');
    }
}
