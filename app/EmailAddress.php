<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\StoreWebsite;
use Illuminate\Database\Eloquent\Model;

class EmailAddress extends Model
{
      /**
     * @var string
     * @SWG\Property(property="from_name",type="string")
     * @SWG\Property(property="from_address",type="string")
     * @SWG\Property(property="driver",type="string")
     * @SWG\Property(property="host",type="string")
     * @SWG\Property(property="port",type="string")
     * @SWG\Property(property="encryption",type="string")
     * @SWG\Property(property="username",type="string")
     * @SWG\Property(property="template",type="string")
     * @SWG\Property(property="additional_data",type="string")
     * @SWG\Property(property="password",type="datetime")
     * @SWG\Property(property="store_website_id",type="integer")
     */
  
  protected $fillable = [
    'from_name',
    'from_address',
    'driver',
    'host',
    'port',
    'encryption',
    'username',
    'password',
    'store_website_id',
    'recovery_phone',
    'recovery_email',
  ];
  
   public function website()
    {
       return $this->hasOne(StoreWebsite::class,'id','store_website_id');
    }

    public function email_run_history()
    {
       return $this->hasMany(EmailRunHistories::class,'email_address_id','id');
    }

    public function history_last_message()
    {
       return $this->hasOne(EmailRunHistories::class,'email_address_id','id')->latest();
    }


}
