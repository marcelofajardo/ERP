<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
	 /**
     * @var string
	 * @SWG\Property(property="order_id",type="integer")
     * @SWG\Property(property="sku",type="string")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="product_price",type="float")
     * @SWG\Property(property="size",type="string")
     * @SWG\Property(property="color",type="string")
     * @SWG\Property(property="qty",type="integer")
     * @SWG\Property(property="purchase_status",type="string")
     * @SWG\Property(property="supplier_discount_info_id",type="integer")
     * @SWG\Property(property="inventory_status_id",type="integer")
     */

    protected $fillable = [
		'order_id',
	    'sku',
	    'product_id',
	    'product_price',
	    'size',
	    'color',
      	'qty',
		'purchase_status',
		'supplier_discount_info_id',
		'inventory_status_id'
    ];

    protected $appends = ['communication'];

	public function order(){

		return $this->belongsTo('App\Order','order_id','id');
	}

	public function product(){

//		return $this->hasOne('App\Product',['sku','color'],['sku','color']);
		return $this->hasOne('App\Product','id','product_id');

	}

	public function products(){
		return $this->hasMany('App\Product','id','product_id');
	}

  public function purchase()
  {
		return $this->belongsTo('App\Purchase');
	}

  public function private_view()
  {
		return $this->hasOne('App\PrivateView');
	}

  public function is_delivery_date_changed()
	{
		$count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\OrderProduct')->where('type', 'order-delivery-date-changed')->count();

		return $count > 0 ? TRUE : FALSE;
	}

  public function messages()
	{
		return $this->hasMany('App\Message', 'moduleid', 'order_id')->where('moduletype', 'order')->latest()->first();
	}

	public function whatsapps()
	{
		return $this->hasMany('App\ChatMessage', 'order_id', 'order_id')->latest()->first();
	}

  public function status_changes()
	{
		return $this->hasMany('App\StatusChange', 'model_id')->where('model_type', 'App\OrderProduct')->latest();
	}

  public function getCommunicationAttribute()
	{
		$message = $this->messages();
		$whatsapp = $this->whatsapps();

		if ($message && $whatsapp) {
			if ($message->created_at > $whatsapp->created_at) {
				return $message;
			}

			return $whatsapp;
		}

		if ($message) {
			return $message;
		}

		return $whatsapp;
	}
}
