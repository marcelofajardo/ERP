<?php

namespace App\Github;

use Illuminate\Database\Eloquent\Model;

class GithubGroupMember extends Model{

    public $timestamps = false;

    protected $fillable = [
        'github_groups_id',
        'github_users_id'
    ];
}