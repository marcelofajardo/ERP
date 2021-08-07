<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProductAi;

class OrderHelper extends Model
{
    public static $orderRecieved = 1;
    public static $followUpForAdvance = 2;
    public static $prepaid = 3;
    public static $proceedWithOutAdvance = 4;
    public static $pendingPurchase = 5;
    public static $purchaseComplete = 6;
    public static $productShippedFromItaly = 7;
    public static $productInStock = 8;
    public static $productShippedToClient = 9;
    public static $delivered = 10;
    public static $cancel = 11;
    public static $fraud = 12;
    public static $advanceRecieved = 13;
    public static $inTransistFromItaly = 14;
    public static $refundToBeProcessed = 15;
    public static $refundDispatched = 16;
    public static $refundCredited = 17;
    public static $vip = 18;
    public static $highPriority = 19;
    

    public static function getStatus()
    {
        return [
            1 => 'Order Received',
            2 => 'Follow up for advance',
            3 => 'Prepaid',
            4 => 'Proceed without advance',
            5 => 'Pending purchase (advance received)',
            6 => 'Purchase complete',
            7 => 'Product shipped from italy',
            8 => 'Product in stock',
            9 => 'Product shipped to client',
            10 => 'Delivered',
            11 => 'Cancel',
            12 => 'Fraud',
            13 => 'Advance received',
            14 => 'In Transist from Italy',
            15 => 'Refund to be processed',
            16 => 'Refund Dispatched',
            17 => 'Refund Credited',
            18 => 'VIP',
            19 => 'HIGH PRIORITY',
        ];
    }

    public static function getStatusNameById($id){
        foreach (self::getStatus() as $status_id => $value) {
            if($status_id == $id){
                return $value;
            }
        }
    }
}
