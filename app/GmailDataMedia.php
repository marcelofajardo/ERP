<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GmailDataMedia extends Model
{
    protected $fillable = [
        'gmail_data_list_id',
        'images',
        'page_url',
    ];

    public function gmailDataList()
    {
        return $this->belongsTo(GmailDataList::class);
    }
}
