<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\Marketing\MarketingPlatform;

class CustomerMarketingPlatform extends Model
{
	      /**
     * @var string
   * @SWG\Property(property="customer_id",type="integer")
   * @SWG\Property(property="marketing_platform_id",type="integer")
   * @SWG\Property(property="active",type="string")
   * @SWG\Property(property="remark",type="string")
   * @SWG\Property(property="user_name",type="string")
     */
	protected $fillable = ['customer_id','marketing_platform_id','active','remark','user_name'];
    public function customer()
    {
    	return $this->belongsTo(Customer::class,'id','customer_id');
    }

    public function marketing()
    {
    	return $this->hasOne(MarketingPlatform::class,'id','marketing_platform_id');
    }
}
