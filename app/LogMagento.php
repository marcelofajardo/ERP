<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class LogMagento extends Model
{
	/**
     * @var string
   
     * @SWG\Property(property="log_magento",type="string")
     * @SWG\Property(property="timestamps",type="boolean")
     */
    protected $table = 'log_magento';
    public $timestamps = false;
}
