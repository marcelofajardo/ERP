<?php

namespace App;


use DB;
use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class Coupon extends Model
{
    
    /**
     * @var string
     @SWG\Property(property="magento_id",type="integer")
     * @SWG\Property(property="code",type="string")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="start",type="string")
     * @SWG\Property(property="expiration",type="string")
     * @SWG\Property(property="details",type="string")
     * @SWG\Property(property="currency",type="string")
     * @SWG\Property(property="discount_fixed",type="integer")
     * @SWG\Property(property="discount_percentage",type="integer")
     * @SWG\Property(property="maximum_usage",type="integer")
     * @SWG\Property(property="usage_count",type="integer")
     * @SWG\Property(property="coupon_type",type="string")
     * @SWG\Property(property="email",type="sting")
     * @SWG\Property(property="status",type="sting")
     * @SWG\Property(property="initial_amount",type="sting")
     * @SWG\Property(property="uuid",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="deleted_at",type="datetime")
     */
    protected $fillable = [
        'magento_id', 'code', 'description', 'start', 'expiration', 'details', 'currency', 'discount_fixed', 'discount_percentage', 'minimum_order_amount', 'maximum_usage', 'usage_count','coupon_type','email','status','initial_amount','uuid'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $appends = ['discount'];

    public function getDiscountAttribute()
    {
        $discount = '';
        if ($this->currency) {
            $discount .= $this->currency . ' ';
        }
        if ($this->discount_fixed) {
            $discount .= $this->discount_fixed . ' fixed plus ';
        }
        if ($this->discount_percentage) {
            $discount .= $this->discount_percentage . '% discount';
        }
        return $discount;
    }

    public static function usageCount($couponIds)
    {
        $query =  DB::table('orders')
            ->select('coupon_id', DB::raw('count(*) as count'))
            ->groupBy('coupon_id');

        foreach ($couponIds as $couponId) {
            $query->orHaving('coupon_id', '=', $couponId);
        }

        return $query->get();
    }

    public function usage()
    {
        return $this->hasMany(
            'App\Order',
            'coupon_id'
        );
    }
}
