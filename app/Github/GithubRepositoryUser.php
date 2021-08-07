<?php

namespace App\Github;

use Illuminate\Database\Eloquent\Model;

class GithubRepositoryUser extends Model{

    protected $fillable = [
        'id',
        'github_repositories_id',
        'github_users_id',
        'rights'
    ];

    public function githubUser(){
        return $this->hasOne('App\Github\GithubUser', 'id', 'github_users_id');
    }

    public function githubRepository(){
        return $this->hasOne('App\Github\GithubRepository', 'id', 'github_repositories_id');
    }

}