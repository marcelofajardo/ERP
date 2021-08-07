<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class BloggerPayment extends Model
{
    use SoftDeletes;
      /**
     * @var string
     * @SWG\Property(property="blogger_id",type="integer")
     * @SWG\Property(property="payment_date",type="date")
     * @SWG\Property(property="paid_date",type="date")
     * @SWG\Property(property="payable_amount",type="integer")
     * @SWG\Property(property="paid_amount",type="integer")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="currency",type="integer")
     * @SWG\Property(property="other",type="sting")
     * @SWG\Property(property="status",type="sting")
     * @SWG\Property(property="updated_by",type="datetime")

     */
    protected $fillable = ['blogger_id', 'payment_date', 'paid_date', 'payable_amount', 'paid_amount', 'description', 'other', 'status', 'user_id', 'updated_by', 'currency'];
    /**
     * @var string
     * @SWG\Property(enum={"deleted_at"})
     */
    protected $dates = ['deleted_at'];

    public function blogger()
    {
        return $this->belongsTo(Blogger::class);
    }
}
