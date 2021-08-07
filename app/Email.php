<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
      /**
     * @var string
     * @SWG\Property(property="model_id",type="integer")
     * @SWG\Property(property="model_type",type="string")
     * @SWG\Property(property="seen",type="string")
     * @SWG\Property(property="from",type="string")
     * @SWG\Property(property="to",type="string")
     * @SWG\Property(property="subject",type="string")
     * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="template",type="string")
     * @SWG\Property(property="additional_data",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="cc",type="string")
     * @SWG\Property(property="bcc",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="approve_mail",type="string")
     * @SWG\Property(property="origin_id",type="integer")
     * @SWG\Property(property="reference_id",type="integer")
     */
  protected $fillable = [
    'model_id', 'model_type', 'type', 'seen', 'from', 'to', 'subject', 'message', 'template', 'additional_data', 'created_at',
      'cc', 'bcc','origin_id','reference_id', 'status','approve_mail','is_draft' , 'error_message','store_website_id'
  ];

  protected $casts = [
    'cc' => 'array',
    'bcc' => 'array',
  ];


  public function model()
  {
  	return $this->morphTo();
  }

  public function remarks(){
    return $this->hasMany(EmailRemark::class);
  }
}
