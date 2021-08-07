<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeveloperModule extends Model
{

		     /**
     * @var string
   * @SWG\Property(property="name",type="string")

     */
  use SoftDeletes;

  protected $fillable = [
    'name'
  ];

  public function tasks()
  {
    return $this->hasMany('App\DeveloperTask', 'module_id');
  }
}
