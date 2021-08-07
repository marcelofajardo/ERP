<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreSocialContentReview extends Model
{
	/**
     * @var string
      * @SWG\Property(property="file_id",type="integer")
     * @SWG\Property(property="review",type="string")
     * @SWG\Property(property="review_by",type="string")
     */
    protected $fillable = ['file_id','review','review_by'];
}
