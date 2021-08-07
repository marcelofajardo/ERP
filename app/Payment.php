<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Payment extends Model{
 /**
     * @var string
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="payment_method_id",type="integer")
     * @SWG\Property(property="note",type="string")
     * @SWG\Property(property="amount",type="float")
     * @SWG\Property(property="paid",type="float")
     * @SWG\Property(property="payment_receipt_id",type="interger")
     * @SWG\Property(property="date",type="datetime")
     * @SWG\Property(property="currency",type="string")
     */
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'note',
        'amount',
        'paid',
        'payment_receipt_id',
        'date',
        'currency'
    ];

    public static function getConsidatedUserPayments(){
        return self::groupBy('user_id')
            ->selectRaw('user_id, SUM(amount) as paid')
            ->get();
    }
        
}