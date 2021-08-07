<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class AdAccount extends Model
{
	/**
     * @var string    
     * @SWG\Property(property="account_name",type="string")
     * @SWG\Property(property="note",type="text")
     * @SWG\Property(property="config_file",type="string")
     * @SWG\Property(property="status",type="string")
     */
    protected $fillable = [
        'account_name', 'note', 'config_file', 'status',
    ];
}
