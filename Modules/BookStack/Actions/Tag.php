<?php namespace Modules\BookStack\Actions;

use Modules\BookStack\Model;

/**
 * Class Attribute
 * @package BookStack
 */
class Tag extends Model
{
	protected $table = 'book_tags';    
    protected $fillable = ['name', 'value', 'order'];

    /**
     * Get the entity that this tag belongs to
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo('entity');
    }
}
