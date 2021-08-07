<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class CallBusyMessage extends Model {

    /**
     * @var string
      * @SWG\Property(property="lead_id",type="integer")
     * @SWG\Property(property="twilio_call_sid",type="string")
     * @SWG\Property(property="caller_sid",type="string")
     * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="recording_url",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="call_busy_messages",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
    
     */
    protected $fillable = ['lead_id', 'twilio_call_sid', 'caller_sid', 'message', 'recording_url', 'status'];
 
    protected $table = "call_busy_messages";

    protected $dates = ['created_atcreated_at', 'updated_at'];

    /**
     * Function to insert large amount of data
     * @param type $data
     * @return $result
     */
    public static function bulkInsert($data) {
        CallBusyMessage::insert($data);
    }
    
    /**
     * Function to check Twilio Sid 
     * @param string $sId twilio sid
     */
     public static function checkSidAlreadyExist($sId){
         return CallBusyMessage::where('caller_sid', '=', $sId)->first();
     }

}
