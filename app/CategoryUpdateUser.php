<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class CategoryUpdateUser extends Model
{
    /**
     * @var string
     * @SWG\Property(property="supplier_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     */
    public $fillable = [
        "supplier_id",
        "user_id"
    ];

    public function user()
    {
        return $this->hasOne(\App\User::class, "id","user_id");
    }

    public function supplier()
    {
        return $this->hasOne(\App\Supplier::class, "id", "supplier_id");
    }
}
