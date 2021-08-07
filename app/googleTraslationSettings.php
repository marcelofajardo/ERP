<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class googleTraslationSettings extends Model
{
    protected $fillable = [
		'email', 'account_json','status', 'last_note',
	];
}
