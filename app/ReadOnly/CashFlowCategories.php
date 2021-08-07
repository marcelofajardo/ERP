<?php
/**
 * Created by PhpStorm.
 * User: mustafaflexwala
 * Date: 13/10/18
 * Time: 7:12 PM
 */

namespace App\ReadOnly;


use App\ReadOnlyBase;

class CashFlowCategories extends ReadOnlyBase {

	protected $data = [
		'received'	=> [
			'1'	=> 'General',
			'2'	=> 'Car',
		],
		'paid'	=> [
			'3'	=> 'Paid Category',
			'4'	=> 'Paid Category 2',
		]
	];
}
