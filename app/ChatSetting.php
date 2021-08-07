<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatSetting extends Model
{
	/**
     * @var string
       * @SWG\Property(property="chat_name",type="string")
      * @SWG\Property(property="vendor",type="string")
        * @SWG\Property(property="instance_id",type="integer")
      * @SWG\Property(property="workspace_id",type="integer")
      * @SWG\Property(property="is_active",type="boolean")
     */
    protected $fillable = [
        'chat_name', 'vendor', 'instance_id', 'workspace_id', 'is_active',
    ];
}
