<?php

namespace App;

use App\AssetsCategory;
use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class AssetsManager extends Model
{

    //use SoftDeletes;
    protected $table = 'assets_manager';

    protected $casts = [
        'notes' => 'array',
    ];
    /**
     * @var string
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="capacity",type="string")
     * @SWG\Property(property="asset_type",type="string")
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="purchase_type",type="string")
     * @SWG\Property(property="payment_cycle",type="string")
     * @SWG\Property(property="amount",type="integer")
     * @SWG\Property(property="archived",type="string")
     * @SWG\Property(property="password",type="password")
     * @SWG\Property(property="provider_name",type="string")
     * @SWG\Property(property="location",type="string")
     * @SWG\Property(property="currency",type="string")
     * @SWG\Property(property="usage",type="string")
     * @SWG\Property(property="due_date",type="datetime")
     */
    protected $fillable = [
        'name', 'capacity', 'asset_type', 'category_id', 'purchase_type', 'payment_cycle', 'amount', 'archived', 'password', 'provider_name', 'location', 'currency','usage','due_date'];

    public function category()
    {
        return $this->hasOne(AssetsCategory::class, 'id', 'category_id');
    }

    public static function assertTypeList()
    {
        return [
            ""     => "-- Assert Type --",
            "Hard" => "Hard",
            "Soft" => "Soft",
        ];
    }

    public static function purchaseTypeList()
    {
        return [
            ""             => "-- Purchase Type --",
            "Owned"        => "Owned",
            "Rented"       => "Rented",
            "Subscription" => "Subscription",
        ];
    }

    public static function paymentCycleList()
    {
        return [
            ""          => "-- Payment Cycle --",
            "Daily"     => "Daily",
            "Weekly"    => "Weekly",
            "Bi-Weekly" => "Bi-Weekly",
            "Monthly"   => "Monthly",
            "Yearly"    => "Yearly",
            "One time"  => "One time",
        ];
    }

}
