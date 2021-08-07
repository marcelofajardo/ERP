<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class LandingPageStatus extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="landing_page_statuses",type="string")
     */
    protected $table = 'landing_page_statuses';
}
