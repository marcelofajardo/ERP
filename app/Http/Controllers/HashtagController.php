<?php

namespace App\Http\Controllers;

use App\Account;
use App\CommentsStats;
use App\FlaggedInstagramPosts;
use App\HashTag;
use App\InfluencerKeyword;
use App\InfluencersHistory;
use App\InstagramPosts;
use App\InstagramPostsComments;
use App\InstagramUsersList;
use App\ScrapInfluencer;
use App\Services\Instagram\Hashtags;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use InstagramAPI\Instagram;
use InstagramAPI\Signatures;
use Plank\Mediable\Media;

Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class HashtagController extends Controller
{

    private $maxId;
    public $platformsId;

    public function __construct(Request $request)
    {
        $this->platformsId = 1;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * Show all the hashtags we have saved
     */
    public function index(Request $request)
    {
        if ($request->term || $request->priority) {

            if ($request->term != null && $request->priority == 'on') {

                $hashtags = HashTag::query()
                    ->where('priority', 1)
                    ->where('platforms_id', $this->platformsId)
                    ->where('hashtag', 'LIKE', "%{$request->term}%")
                    ->paginate(Setting::get('pagination'));
                return view('instagram.hashtags.index', compact('hashtags'));
            }
            if ($request->priority == 'on') {
                $hashtags = HashTag::where('priority', 1)->where('platforms_id', $this->platformsId)->paginate(Setting::get('pagination'));
                return view('instagram.hashtags.index', compact('hashtags'));
            }
            if ($request->term != null) {
                $hashtags = HashTag::query()
                    ->where('hashtag', 'LIKE', "%{$request->term}%")
                    ->where('platforms_id', $this->platformsId)
                    ->paginate(Setting::get('pagination'));
                return view('instagram.hashtags.index', compact('hashtags'));
            }

        } else {
            $hashtags = HashTag::where('platforms_id', $this->platformsId)->paginate(Setting::get('pagination'));
            return view('instagram.hashtags.index', compact('hashtags'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * Create a new hashtag entry
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $hashtag               = new HashTag();
        $hashtag->hashtag      = $request->get('name');
        $hashtag->rating       = $request->get('rating') ?? 8;
        $hashtag->platforms_id = $this->platformsId;
        $hashtag->save();

        return redirect()->back()->with('message', 'Hashtag created successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * Show hashtag
     */
    public function edit($hashtag, Request $request)
    {

        $h = HashTag::where('hashtag', $hashtag)->first();

        $maxId = '';
        if ($request->has('maxId')) {
            $maxId = $request->get('maxId');
        }

        $hashtags = new Hashtags();
        $hashtags->login();

        //get media for this instance + maxId (for next pagination)
        [$medias, $maxId] = $hashtags->getFeed($hashtag, $maxId);
        $media_count      = $hashtags->getMediaCount($hashtag);
        if ($h) {
            $h->post_count = $media_count;
            $h->save();
        }

        // Also get related hashtag..
        $relatedHashtags = $hashtags->getRelatedHashtags($hashtag);

        $accounts = Account::where('platform', 'instagram')->where('manual_comment', 1)->get();

        return view('instagram.hashtags.grid2', compact('medias', 'media_count', 'relatedHashtags', 'hashtag', 'accounts', 'maxId'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (is_numeric($id)) {
            $hash = HashTag::findOrFail($id);
            $hash->delete();
        } else {
            HashTag::where('hashtag', $id)->delete();
        }

        return redirect()->back()->with('message', 'Hashtag has been deleted successfuly!');
    }

    public function showGrid($id, Request $request)
    {

        $maxId = '';

        if ($request->has('maxId')) {
            $maxId = $request->get('maxId');
        }

        $txt = $id;
        $ht  = null;
        if (is_numeric($id)) {
            $hashtag = HashTag::findOrFail($id);
            $medias  = $hashtag->instagramPost()->orderBy('id', 'desc')->paginate(20);
        } else {
            $hashtag = HashTag::where('hashtag', 'LIKE', $id)->first();
            $medias  = $hashtag->instagramPost()->orderBy('id', 'desc')->paginate(20);
        }

        if ($request->term || $request->date || $request->username || $request->caption || $request->location || $request->comment) {
            $query = InstagramPosts::query();
            if (request('term') != null) {
                $query->where('username', 'LIKE', "%{$request->term}%")
                    ->orWhere('caption', 'LIKE', "%{$request->term}%")
                    ->orWhere('location', 'LIKE', "%{$request->term}%")
                    ->orWhereHas('comments', function ($qu) use ($request) {
                        $qu->where('comment', 'LIKE', "%{$request->term}%");
                    });
            }

            if (request('username') != null) {
                $query->where('username', 'LIKE', '%' . request('username') . '%');
            }
            if (request('caption') != null) {
                $query->where('caption', 'LIKE', '%' . request('caption') . '%');
            }
            if (request('location') != null) {
                $query->where('location', 'LIKE', '%' . request('location') . '%');
            }

            if (request('comments') != null) {
                $query->whereHas('comments', function ($qu) use ($request) {
                    $qu->where('comment', 'LIKE', '%' . request('comments') . '%');
                });
            }

            $medias = $query->where('hashtag_id', $hashtag->id)->orderBy('id', 'desc')->paginate(20);

        }

        $media_count = 1;

        $hashtagList = HashTag::all();

        $accs = Account::where('platform', 'instagram')->where('status', 1)->whereNotNull('proxy')->get();

        $stats = CommentsStats::selectRaw('COUNT(*) as total, narrative')->where('target', $hashtag->hashtag)->groupBy(['narrative'])->get();

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('instagram.hashtags.data', compact('medias', 'hashtag', 'media_count', 'maxId', 'stats', 'accs', 'hashtagList'))->render(),
                'links' => (string) $medias->render(),
            ], 200);
        }

        return view('instagram.hashtags.grid', compact('medias', 'hashtag', 'media_count', 'maxId', 'stats', 'accs', 'hashtagList'));
    }

    public function showUserGrid($id, Request $request)
    {

        $maxId = '';

        if ($request->has('maxId')) {
            $maxId = $request->get('maxId');
        }

        $hashtag = InstagramUsersList::where('user_id', $id)->first();

        $txt = $id;
        $ht  = null;

        $query = InstagramPosts::query();

        if ($request->term || $request->date || $request->username || $request->caption || $request->location || $request->comment) {

            if (request('term') != null) {
                $query->where('username', 'LIKE', "%{$request->term}%")
                    ->orWhere('caption', 'LIKE', "%{$request->term}%")
                    ->orWhere('location', 'LIKE', "%{$request->term}%")
                    ->orWhereHas('comments', function ($qu) use ($request) {
                        $qu->where('comment', 'LIKE', "%{$request->term}%");
                    });
            }

            if (request('username') != null) {
                $query->where('username', 'LIKE', '%' . request('username') . '%');
            }
            if (request('caption') != null) {
                $query->where('caption', 'LIKE', '%' . request('caption') . '%');
            }
            if (request('location') != null) {
                $query->where('location', 'LIKE', '%' . request('location') . '%');
            }

            if (request('comments') != null) {
                $query->whereHas('comments', function ($qu) use ($request) {
                    $qu->where('comment', 'LIKE', '%' . request('comments') . '%');
                });
            }

            $medias = $query->where('user_id', $hashtag->id)->orderBy('id', 'desc')->paginate(20);

        } else {
            $medias = $query->where('user_id', $id)->orderBy('id', 'desc')->paginate(20);
        }

        $media_count = 1;

        $hashtagList = HashTag::all();

        $accs = Account::where('platform', 'instagram')->where('status', 1)->get();

        $stats = [];

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('instagram.hashtags.data', compact('medias', 'hashtag', 'media_count', 'maxId', 'stats', 'accs', 'hashtagList'))->render(),
                'links' => (string) $medias->render(),
            ], 200);
        }

        return view('instagram.hashtags.grid', compact('medias', 'hashtag', 'media_count', 'maxId', 'stats', 'accs', 'hashtagList'));
    }

    public function showGridComments($id = null, Request $request)
    {
        $hashtag = HashTag::find($id);

        $query = InstagramPostsComments::query();

        if ($request->term) {
            $query = $query->where('comment', 'LIKE', '%' . $request->term . '%');
        }

        if (!empty($hashtag)) {
            $query = $query->where('comment', 'LIKE', '%' . $hashtag->hashtag . '%');
        }

        $comments = $query->orderBy('id', 'desc')->paginate(25);

        $accs = Account::where('platform', 'instagram')->where('manual_comment', 1)->get();

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('instagram.hashtags.comments.partials.data', compact('hashtag', 'comments', 'accs'))->render(),
                'links' => (string) $comments->render(),
                'total' => $comments->total(),
            ], 200);
        }

        return view('instagram.hashtags.comments.grid', compact('hashtag', 'comments', 'accs', 'id'));
    }

    public function loadComments($mediaId)
    {
        $instagram = new Instagram();
        $instagram->login('sololuxury.official', "NcG}4u'z;Fm7");
        $token = Signatures::generateUUID();

        $comments = $instagram->media->getComments($mediaId)->asArray();

        $comments['comments'] = array_map(function ($comment) {
            $c                          = $comment['created_at'];
            $comment['created_at']      = Carbon::createFromTimestamp($comment['created_at'])->diffForHumans();
            $comment['created_at_time'] = Carbon::createFromTimestamp($c)->toDateTimeString();
            return $comment;
        }, $comments['comments']);

        return response()->json([
            'comments'          => $comments['comments'] ?? [],
            'has_more_comments' => $comments['has_more_comments'] ?? false,
            'caption'           => $comments['caption'],
        ]);
    }

    /**
     * @SWG\Get(
     *   path="/hashtags",
     *   tags={"Hashtags"},
     *   summary="Get hashtags",
     *   operationId="get-hashtags",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true,
     *          type="string"
     *      ),
     * )
     *
     */
    public function sendHashtagsApi()
    {
        $hashtags = HashTag::where('platforms_id', $this->platformsId)->get(['hashtag', 'id']);

        return response()->json($hashtags);
    }

    public function showNotification()
    {
        $hashtags = new Hashtags();
        $hashtags->login();
        $maxId         = '';
        $commentsFinal = [];

        do {
            $hashtagPostsAll        = $hashtags->getFeed('sololuxury', $maxId);
            [$hashtagPosts, $maxId] = $hashtagPostsAll;

            foreach ($hashtagPosts as $hashtagPost) {
                $comments = $hashtagPost['comments'] ?? [];

                if ($comments === []) {
                    continue;
                }

                $postId                         = $hashtagPost['media_id'];
                $commentsFinal[$postId]['text'] = $hashtagPost['caption'];
                $commentsFinal[$postId]['code'] = $hashtagPost['code'];
                foreach ($comments as $comment) {
                    $createdAt                            = Carbon::createFromTimestamp($comment['created_at'])->diffForHumans();
                    $commentsFinal[$postId]['comments'][] = [
                        'username'   => $comment['user']['username'],
                        'text'       => $comment['text'],
                        'created_at' => $createdAt,
                    ];
                }

            }

        } while ($maxId != 'END');

        return view('instagram.notifications', compact('commentsFinal'));

    }

    public function showProcessedComments(Request $request)
    {
        $posts = InstagramPosts::all();

        return view('instagram.comments', compact('posts'));
    }

    public function commentOnHashtag(Request $request)
    {

        $this->validate($request, [
            'message'    => 'required',
            'account_id' => 'required',
            'id'         => 'required',
            'hashtag'    => 'required',
            'narrative'  => 'required',
        ]);

        $acc = Account::findOrFail($request->get('account_id'));

        $instagram = new Instagram();

        try {

            $senderUsername = $acc->last_name;
            $password       = $acc->password;

            if ($senderUsername != '' && $password != '') {
                $instagram->setProxy($acc->proxy);
                $instagram->login($senderUsername, $password);

            } else {

                return response()->json([
                    'status' => 'Username Or PassWord empty',
                ]);

            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => $e,
            ]);
        }

        $instagram->media->comment($request->get('post_id'), $request->get('message'));

        $stat              = new CommentsStats();
        $stat->target      = $request->get('hashtag');
        $stat->sender      = $acc->last_name;
        $stat->comment     = $request->get('message');
        $stat->post_author = '';
        $stat->code        = '';
        $stat->narrative   = $request->get('narrative');
        $stat->save();

        return response()->json([
            'status' => 'Message send success',
        ]);

        //InstagramComment::dispatchNow($request);

    }

    public function flagMedia($id)
    {
        $m           = new FlaggedInstagramPosts();
        $m->media_id = $id;
        $m->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function markPriority(Request $request)
    {
        // dd($request);
        $id = $request->id;
        //check if 30 limit is exceded
        $hashtags = HashTag::where('priority', 1)->where('platforms_id', $this->platformsId)->get();

        if (count($hashtags) > 30 && $request->type == 1) {
            return response()->json([
                'status' => 'error',
            ]);
        }

        $hashtag           = HashTag::findOrFail($id);
        $hashtag->priority = $request->type;
        $hashtag->update();
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function rumCommand(Request $request)
    {
        $id      = $request->id;
        $account = $request->account;

        try {

            $hashTag = \App\HashTag::find($id);
            if ($hashTag) {
                $hashTag->instagram_account_id = $account;
                $hashTag->save();
            }

            $cmd    = 'ps waux | grep competitors:process-local-users\ ' . $id;
            $export = shell_exec($cmd);
            $export = trim($export);
            //getting username
            $cmd      = 'echo $USER';
            $username = shell_exec($cmd);
            $username = trim($username);

            $re = '/' . $username . '(\s*)(\d*)/m';

            preg_match_all($re, $export, $matches, PREG_SET_ORDER, 0);

            if (count($matches) == 0 || count($matches) == 1 || count($matches) == 2) {
                $cmd    = 'php ' . base_path() . '/artisan competitors:process-local-users ' . $id . ' &';
                $export = shell_exec($cmd);

                // $art = \Artisan::call("competitors:process-local-users",['hastagId' => $id]);
                return ['success' => true, 'message' => 'Process Started Running'];
            } elseif (count($matches) == 3 || count($matches) == 4) {
                return ['success' => true, 'message' => 'Process Is Already Running'];
            }

            return ['error' => true, 'message' => 'Something went wrong'];

        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Something went wrong'];
        }
    }

    public function killCommand(Request $request)
    {
        try {
            $id     = $request->id;
            $cmd    = 'ps waux | grep competitors:process-local-users\ ' . $id;
            $export = shell_exec($cmd);
            $export = trim($export);
            //getting username
            $cmd      = 'echo $USER';
            $username = shell_exec($cmd);
            $username = trim($username);

            $re = '/' . $username . '(\s*)(\d*)/m';

            preg_match_all($re, $export, $matches, PREG_SET_ORDER, 0);

            if (count($matches) == 0 || count($matches) == 1 || count($matches) == 2) {
                return ['success' => true, 'message' => 'Process Is Not Running'];
            } elseif (count($matches) == 3 || count($matches) == 4) {
                foreach ($matches as $match) {
                    if (isset($match[2])) {
                        $cmd    = 'kill -9 ' . $match[2];
                        $export = shell_exec($cmd);
                    }
                }
            }

            return ['success' => true, 'message' => 'Process Killed successfuly'];

        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Something went wrong'];
        }

    }

    public function checkStatusCommand(Request $request)
    {
        try {

            $id     = $request->id;
            $cmd    = 'ps waux | grep competitors:process-local-users\ ' . $id;
            $export = shell_exec($cmd);
            $export = trim($export);
            //getting username
            $cmd      = 'echo $USER';
            $username = shell_exec($cmd);
            $username = trim($username);

            $re = '/' . $username . '(\s*)(\d*)/m';

            preg_match_all($re, $export, $matches, PREG_SET_ORDER, 0);

            if (count($matches) == 0 || count($matches) == 1 || count($matches) == 2) {
                return ['success' => true, 'message' => 'Process Is Not Running'];
            } elseif (count($matches) == 3 || count($matches) == 4) {
                return ['success' => true, 'message' => 'Process Is Running'];
            }

            return ['success' => true, 'message' => 'Process cannot be check !'];

        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Something went wrong'];
        }

    }

    public function influencer(Request $request)
    {
        $request->posts ? $posts         = $request->posts : $posts         = null;
        $request->followers ? $followers = $request->followers : $followers = null;
        $request->following ? $following = $request->following : $following = null;
        $request->term ? $term           = $request->term : $term           = null;
        $influencers                     = ScrapInfluencer::query();
        if ($posts) {
            $influencers = $influencers->where('posts', '>=', $posts);
        }
        if ($followers) {
            $influencers = $influencers->where('followers', '>=', $followers);
        }
        if ($following) {
            $influencers = $influencers->where('following', '>=', $following);
        }
        if ($term != null) {

            $influencers = $influencers->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('phone', 'LIKE', "%{$term}%")
                    ->orWhere('website', 'LIKE', "%{$term}%")
                    ->orWhere('twitter', 'LIKE', "%{$term}%")
                    ->orWhere('facebook', 'LIKE', "%{$term}%")
                    ->orWhere('country', 'LIKE', "%{$term}%")
                    ->orWhere('email', 'LIKE', "%{$term}%");
            });

            //  $influencers  = $influencers->where('name', 'LIKE', "%{$request->term}%")
            //         ->orWhere('phone', 'LIKE', "%{$request->term}%")
            //         ->orWhere('website', 'LIKE', "%{$request->term}%")
            //         ->orWhere('twitter', 'LIKE', "%{$request->term}%")
            //         ->orWhere('facebook', 'LIKE', "%{$request->term}%")
            //         ->orWhere('country', 'LIKE', "%{$request->term}%")
            //         ->orWhere('email', 'LIKE', "%{$request->term}%");

        }
        $influencers = $influencers->orderBy('created_at', 'desc')->paginate(25);
        $keywords    = InfluencerKeyword::all();
        $accounts    = Account::where('status', 1)->where('platform', 'instagram')->pluck('last_name', 'id');
        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('instagram.hashtags.partials.influencer-data', compact('influencers', 'posts', 'followers', 'following', 'term'))->render(),
                'links' => (string) $influencers->render(),
                'total' => $influencers->total(),
            ], 200);
        }

        $replies = \App\Reply::where("model", "influencers")->whereNull("deleted_at")->pluck("reply", "id")->toArray();

        return view('instagram.hashtags.influencers', compact('accounts', 'replies', 'influencers', 'keywords', 'posts', 'followers', 'following', 'term'));
    }

    public function addReply(Request $request)
    {
        $reply     = $request->get("reply");
        $autoReply = [];
        if (!empty($reply)) {
            $autoReply = \App\Reply::updateOrCreate(
                ['reply' => $reply, 'model' => 'influencers', "category_id" => 1],
                ['reply' => $reply]
            );
        }
        return response()->json(["code" => 200, 'data' => $autoReply]);
    }

    public function deleteReply(Request $request)
    {
        $id = $request->get("id");

        if ($id > 0) {
            $autoReply = \App\Reply::where("id", $id)->first();
            if ($autoReply) {
                $autoReply->delete();
            }
        }

        return response()->json([
            "code" => 200, "data" => \App\Reply::where("model", "influencers")
                ->whereNull("deleted_at")
                ->pluck("reply", "id")
                ->toArray(),
        ]);
    }

    public function history(Request $request)
    {

        if ($request->id) {
            $history = InfluencersHistory::where('influencers_name', $request->id)->orderBy("created_at", "desc")->get();
            return response()->json(["code" => 200, "data" => $history]);
        }
    }

    public function showGridUsers($id = null, Request $request)
    {

        if ($request->term != null) {

            $users = InstagramUsersList::query()
                ->where('username', 'LIKE', "%{$request->term}%")
                ->orWhere('bio', 'LIKE', "%{$request->term}%")
                ->orWhere('location', 'LIKE', "%{$request->term}%")
                ->orWhere('because_of', 'LIKE', "%{$request->term}%")
                ->orderBy('id', 'desc')
                ->paginate(25);

        } else {
            if ($id) {
                $users = InstagramUsersList::where('because_of', $id)->orderBy('id', 'desc')->paginate(25);
            } else {
                $users = InstagramUsersList::orderBy('id', 'desc')->paginate(25);
            }

        }

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('instagram.hashtags.partials.users-data', compact('users', 'id'))->render(),
                'links' => (string) $users->render(),
                'total' => $users->total(),
            ], 200);
        }

        return view('instagram.hashtags.users', compact('users', 'id'));
    }
}
