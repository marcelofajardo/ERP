<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="UserUpdatedAttributeHistory"))
 */
class UserUpdatedAttributeHistory extends Model
{
  /**
     * @var string
     * @SWG\Property(property="old_value",type="string")
     * @SWG\Property(property="new_value",type="string")
     * @SWG\Property(property="attribute_name",type="string")
     * @SWG\Property(property="attribute_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     */

  protected $fillable = [
    'old_value', 'new_value', 'attribute_name', 'attribute_id', 'user_id', 'need_to_skip'
  ];

  public function user()
  {
    return $this->hasOne('App\User','id','user_id');
  }
}
