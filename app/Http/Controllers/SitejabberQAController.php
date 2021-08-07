<?php

namespace App\Http\Controllers;

use App\Account;
use App\ActivitiesRoutines;
use App\BrandReviews;
use App\NegativeReviews;
use App\QuickReply;
use App\Review;
use App\Setting;
use App\SitejabberQA;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SitejabberQAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * Show the list of all questions related to sitejabber...
     */
    public function index()
    {
        $sjs = SitejabberQA::where('type', 'question')->get();

        return view('sitejabber.index', compact('sjs'));

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
     * STore the question for the sitejabber
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'question' => 'required'
        ]);


        $question = new SitejabberQA();
        $question->status = 0;
        $question->text = $request->get('question');
        $question->type = 'question';
        $question->is_approved = 1;
        $question->save();

        return redirect()->back()->with('message', 'Question added successfully. Note: This will be posted within 24 hours.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SitejabberQA  $sitejabberQA
     * @return \Illuminate\Http\Response
     */
    public function show(SitejabberQA $sitejabberQA)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SitejabberQA  $sitejabberQA
     * @return \Illuminate\Http\Response
     * This will simply update the sitejabber review settings
     */
    public function edit(Request $request)
    {
        $this->validate($request, [
            'range' => 'required',
            'range2' => 'required',
            'range3' => 'required',
        ]);

        $setting = ActivitiesRoutines::where('action', 'sitejabber_review')->first();
        if (!$setting) {
            $setting = new ActivitiesRoutines();
        }
        $setting->action = 'sitejabber_review';
        $setting->times_a_day = $request->get('range');
        $setting->save();
        $setting2 = ActivitiesRoutines::where('action', 'sitejabber_account_creation')->first();
        if (!$setting2) {
            $setting2 = new ActivitiesRoutines();
        }
        $setting2->action = 'sitejabber_account_creation';
        $setting2->times_a_day = $request->get('range2');
        $setting2->save();


        $setting3 = ActivitiesRoutines::where('action', 'sitejabber_qa_post')->first();
        if (!$setting3) {
            $setting3 = new ActivitiesRoutines();
        }
        $setting3->action = 'sitejabber_qa_post';
        $setting3->times_a_week = $request->get('range3');
        $setting3->save();


        return redirect()->back()->with('message', 'Sitejabber review settings updated!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SitejabberQA  $sitejabberQA
     * @return \Illuminate\Http\Response
     * Updates the Sitejabber question answer reply..
     */
    public function update(Request $request, $id)
    {


        $sj = SitejabberQA::findOrFail($id);

        $sju = new SitejabberQA();
        $sju->parent_id = $id;
        $sju->url = $sj->url;
        $sju->text = $request->get('reply');
        $sju->type = 'reply';
        $sju->author = 'TBD';
        $sju->status = 0;
        $sju->save();

        return redirect()->back()->with('message', 'Comment added successfully! And will be posted anytime within 24 hours!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SitejabberQA  $sitejabberQA
     * @return \Illuminate\Http\Response
     */
    public function destroy(SitejabberQA $sitejabberQA)
    {
        //
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * This method will simply give all the list of the accounts which falls under the platform sitejabber
     * ALso there are filters for different status for reviews and account itslef which is clearly
     * visible in the code
     */
    public function accounts(Request $request) {
        $date = null;

        if (strlen($request->get('date')) === 10) {
            $date = $request->get('date');
        }

        $negativeReviews = NegativeReviews::all();
        $reviewsPostedToday = Review::whereIn('status', ['posted', 'posted_one'])->whereRaw('DATE(updated_at) = "' . date('Y-m-d'). '"')->get();
        $accounts = Account::where('platform', 'sitejabber');

        if ($date !== null) {
            $accounts = $accounts->whereHas('reviews', function($query) use ($date) {
                $query->where('updated_at', 'LIKE', "%$date%");
            });
        }

        // Add the filter by sttaus of review like live, approved, unapproved.
        if ($request->get('filter') !== '') {
            $filter = $request->get('filter');
            if ($filter === 'live') {
                $accounts = $accounts->whereHas('reviews', function($query) {
                    $query->where('status', 'posted');
                });
            } else if ($filter === 'approved') {
                $accounts = $accounts->whereHas('reviews', function($query) {
                    $query->where('is_approved', 1)->where('status', '0');
                });
            } else if ($filter === 'unapproved') {
                $accounts = $accounts->whereHas('reviews', function($query) {
                    $query->where('is_approved', 0)->where('status', '0');
                });
            } else if ($filter === 'not_live') {
                $accounts = $accounts->whereHas('reviews', function($query) {
                    $query->where('status', 'posted_one');
                });
            }
        }

        $accounts = $accounts->orderBy('updated_at', 'DESC')->get();

        $brandReviews = BrandReviews::where('used', 0)->take(100)->get();
        $accountsRemaining = Account::whereDoesntHave('reviews')->where('platform', 'sitejabber')->count();
        $remainingReviews = Review::whereHas('account')->whereNotIn('status', ['posted', 'posted_one'])->count();
        $sjs = SitejabberQA::where('type', 'question')->get();
        $setting = ActivitiesRoutines::where('action', 'sitejabber_review')->first();
        $quickReplies = QuickReply::all();
//        if (!$setting) {
//            $setting = new ActivitiesRoutines();
//            $setting->action = 'sitejabber_review';
//            $setting->times_a_day = 5;
//            $setting->save();
//        }
        $setting2 = ActivitiesRoutines::where('action', 'sitejabber_account_creation')->first();
//        if (!$setting2) {
//            $setting2 = new ActivitiesRoutines();
//            $setting2->action = 'sitejabber_account_creation';
//            $setting2->times_a_day = 5;
//            $setting2->save();
//        }
//
        $setting3 = ActivitiesRoutines::where('action', 'sitejabber_qa_post')->first();
//        if (!$setting3) {
//            $setting3 = new ActivitiesRoutines();
//            $setting3->action = 'sitejabber_qa_post';
//            $setting3->times_a_week = 1;
//            $setting3->save();
//        }

        //return view('sitejabber.accounts', compact('reviewsPostedToday', 'accounts', 'sjs', 'setting', 'setting2', 'setting3', 'accountsRemaining', 'totalAccounts', 'remainingReviews', 'brandReviews', 'negativeReviews', 'quickReplies', 'request'));
        return view('sitejabber.accounts', compact('reviewsPostedToday', 'accounts', 'sjs', 'setting', 'setting2', 'setting3', 'accountsRemaining', 'remainingReviews', 'brandReviews', 'negativeReviews', 'quickReplies', 'request'));
    }

    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * get all the reviews for platform sitejabber
     */
    public function reviews() {
        $reviews = Review::where('platform', 'sitejabber')->get();

        return view('sitejabber.reviews', compact('reviews'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * Attach reviews to the account which can be posted, and marks the review from review bank as used
     */
    public function attachBrandReviews($id) {
        $reviewx = BrandReviews::findOrFail($id);
        $account = Account::whereDoesntHave('reviews')->where('platform', 'sitejabber')->orderBy('created_at', 'DESC')->first();

        $review = new Review();
        $review->account_id = $account->id;
        $review->review = $reviewx->body;
        $review->platform = 'sitejabber';
        $review->title = $reviewx->title;
        $review->save();

        $reviewx->used = 1;
        $reviewx->save();
        $account->touch();

        return redirect()->back()->with('message', 'Attached to a customer!');

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * Delets the brand reviews..
     */
    public function detachBrandReviews($id) {
        $reviewx = BrandReviews::findOrFail($id);

        $reviewx->delete();

        return redirect()->back()->with('message', 'Attached to a customer!');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * The action can be attached/ detached, as per the action value the review is atatched or detached if already attached
     */
    public function attachOrDetachReviews(Request $request) {
        $this->validate($request, [
            'action' => 'required',
            'reviewTemplate' => 'required|array'
        ]);

        $templates = $request->get('reviewTemplate');
        $action = $request->get('action');

        foreach ($templates as $id) {
            if ($action == 'attach') {
                $reviewx = BrandReviews::findOrFail($id);
                $account = Account::whereDoesntHave('reviews')->where('platform', 'sitejabber')->orderBy('created_at', 'DESC')->first();

                //set the account id, create review.
                $review = new Review();
                $review->account_id = $account->id;
                $review->review = $reviewx->body;
                $review->platform = 'sitejabber';
                $review->title = $reviewx->title;
                $review->save();

                //mark review as used
                $reviewx->used = 1;
                $reviewx->save();
                $account->touch();

                continue;
            }

            $reviewx = BrandReviews::findOrFail($id);
            $reviewx->delete();
        }

        return redirect()->back()->with('messages', 'Action completed successfully!');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * This method will comply confirm the review as posted, setting the status to 'posted' status
     */
    public function confirmReviewAsPosted($id) {
        Review::where('id', $id)->update([
            'status' => 'posted'
        ]);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     * This method sends the code to post reply to nodejs server, the IP is there which can be changed over time.
     */
    public function sendSitejabberQAReply(Request $request, Client $client) {
        $id = $request->get('rid');
        $negativeReview = NegativeReviews::where('id', $id)->first();
        if (!$negativeReview) {
            return response()->json([
                'status' => 'success'
            ]);
        }

        $comment = $request->get('comment');
        $reply = $request->get('reply');
//        dd($comment, $reply);

        $negativeReview->reply = $reply;
        $negativeReview->save();


        //log to VPS and trigger the reply
        $response = $client->post('http://144.202.53.198/postReply', [
            'form_params' => [
                'comment' => $comment,
                'reply' => $reply
            ],
        ]);

        $data = $response->getBody()->getContents();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
