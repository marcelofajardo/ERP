<?php

namespace App\Http\Controllers;

use App\Account;
use App\AutoCommentHistory;
use App\ErpAccounts;
use App\User;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram;

\InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class UsersAutoCommentHistoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $autoCommentHistories = new AutoCommentHistory();
        $user = Auth::user();
        if (!$user->hasRole('Admin')) {
            $autoCommentHistories = $autoCommentHistories->whereIn('id', DB::table('users_auto_comment_histories')
                ->where('user_id', $user->id)
                ->pluck('auto_comment_history_id')
                ->toArray()
            );
        }

        $accounts = Account::where('platform', 'instagram')->where('manual_comment', 1)->where('blocked', 0)->get();

        $comments = $autoCommentHistories->orderBy('created_at', 'DESC')->paginate(25);

        return view('instagram.auto_comments.user_ach', compact('comments', 'accounts'));

    }

    public function assignPosts() {
        $user = Auth::user();

        $autoCommentHistory = AutoCommentHistory::where('status', 0)
            ->whereNotIn('id', DB::table('users_auto_comment_histories')->pluck('auto_comment_history_id')->toArray())
            ->take(25)
            ->get();

        $productsAttached = 0;

        foreach ($autoCommentHistory as $ach) {
            DB::table('users_auto_comment_histories')->insert([
                'user_id' => $user->id,
                'auto_comment_history_id' => $ach->id,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
            $productsAttached++;
        }

        return redirect()->back()->with('message', 'Successfully added ' . $productsAttached . ' posts to comment!');

    }

    public function sendMessagesToWhatsappToScrap(Request $request) {
        $posts = $request->get('posts');
        $user = Auth::user();

        $message = 'The comments to be posted on posts are: ';

        foreach ($posts as $postId) {
            $post = AutoCommentHistory::find($postId);
            $message .= "\n Post Url: instagram://media?id=$post->post_id \n Comment: $post->comment \n\n";
        }

        $myRequest = new Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add([
            'vendor_id' => $user->id,
            'message' => $message,
            'is_vendor_user' => 'yes',
            'status' => 1
        ]);

        app(WhatsAppController::class)->sendMessage($myRequest, 'vendor');

        return response()->json([
            'status' => 'success'
        ]);

    }

    public function verifyComment(Request $request) {
        $autoCommentHistory = AutoCommentHistory::find($request->get('id'));
        $autoCommentHistory->account_id = $request->get('account_id');
        $autoCommentHistory->status = 1;
        $autoCommentHistory->save();
        $commentPosted = $autoCommentHistory->comment;

        $instagram = new Instagram();
        $instagram->login('sololuxury.official', 'Solo123!@#');

        try {
            $comments = $instagram->media->getComments($autoCommentHistory->post_id)->asArray();
        } catch (\Exception $exception) {
            $comments = ['comments' => []];
        }

        $comments = $comments['comments'];
        $posted = false;


        foreach ($comments as $comment) {
            $text = $comment['text'];
            similar_text($text, $commentPosted, $percentage);

            if ($percentage > 95) {
                $posted = true;
                $autoCommentHistory->status = 1;
                $autoCommentHistory->is_verified = 1;
                $autoCommentHistory->save();

                $history = DB::table('users_auto_comment_histories')
                    ->where('user_id', Auth::user()->id)
                    ->where('auto_comment_history_id', $autoCommentHistory->id)
                    ->first();

                if ($history) {
                    DB::table('users_auto_comment_histories')
                        ->where('user_id', Auth::user()->id)
                        ->where('auto_comment_history_id', $autoCommentHistory->id)
                        ->update([
                        'status' => 1,
                        'is_confirmed' => 1
                    ]);

                    //Save cost amount...

                    $erpAccount = new ErpAccounts();
                    $erpAccount->table = 'users_auto_comment_histories';
                    $erpAccount->row_id = $history->id;
                    $erpAccount->transacted_by = 109;
                    $erpAccount->debit = 0;
                    $erpAccount->credit = 0.1;
                    $erpAccount->user_id = Auth::user()->id;
                    $erpAccount->remark = json_encode([
                        'table' => 'users_auto_comment_histories',
                        'row_id' => $history->id,
                        'message' => 'Instagram Post Comment confirmed',
                        'tables_involved' => [
                            ['accounts', $autoCommentHistory->account_id],
                            ['users'. Auth::user()->id]
                        ]
                    ]);
                    $erpAccount->save();

                    return response()->json([
                        'status' => 'verified'
                    ]);
                }
            }
        }

        if ($posted) {
            return response()->json([
                'status' => 'posted'
            ]);
        }

        return response()->json([
            'status' => 'not_found'
        ]);
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
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}
