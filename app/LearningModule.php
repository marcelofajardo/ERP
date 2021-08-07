<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nestable\NestableTrait;

class LearningModule extends Model
{
		  /**
     * @var string
      * @SWG\Property(property="title",type="string")
      * @SWG\Property(property="parent_id",type="integer")
      * @SWG\Property(property="is_approved",type="boolean")
      * @SWG\Property(property="is_active",type="boolean")
     */
	use SoftDeletes;
	use NestableTrait;

	protected $parent = 'parent_id';
	protected $fillable = [
		'title', 'parent_id', 'is_approved'
	];
}
