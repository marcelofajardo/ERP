<?php

namespace App\Http\Controllers\Github;

use App\Github\GithubGroup;
use App\Github\GithubGroupMember;
use App\Github\GithubRepository;
use App\Github\GithubRepositoryGroup;
use App\Github\GithubUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Input;
use Route;

class GroupController extends Controller
{

    private $client;

    function __construct()
    {
        $this->client = new Client([
            // 'auth' => [getenv('GITHUB_USERNAME'), getenv('GITHUB_TOKEN')]
            'auth' => [config('env.GITHUB_USERNAME'), config('env.GITHUB_TOKEN')],
        ]);
    }

    public function listGroups()
    {
        $groups = GithubGroup::with('users')->get();
        return view('github.groups', ['groups' => $groups]);
    }

    public function groupDetails()
    {
        $groupId = Route::current()->parameter('groupId');
        $group = GithubGroup::find($groupId);
        $repositories = $group->repositories;
        $users = $group->users;

        return view(
            'github.group_details',
            [
                'group' => $group,
                'repositories' => $repositories,
                'users' => $users
            ]
        );
    }

    public function addRepositoryForm($groupId)
    {
        $group = GithubGroup::find($groupId);
        $existingRepositories = $group->repositories;

        $repositoryIds = $existingRepositories->map(function ($repository) {
            return $repository->id;
        });

        $repositories = GithubRepository::whereNotIn('id',$repositoryIds)->get();

        $repositorySelect = [];

        foreach($repositories as $repository){
            $repositorySelect[$repository->name] = $repository->name;
        }

        return view(
            'github.group_add_repository',
            [
                'group' => $group,
                'repositories' => $repositorySelect
            ]
        );
    }

    public function addRepository(Request $request){
        $validatedData = $request->validate([
            'group_id' => 'required',
            'repository_name' => 'required',
            'permission' => 'required'
        ]);

        $groupId = Input::get('group_id');
        $repoName = Input::get('repository_name');
        $permission = Input::get('permission');

        $this->callApiToAddRepository($groupId, $repoName, $permission);
        return redirect()->back();
    }

    private function callApiToAddRepository($groupId, $repoName, $permission){
        // https://api.github.com/organizations/:org_id/team/:team_id/repos/:owner/:repo
        $url = 'https://api.github.com/organizations/'. getenv('GITHUB_ORG_ID') .'/team/'.$groupId.'/repos/'. getenv('GITHUB_ORG_ID') .'/'.$repoName;
        $url = 'https://api.github.com/organizations/'. config('env.GITHUB_ORG_ID') .'/team/'.$groupId.'/repos/'. config('env.GITHUB_ORG_ID') .'/'.$repoName;

        try{
            $response = $this->client->put($url);
            return true;
        }catch(ClientException $e){
            //throw $e;
        }
        return false;

    }

    public function addUserForm($groupId)
    {
        $group = GithubGroup::find($groupId);
        $existingUsers = $group->users;

        $userIds = $existingUsers->map(function ($repository) {
            return $repository->id;
        });

        $users = GithubUser::whereNotIn('id', $userIds)->get(['username']);

        $userSelect = [];
        foreach ($users as $user) {
            $userSelect[$user->username] = $user->username;
        }

        return view(
            'github.group_add_user',
            [
                'group' => $group,
                'users' => $userSelect
            ]
        );
    }

    public function addUser(Request $request)
    {

       $validatedData = $request->validate([
            'group_id' => 'required',
            'role' => 'required',
            'username' => 'required'
        ]);

        $groupId = Input::get('group_id');
        $role = Input::get('role');
        $username = Input::get('username');

        $this->addUserToGroup($groupId, $username, $role);
        return redirect()->back();
    }

    private function addUserToGroup($groupId, $username, $role)
    {
        // https://api.github.com/organizations/:org_id/team/:team_id/memberships/:username
        // $url = "https://api.github.com/organizations/" . getenv('GITHUB_ORG_ID') . "/team/" . $groupId . "/memberships/". $username;
        $url = "https://api.github.com/organizations/" . config('env.GITHUB_ORG_ID') . "/team/" . $groupId . "/memberships/". $username;
        
        try{
            $response = $this->client->put(
                $url,
                [
                    RequestOptions::BODY => json_encode([
                        'role' => $role
                    ])
                ]
            );
            return true;
        }catch(ClientException $e){
            //throw $e;
        }
        return false;
    }

    public function removeUsersFromGroup()
    {
        $groupId = Route::current()->parameter('groupId');
        $userId = Route::current()->parameter('userId');

        $githubUser = GithubUser::find($userId);

        //https://api.github.com/teams/:team_id/memberships/:username
        $url = 'https://api.github.com/teams/' . $groupId . '/memberships/' . $githubUser->username;
        $this->client->delete($url);

        GithubGroupMember::where('github_groups_id', $groupId)->where('github_users_id', $userId)->delete();

        return redirect()->back();
    }

    public function removeRepositoryFromGroup()
    {
        $groupId = Route::current()->parameter('groupId');
        $repoId = Route::current()->parameter('repoId');

        $repo = GithubRepository::find($repoId);

        //https://api.github.com/teams/:team_id/repos/:owner/:repo
        // $url = 'https://api.github.com/teams/' . $groupId . '/repos/' . getenv('GITHUB_ORG_ID') . '/' . $repo->name;
        $url = 'https://api.github.com/teams/' . $groupId . '/repos/' . config('env.GITHUB_ORG_ID') . '/' . $repo->name;

        $this->client->delete($url);

        GithubRepositoryGroup::where('github_repositories_id', $repoId)->where('github_groups_id', $groupId)->delete();

        return redirect()->back();
    }
}
