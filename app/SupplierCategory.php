<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SupplierCategory extends Model
{
	      /**
     * @var string
      * @SWG\Property(property="supplier_category",type="string")
      * @SWG\Property(property="name",type="string")
      * @SWG\Property(property="timestamps",type="boolean")
     */
    protected $table = 'supplier_category';
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];
}