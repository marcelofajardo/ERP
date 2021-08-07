<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
      /**
     * @var string
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="magento_id",type="integer")
     */
    protected $fillable = ['name', 'magento_id'];

    public function storeWebsitSize()
    {
        return $this->hasMany(\App\StoreWebsiteSize::class, 'size_id' , 'id');
    }

}
