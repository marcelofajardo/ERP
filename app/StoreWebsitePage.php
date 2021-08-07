<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsitePage extends Model
{
        /**
     * @var string
    
      * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="meta_title",type="string")
     * @SWG\Property(property="meta_keywords",type="string")
     * @SWG\Property(property="meta_description",type="string")
     * @SWG\Property(property="content_heading",type="string")
     * @SWG\Property(property="content",type="string")
     * @SWG\Property(property="layout",type="string")
     * @SWG\Property(property="url_key",type="string")
     * @SWG\Property(property="active",type="string")
     * @SWG\Property(property="stores",type="string")
     * @SWG\Property(property="language",type="string")
      * @SWG\Property(property="platform_id",type="integer")
     * @SWG\Property(property="store_website_id",type="integer")
     */
    protected $fillable = [
        'title',
        'meta_title',
        'meta_keywords',
        'meta_keyword_avg_monthly',
        'meta_description',
        'content_heading',
        'content',
        'layout',
        'url_key',
        'active',
        'stores',
        'platform_id',
        'store_website_id',
        'language'
    ];

    public function storeWebsite()
    {
        return $this->hasOne(\App\StoreWebsite::class, "id", "store_website_id");
    }
}
