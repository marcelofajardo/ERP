<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ImQueue;

class WatsonWorkspace extends Model
{
	  /**
     * @var string
      * @SWG\Property(property="id",type="integer")
      * @SWG\Property(property="element_id",type="integer")
      * @SWG\Property(property="type",type="string")
      * @SWG\Property(property="watson_workspace",type="string")
      * @SWG\Property(property="created_at",type="datetime")
      * @SWG\Property(property="deleted_at",type="datetime")
     */
    public $table = 'watson_workspace';

  protected $fillable = [
    'id', 'type', 'element_id', 'created_at', 'deleted_at'
  ];

}
