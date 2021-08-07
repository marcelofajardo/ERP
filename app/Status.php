<?php


namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class Status extends ReadOnlyBase {

	protected $data =[
		'Cold Lead' => '1',
		'Cold / Important Lead' => '2',
		'Hot Lead' => '3',
		'Very Hot Lead' => '4',
		'Advance Follow Up' => '5',
		'HIGH PRIORITY' => '6',		
	];

	protected $messagestatus =[
		'Reply Pending' => '1',
		'Approval Pending' => '2',
		'Approved' => '3',
		'Send' => '4',
	];

}
