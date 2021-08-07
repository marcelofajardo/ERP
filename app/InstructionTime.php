<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class InstructionTime extends Model
{
  	   /**
     * @var string
     * @SWG\Property(property="start",type="datetime")
     * @SWG\Property(property="end",type="datetime")
     * @SWG\Property(property="instructions_id",type="integer")
     * @SWG\Property(property="total_minutes",type="integer")

     */
  protected $fillable = [
    'start', 'end', 'instructions_id', 'total_minutes'
  ];

}
