<?php

namespace App\Github;

use Illuminate\Database\Eloquent\Model;

class GithubRepositoryGroup extends Model{
    protected $fillable = [
        'github_repositories_id',
        'github_groups_id',
        'rights'
    ];
}