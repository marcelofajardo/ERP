<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class PurchaseStatus extends Model
{
	/**
     * @var string
     * @SWG\Property(property="purchase_status",type="string")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="timestamps",type="boolean")
     */
    protected $table = 'purchase_status';
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];
}