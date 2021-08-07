<?php

namespace App;

use Plank\Mediable\Mediable;
use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class BroadcastImage extends Model
{
	/**
     * @var string
    * @SWG\Property(property="sending_time",type="datetime")
     */
  use Mediable;

  protected $fillable = [
    'sending_time'
  ];
}
