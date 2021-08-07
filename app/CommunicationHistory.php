<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class CommunicationHistory extends Model
{


     /**
     * @var string
     * @SWG\Property(property="model_id",type="integer")
   * @SWG\Property(property="model_type",type="string")
   * @SWG\Property(property="type",type="string")
   * @SWG\Property(property="method",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     
     */
  protected $fillable = [
    'model_id', 'model_type', 'type', 'method', 'created_at','refer_id'
  ];
}
