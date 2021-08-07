<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GmailDataList extends Model
{
    protected $fillable = [
        'sender',
        'received_at',
        'domain',
        'status',
        'tags',
    ];

    public function gmailDataMedia()
    {
        return $this->hasMany(GmailDataMedia::class);
    }
}
