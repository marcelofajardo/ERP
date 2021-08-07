<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class WebhookNotification extends Model
{
   /**
     * @var string
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="method",type="string")
     * @SWG\Property(property="payload",type="string")

     */
  protected $fillable = [
    'user_id',
    'url',
    'method',
    'payload'
  ];
  
}
