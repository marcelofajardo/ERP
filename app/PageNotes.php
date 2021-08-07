<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class PageNotes extends Model
{
     /**
     * @var string
     * @SWG\Property(property="url",type="strng")
     * @SWG\Property(property="note",type="string")
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     */
    protected $fillable = [
        'url', 'category_id', 'note', 'user_id',
    ];

    public function user()
    {
    	return $this->hasOne("\App\User","id", "user_id");
    }

    public function pageNotesCategories()
    {
    	return $this->hasOne("\App\PageNotesCategories","id", "category_id");
    }
}
