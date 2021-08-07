<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="StoreWebsiteSeoFormat"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteSeoFormat extends Model
{
    //

      /**
     * @var string
    
     * @SWG\Property(property="meta_title",type="string")
     * @SWG\Property(property="meta_keyword",type="string")
     * @SWG\Property(property="meta_description",type="string")
     * @SWG\Property(property="store_website_id",type="integer")
     */
    protected $fillable = [
        'meta_title',
        'meta_keyword',
        'meta_description',
        'store_website_id'
    ];

}
