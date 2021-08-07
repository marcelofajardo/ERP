<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;
class StoreSocialContent extends Model
{
     /**
     * @var string
      * @SWG\Property(property="store_social_content_category_id",type="integer")
      * @SWG\Property(property="store_website_id",type="integer")
      * @SWG\Property(property="store_social_content_status_id",type="integer")
      * @SWG\Property(property="creator_id",type="integer")
      * @SWG\Property(property="publisher_id",type="integer")
     * @SWG\Property(property="request_date",type="datetime")
     * @SWG\Property(property="due_date",type="datetime")
     * @SWG\Property(property="publish_date",type="datetime")
     * @SWG\Property(property="platform",type="string")
     */
    use Mediable;
    protected $fillable = [
        'store_social_content_category_id','store_website_id','store_social_content_status_id','creator_id','publisher_id','request_date','due_date','publish_date','platform'
    ];

    public function lastChat()
    {
    	return $this->hasOne(ChatMessage::class,'store_social_content_id','id')->orderBy('created_at', 'desc')->latest();
    }

    public function whatsappAll($needBroadcast = false)
    {
        if($needBroadcast) {
            return $this->hasMany('App\ChatMessage', 'store_social_content_id')->where(function($q){
                $q->whereIn('status', ['7', '8', '9', '10'])->orWhere("group_id",">",0);
            })->latest();
        }else{
            return $this->hasMany('App\ChatMessage', 'store_social_content_id')->whereNotIn('status', ['7', '8', '9', '10'])->latest();
        }
    }

    public function publisher()
    {
        return $this->hasOne('App\User','id','publisher_id');
    }

    public function creator()
    {
        return $this->hasOne('App\User','id','creator_id');
    }

    public function website()
    {
        return $this->hasOne("\App\StoreWebsite","id", "store_website_id");
    }

}
