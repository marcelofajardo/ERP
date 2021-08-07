<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class InstructionCategory extends Model
{
	   /**
     * @var string
     * @SWG\Property(property="name",type="string")

     */
  protected $fillable = ['name'];

  public function instructions()
  {
    return $this->hasMany('App\Instruction', 'category_id');
  }
}
