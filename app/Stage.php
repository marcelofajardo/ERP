<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Stage extends ReadOnlyBase {

 
	protected $data =[
		'Selection' => '1',
		'Searcher' => '1',
		'Attribute' => '1',
		'Supervisor' => '1',
		'ImageCropper' => '1',
		'Lister' => '1',
		'Approver' => '1',
		'Inventory' => '1',
		'Sale' => '1'
	];

	

}