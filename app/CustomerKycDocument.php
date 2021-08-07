<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class CustomerKycDocument extends Model
{

      /**
     * @var string
   * @SWG\Property(property="customer_id",type="integer")
   * @SWG\Property(property="type",type="string")
   * @SWG\Property(property="url",type="string")
   * @SWG\Property(property="path",type="string")
   * @SWG\Property(property="created_at",type="datetime")
   * @SWG\Property(property="updated_at",type="datetime")
   

     */
    const TYPE = [
        "Unknown"
    ];

    protected $fillable = [
        'customer_id','type','url','path','created_at','updated_at'
    ];

    public function customer()
    {
        return $this->hasOne(App\Customer::class, "id", "customer_id");
    }
}
