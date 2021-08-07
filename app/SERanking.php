<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class SERanking extends Model
{
    /**
     * @var string
     * @SWG\Property(property="id",type="integer")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="group_id",type="integer")
     * @SWG\Property(property="link",type="string")
     * @SWG\Property(property="first_check_date",type="string")
     */
    /**
     * Fillables for the database
     *
     * @access protected
     *
     * @var array $fillable
     */
    protected $fillable = array(
        'id', 'name', 'group_id', 'link',
        'first_check_date'
    );
}
