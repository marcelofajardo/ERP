<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Category_translation extends Model
{
        /**
     * @var string
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="locale",type="string")
     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="site_id",type="integer")
     * @SWG\Property(property="is_rejected",type="boolen")
     */
    protected $fillable = [
        'category_id',
        'locale',
        'title',
        'site_id',
        'is_rejected'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function site()
    {
        return $this->hasOne(StoreWebsite::class, 'id', 'site_id');
    }
}
