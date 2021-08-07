<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class CaseReceivable extends Model
{
    use SoftDeletes;
      /**
     * @var string
     * @SWG\Property(property="case_id",type="integer")
     * @SWG\Property(property="currency",type="string")
     * @SWG\Property(property="receivable_date",type="datetime")
     * @SWG\Property(property="received_date",type="datetime")
     * @SWG\Property(property="receivable_amount",type="integer")
     * @SWG\Property(property="received_amount",type="integer")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="other",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="updated_by",type="datetime")
     * @SWG\Property(property="deleted_at",type="datetime")
 
     */
    protected  $fillable = ['case_id','currency','receivable_date','received_date','receivable_amount','received_amount','description','other','status','user_id','updated_by'];
    /**
     * @var string
     * @SWG\Property(enum={"model_id", "model_type", "name", "phone", "whatsapp_number", "address", "email"})
     */
    protected  $dates = ['deleted_at'];

    public function case()
    {
        return $this->belongsTo(LegalCase::class,'case_id');
    }
}
