<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 */
class CustomerBulkMessageDND extends Model
{
  
public $table = 'customer_bulk_messages_dnd';

public $incrementing = false;

  protected $fillable = [
    'customer_id', 'filter'
  ];

  
}
