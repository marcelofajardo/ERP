<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{

		    /**
     * @var string
      * @SWG\Property(property="courier",type="string")
      * @SWG\Property(property="package_from",type="string")
      * @SWG\Property(property="date",type="datetime")
      * @SWG\Property(property="awb",type="string")
      * @SWG\Property(property="l_dimension",type="string")
      * @SWG\Property(property="h_dimension",type="string")
      * @SWG\Property(property="w_dimension",type="string")
      * @SWG\Property(property="weight",type="string")
      * @SWG\Property(property="pcs",type="string")
     */
  use SoftDeletes;

  protected $fillable = [
    'courier', 'package_from', 'date', 'awb', 'l_dimension', 'h_dimension', 'w_dimension', 'weight', 'pcs'
  ];

  public function products()
  {
    return $this->belongsToMany('App\Product', 'stock_products', 'stock_id', 'product_id');
  }
}
