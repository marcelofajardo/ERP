<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use App\Documents;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
	  /**
   * @SWG\Property(property="name",type="string")
        */
    protected $fillable = array('name');

    public function documents()
    {
     return $this->belongsTo(Documents::class,'id','category_id');
    }
}
