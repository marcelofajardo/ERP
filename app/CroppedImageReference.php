<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Media;
use Plank\Mediable\Mediable;
use App\StoreWebsite;
use DB;

class CroppedImageReference extends Model
{
    public function media() {
        return $this->hasOne(Media::class, 'id', 'original_media_id');
    }

    public function newMedia() {
        return $this->hasOne(Media::class, 'id', 'new_media_id');
    }

    public function getDifferentWebsiteImage($original_media_id) {
        return $this->where('original_media_id',$original_media_id)->get();
    }

    public function differentWebsiteImages() {
        return $this->hasMany(self::class, 'original_media_id', 'original_media_id');
    }

    public function getDifferentWebsiteName($media_id) {
       $media =  DB::table('mediables')->select('tag')->where('media_id',$media_id)->first();
       if($media->tag == 'gallery'){
            return 'Default';
       }else{
            $colorCode = str_replace('gallery_','',$media->tag);
            $site = StoreWebsite::select('title')->where('cropper_color',$colorCode)->first();
            if($site){
                return $site->title;
            }else{
                return 'Default';
            }
            
       }
        
    }

    public function product()
    {
    	return $this->hasOne(Product::class,'id','product_id');
    }

    public function getProductIssueStatus($id){
    	$task = DeveloperTask::where('task','LIKE','%'.$id.'%')->first();
    	if($task != null){
    		if($task->status == 'done'){
    			return '<p>Issue Resolved</p><button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="developer_task" data-id="'.$task->id.'" title="Load messages"><img src="/images/chat.png" alt=""></button>';
    		}else{

    			return '<p>Issue Pending</p><button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="developer_task" data-id="'.$task->id.'" title="Load messages"><img src="/images/chat.png" alt=""></button>';
    		}
    	}else{
    		return 'No Issue Yet';
    	}
	}





}
