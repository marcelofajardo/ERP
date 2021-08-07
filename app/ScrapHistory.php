<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScrapHistory extends Model
{
	/**
     * @var string
     * @SWG\Property(property="operation",type="string")
     * @SWG\Property(property="model",type="string")
     * @SWG\Property(property="model_id",type="integer")
     * @SWG\Property(property="text",type="string")
     * @SWG\Property(property="created_by",type="integer")
     */
  
    protected $fillable = [
        'operation','model','model_id','text', 'created_by',
    ];

}
