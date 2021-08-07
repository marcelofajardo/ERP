<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteCategorySeo extends Model
{
			/**
     * @var string
      * @SWG\Property(property="category_id",type="integer")
      * @SWG\Property(property="language_id",type="integer")
     * @SWG\Property(property="meta_title",type="string")
     * @SWG\Property(property="meta_description",type="string")
     * @SWG\Property(property="meta_keyword",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'category_id','store_website_id', 'meta_title', 'meta_description', 'meta_keyword', 'created_at', 'updated_at','language_id','meta_keyword_avg_monthly'
    ];

     public static function boot()
    {
        parent::boot();

        static::updating(function ($data) {

            $newKeyword = $data->meta_keyword;
            $oldKeyword = $data->getOriginal('meta_keyword');
            $newDescription = $data->meta_description;
            $oldDescription = $data->getOriginal('meta_description');

            if( $oldKeyword != $newKeyword || $newDescription != $oldDescription ) {
                $insert_data = array(
                    'store_website_cate_seos_id' => $data->id,
                    'old_keywords'               => $oldKeyword,
                    'new_keywords'               => $newKeyword,
                    'old_description'            => $newDescription,
                    'new_description'            => $oldDescription,
                    'user_id'                    => auth()->user()->id,
                    'created_at'                 => date("Y-m-d H:i:s")
                );
                \App\StoreWebsiteCategorySeosHistories::insert( $insert_data );
            }

            // if( $newDescription != $oldDescription ) {
            //     $insert_data = array(
            //         'store_website_cate_seos_id' => $data->id,
            //         'old_description' => $newDescription,
            //         'new_description' => $oldDescription,
            //         'created_at' => date("Y-m-d H:i:s")
            //     );
            //     \App\store_website_category_seos_histories::insert( $insert_data );
            // }
            
        });
       
    }
}
