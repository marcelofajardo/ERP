<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StatusChange extends Model
{
		/**
     * @var string
     * @SWG\Property(property="model_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="model_type",type="string")
     * @SWG\Property(property="from_status",type="string")
     * @SWG\Property(property="to_status",type="string")
     */
  protected $fillable = [
    'model_id', 'model_type', 'user_id', 'from_status', 'to_status'
  ];
}
