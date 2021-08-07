<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Plank\Mediable\Mediable;
use Spatie\Activitylog\Traits\LogsActivity;


class Leads extends Model {
	  /**
     * @var string
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="client_name",type="string")
     * @SWG\Property(property="city",type="string")
     * @SWG\Property(property="contactno",type="string")
     * @SWG\Property(property="solophone",type="string")
     * @SWG\Property(property="rating",type="string")
     * @SWG\Property(property="instahandler",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="userid",type="integer")

     * @SWG\Property(property="comments",type="sting")
     * @SWG\Property(property="assigned_user",type="sting")
     * @SWG\Property(property="selected_product",type="sting")
     * @SWG\Property(property="size",type="sting")
     * @SWG\Property(property="address",type="sting")
     * @SWG\Property(property="email",type="sting")
     * @SWG\Property(property="source",type="sting")
     * @SWG\Property(property="brand",type="sting")
     * @SWG\Property(property="leadsourcetxt",type="sting")
     * @SWG\Property(property="multi_brand",type="sting")
     * @SWG\Property(property="multi_category",type="sting")
     * @SWG\Property(property="remark",type="sting")
     * @SWG\Property(property="whatsapp_number",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="communication",type="sting")
     */
	use Mediable;
	use SoftDeletes;
	protected $fillable = [
		'customer_id',
		'client_name',
		'city',
		'contactno',
		'solophone',
		'rating',
		'instahandler',
		'status',
		'userid',
		'comments',
		'assigned_user',
		'selected_product',
		'size',
		'address',
		'email',
		'source',
		'brand',
		'leadsourcetxt',
		'multi_brand',
		'multi_category',
		'remark',
		'whatsapp_number',
		'created_at'
	];

	const CREATED_AT = null;

	protected $appends = ['communication'];
	protected $communication = '';

	public function customer()
	{
		return $this->belongsTo('App\Customer');
	}

	public function messages()
	{
		return $this->hasMany('App\Message', 'moduleid')->where('moduletype', 'leads')->latest()->first();
	}

	public function whatsapps()
	{
		return $this->hasMany('App\ChatMessage', 'lead_id')->latest()->first();
	}

	public function status_changes()
	{
		return $this->hasMany('App\StatusChange', 'model_id')->where('model_type', 'App\Leads')->latest();
	}

	public function instagram()
	{
		return $this->hasMany('App\InstaMessages', 'lead_id')->latest()->first();
	}

	public function getCommunicationAttribute()
	{
		$message  = $this->messages();
		$whatsapp = $this->whatsapps();
		$instagram = $this->instagram();

		if (!empty($message) && !empty($whatsapp) && !empty($instagram)) {
			if ($message->created_at > $whatsapp->created_at && $message->created_at > $instagram->created_at) {
				return $message;
			} elseif ($whatsapp->created_at > $message->created_at && $whatsapp->created_at > $instagram->created_at) {
				return $whatsapp;
			} elseif ($instagram->created_at > $message->created_at && $instagram->created_at > $whatsapp->created_at) {
				return $instagram;
			}
		} elseif(!empty($message) && !empty($whatsapp)) {
			if($message->created_at > $whatsapp->created_at) {
				return $message;
			} else {
				return $whatsapp;
			}
		} elseif(!empty($message) && !empty($instagram)) {
			if($message->created_at > $instagram->created_at) {
				return $message;
			} else {
				return $instagram;
			}
		} elseif(!empty($whatsapp) && !empty($instagram)) {
			if($whatsapp->created_at > $instagram->created_at) {
				return $whatsapp;
			} else {
				return $instagram;
			}
		} elseif(!empty($whatsapp)) {
			return $whatsapp;
		} elseif(!empty($instagram)) {
			return $instagram;
		} elseif(!empty($message)) {
			return $message;
		}
	}
}
