<?php
/**
 * Created by PhpStorm.
 * User: mustafaflexwala
 * Date: 13/10/18
 * Time: 7:12 PM
 */

namespace App\ReadOnly;


use App\ReadOnlyBase;

class LocationList extends ReadOnlyBase {

	protected $data = [
		'Mulund'			=> 'Mulund',
		'Jogeshwari'	=> 'Jogeshwari',
		'Malad'				=> 'Malad',
		'Pune'				=> 'Pune',
		'Dubai'				=> 'Dubai',
		'Customs'			=> 'Customs',
		'Mumbai'			=> 'Mumbai',
	];
}
