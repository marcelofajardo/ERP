<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScrapStatistics extends Model
{

	/**
     * @var string
     * @SWG\Property(property="id",type="integer")
     * @SWG\Property(property="supplier",type="string")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="brand",type="string")
     */
    protected $fillable = ['id', 'supplier', 'type', 'url', 'description', 'brand'];
}
