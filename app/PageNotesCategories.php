<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class PageNotesCategories extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="name",type="strng")
     * @SWG\Property(property="timestamps",type="false")
     */
	public $timestamps = false;
    protected $fillable = [
        'name',
    ];
}
