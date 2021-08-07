<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalCase extends Model
{
     /**
     * @var string
     * @SWG\Property(property="cases",type="string")
     * @SWG\Property(property="lawyer_id",type="integer")
      * @SWG\Property(property="case_number",type="string")
      * @SWG\Property(property="for_against",type="string")
      * @SWG\Property(property="court_detail",type="string")
     * @SWG\Property(property="phone",type="string")
     * @SWG\Property(property="default_phone",type="string")
     * @SWG\Property(property="whatsapp_number",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="resource",type="string")
     * @SWG\Property(property="last_date",type="datetime")
     * @SWG\Property(property="next_date",type="datetime")
     * @SWG\Property(property="cost_per_hearing",type="string")
     * @SWG\Property(property="remarks",type="string")
     * @SWG\Property(property="other",type="string")
     * @SWG\Property(property="deleted_at",type="datetime")
     */
    use SoftDeletes;
    protected $table = 'cases';
    protected $fillable = ['lawyer_id', 'case_number', 'for_against', 'court_detail', 'phone','default_phone', 'whatsapp_number', 'status', 'resource', 'last_date', 'next_date', 'cost_per_hearing', 'remarks', 'other'];
    protected $dates = ['deleted_at'];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class,'lawyer_id');
    }

    public function chat_message()
    {
        return $this->hasMany(ChatMessage::class,'case_id');
    }

    public function costs()
    {
        return $this->hasMany(CaseCost::class,'case_id');
    }

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'cash_flow_able');
    }

    public function receivables()
    {
        return $this->hasMany(CaseReceivable::class,'case_id');
    }
}
