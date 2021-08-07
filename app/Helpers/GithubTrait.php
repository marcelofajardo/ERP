<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

trait githubTrait
{

    private function getGithubClient()
    {
        return new Client([
            // 'auth' => [getenv('GITHUB_USERNAME'), getenv('GITHUB_TOKEN')],
            'auth' => [config('env.GITHUB_USERNAME'), config('env.GITHUB_TOKEN')],
        ]);
    }

    private function compareRepoBranches(int $repoId, string $branchName, string $base = 'master')
    {
        $githubClient = $this->getGithubClient();
        //https://api.github.com/repositories/:repoId/compare/:diff

        try {
            $url = 'https://api.github.com/repositories/' . $repoId . '/compare/' . $base . '...' . $branchName;
            $response = $githubClient->get($url);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 404) {
                // known error which happens in case there is more changes 
                return [
                    'ahead_by' => 0,
                    'behind_by' => 0,
                    'last_commit_author_username' => null,
                    'last_commit_time' => null
                ];
            }
        }


        $compare = json_decode($response->getBody()->getContents());

        $lastCommitAuthorUsername = null;
        $lastCommitTime           = null;

        if (is_array($compare->commits) && sizeof($compare->commits) > 0) {
            $index = sizeof($compare->commits) - 1;

            try {
                $lastCommitAuthorUsername = $compare->commits[$index]->author->login;
            } catch (Exception $e) {
                // do nothing
                $lastCommitAuthorUsername = $compare->commits[$index]->commit->author->name;
            }
            $lastCommitTime = Carbon::parse($compare->commits[$index]->commit->author->date);
        } else {
            $lastCommitAuthorUsername = $compare->merge_base_commit->commit->author->name;
            $lastCommitTime           = Carbon::parse($compare->merge_base_commit->commit->author->date);
        }

        return [
            'ahead_by'                    => $compare->ahead_by,
            'behind_by'                   => $compare->behind_by,
            'last_commit_author_username' => $lastCommitAuthorUsername,
            'last_commit_time'            => $lastCommitTime,
        ];
    }

    private function inviteUser(string $email)
    {
        // /orgs/:org/invitations
        // $url = 'https://api.github.com/orgs/' . getenv('GITHUB_ORG_ID') . '/invitations';
        $url = 'https://api.github.com/orgs/' . config('env.GITHUB_ORG_ID') . '/invitations';

        try {
            $this->getGithubClient()->post(
                $url,
                [
                    'json' => [
                        'email' => $email
                    ]
                ]
            );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
