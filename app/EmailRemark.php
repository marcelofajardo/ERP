<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class EmailRemark extends Model
{
   /**
     * @var string
     * @SWG\Property(property="email_id",type="string")
     * @SWG\Property(property="user_name",type="string")
     * @SWG\Property(property="remarks",type="string")

     */
  protected $fillable = [
    'email_id',
    'user_name',
    'remarks'
  ];

  public function email() {
    return $this->belongsTo(Email::class);
  }

}
