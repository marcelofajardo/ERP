<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\SiteDevelopmentCategory;
use App\ChatMessage;
use App\StoreDevelopmentRemark;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;

class SiteDevelopment extends Model
{

    /**
     * @var string
     * @SWG\Property(property="site_development_category_id",type="integer")
     * @SWG\Property(property="status",type="string")
 
     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="brand_id",type="interger")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="developer_id",type="integer")
     * @SWG\Property(property="designer_id",type="integer")
     * @SWG\Property(property="website_id",type="integer")

     * @SWG\Property(property="html_designer",type="string")
     * @SWG\Property(property="artwork_status",type="string")
     * @SWG\Property(property="tester_id",type="integer")

     */
    use Mediable;

    protected $fillable = ['site_development_category_id','status','title','description','developer_id','designer_id','website_id','html_designer','artwork_status','tester_id'];


    public function category()
    {
    	$this->belongsTo(SiteDevelopmentCategory::class,'id','site_development_category_id');
    }

    public function lastChat()
    {
    	return $this->hasOne(ChatMessage::class,'site_development_id','id')->orderBy('created_at', 'desc')->latest();
    }

    //START - Purpose : Get Last Remarks - #DEVTASK-19918 
    public function lastRemark()
    {
    	return $this->hasOne(StoreDevelopmentRemark::class,'store_development_id','id')->orderBy('created_at', 'desc')->latest();
    }
    //END - #DEVTASK-19918 

    public function whatsappAll($needBroadcast = false)
    {
        if($needBroadcast) {
            return $this->hasMany('App\ChatMessage', 'site_development_id')->where(function($q){
                $q->whereIn('status', ['7', '8', '9', '10'])->orWhere("group_id",">",0);
            })->latest();
        }else{
            return $this->hasMany('App\ChatMessage', 'site_development_id')->whereNotIn('status', ['7', '8', '9', '10'])->latest();
        }
    }

    public function developer()
    {
        return $this->hasOne('App\User','id','developer_id');
    }

    public function designer()
    {
        return $this->hasOne('App\User','id','designer_id');
    }

    

}
