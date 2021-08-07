<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Wetransfer extends Model
{
	   /**
     * @var string
      * @SWG\Property(property="type",type="string")
      * @SWG\Property(property="url",type="string")
      * @SWG\Property(property="supplier",type="string")
      * @SWG\Property(property="is_processed",type="float")
     */
    public $fillable = [ 'type','url', 'supplier','is_processed', 'files_list', 'files_count'];
}
