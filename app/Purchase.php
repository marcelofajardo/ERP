<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
   /**
     * @var string
     * @SWG\Property(property="communication",type="string")
     * @SWG\Property(property="whatsapp_number",type="string")
    
     */
  use SoftDeletes;

  // protected $appends = ['communication'];
	protected $communication = '';
  protected $fillable = ['whatsapp_number'];

	public function messages()
	{
		return $this->hasMany('App\Message', 'moduleid')->where('moduletype', 'purchase')->latest()->first();
	}

	// public function getCommunicationAttribute()
	// {
	// 	return $this->messages();
	// }

  public function products()
  {
    return $this->belongsToMany('App\Product', 'purchase_products', 'purchase_id', 'product_id');
  }

  public function orderProducts()
  {
    return $this->belongsToMany('App\OrderProduct', 'purchase_products', 'purchase_id', 'order_product_id');
  }

  public function files()
  {
    return $this->hasMany('App\File', 'model_id')->where('model_type', 'App\Purchase');
  }

  public function purchase_supplier()
  {
    return $this->belongsTo('App\Supplier', 'supplier_id');
  }

  public function agent()
  {
    return $this->belongsTo('App\Agent', 'agent_id');
  }

  public function emails()
  {
    return $this->hasMany('App\Email', 'model_id')->where('model_type', 'App\Purchase')->orWhere('model_type', 'App\Supplier');
  }

  public function status_changes()
	{
		return $this->hasMany('App\StatusChange', 'model_id')->where('model_type', 'App\Purchase')->latest();
	}

  public function is_sent_in_italy()
	{
		$count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Purchase')->where('type', 'purchase-in-italy')->count();

		return $count > 0 ? TRUE : FALSE;
	}

  public function is_sent_in_dubai()
	{
		$count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Purchase')->where('type', 'purchase-in-dubai')->count();

		return $count > 0 ? TRUE : FALSE;
	}

  public function is_sent_dubai_to_india()
	{
		$count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Purchase')->where('type', 'purchase-dubai-to-india')->count();

		return $count > 0 ? TRUE : FALSE;
	}

  public function is_sent_in_mumbai()
	{
		$count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Purchase')->where('type', 'purchase-in-mumbai')->count();

		return $count > 0 ? TRUE : FALSE;
	}

  public function is_sent_awb_actions()
	{
		$count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Purchase')->where('type', 'purchase-awb-generated')->count();

		return $count > 0 ? TRUE : FALSE;
	}

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'cash_flow_able');
    }

  public function customers()
  {
    return $this->belongsToMany('App\Customer', 'purchase_order_customer', 'purchase_id', 'customer_id');
  }

  public function purchaseProducts()
  {
    return $this->hasMany('App\PurchaseProduct', 'purchase_id', 'id');
  }

}
