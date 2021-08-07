<?php
/**
 * Created by PhpStorm.
 * User: mustafaflexwala
 * Date: 23/10/18
 * Time: 5:20 PM
 */

namespace App\ReadOnly;


use App\ReadOnlyBase;

class PaymentModes extends ReadOnlyBase {

	protected $data = [
		'cash on delivery' => 'cash on delivery',
		'paytm' => 'paytm',
		'zestpay' => 'zestpay',
		'bank transfer' => 'bank transfer',
	];
}
