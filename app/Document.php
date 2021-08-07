<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;
use App\DocumentCategory;
use Storage;

class Document extends Model
{
       /**
   * @SWG\Property(property="user_id",type="integer")
   * @SWG\Property(property="name",type="string")
   * @SWG\Property(property="filename",type="string")
   * @SWG\Property(property="category_id",type="integer")
   * @SWG\Property(property="version",type="string")
   * @SWG\Property(property="status",type="string")
        */
    protected $fillable = [
        'user_id',
        'name',
        'filename',
        'file_contents',
        'category_id',
        'version',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getDocumentPathById($id)
    {
        $document = $this::find($id);
        return Storage::disk('files')->url('documents/' . $document->filename);
    }

    public function documentCategory()
    {
        return $this->hasOne(DocumentCategory::class, 'id', 'category_id');
    }
}
