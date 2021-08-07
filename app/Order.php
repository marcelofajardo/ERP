<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\Events\OrderCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
       /**
     * @var string
 
     * @SWG\Property(property="order_id",type="integer")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="order_type",type="string")
     * @SWG\Property(property="order_date",type="datetime")
     * @SWG\Property(property="awb",type="string")

     * @SWG\Property(property="client_name",type="sting")
     * @SWG\Property(property="city",type="sting")
     * @SWG\Property(property="contact_detail",type="sting")
     * @SWG\Property(property="shoe_size",type="sting")
     * @SWG\Property(property="clothing_size",type="sting")
     * @SWG\Property(property="solophone",type="sting")
     * @SWG\Property(property="advance_detail",type="sting")
     * @SWG\Property(property="advance_date",type="datetime")
     * @SWG\Property(property="balance_amount",type="float")
     * @SWG\Property(property="office_phone_number",type="integer")
     * @SWG\Property(property="order_status",type="sting")
     * @SWG\Property(property="order_status_id",type="integer")
     * @SWG\Property(property="estimated_delivery_date",type="datetime")
     * @SWG\Property(property="note_if_any",type="sting")
     * @SWG\Property(property="date_of_delivery",type="datetime")
     * @SWG\Property(property="received_by",type="integer")
     * @SWG\Property(property="payment_mode",type="string")
     * @SWG\Property(property="auto_messaged",type="string")
     * @SWG\Property(property="auto_messaged_date",type="datetime")
     * @SWG\Property(property="auto_emailed",type="string")
     * @SWG\Property(property="auto_emailed_date",type="datetime")
     * @SWG\Property(property="remark",type="string")
     * @SWG\Property(property="whatsapp_number",type="string")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="is_priority",type="boolean")
     * @SWG\Property(property="currency",type="string")
     * @SWG\Property(property="invoice_id",type="string")
     */

    use SoftDeletes;

    const ORDER_STATUS_TEMPLATE = 'Greetings from Solo Luxury Ref: order number #{order_id} we have updated your order with status : #{order_status}.';

    protected $fillable = [
        'order_id',
        'customer_id',
        'order_type',
        'order_date',
        'awb',
        'client_name',
        'city',
        'contact_detail',
        'shoe_size',
        'clothing_size',
        'solophone',
        'advance_detail',
        'advance_date',
        'balance_amount',
        'sales_person',
        'office_phone_number',
        'order_status',
        'order_status_id',
        'estimated_delivery_date',
        'note_if_any',

        'date_of_delivery',
        'received_by',
        'payment_mode',
        'auto_messaged',
        'auto_messaged_date',
        'auto_emailed',
        'auto_emailed_date',
        'remark',
        'whatsapp_number',
        'user_id',
        'is_priority',
        'currency',
        'invoice_id'
    ];

    protected $appends = ['action'];
    // protected $communication = '';
    // protected $action = '';

    public function order_product()
    {

        return $this->hasMany(OrderProduct::class, 'order_id', 'id');

    }

    public function orderProducts(){
        return $this->hasMany(OrderProduct::class, 'order_id', 'id')->where('product_id', '!=', 0);
    }

    public function products(){
        return $this->belongsToMany(Product::class, OrderProduct::class, 'user_id', 'role_id');
    }

    public function latest_product(){
        return $this->hasOne(OrderProduct::class, 'order_id', 'id')->latest();
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function Comment()
    {

        return $this->hasMany(Comment::class, 'subject_id', 'id')
            ->where('subject_type', '=', Order::class);
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'moduleid')->where('moduletype', 'order')->latest()->first();
    }

    public function reports()
    {
        return $this->hasMany('App\OrderReport', 'order_id')->latest()->first();
    }

    public function latest_report()
    {
        return $this->hasOne('App\OrderReport', 'order_id')->latest();
    }

    public function status_changes()
    {
        return $this->hasMany('App\StatusChange', 'model_id')->where('model_type', 'App\Order')->latest();
    }

    public function many_reports()
    {
        return $this->hasMany('App\OrderReport', 'order_id')->latest();
    }

    public function whatsapps()
    {
        return $this->hasMany('App\ChatMessage', 'order_id')->latest()->first();
    }

    public function delivery_approval()
    {
        return $this->hasOne('App\DeliveryApproval');
    }

    public function waybill()
    {
        return $this->hasOne('App\Waybill');
    }

    public function waybills()
    {
        return $this->hasMany('App\Waybill');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    public function is_sent_initial_advance()
    {
        $count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Order')->where('type', 'initial-advance')->count();

        return $count > 0 ? TRUE : FALSE;
    }

    public function is_sent_advance_receipt()
    {
        $count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Order')->where('type', 'advance-receipt')->count();

        return $count > 0 ? TRUE : FALSE;
    }

    public function is_sent_online_confirmation()
    {
        $count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Order')->where('type', 'online-confirmation')->count();

        return $count > 0 ? TRUE : FALSE;
    }

    public function is_sent_refund_initiated()
    {
        $count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Order')->where('type', 'refund-initiated')->count();

        return $count > 0 ? TRUE : FALSE;
    }

    public function is_sent_offline_confirmation()
    {
        $count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Order')->where('type', 'offline-confirmation')->count();

        return $count > 0 ? TRUE : FALSE;
    }

    public function is_sent_order_delivered()
    {
        $count = $this->hasMany('App\CommunicationHistory', 'model_id')->where('model_type', 'App\Order')->where('type', 'order-delivered')->count();

        return $count > 0 ? TRUE : FALSE;
    }

    public function order_status(){
        return $this->belongsTo('App\OrderStatus');
    }
    // public function getCommunicationAttribute()
    // {
    // 	$message = $this->messages();
    // 	$whatsapp = $this->whatsapps();
    //
    // 	if ($message && $whatsapp) {
    // 		if ($message->created_at > $whatsapp->created_at) {
    // 			return $message;
    // 		}
    //
    // 		return $whatsapp;
    // 	}
    //
    // 	if ($message) {
    // 		return $message;
    // 	}
    //
    // 	return $whatsapp;
    // }

    public function getActionAttribute()
    {
        return $this->reports();
    }

    protected $dispatchesEvents = [
        'created' => OrderCreated::class,
    ];

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'cash_flow_able');
    }

    public function storeWebsiteOrder()
    {
        return $this->hasOne(\App\StoreWebsiteOrder::class, "order_id","id");
    }

    public function whatsappAll($needBroadcast = false)
    {
        if($needBroadcast) {
            return $this->hasMany('App\ChatMessage', 'order_id')->where(function($q){
                $q->whereIn('status', ['7', '8', '9', '10'])->orWhere("group_id",">",0);
            })->latest();
        }else{
            return $this->hasMany('App\ChatMessage', 'order_id')->whereNotIn('status', ['7', '8', '9', '10'])->latest();
        }
    }

    public function status()
    {
        return $this->hasOne(\App\OrderStatus::class, 'id','order_status_id');
    }

    public function orderCustomerAddress()
    {
        return $this->hasMany(\App\OrderCustomerAddress::class,'order_id','id');
    }

    public function shippingAddress()
    {
        return $this->orderCustomerAddress()->where("address_type","shipping")->first();
    }


    public function billingAddress()
    {
        return $this->orderCustomerAddress()->where("address_type","billing")->first();
    }

    public function email()
    {
        return $this->belongsTo(Email::class,'id', 'model_id');
    }

    public function duty_tax()
    {
        return $this->hasOne(\App\WebsiteStore::class, 'website_id','store_id');
    }


    // public function calculateTotal($order)
    // {
    //     $orderTotal = 0;

    //     if (!empty($order)) {
    //         foreach ($order->order_product as $products) {
    //             if($products->product) {
    //                 $string .= '<tr class="item last" style="height: 25px;">
    //                           <td style="height: 25px; width: 300px; text-align: left;">' . $products->product->name . ' '. $products->product->short_description .'</td>
    //                           <td style="height: 25px; width: 100px; text-align: left;"></td>
    //                           <td style="height: 25px; width: 100px; text-align: left;">' . $products->product->made_in . '</td>
    //                           <td style="height: 25px; width: 100px; text-align: left;">' . $products->qty . '</td>
    //                           <td style="height: 25px; width: 100px; text-align: left;">1</td>
    //                           <td style="height: 25px; width: 100px; text-align: left;">&#8377;' . $products->product_price . '</td>
    //                        </tr>';
    //             }
    //             $orderTotal += $products->product_price;
    //         }
    //     }

    //     return $string;
    // }
}
