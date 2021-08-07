<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Waybill extends Model
{

       /**
     * @var string
      * @SWG\Property(property="order_id",type="integer")
      * @SWG\Property(property="customer_id",type="integer")
      * @SWG\Property(property="waybills",type="string")
      * @SWG\Property(property="box_length",type="string")
      * @SWG\Property(property="box_width",type="string")
      * @SWG\Property(property="box_height",type="string")
      * @SWG\Property(property="volume_weight",type="string")
      * @SWG\Property(property="package_slip",type="string")
      * @SWG\Property(property="pickup_date",type="datetime")
       * @SWG\Property(property="from_customer_id",type="integer")
      * @SWG\Property(property="from_customer_name",type="string")
      * @SWG\Property(property="from_city",type="string")
      * @SWG\Property(property="from_country_code",type="string")
      * @SWG\Property(property="from_customer_phone",type="string")
      * @SWG\Property(property="from_customer_address_1",type="string")
      * @SWG\Property(property="from_customer_address_2",type="string")
      * @SWG\Property(property="from_customer_pincode",type="string")
      * @SWG\Property(property="from_company_name",type="string")
      * @SWG\Property(property="to_customer_id",type="integer")
      * @SWG\Property(property="to_customer_name",type="string")
      * @SWG\Property(property="to_city",type="string")
      * @SWG\Property(property="to_country_code",type="string")
      * @SWG\Property(property="to_customer_phone",type="string")
      * @SWG\Property(property="to_customer_address_1",type="string")
      * @SWG\Property(property="to_customer_address_2",type="string")
      * @SWG\Property(property="to_customer_pincode",type="string")
      * @SWG\Property(property="to_company_name",type="string")
      * @SWG\Property(property="cost_of_shipment",type="string")
      * @SWG\Property(property="duty_cost",type="string")
      * @SWG\Property(property="pickuprequest",type="string")
     */
    protected $table = 'waybills';

    protected $fillable = [
        'order_id', 
        'customer_id', 
        'awb', 
        'box_length', 
        'box_width', 
        'box_height', 
        'actual_weight', 
        'volume_weight', 
        'package_slip', 
        'pickup_date',
        'from_customer_id',
        'from_customer_name',
        'from_city',
        'from_country_code',
        'from_customer_phone',
        'from_customer_address_1',
        'from_customer_address_2',
        'from_customer_pincode',
        'from_company_name',
        'to_customer_id',
        'to_customer_name',
        'to_city',
        'to_country_code',
        'to_customer_phone',
        'to_customer_address_1',
        'to_customer_address_2',
        'to_customer_pincode',
        'to_company_name',
        'cost_of_shipment',
        'duty_cost',
        'pickuprequest',
        'customer_id',
    ];

    protected $appends = ['dimension'];

    /**
     * Get order detail
     */
    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function getDimensionAttribute()
    {
        return $this->box_length * $this->box_width * $this->box_height;
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function waybill_track_histories()
    {
        return $this->hasMany('App\waybillTrackHistories');
    }

    public static function PaymentMode(){
        return array(
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer'
        );
    }
}
