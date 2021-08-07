<?php

namespace App\Http\Controllers;

use App\AutoReply;
use App\ChatbotDialog;
use App\ChatbotKeyword;
use App\ChatbotKeywordValue;
use App\ChatbotQuestion;
use App\ChatbotQuestionExample;
use App\ChatMessagePhrase;
use App\ChatMessageWord;
use App\Customer;
use App\Library\Watson\Model as WatsonManager;
use App\ScheduledMessage;
use App\Setting;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\ChatbotCategory;
use App\ChatbotQuestionReply;
use App\WatsonAccount;
class AutoReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $keyword = $request->keyword;

        $simple_auto_replies = AutoReply::where('type', 'simple');
            if(!empty($keyword)){
                $simple_auto_replies->where('reply',"LIKE", "%$keyword%");
            }
        $simple_auto_replies = $simple_auto_replies->latest()->get()->groupBy('reply')->toArray();


        $priority_customers_replies = AutoReply::where('type', 'priority-customer');
            if(!empty($keyword)){
                $priority_customers_replies->where('reply',"LIKE", "%$keyword%");
            }
        $priority_customers_replies = $priority_customers_replies->latest()->paginate(Setting::get('pagination'), ['*'], 'priority-page');
        

        $auto_replies   = AutoReply::where('type', 'auto-reply');
            if(!empty($keyword)){
                $auto_replies->where('reply',"LIKE", "%$keyword%");
            }
        $auto_replies = $auto_replies->latest()->paginate(Setting::get('pagination'), ['*'], 'autoreply-page');
        

        $show_automated_messages    = Setting::get('show_automated_messages');

        $currentPage  = LengthAwarePaginator::resolveCurrentPage();
        $perPage      = Setting::get('pagination');
        $currentItems = array_slice($simple_auto_replies, $perPage * ($currentPage - 1), $perPage);

        $simple_auto_replies = new LengthAwarePaginator($currentItems, count($simple_auto_replies), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        return view('autoreplies.index', [
            'auto_replies'               => $auto_replies,
            'simple_auto_replies'        => $simple_auto_replies,
            'priority_customers_replies' => $priority_customers_replies,
            'show_automated_messages'    => $show_automated_messages,
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type'         => 'required|string',
            'keyword'      => 'sometimes|nullable|string',
            'reply'        => 'required|min:3|string',
            'sending_time' => 'sometimes|nullable|date',
            'repeat'       => 'sometimes|nullable|string',
            'is_active'    => 'sometimes|nullable|integer',
        ]);

        $exploded = explode(',', $request->keyword);

        foreach ($exploded as $keyword) {
            $auto_reply               = new AutoReply;
            $auto_reply->type         = $request->type;
            $auto_reply->keyword      = trim($keyword);
            $auto_reply->reply        = $request->reply;
            $auto_reply->sending_time = $request->sending_time;
            $auto_reply->repeat       = $request->repeat;
            $auto_reply->is_active    = $request->is_active;
            $auto_reply->save();
        }

        if ($request->type == 'priority-customer') {
            if ($request->repeat == '') {
                $customers = Customer::where('is_priority', 1)->get();

                foreach ($customers as $customer) {
                    ScheduledMessage::create([
                        'user_id'      => Auth::id(),
                        'customer_id'  => $customer->id,
                        'message'      => $auto_reply->reply,
                        'sending_time' => $request->sending_time,
                    ]);
                }
            }
        }

        return redirect()->route('autoreply.index')->withSuccess('You have successfully created a new auto-reply!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type'         => 'required|string',
            'keyword'      => 'sometimes|nullable|string',
            'reply'        => 'required|min:3|string',
            'sending_time' => 'sometimes|nullable|date',
            'repeat'       => 'sometimes|nullable|string',
            'is_active'    => 'sometimes|nullable|integer',
        ]);

        $auto_reply               = AutoReply::find($id);
        $auto_reply->type         = $request->type;
        $auto_reply->keyword      = $request->keyword;
        $auto_reply->reply        = $request->reply;
        $auto_reply->sending_time = $request->sending_time;
        $auto_reply->repeat       = $request->repeat;
        $auto_reply->is_active    = $request->is_active;
        $auto_reply->save();

        return redirect()->route('autoreply.index')->withSuccess('You have successfully updated auto reply!');
    }

    public function updateReply(Request $request, $id)
    {
        $auto_reply        = AutoReply::find($id);
        $auto_reply->reply = $request->reply;
        $auto_reply->save();

        return response('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AutoReply::find($id)->delete();

        return redirect()->route('autoreply.index')->withSuccess('You have successfully deleted auto reply!');
    }

    public function deleteChatWord(Request $request)
    {
        $id = $request->get("id");

        if ($id > 0) {
            \App\ChatMessagePhrase::where("word_id", $id)->delete();
            \App\ChatMessageWord::where("id", $id)->delete();

            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500]);

    }

    public function getRepliedChat(Request $request, $id)
    {
        $currentMessage = \App\ChatMessage::where("id", $id)->first();

        if ($currentMessage) {
            $customerId = $currentMessage->customer_id;

            $lastReplies = \App\ChatMessage::join("customers", "customers.id", "chat_messages.customer_id")->where("chat_messages.id", ">", $id)
                ->where("customer_id", $customerId)
                ->whereNull("number")
                ->orderBy("chat_messages.id", "ASC")
                ->paginate(5);

            return response()->json(["code" => 200, "data" => $lastReplies->items(), "question" => $currentMessage->message, "page" => $lastReplies->currentPage() + 1]);

        }

        return response()->json(["code" => 200, "data" => []]);

    }

    public function saveByQuestion(Request $request)
    {
        $question = $request->get("q");
        $answer   = $request->get("a");

        \App\AutoReply::updateOrCreate([
            "type"    => "auto-reply",
            "keyword" => $question,
        ], [
            "type"    => "auto-reply",
            "keyword" => $question,
            "reply"   => $answer,
        ]);

        return response()->json(["code" => 200]);

    }

    public function saveGroup(Request $request)
    {
        $keywords = $request->id;
        $name     = $request->name;
        $group    = $request->keyword_group;

        //Check Existing Group
        if ($group != '') {
            $group   = ChatbotKeyword::find($group);
            $groupId = $group->id;
        } else {
            //Create Group
            $group          = new ChatbotKeyword();
            $group->keyword = str_replace(" ", "_", preg_replace('/\s+/', ' ', $name));
            $group->save();
            $groupId = $group->id;
        }

        if (!empty($keywords) && is_array($keywords)) {
            $words = ChatMessageWord::whereIn("id", $keywords)->get();
            if (!$words->isEmpty()) {
                foreach ($words as $word) {
                    //Check If Group ALready Exist
                    $checkExistingGroup = ChatbotKeywordValue::where('chatbot_keyword_id', $groupId)->where('value', $word->word)->first();
                    if ($checkExistingGroup == null) {
                        $keywordSave                     = new ChatbotKeywordValue();
                        $keywordSave->chatbot_keyword_id = $groupId;
                        $keywordSave->value              = preg_replace("/\s+/", " ", $word->word);
                        $keywordSave->save();
                    }
                }
            }
        }
        // call api to store data
        WatsonManager::pushKeyword($groupId);

        return response()->json(["response" => 200]);
    }

    public function saveGroupPhrases(Request $request)
    {
        $phrasesReq     = $request->phraseId;
        $keyword        = $request->keyword;
        $erp_or_watson           = $request->erp_or_watson;
        $group          = $request->phrase_group;
        $suggestedReply = $request->reply;
        $auto_approve = $request->auto_approve;
        $category_id = $request->category_id;

        if($group && $group != "") {
            if (is_numeric($group)) {
                $chatbotQuestion = ChatbotQuestion::find($group);
            } else {
                $chatbotQuestion        = new ChatbotQuestion;
                $chatbotQuestion->value = str_replace(" ", "_", preg_replace('/\s+/', ' ', $group));
            }
        }
        else {
            return response()->json(["message" => 'Select one intent or create one', 'code' => 500]);
        }
       
        if($category_id) {
            $chatbotQuestion->category_id = $category_id;
        }
        $chatbotQuestion->erp_or_watson = $erp_or_watson;
        $chatbotQuestion->auto_approve = $auto_approve;
        $chatbotQuestion->suggested_reply = $suggestedReply;
        $chatbotQuestion->keyword_or_question = 'intent';
        $chatbotQuestion->is_active = 1;
        $chatbotQuestion->save();


        //Getting Phrase in array
        if (!empty($phrasesReq) && is_array($phrasesReq)) {
            $phrase = ChatMessagePhrase::whereIn("id", $phrasesReq)->get();
            if (!$phrase->isEmpty()) {
                foreach ($phrase as $rec) {
                    $checkExistingGroup = ChatbotQuestionExample::where('chatbot_question_id', $chatbotQuestion->id)->where('question', $rec->phrase)->first();
                    if ($checkExistingGroup == null) {
                        //Place Api Here For Keywords
                        $phraseSave                      = new ChatbotQuestionExample();
                        $phraseSave->chatbot_question_id = $chatbotQuestion->id;
                        $phraseSave->question            = preg_replace("/\s+/", " ", $rec->phrase);
                        $phraseSave->save();
                    }
                    $value = $rec->phrase;
                    $rec->deleted_by = \Auth::user()->id;
                    $rec->save();
                    $rec->delete();
                    ChatMessagePhrase::where("phrase", $value)->whereNotIn("id",[$rec->id])->forceDelete();
                }
            }
        }

        // call api to store data
        if($chatbotQuestion->erp_or_watson == 'watson') {
            WatsonManager::pushQuestion($chatbotQuestion->id);
        }
        else {
            $watson_account_ids = WatsonAccount::all();
            foreach($watson_account_ids as $id){
                $data_to_insert[] = [
                    'suggested_reply' => $suggestedReply,
                    'store_website_id' => $id->store_website_id,
                    'chatbot_question_id' => $chatbotQuestion->id
                ];
            }
            ChatbotQuestionReply::insert($data_to_insert);
        }
        

        return response()->json(["message" => 'Successfully created intent', 'code' => 200]);
    }

    public function mostUsedWords(Request $request)
    {
        $groupKeywords = \App\ChatbotKeyword::all();
        $groupPhrases  = \App\ChatbotQuestion::all();

        $keyword = request("keyword", "");

        $mostUsedWords = new \App\ChatMessageWord;

        if (!empty($keyword)) {
            $mostUsedWords = $mostUsedWords->where("word", "like", "%$keyword%");
        }

        $mostUsedWords = $mostUsedWords->orderBy("total", "asc");

        $mostUsedWords = $mostUsedWords->paginate(10);

        $allSuggestedOptions = \App\ChatbotDialog::allSuggestedOptions();

        return view("autoreplies.most-used-words", [
            'mostUsedWords'       => $mostUsedWords,
            'groupPhrases'        => $groupPhrases,
            'groupKeywords'       => $groupKeywords,
            'allSuggestedOptions' => $allSuggestedOptions,
        ]);
    }

    public function getPhrases(Request $request)
    {

        $id      = $request->get("id", 0);
        $keyword = $request->get("keyword", "");

        $phrases = \App\ChatMessagePhrase::where("word_id", $id)->where("phrase", "!=", "")->groupBy("phrase");

        if (!empty($keyword)) {
            $phrases = $phrases->where("phrase", "like", "%$keyword%");
        }

        $phrases = $phrases->paginate(10);

        $string = (string) view("autoreplies.partials.phrases", compact('phrases', "id", 'keyword'));

        return response()->json(["code" => 200, "html" => $string]);

    }

    public function mostUsedPhrases(Request $request)
    {
        $groupPhrases = \App\ChatbotQuestion::all();
        $allCategory = ChatbotCategory::all();
        $allCategoryList = [];
        if (!$allCategory->isEmpty()) {
            foreach ($allCategory as $all) {
                $allCategoryList[] = ["id" => $all->id, "text" => $all->name];
            }
        }

        $keyword = request("keyword", "");

        $mostUsedPhrases = new \App\ChatMessagePhrase;

        if (!empty($keyword)) {
            $mostUsedPhrases = $mostUsedPhrases->where("phrase", "like", "%$keyword%");
        }

        $mostUsedPhrases = $mostUsedPhrases->where(\DB::raw("LENGTH(phrase) - LENGTH(REPLACE(phrase, ' ', '')) + 1"), ">", 3);

        $mostUsedPhrases->select([\DB::raw("count(phrase) as total_count"), "chat_message_phrases.*"]);
        $mostUsedPhrases->join(\DB::raw('(SELECT id from chat_message_phrases group by chat_id) as cmp'), function ($join) {
            $join->on("chat_message_phrases.id", "=", "cmp.id");
        });

        $mostUsedPhrases->groupBy("phrase");
        $mostUsedPhrases = $mostUsedPhrases->orderBy("total_count", "desc");

        $mostUsedPhrases = $mostUsedPhrases->paginate(10);
        $multiple        = 100;

        $recordsNeedToBeShown = floor($mostUsedPhrases->lastPage() / $multiple);
        $activeNo             = floor($mostUsedPhrases->currentPage() / $multiple);

        $allSuggestedOptions = \App\ChatbotDialog::allSuggestedOptions();

        return view("autoreplies.most-used-phrases", [
            'mostUsedPhrases'      => $mostUsedPhrases,
            'groupPhrases'         => $groupPhrases,
            'groupKeywords'        => [],
            'allSuggestedOptions'  => $allSuggestedOptions,
            'recordsNeedToBeShown' => $recordsNeedToBeShown,
            'multiple'             => $multiple,
            'activeNo'             => $activeNo,
            'allCategoryList'      => $allCategoryList
        ]);
    }

    public function deleteMostUsedPharses(Request $request)
    {
        $id      = $request->id;
        $phrases = \App\ChatMessagePhrase::find($id);
        if ($phrases) {
            $phrases = \App\ChatMessagePhrase::where("phrase",$phrases->phrase)->forceDelete();
        }

        return response()->json(["code" => 200, "data" => []]);

    }

    public function mostUsedPhrasesDeleted(Request $request)
    {
        $title = "Most used pharases deleted";
        return view("autoreplies.most-used-phrases.index", compact('title'));

    }

    public function mostUsedPhrasesDeletedRecords(Request $request)
    {
        $history = \App\ChatMessagePhrase::leftJoin("users as u", "u.id", "chat_message_phrases.deleted_by")
            ->whereNotNull("chat_message_phrases.deleted_at")
            ->withTrashed()
            ->orderBy("chat_message_phrases.deleted_at", "desc")
            ->select(["chat_message_phrases.*", "u.name as user_name"]);

        if ($request->keyword != null) {
            $history = $history->where(function ($q) use ($request) {
                $q->where("chat_message_phrases.phrase", "like", "%" . $request->keyword . "%")->orWhere("u.name", "like", "%" . $request->keyword . "%");
            });
        }

        $history = $history->paginate(\App\Setting::get('pagination'));

        return response()->json([
            "code"       => 200,
            "data"       => $history->items(),
            "total"      => $history->total(),
            "pagination" => (string) $history->render(),
        ]);
    }

    public function getPhrasesReply(Request $request)
    {
        $messageIds = $request->get("message_ids",[]);
        $answers = [];
        if(!empty($messageIds)) {
            foreach($messageIds as $id) {
                $lastReplies = \App\ChatMessage::join("customers", "customers.id", "chat_messages.customer_id")->where("chat_messages.id", ">", $id)
                        ->whereNull("number")
                        ->orderBy("chat_messages.id", "ASC")
                        ->limit(5)->get();

                if(!$lastReplies->isEmpty()) {
                    foreach($lastReplies as $lr) {
                        if(trim($lr->message) != "") {
                            $answers[] = $lr->message;
                        }
                    }
                }
            }
        }

        return response()->json(["code" => 200 , "data" => $answers]);
    }

    public function getPhrasesReplyResponse(Request $request)
    {
        if($request->keyword != null) {
            $question = ChatbotQuestion::where("value",str_replace("#", "", $request->keyword))->first();
            if($question) {
                return response()->json(["code" => 200 , "data" => ["message" => $question->suggested_reply]]);
            }
        }

        return response()->json(["code" => 200 , "data" => ["message" => ""]]);
    }

}
