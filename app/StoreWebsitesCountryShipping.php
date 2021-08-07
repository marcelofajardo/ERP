<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreWebsitesCountryShipping extends Model
{
    protected $table = 'store_websites_country_shipping';

	protected $fillable = ['store_website_id', 'country_code','country_name','price','currency','ship_id'];

	public function storeWebsiteDetails() {
        return $this->belongsTo(StoreWebsite::class, 'store_website_id', 'id');
    }
}
