<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
           /**
     * @var string
      * @SWG\Property(property="user_id",type="integer")
      * @SWG\Property(property="delivery_approval_id",type="integer")
      * @SWG\Property(property="category_id",type="integer")
      * @SWG\Property(property="description",type="string")
      * @SWG\Property(property="travel_type",type="string")
      * @SWG\Property(property="recurring_type",type="string")
      * @SWG\Property(property="amount",type="float")
      * @SWG\Property(property="paid",type="float")
      * @SWG\Property(property="date",type="datetime")
      * @SWG\Property(property="reject_reason",type="string")
      * @SWG\Property(property="resubmit_count",type="integer")
      * @SWG\Property(property="reject_count",type="integer")
     */
  protected $fillable = [
    'user_id', 'delivery_approval_id', 'category_id', 'description', 'travel_type', 'amount', 'paid', 'date','reject_reason','resubmit_count','reject_count'  ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function category()
  {
    return $this->belongsTo('App\VoucherCategory');
  }

    public function chat_message()
    {
        return $this->hasMany(ChatMessage::class,'voucher_id');
    }

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'cash_flow_able');
    }
}
