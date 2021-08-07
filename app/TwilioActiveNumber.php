<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class TwilioActiveNumber extends Model
{
            /**
     * @var string
      * @SWG\Property(property="twilio_credential_id",type="integer")
      * @SWG\Property(property="sid",type="integer")
      * @SWG\Property(property="account_sid",type="integer")
      * @SWG\Property(property="friendly_name",type="string")
      * @SWG\Property(property="phone_number",type="string")
      * @SWG\Property(property="voice_url",type="string")
      * @SWG\Property(property="date_created",type="string")
      * @SWG\Property(property="date_updated",type="datetime")
      * @SWG\Property(property="sms_url",type="string")
      * @SWG\Property(property="voice_receive_mode",type="string")
      * @SWG\Property(property="api_version",type="string")
      * @SWG\Property(property="voice_application_sid",type="integer")
      * @SWG\Property(property="sms_application_sid",type="integer")
      * @SWG\Property(property="trunk_sid",type="integer")
      * @SWG\Property(property="emergency_address_sid",type="integer")
      * @SWG\Property(property="address_sid",type="integer")
      * @SWG\Property(property="identity_sid",type="integer")
      * @SWG\Property(property="bundle_sid",type="integer")
      * @SWG\Property(property="uri",type="string")
      * @SWG\Property(property="status",type="string")
     */
    protected $table = 'twilio_active_numbers';

    protected $fillable = [ 'twilio_credential_id','sid','account_sid','friendly_name','phone_number','voice_url','date_created','date_updated',
                            'sms_url','voice_receive_mode','api_version','voice_application_sid','sms_application_sid',
                            'trunk_sid','emergency_status','emergency_address_sid','address_sid','identity_sid','bundle_sid',
                            'uri','status'
                            ];

    public function assigned_stores()
    {
        return $this->hasOne('\App\StoreWebsiteTwilioNumber','twilio_active_number_id');
    }

    public function forwarded()
    {
        return $this->hasOne('App\TwilioCallForwarding','twilio_number_sid','sid');
    }

}
