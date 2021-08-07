<?php

namespace App\Console\Commands;

use App\Github\GithubUser;
use App\Issue;
use App\LaravelGithubLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Storage;

class AnalyzeLaravelLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:analyze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze all the log files';

    private $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client([
            // 'auth' => [getenv('GITHUB_USERNAME'), getenv('GITHUB_TOKEN')],
            'auth' => [config('env.GITHUB_USERNAME'), config('env.GITHUB_TOKEN')],
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $path =  base_path() . '/';

        $escaped = str_replace('/', '\/', $path);


        $errorData = array();

        $files = Storage::disk('logs')->files();

        foreach ($files as $file) {

            $yesterday = strtotime('yesterday');
            $today = strtotime('today');

            $time = Storage::disk('logs')->lastModified($file);

            if ($yesterday > $time || $time >= $today) {
                echo 'HERE' . PHP_EOL;
                continue;
            }

            echo '====== Getting logs from file:' . $file . ' ======' . PHP_EOL;


            $content = Storage::disk('logs')->get($file);

            $matches = [];
            preg_match_all('/\[([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2})\].*?' . $escaped . '(\S*?):\d*?\)\n.*?(#0.*?)main/s', $content, $matches);

            $timestamps = $matches[1];
            $filenames = $matches[2];
            $errorStackTrace = $matches[3];

            foreach ($timestamps as $index => $timestamp) {

                $data =  array(
                    'log_file_name' => $file,
                    'timestamp' => $timestamp,
                    'filename' => $filenames[$index],
                    'stacktrace' => $errorStackTrace[$index]
                );
                $errorData[] = $data;
            }
        }

        echo '====== Got the following errors: ======'.PHP_EOL;
        echo print_r($errorData, true) . PHP_EOL;


        echo '====== Executing Github commands: ======'.PHP_EOL;

        foreach ($errorData as $key => $error) {
            $cmdReponse = [];
            $cmd = 'git log -n 1 ' . $path . $error['filename'] . ' 2>&1';
            echo 'git command: ' . $cmd;
            exec($cmd, $cmdReponse);
            echo 'Command execution response :' . print_r($cmdReponse, true) . PHP_EOL;
            $commitDetails = $this->getDetailsFromCommit($cmdReponse);
            if ($commitDetails) {
                $errorData[$key]['commit'] = $commitDetails;
            }
        }

        $errorData = array_filter(
            $errorData,
            function ($data) {
                //echo print_r($data, true);
                return isset($data['commit']);
            }
        );

        echo '== DATA ENTRIES == ' . PHP_EOL;
        echo print_r($errorData, true);

        $newlyCreatedLogs  = [];

        foreach ($errorData as $error) {

            $log = LaravelGithubLog::firstOrCreate(
                [
                    'log_time' => $error['timestamp'],
                    'log_file_name' => $error['log_file_name'],
                    'file' => $error['filename']
                ],
                [
                    'commit' => $error['commit']['commit'],
                    'author' =>  $error['commit']['author'],
                    'commit_time' => $error['commit']['date'],
                    'stacktrace' => $error['stacktrace']
                ]
            );

            if ($log->wasRecentlyCreated) {
                $newlyCreatedLogs[] = $log;
            }
        }

        echo 'Getting github user IDs....' . PHP_EOL;

        // assign the github user ID to the logs
        foreach ($newlyCreatedLogs as $log) {
            $githubUserId = $this->getUserIdFromCommit($log->commit);
            $log->githubUserId = $githubUserId;
        }

        // get all the user id corresponding to github user ID

        $githubUserIds = array_unique(
            array_filter(
                array_map(
                    function ($log) {
                        return $log->githubUserId;
                    },
                    $newlyCreatedLogs
                )
            )
        );

        echo '====== Github User IDs ======' . PHP_EOL;
        echo print_r($githubUserIds, true) . PHP_EOL;


        $users = GithubUser::whereIn('id', $githubUserIds)->select(['id', 'user_id'])->get();

        echo print_r($users, true) . PHP_EOL;

        // create issue for the newly create log
        foreach ($newlyCreatedLogs as $log) {

            $issue = $log->file . PHP_EOL . PHP_EOL . $log->stacktrce;
            $subject = 'Exception in ' . $log->file;

            $user = $users->first(function ($value, $key) use ($log) {
                return $value->id == $log->githubUserId;
            });

            $user_id = 0;
            if ($user) {
                $user_id = $user->user_id;
            }

            Issue::create([
                'user_id' => $user_id,
                'issue' => $issue,
                'priority' => 0,
                'module' => '',
                'subject' => $subject
            ]);
        }

        echo 'done';
    }

    private function getDetailsFromCommit($commit)
    {
        foreach ($commit as $line) {
            if ($this->startsWith($line, 'Author: ')) {
                $author = substr($line, strlen('Author: '));
                $author = trim($author);
            } else if ($this->startsWith($line, 'Date: ')) {
                $date = substr($line, strlen('Date: '));
                $date = trim($date);
            } else if ($this->startsWith($line, 'commit')) {
                $commit = substr($line, strlen('commit'));
                $commit = trim($commit);
            }
        }
        if (isset($author) && isset($date)) {
            echo print_r(
                array(
                    'author' => $author,
                    'date' => $date,
                    'commit' => $commit
                ),
                true
            );
            return array(
                'author' => $author,
                'date' => $date,
                'commit' => $commit
            );
        }
        return false;
    }

    private function getUserIdFromCommit($commit)
    {
        // NOTE: 231925646 is the ERP repo ID
        $url = 'https://api.github.com/repositories/231925646/commits/' . $commit;
        try {
            $response = $this->client->get($url);
            $decodedResponse = json_decode($response->getBody()->getContents());
            if (isset($decodedResponse->author)) {
                return $decodedResponse->author->id;
            }
            echo 'COULD NOT FIND USER DETAILS FOR: ' . $commit . ' Error: Parsing response' . PHP_EOL;
            return false;
        } catch (ClientException $e) {
            echo 'COULD NOT FIND USER DETAILS FOR: ' . $commit . ' Error: ' . $e->getMessage() . PHP_EOL;
            return false;
        }
    }

    function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
