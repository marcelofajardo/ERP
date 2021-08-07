<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DeveloperComment extends Model
{
		     /**
     * @var string
   * @SWG\Property(property="user_id",type="integer")
   * @SWG\Property(property="size",type="string")
   * @SWG\Property(property="message",type="text")
   * @SWG\Property(property="status",type="boolean")
     */
  protected $fillable = [
    'user_id', 'send_to', 'message', 'status'
  ];
}
