<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ImQueue;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class AutoCompleteMessage extends Model
{
 
  protected $fillable = [
    'id', 'message', 'created_at', 'updated_at'
  ];

  public $table = 'auto_complete_messages';
}
