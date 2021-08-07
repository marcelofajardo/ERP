<?php
/**
 * Created by PhpStorm.
 * User: mustafaflexwala
 * Date: 13/10/18
 * Time: 7:12 PM
 */

namespace App\ReadOnly;

use App\ReadOnlyBase;

class OrderStatus extends ReadOnlyBase
{

    protected $data = [
        2  => 'Follow up for advance',
        4  => 'Proceed without advance',
        13 => 'Advance received',
        11 => 'Cancel',
        3  => 'Prepaid',
        7  => 'Product shipped from italy',
        14 => 'In Transist from Italy',
        9  => 'Product shipped to client',
        10 => 'Delivered',
        15 => 'Refund to be processed',
        16 => 'Refund Dispatched',
        17 => 'Refund Credited',
        18 => 'VIP',
        19 => 'HIGH PRIORITY',
    ];

//     protected $data = [
    // //        'Order' => 'Order',
    // //        'Advance' => 'Advance',
    //         'Follow up for advance' => 'Follow up for advance',
    //         'Proceed without Advance' => 'Proceed without Advance',
    //         'Advance received' => 'Advance received',
    //         'Cancel' => 'Cancel',
    //         'Prepaid' => 'Prepaid',
    //         'Product Shiped form Italy' => 'Product Shiped form Italy',
    //         'In Transist from Italy' => 'In Transist from Italy',
    //         'Product shiped to Client' => 'Product shiped to Client',
    //         'Delivered' => 'Delivered',
    //         'Refund to be processed' => 'Refund to be processed',
    //         'Refund Dispatched' => 'Refund Dispatched',
    //         'Refund Credited' => 'Refund Credited',
    //         'VIP'    => 'VIP',
    //         'HIGH PRIORITY'    => 'HIGH PRIORITY'
    //     ];
}
