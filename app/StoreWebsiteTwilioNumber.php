<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteTwilioNumber extends Model
{
		  /**
     * @var string
    
      * @SWG\Property(property="store_website_twilio_numbers",type="string")
      * @SWG\Property(property="store_website_id",type="integer")
      * @SWG\Property(property="twilio_active_number_id",type="integer")
     * @SWG\Property(property="message_available",type="string")
     * @SWG\Property(property="message_not_available",type="string")
     * @SWG\Property(property="message_busy",type="string")
     */
    protected $table = 'store_website_twilio_numbers';

    protected $fillable = [
        'store_website_id', 'twilio_active_number_id','message_available','message_not_available','message_busy'
    ];

    public function store_website()
    {
        return $this->hasOne("\App\StoreWebsite","id", "store_website_id");
    }

}
