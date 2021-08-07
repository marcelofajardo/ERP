<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Benchmark extends Model
{
	/**
     * @var string
     * @SWG\Property(property="selections",type="string")
     * @SWG\Property(property="searches",type="string")
     * @SWG\Property(property="attributes",type="string")
     * @SWG\Property(property="supervisor",type="string")
     * @SWG\Property(property="imagecropper",type="string")
     * @SWG\Property(property="lister",type="string")
     * @SWG\Property(property="approver",type="string")
     * @SWG\Property(property="inventory",type="string")
     * @SWG\Property(property="for_date",type="datetime")
     */
    protected $fillable = [
    	'selections',
	    'searches',
	    'attributes',
	    'supervisor',
	    'imagecropper',
	    'lister',
	    'approver',
	    'inventory',
	    'for_date'
    ];
}
