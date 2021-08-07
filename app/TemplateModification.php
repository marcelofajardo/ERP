<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateModification extends Model
{
    protected $fillable=['tag','value','template_id','row_index'];
}
