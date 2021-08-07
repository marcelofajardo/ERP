<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;


class WebsiteStore extends Model
{

        /**
     * @var string
      * @SWG\Property(property="name",type="string")
      * @SWG\Property(property="code",type="string")
      * @SWG\Property(property="root_category",type="string")
      * @SWG\Property(property="platform_id",type="integer")
      * @SWG\Property(property="website_id",type="integer")
     */
    protected $fillable = [
        'name', 
        'code', 
        'root_category', 
        'platform_id', 
        'website_id' 
    ];

    public function website()
    {
        return $this->hasOne(\App\Website::class, 'id','website_id');
    }

    public function storeView()
    {
        return $this->hasMany(\App\WebsiteStoreView::class, 'id','website_id');
    }
	
	 public function scrapperImage()
    {
        return $this->hasMany(\App\scraperImags::class, 'website_id', 'code');
    }

    public function website_code()
    {
        return $this->hasOne(\App\Website::class, 'platform_id','platform_id');
    }

    public function storeViewMany()
    {
        return $this->hasMany(\App\WebsiteStoreView::class);
    }
}
