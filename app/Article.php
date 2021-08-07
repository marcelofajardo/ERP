<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Article extends Model
{
    /**
     * Fillables for the database
     *
     * @access protected
     *
     * @var array $fillable
     */
    /**
     * @var string
     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="description",type="text")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="remark",type="string")
     * @SWG\Property(property="assign_to",type="string")
     * @SWG\Property(property="posted_to",type="string")
     */
    protected $fillable = array(
        'title', 'description', 'status', 'remark', 'assign_to', 'posted_to' 
    );

}
