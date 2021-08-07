<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SuggestionProduct extends Model
{
	       /**
     * @var string
      * @SWG\Property(property="suggestion_id",type="integer")
      * @SWG\Property(property="product_id",type="integer")
      * @SWG\Property(property="created_at",type="datetime")
      * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'suggestion_id', 'product_id', 'created_at', 'updated_at',
    ];
}
