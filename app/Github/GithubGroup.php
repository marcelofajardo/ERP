<?php

namespace App\Github;

use DB;
use Illuminate\Database\Eloquent\Model;

class GithubGroup extends Model
{

    protected $fillable = [
        'id',
        'name'
    ];



    public function users()
    {
        return $this->belongsToMany(
            'App\Github\GithubUser',
            'github_group_members',
            'github_groups_id',
            'github_users_id'
        );
    }

    public function repositories()
    {
        return $this->belongsToMany(
            'App\Github\GithubRepository',
            'github_repository_groups',
            'github_groups_id',
            'github_repositories_id'
        )->withPivot(['rights']);
    }

    static function getGroupDetails($groupId)
    {
        $group = GithubGroup::find($groupId);

        $repositories = DB::table('github_groups')
            ->join('github_repository_groups', 'github_groups.id', '=', 'github_repository_groups.github_groups_id')
            ->join('github_repositories', 'github_repositories.id', '=' . 'github_repository_groups.github_repositories_id')
            ->where('github_groups.id', '=', $groupId)
            ->get();

        $users = $group->users;

        return [
            'group' => $group,
            'repositories' => $repositories,
            'users' => $users
        ];
    }
}
