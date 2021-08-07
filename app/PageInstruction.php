<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class PageInstruction extends Model
{
    /**
     * @var string
     * @SWG\Property(property="page",type="strng")
     * @SWG\Property(property="instruction",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     * @SWG\Property(property="created_by",type="integer")
     */
    protected $fillable = [
        'page',
        'instruction',
        'created_by',
        'created_at',
        'updated_at'
    ];

}
