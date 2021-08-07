<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;


class Newsletter extends Model
{
        /**
     * @var string
  
   * @SWG\Property(property="subject",type="string")
     * @SWG\Property(property="mail_list_id",type="integer")
     * @SWG\Property(property="sent_at",type="datetime")
     * @SWG\Property(property="sent_on",type="string")
     * @SWG\Property(property="updated_by",type="integer")
     * @SWG\Property(property="store_website_id",type="integer")
     */
    protected $fillable = [
       'subject' , 'store_website_id' , 'sent_at' , 'sent_on' , 'updated_by','mail_list_id'
    ];

    public function newsletterProduct()
    {
        return $this->hasMany(\App\NewsletterProduct::class, 'newsletter_id' , 'id');
    }

    public function storeWebsite()
    {
        return $this->hasOne(\App\StoreWebsite::class, 'id', 'store_website_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'newsletter_products', 'newsletter_id', 'product_id','id','id');

    }

    public function mailinglist()
    {
        return $this->hasOne(\App\Mailinglist::class, 'id', 'mail_list_id');
    }

}
