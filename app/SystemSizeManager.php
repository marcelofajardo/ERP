<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SystemSizeManager extends Model
{
		    /**
     * @var string
      * @SWG\Property(property="erp_size",type="string")
      * @SWG\Property(property="category_id",type="integer")
      * @SWG\Property(property="status",type="string")
     */
    protected $fillable = [
        'category_id',
        'erp_size',
        'status',
    ];
}
