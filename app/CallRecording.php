<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class CallRecording extends Model
{
    //
    /**
     * @var string
      * @SWG\Property(property="lead_id",type="integer")
     * @SWG\Property(property="order_id",type="integer")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="recording_url",type="string")
     * @SWG\Property(property="twilio_call_sid",type="string")
     * @SWG\Property(property="customer_number",type="integer")
     * @SWG\Property(property="callsid",type="sting")
     * @SWG\Property(property="message",type="sting")
     * @SWG\Property(property="call_recordings",type="sting")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = ['lead_id', 'order_id', 'customer_id', 'recording_url', 'twilio_call_sid' , 'customer_number','callsid', 'message'];
    /**
     * @var string
     * @SWG\Property(enum={"model_id", "model_type", "name", "phone", "whatsapp_number", "address", "email"})
     */
	protected $table ="call_recordings";
	/**
     * @var string
     * @SWG\Property(enum={"model_id", "model_type", "name", "phone", "whatsapp_number", "address", "email"})
     */
	protected $dates = ['created_at', 'updated_at'];
}
