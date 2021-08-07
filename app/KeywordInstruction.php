<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class KeywordInstruction extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="keywords",type="string")
     */
    protected $casts = [
        'keywords' => 'array'
    ];

    public function instruction() {
        return $this->belongsTo(InstructionCategory::class, 'instruction_category_id', 'id');
    }
}
