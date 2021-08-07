<?php

namespace App\Hubstaff;

use Illuminate\Database\Eloquent\Model;

class HubstaffPaymentAccount extends Model
{
    const STATUS = [
        "1" => "Pending",
        "2" => "Done",
        "3" => "Partial Done"
    ];

    protected $fillable = [
        'user_id',
        'accounted_at',
        'amount',
        'billing_start',
        'billing_end',
        'hrs',
        'rate',
        'currency',
        'payment_currency',
        'ex_rate',
        'status',
        'payment_info',
        'payment_remark',
        'scheduled_on',
    ];

    public static function getConsidatedUserAmountToBePaid()
    {
        return self::groupBy('user_id')
            ->selectRaw('user_id, SUM(amount) as amount')
            ->get();
    }
}
