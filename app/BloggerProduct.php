<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class BloggerProduct extends Model
{

    use Mediable;
    /**
     * @var string
     * @SWG\Property(property="blogger_id",type="integer")
     * @SWG\Property(property="brand_id",type="integer")
     * @SWG\Property(property="shoot_date",type="datetime")
     * @SWG\Property(property="first_post",type="string")
     * @SWG\Property(property="second_post",type="string")
     * @SWG\Property(property="first_post_likes",type="string")
     * @SWG\Property(property="first_post_engagement",type="string")
     * @SWG\Property(property="first_post_response",type="string")
     * @SWG\Property(property="first_post_sales",type="string")
     * @SWG\Property(property="second_post_likes",type="string")
     * @SWG\Property(property="second_post_engagement",type="sting")
     * @SWG\Property(property="second_post_response",type="sting")
     * @SWG\Property(property="second_post_sales",type="sting")
     * @SWG\Property(property="city",type="sting")
     * @SWG\Property(property="initial_quote",type="sting")
     * @SWG\Property(property="final_quote",type="sting")
     * @SWG\Property(property="whatsapp_number",type="integer")
     * @SWG\Property(property="remarks",type="string")
     * @SWG\Property(property="other",type="string")
     * @SWG\Property(property="images",type="string")
     */
    protected $fillable = ['blogger_id', 'brand_id', 'shoot_date', 'first_post', 'second_post', 'first_post_likes', 'first_post_engagement', 'first_post_response', 'first_post_sales', 'second_post_likes', 'second_post_engagement', 'second_post_response', 'second_post_sales', 'city', 'initial_quote', 'final_quote', 'whatsapp_number', 'remarks', 'other','images'];
    /**
     * @var string
     * @SWG\Property(enum={"images"})
     */
    protected $casts = [
        'images' => 'array',
    ];

    public function blogger()
    {
        return $this->belongsTo(Blogger::class,'blogger_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
}
