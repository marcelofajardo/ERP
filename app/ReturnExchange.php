<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\ReturnExchangeHistory;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
use Illuminate\Database\Eloquent\SoftDeletes;
class ReturnExchange extends Model
{
     /**
     * @var string
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="reason_for_refund",type="string")
     * @SWG\Property(property="refund_amount",type="float")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="pickup_address",type="string")
     * @SWG\Property(property="refund_amount_mode",type="string")
     * @SWG\Property(property="chq_number",type="string")
     * @SWG\Property(property="reawbmarks",type="string")
     * @SWG\Property(property="payment",type="string")
     * @SWG\Property(property="remarks",type="string")
     * @SWG\Property(property="date_of_refund",type="datetime")
     * @SWG\Property(property="date_of_issue",type="datetime")
     * @SWG\Property(property="dispatch_date",type="datetime")
     * @SWG\Property(property="date_of_request",type="datetime")
     * @SWG\Property(property="details",type="string")
     * @SWG\Property(property="credited",type="string")
     * @SWG\Property(property="est_completion_date",type="datetime")
     */
    use Mediable;
    use SoftDeletes;
    protected $fillable = [
        'customer_id',
        'type',
        'reason_for_refund',
        'refund_amount',
        'status',
        'pickup_address',
        'remarks',
        'refund_amount_mode',
        'chq_number',
        'awb',
        'payment',
        'date_of_refund',
        'date_of_issue',
        'details',
        'dispatch_date',
        'date_of_request',
        'credited',
        'est_completion_date',
        'send_email',
        'website_id'
    ];
    
    const STATUS = [
        1 => 'Return request received from customer',
        2 => 'Return request sent to courier',
        3 => 'Return pickup',
        4 => 'Return received in warehouse',
        5 => 'Return accepted',
        6 => 'Return rejected',
    ];
    
    public function notifyToUser()
    {
        if ($this->type == "refund") {
            // notify message we need to add here
        }
    }

    public function returnExchangeProducts()
    {
        return $this->hasMany(\App\ReturnExchangeProduct::class, "return_exchange_id", "id");
    }

    public function returnExchangeHistory()
    {
        return $this->hasMany(\App\ReturnExchangeHistory::class, "return_exchange_id", "id");
    }
    
    public function returnExchangeStatus()
    {
        return $this->hasOne(\App\ReturnExchangeStatus::class, "id", "status");
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    /**
     * Update return exchange history
     *
     */

    public function updateHistory()
    {
        ReturnExchangeHistory::create([
            "return_exchange_id" => $this->id,
            "status_id"          => $this->status,
            "user_id"            => \Auth::user()->id,
            "comment"            => $this->remarks,
            "history_type"       => 'status'
        ]);

        return true;
    }

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'cash_flow_able');
    }

}
