<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorPayment extends Model
{
	     /**
     * @var string
      * @SWG\Property(property="vendor_id",type="integer")
      * @SWG\Property(property="payment_date",type="datetime")
      * @SWG\Property(property="paid_date",type="datetime")
      * @SWG\Property(property="payable_amount",type="float")
      * @SWG\Property(property="paid_amount",type="float")
      * @SWG\Property(property="service_provided",type="string")
      * @SWG\Property(property="work_hour",type="string")
      * @SWG\Property(property="description",type="string")
      * @SWG\Property(property="other",type="string")
      * @SWG\Property(property="status",type="string")
      * @SWG\Property(property="user_id",type="integer")
      * @SWG\Property(property="updated_by",type="integer")
      * @SWG\Property(property="currency",type="string")
     */
    use SoftDeletes;
    protected $fillable = ['vendor_id', 'payment_date', 'paid_date', 'payable_amount', 'paid_amount', 'service_provided', 'module', 'work_hour', 'description', 'other', 'status', 'user_id', 'updated_byupdated_by', 'currency'];
    protected $dates = ['deleted_at'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
