<?php

namespace App\Http\Controllers\Github;

use App\Github\GithubRepository;
use App\Github\GithubRepositoryUser;
use App\Github\GithubUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Input;
use Route;

class UserController extends Controller
{

    private $client;
    function __construct()
    {
        $this->client = new Client([
            // 'auth' => [getenv('GITHUB_USERNAME'), getenv('GITHUB_TOKEN')]
            'auth' => [config('env.GITHUB_USERNAME'), config('env.GITHUB_TOKEN')],
        ]);
    }

    public function listOrganizationUsers()
    {
        $platformUsers = User::all(['id', 'name', 'email']);

        $users = GithubUser::with('repositories', 'platformUser')->get();
        return view(
            'github.org_users',
            [
                'users' => $users,
                'platformUsers' => $platformUsers
            ]
        );
    }

    public function listUsersOfRepository()
    {
        $name = Route::current()->parameter('name');
        //$users = $this->refreshUsersForRespository($name);
        $users = GithubRepository::where('name', $name)->first()->users;
        return view(
            'github.repository_users',
            [
                'users' => $users,
                'repoName' => $name
            ]
        );
    }

    public function linkUser(Request $request)
    {
        $bodyContent = $request->getContent();
        $jsonDecodedBody = json_decode($bodyContent);

        $userId = $jsonDecodedBody->user_id;
        $githubUserId = $jsonDecodedBody->github_user_id;

        if (!$userId || !$githubUserId) {
            return response()->json(
                [
                    'error' => 'Missing parameters',
                ],
                400
            );
        }

        $githubUser = GithubUser::find($githubUserId);
        if ($githubUser) {
            $githubUser->user_id = $userId;
            $githubUser->save();
            return response()->json(
                [
                    'message' => 'Saved user',
                ]
            );
        }

        return response()->json(
            [
                'error' => 'Unable to find user',
            ],
            404
        );
    }

    public function modifyUserAccess(Request $request)
    {
        $bodyContent = $request->getContent();
        $jsonDecodedBody = json_decode($bodyContent);

        $userName = $jsonDecodedBody->user_name;
        $access = $jsonDecodedBody->access;
        $repoName  = $jsonDecodedBody->repository_name;



        if (!$userName || !$access || !$repoName) {
            return response()->json(
                [
                    'error' => 'Missing parameters',
                ],
                400
            );
        }

        //https://api.github.com/repos/:owner/:repo/collaborators/:username
        // $url = "https://api.github.com/repos/" . getenv('GITHUB_ORG_ID')  . "/" . $repoName . "/collaborators/" . $userName;
        $url = "https://api.github.com/repos/" . config('env.GITHUB_ORG_ID')  . "/" . $repoName . "/collaborators/" . $userName;

        // cannot update users access directly and hence need to remove and then add them explicitly
        $this->client->delete($url);
        $this->client->put(
            $url,
            [
                RequestOptions::JSON => [
                    'permission' => $access
                ]
            ]
        );
        return response()->json([
            'message' => 'user invited'
        ]);
    }

    public function removeUserFromRepository()
    {

        $id = Route::current()->parameter('id');

        $repositoryUser = GithubRepositoryUser::find($id);

        $user = $repositoryUser->githubUser;
        $repository = $repositoryUser->githubRepository;

        // $url = "https://api.github.com/repos/" . getenv('GITHUB_ORG_ID')  . "/" . $repository->name . "/collaborators/" . $user->username;
        $url = "https://api.github.com/repos/" . config('env.GITHUB_ORG_ID')  . "/" . $repository->name . "/collaborators/" . $user->username;

        $this->client->delete($url);

        $repositoryUser->delete();

        return redirect()->back();
    }

    public function userDetails()
    {
        $id = Route::current()->parameter('userId');

        $userDetails = GithubUser::getUserDetails($id);

        return view('github.user_details', ['userDetails' => $userDetails]);
    }

    public function addUserToRepositoryForm()
    {
        $repositoryName = Route::current()->parameter('name');

        $githubUsers = GithubUser::all();

        $users = [];
        foreach ($githubUsers as $user) {
            $users[$user->username] = $user->username;
        }

        return view('github.add_user_to_repo', ['users' => $users]);
    }

    public function addUserToRepository(){
        $repositoryName = Input::get('repo_name');
        $username = Input::get('username');
        $permission = Input::get('permission');

        //https://api.github.com/repos/:owner/:repo/collaborators/:username
        // $url = 'https://api.github.com/repos/' . getenv('GITHUB_ORG_ID') . '/' . $repositoryName . '/collaborators/' . $username;
        $url = 'https://api.github.com/repos/' . config('env.GITHUB_ORG_ID') . '/' . $repositoryName . '/collaborators/' . $username;

        $this->client->put(
            $url,
            [
                RequestOptions::JSON => [
                    'permission' => $permission
                ]
            ]
        );

        // cannot update the database still as the above will raise and invitation

        return redirect('/github/repos/'.$repositoryName.'/users');
    }
}
