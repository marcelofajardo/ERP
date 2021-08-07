<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SEOAnalytics extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="seo_analytics",type="string")
     */
    protected $table = 'seo_analytics';
}
