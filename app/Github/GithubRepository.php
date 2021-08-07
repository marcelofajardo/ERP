<?php

namespace App\Github;

use Illuminate\Database\Eloquent\Model;

class GithubRepository extends Model
{
    protected $fillable = [
        'id',
        'name',
        'html',
        'webhook',
        'created_at',
        'updated_at'
    ];

    public function users()
    {
        return $this->belongsToMany(
            'App\Github\GithubUser',
            'github_repository_users',
            'github_repositories_id',
            'github_users_id'
        )
            ->withPivot(['id', 'rights']);
    }

    public function branches()
    {
        return $this->hasMany(
            'App\Github\GithubBranchState',
            'repository_id',
            'id'
        )->orderBy('last_commit_time', 'desc');
    }

}
