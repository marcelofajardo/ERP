<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SupplierInventory extends Model
{
	      /**
     * @var string
     
      * @SWG\Property(property="supplier_inventory",type="string")
      * @SWG\Property(property="supplier",type="string")
      * @SWG\Property(property="sku",type="string")
      * @SWG\Property(property="inventory",type="string")
     */
    protected $table = 'supplier_inventory';
    protected $fillable = [ 'supplier', 'sku', 'inventory' ];

}
