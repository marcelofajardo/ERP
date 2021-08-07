<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lawyer extends Model
{
/**
     * @var string
     * @SWG\Property(property="phone",type="string")
     * @SWG\Property(property="default_phone",type="string")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="email",type="string")
     * @SWG\Property(property="address",type="string")
     * @SWG\Property(property="referenced_by",type="integer")
     * @SWG\Property(property="speciality_id",type="integer")
     * @SWG\Property(property="whatsapp_number",type="integer")
     * @SWG\Property(property="rating",type="string")
     * @SWG\Property(property="remarks",type="string")
     * @SWG\Property(property="other",type="string")
     */
    use SoftDeletes;

    protected $fillable = ['name','phone','default_phone','email','address','referenced_by','speciality_id','rating','whatsapp_numberwhatsapp_number','remarks','other'];

    public function lawyerSpeciality()
    {
        return $this->belongsTo(LawyerSpeciality::class,'speciality_id');
    }

    public function getSpecialityAttribute()
    {
        return optional($this->lawyerSpeciality)->title;
    }

    public function chat_message()
    {
        return $this->hasMany(ChatMessage::class,'lawyer_id');
    }
}
