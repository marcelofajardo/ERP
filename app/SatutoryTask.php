<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatutoryTask extends Model {
	/**
     * @var string
     * @SWG\Property(property="category",type="string")
     * @SWG\Property(property="assign_from",type="integer")
     * @SWG\Property(property="assign_to",type="interger")
     * @SWG\Property(property="task_details",type="string")
     * @SWG\Property(property="task_subject",type="interger")
     * @SWG\Property(property="remark",type="string")
     * @SWG\Property(property="recurring_type",type="string")
     * @SWG\Property(property="recurring_day",type="string")
     */
	use SoftDeletes;

	protected $fillable = [
		'category',
		'assign_from',
		'assign_to',
		'task_details',
		'task_subject',
		'remark',
		'recurring_type',
		'recurring_day',
	];
}
