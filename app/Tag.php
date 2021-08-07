<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
 		    /**
     * @var string
      * @SWG\Property(property="book_tags",type="string")
      * @SWG\Property(property="tag",type="string")
     */
  protected $table = 'book_tags';	
  protected $fillable = ['tag'];
}
