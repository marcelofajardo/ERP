<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SupplierSize extends Model
{
	    /**
     * @var string
     
      * @SWG\Property(property="supplier_size",type="string")
      * @SWG\Property(property="timestamps",type="datetime")
      * @SWG\Property(property="size",type="string")

     */
    protected $table = 'supplier_size';
    public $timestamps = false;
    protected $fillable = [
        'size'
    ];
}