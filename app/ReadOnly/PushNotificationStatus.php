<?php
/**
 * Created by PhpStorm.
 * User: mustafaflexwala
 * Date: 05/11/18
 * Time: 12:03 PM
 */

namespace App\ReadOnly;


use App\ReadOnlyBase;

class PushNotificationStatus extends ReadOnlyBase {

	protected $data = [
		1   => 'Accepted',
		2   => 'Postponed',
		3  => 'Rejected',
	];
}