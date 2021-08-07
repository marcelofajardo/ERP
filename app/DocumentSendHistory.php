<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DocumentSendHistory extends Model
{
	  /**
   * @SWG\Property(property="send_by",type="integer")
   * @SWG\Property(property="send_to",type="integer")
   * @SWG\Property(property="remarks",type="string")
   * @SWG\Property(property="type",type="string")
   * @SWG\Property(property="via",type="string")
   * @SWG\Property(property="document_id",type="integer")

        */
    protected $fillable = ['send_by','send_to','remarks','type','via','document_id'];
}
