<?php
/**
 * Created by PhpStorm.
 * User: mustafaflexwala
 * Date: 16/08/18
 * Time: 2:51 PM
 */

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */



class Colors extends ReadOnlyBase {

	protected $data = [
		'Black'  => 'Black',
		'White'  => 'White',
		'Pink'   => 'Pink',
		'Brown'  => 'Brown',
		'Purple' => 'Purple',
		'Gold'   => 'Gold',
		'Silver' => 'Silver',
		'Navy'   => 'Navy',
		'Green'  => 'Green',
		'Yellow' => 'Yellow',
		'Multi'  => 'Multi',
		'Maroon' => 'Maroon',
		'Orange' => 'Orange',
		'Red'    => 'Red',
		'Grey'   => 'Grey',
		'Blue'   => 'Blue',
		'Nude'   => 'Nude',
		'Tan'    => 'Tan',
		'Beige'  => 'Beige',
	];
}