<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class AgentCallStatus extends Model
{
  /**
   * @var string
   * @SWG\Property(property="model_id",type="integer")
   * @SWG\Property(property="model_type",type="string")
   * @SWG\Property(property="name",type="string")
   * @SWG\Property(property="phone",type="string")
   * @SWG\Property(property="whatsapp_number",type="string")
   * @SWG\Property(property="address",type="text")
   * @SWG\Property(property="email",type="string")
   */
  protected $fillable = [
    'agent_id', 'agent_name', 'agent_name_id', 'site_id', 'twilio_no', 'status'
  ];

  
}
