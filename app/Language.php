<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Language extends Model {
	/**
     * @var string
     * @SWG\Property(property="locale",type="string")
     * @SWG\Property(property="code",type="string")
     * @SWG\Property(property="store_view",type="string")
     * @SWG\Property(property="status",type="string")
     */

    protected $fillable = [
        'locale', 'code', 'store_view','status'
    ];

}
