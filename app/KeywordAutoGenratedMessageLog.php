<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeywordAutoGenratedMessageLog extends Model
{
    //
    protected $fillable = ['model','model_id','keyword','keyword_match','message_sent_id','comment'];
}
