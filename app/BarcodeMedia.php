<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class BarcodeMedia extends Model
{
    use Mediable;
    /**
     * @var string
     * @SWG\Property(property="media_id",type="integer")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="type_id",type="integer")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="price",type="integer")
     * @SWG\Property(property="extra",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = ['media_id', 'type', 'type_id', 'name', 'price', 'extra', 'created_at', 'updated_at'];
}
