<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class LaravelLog extends Model
{/**
     * @var string
     * @SWG\Property(property="log_created",type="string")
     */
    protected $dates = [
        'log_created',
    ];
}
