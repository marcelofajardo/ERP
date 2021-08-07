<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Mediable;

class ErpLeads extends Model
{
 /**
     * @var string
   
     * @SWG\Property(property="lead_status_id",type="integer")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="brand_id",type="integer")
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="color",type="string")
     * @SWG\Property(property="size",type="string")
     * @SWG\Property(property="min_price",type="float")
     * @SWG\Property(property="max_price",type="float")
     * @SWG\Property(property="brand_segment",type="string")
     * @SWG\Property(property="gender",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
   

     */
    use Mediable;
    protected $fillable = [
        'lead_status_id',
        'customer_id',
        'product_id',
        'brand_id',
        'category_id',
        'color',
        'size',
        'min_price',
        'max_price',
        'brand_segment',
        'gender',
        'created_at',
        'updated_at',
    ];

    public function status_changes()
    {
        return $this->hasMany('App\StatusChange', 'model_id')->where('model_type', 'App\ErpLeads')->latest();
    }

    public function customer()
    {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }
}
