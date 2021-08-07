<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsitePageHistory extends Model
{
	     /**
     * @var string
    
      * @SWG\Property(property="content",type="string")
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="result",type="string")
     * @SWG\Property(property="result_type",type="string")
      * @SWG\Property(property="store_website_page_id",type="integer")
     * @SWG\Property(property="updated_by",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'content','url','content','result','result_type', 'store_website_page_id', 'updated_by', 'created_at', 'updated_at',
    ];

    public function user()
    {
        return $this->hasOne(\App\User::class, 'id', 'updated_by');
    }
}
