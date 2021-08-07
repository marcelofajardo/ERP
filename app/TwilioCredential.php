<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class TwilioCredential extends Model
{
	       /**
     * @var string
      * @SWG\Property(property="twilio_credentials",type="string")
      * @SWG\Property(property="account_id",type="integer")
      * @SWG\Property(property="twilio_email",type="string")
      * @SWG\Property(property="auth_token",type="string")
          */
    protected $table = 'twilio_credentials';

    protected $fillable = ['twilio_email', 'account_id', 'auth_token'];

    public function numbers()
    {
        return $this->hasMany('App\TwilioActiveNumber', 'account_sid', 'account_id');
    }

}
