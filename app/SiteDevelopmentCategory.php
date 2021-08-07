<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\SiteDevelopment;

class SiteDevelopmentCategory extends Model
{
      /**
     * @var string

     */
    protected $fillable = ['title'];


    public function development()
    {
    	return $this->hasOne(SiteDevelopment::class,'site_development_category_id','id');
    }

    public function getDevelopment($categoryId,$websiteId)
    {
    	return SiteDevelopment::where('website_id',$websiteId)->where('site_development_category_id',$categoryId)->first();
    	
    }
}
