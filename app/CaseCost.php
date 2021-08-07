<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class CaseCost extends Model
{
	/**
     * @var string
  * @SWG\Property(property="case_id",type="integer")
     * @SWG\Property(property="billed_date",type="datetime")
     * @SWG\Property(property="amount",type="integer")
     * @SWG\Property(property="paid_date",type="datetime")
     * @SWG\Property(property="amount_paid",type="string")
     * @SWG\Property(property="other",type="string")
     */
    protected $fillable =['case_id','billed_date','amount','paid_date','amount_paid','other'];

    public function case()
    {
        return $this->belongsTo(LegalCase::class,'case_id');
    }
}
