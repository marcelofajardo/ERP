<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
         /**
     * @var string
     * @SWG\Property(property="start_time",type="datetime")
     * @SWG\Property(property="end_time",type="datetime")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="order_id",type="integer")
     * @SWG\Property(property="instruction",type="string")
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="assigned_to",type="integer")
     * @SWG\Property(property="assigned_from",type="datetime")
     */
  protected $fillable = ['start_time', 'end_time', 'customer_id', 'product_id', 'order_id', 'instruction', 'category_id', 'assigned_to', 'assigned_from'];

  public function customer()
  {
    return $this->belongsTo('App\Customer');
  }

  public function category()
  {
    return $this->belongsTo('App\InstructionCategory');
  }

  public function remarks()
  {
    return $this->hasMany('App\Remark', 'taskid')->where('module_type', 'instruction')->latest();
  }

  public function assingTo()
  {
    return $this->hasOne("\App\User","id","assigned_to");
  }

  public function assignFrom()
  {
    return $this->hasOne("\App\User","id","assigned_from");
  }
}
