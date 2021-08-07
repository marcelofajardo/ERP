<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;
use App\Customer;

class CustomerLiveChat extends Model
{
       /**
     * @var string
   * @SWG\Property(property="customer_id",type="integer")
   * @SWG\Property(property="thread",type="string")
   * @SWG\Property(property="status",type="string")
   * @SWG\Property(property="seen",type="string")
     */
    protected $fillable = ['customer_id','thread','status','seen'];


    public function customer(){
        return $this->hasOne(Customer::class,'id' , 'customer_id');
    }
}
