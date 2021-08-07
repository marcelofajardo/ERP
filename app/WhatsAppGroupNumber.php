<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class WhatsAppGroupNumber extends Model
{

		  /**
     * @var string
    
      * @SWG\Property(property="user_number",type="string")
      * @SWG\Property(property="group_id",type="integer")
      * @SWG\Property(property="user_id",type="integer")
     
     */
    protected $fillable = [
        'user_id',
        'group_id',
        'user_number'
    ];
}
