<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\StoreWebsite;
use Illuminate\Database\Eloquent\Model;

class Mailinglist extends Model
{
    /**
     * @var string
     * @SWG\Property(property="id",type="integer")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="remote_id",type="integer")
     * @SWG\Property(property="service_id",type="integer")
     * @SWG\Property(property="website_id",type="integer")
     * @SWG\Property(property="email",type="string")
     */
    protected $fillable = ['id', 'name', 'remote_id', 'service_id','website_id','email'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
       return $this->belongsTo(Service::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function website()
    {
       return $this->hasOne(StoreWebsite::class,'id','website_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function listCustomers()
    {
        return $this->belongsToMany(Customer::class, 'list_contacts', 'list_id', 'customer_id')->withTimestamps();
    }

}
