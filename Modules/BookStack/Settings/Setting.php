<?php namespace Modules\BookStack\Settings;

use Modules\BookStack\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value'];

    protected $primaryKey = 'name';
}
