<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Blogger extends Model
{
    /**
     * @var string
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="phone",type="string")
     * @SWG\Property(property="default_phone",type="string")
     * @SWG\Property(property="instagram_handle",type="string")
     * @SWG\Property(property="city",type="string")
     * @SWG\Property(property="country",type="string")
     * @SWG\Property(property="followers",type="integer")
     * @SWG\Property(property="followings",type="integer")
     * @SWG\Property(property="fake_followers",type="integer")
     * @SWG\Property(property="rating",type="string")
     * @SWG\Property(property="whatsapp_number",type="integer")
     * @SWG\Property(property="email",type="sting")
     * @SWG\Property(property="other",type="sting")
     * @SWG\Property(property="agency",type="sting")
     * @SWG\Property(property="industry",type="sting")
     * @SWG\Property(property="brands",type="sting")
     */
    protected $fillable = ['name','phone','default_phone','instagram_handle','city','country','followers','followings','avg_engagement','fake_followers','email','rating','whatsapp_number','other','agency','industry','brands'];
    /**
     * @var string
     * @SWG\Property(enum={"brands"})
     */
    protected $casts = [
        'brands' => 'array'
    ];

    public function chat_message()
    {
        return $this->hasMany(ChatMessage::class,'blogger_id');
    }

    public function payments()
    {
        return $this->hasMany(BloggerPayment::class);
    }

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'cash_flow_able');
    }
}
