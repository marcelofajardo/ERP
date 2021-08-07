<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MagentoUserPasswordHistory extends Model
{
    protected $table = 'magento_user_password_history';
    protected $fillable = [
        'store_website_userid','old_password','new_password'
    ];
}
