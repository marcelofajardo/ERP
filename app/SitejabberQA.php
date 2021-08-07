<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SitejabberQA extends Model
{
   /**
     * @var string
     * @SWG\Property(property="sitejabber_q_a_s",type="string")
 
     */
    protected $table = 'sitejabber_q_a_s';

    public function answers() {
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }
}
