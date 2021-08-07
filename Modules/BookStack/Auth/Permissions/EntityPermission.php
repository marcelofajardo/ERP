<?php namespace Modules\BookStack\Auth\Permissions;

use Modules\BookStack\Model;

class EntityPermission extends Model
{

    protected $fillable = ['role_id', 'action'];
    public $timestamps = false;

    /**
     * Get all this restriction's attached entity.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function restrictable()
    {
        return $this->morphTo('restrictable');
    }
}
