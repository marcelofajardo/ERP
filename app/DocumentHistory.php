<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DocumentHistory extends Model
{
	  /**
   * @SWG\Property(property="user_id",type="integer")
   * @SWG\Property(property="document_id",type="integer")
   * @SWG\Property(property="name",type="string")
   * @SWG\Property(property="filename",type="string")
   * @SWG\Property(property="category_id",type="integer")
   * @SWG\Property(property="version",type="string")
        */
    protected $fillable = [
        'user_id',
        'document_id',
        'name',
        'filename',
        'category_id',
        'version'
    ];
}