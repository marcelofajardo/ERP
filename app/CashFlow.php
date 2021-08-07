<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class CashFlow extends Model
{
    /**
     * @var string
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="cash_flow_category_id",type="integer")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="date",type="datetime")
     * @SWG\Property(property="amount",type="integer")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="actual",type="string")
     * @SWG\Property(property="currency",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="order_status",type="sting")
     * @SWG\Property(property="updated_by",type="datetime")
     * @SWG\Property(property="cash_flow_able_id",type="integer")
     * @SWG\Property(property="cash_flow_able_type",type="sting")
     */
  protected $fillable = [
    'user_id', 'cash_flow_category_id', 'description', 'date', 'amount', 'type','expected','actual','currency','status','order_status','updated_by','cash_flow_able_id','cash_flow_able_type'
  ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function files()
  {
    return $this->hasMany('App\File', 'model_id')->where('model_type', 'App\CashFlow');
  }

    public function cashFlowAble()
    {
        return $this->morphTo()->withTrashed();
  }

    public function getModelNameAttribute()
    {
        
  }
}
