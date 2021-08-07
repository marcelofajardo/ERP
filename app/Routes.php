<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Redirect;


class Routes extends Model
{
	  /**
     * @var string
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="page_title",type="string")
     * @SWG\Property(property="page_description",type="string")
     */
	protected $fillable = ['url','page_title','page_description'];

}
