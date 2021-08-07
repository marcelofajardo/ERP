<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class VendorProduct extends Model
{

		     /**
     * @var string
      * @SWG\Property(property="vendor_id",type="integer")
      * @SWG\Property(property="date_of_order",type="datetime")
      * @SWG\Property(property="name",type="string")
      * @SWG\Property(property="qty",type="float")
      * @SWG\Property(property="price",type="float")
      * @SWG\Property(property="payment_terms",type="string")
      * @SWG\Property(property="recurring_type",type="string")
      * @SWG\Property(property="delivery_date",type="datetime")
      * @SWG\Property(property="received_by",type="integer")
      * @SWG\Property(property="approved_by",type="integer")
      * @SWG\Property(property="payment_details",type="string")
  
     */
  use Mediable;

  protected $fillable = [
    'vendor_id', 'date_of_order', 'name', 'qty', 'price', 'payment_terms', 'recurring_type', 'delivery_date', 'received_by', 'approved_by', 'payment_details'
  ];

  public function vendor()
  {
    return $this->belongsTo('App\Vendor');
  }
}
