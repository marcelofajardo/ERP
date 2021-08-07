<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAddressData extends Model
{
    //
    protected $fillable = [
        'customer_id', 'entity_id', 'address_type', 'region', 'region_id', 'postcode', 'firstname', 'middlename', 'company','country_id', 'telephone','prefix','street'
      ];
}
