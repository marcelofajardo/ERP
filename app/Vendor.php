<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Vendor extends Model
{
         /**
     * @var string
      * @SWG\Property(property="category_id",type="integer")
      * @SWG\Property(property="name",type="string")
      * @SWG\Property(property="address",type="string")
      * @SWG\Property(property="phone",type="string")
      * @SWG\Property(property="default_phone",type="string")
      * @SWG\Property(property="whatsapp_number",type="string")
      * @SWG\Property(property="email",type="string")
      * @SWG\Property(property="social_handle",type="string")
      * @SWG\Property(property="website",type="string")
      * @SWG\Property(property="login",type="string")
      * @SWG\Property(property="password",type="string")
      * @SWG\Property(property="gst",type="string")
      * @SWG\Property(property="account_name",type="string")
      * @SWG\Property(property="account_iban",type="string")
      * @SWG\Property(property="is_blocked",type="boolean")
      * @SWG\Property(property="frequency",type="string")
      * @SWG\Property(property="reminder_last_reply",type="string")
      * @SWG\Property(property="reminder_message",type="string")
      * @SWG\Property(property="frequency_of_payment",type="string")
      * @SWG\Property(property="updated_by",type="integer")
      * @SWG\Property(property="status",type="string")
      * @SWG\Property(property="bank_name",type="string")
      * @SWG\Property(property="bank_address",type="string")
      * @SWG\Property(property="city",type="string")
      * @SWG\Property(property="country",type="string")
      * @SWG\Property(property="staifsc_codetus",type="string")
      * @SWG\Property(property="remark",type="string")
     */
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'address',
        'phone',
        'default_phone',
        'whatsapp_number',
        'email',
        'social_handle',
        'website',
        'login',
        'password',
        'gst',
        'account_name',
        'account_swift',
        'account_iban',
        'is_blocked',
        'frequency',
        'reminder_message',
        'reminder_last_reply',
        'reminder_from',
        'updated_by',
        'status',
        'frequency_of_payment', 'bank_name', 'bank_address', 'city', 'country', 'ifsc_code', 'remark','chat_session_id'
    ];

    protected static function boot()
    {
        parent::boot();
        self::updating(function ($model) {
            if(!empty(\Auth::id())) {
               $model->updated_by = \Auth::id();
            }
        });
        self::saving(function ($model) {
            if(!empty(\Auth::id())) {
               $model->updated_by = \Auth::id();
            }
        });
        self::creating(function ($model) {
            if(!empty(\Auth::id())) {
               $model->updated_by = \Auth::id();
            }
        });
    }

    public function products()
    {
        return $this->hasMany('App\VendorProduct');
    }

    public function agents()
    {
        return $this->hasMany('App\Agent', 'model_id')->where('model_type', 'App\Vendor');
    }

    public function chat_messages()
    {
        return $this->hasMany('App\ChatMessage')->orderBy('id','desc');
    }

    public function category()
    {
        return $this->belongsTo('App\VendorCategory');
    }

    public function payments()
    {
        return $this->hasMany(VendorPayment::class);
    }

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'cash_flow_able');
    }

    public function whatsappAll($needBroadCast = false)
    {
        if($needBroadCast) {
            return $this->hasMany('App\ChatMessage', 'vendor_id')->whereIn('status', ['7', '8', '9', '10'])->latest();    
        }

        return $this->hasMany('App\ChatMessage', 'vendor_id')->whereNotIn('status', ['7', '8', '9', '10'])->latest();
    }

    public function emails()
    {
        return $this->hasMany('App\Email', 'model_id', 'id');
    }

    public function whatsappLastTwentyFourHours()
    {
        return $this->hasMany('App\ChatMessage')->where('created_at','>=', Carbon::now()->subDay()->toDateTimeString())->orderBy('id','desc');
    }

    /**
     *  Get information by ids
     *  @param []
     *  @return Mixed
     */

    public static function getInfoByIds($ids, $fields = ["*"], $toArray = false)
    {
        $list = self::whereIn("id",$ids)->select($fields)->get();

        if($toArray) {
            $list = $list->toArray();
        }

        return $list;
    }
}
