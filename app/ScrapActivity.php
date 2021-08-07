<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScrapActivity extends Model
{
	/**
     * @var string
     * @SWG\Property(property="website",type="string")
     * @SWG\Property(property="scraped_product_id",type="integer")
     * @SWG\Property(property="status",type="string")
     */
  protected $fillable = [
    'website', 'scraped_product_id', 'status'
  ];
}
