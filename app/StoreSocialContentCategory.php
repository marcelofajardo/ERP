<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreSocialContentCategory extends Model
{
    /**
     * @var string
      * @SWG\Property(property="title",type="integer")
      
     */
    protected $fillable = ['title'];

    public function getContent($categoryId,$websiteId)
    {
    	return StoreSocialContent::where('store_website_id',$websiteId)->where('store_social_content_category_id',$categoryId)->first();
    	
    }

    // public function getTaskMilestone($site_id)
    // {
    // 	return StoreSocialContentMilestone::where('store_social_content_id',$site_id)->first();
    	
    // }
}
