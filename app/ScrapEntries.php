<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScrapEntries extends Model
{
	/**
     * @var string
     * @SWG\Property(property="pagination",type="string")
     */
    protected $casts = [
        'pagination' => 'array'
    ];
}
