<?php

namespace Modules\ChatBot\Http\Controllers;

use App\Library\Watson\Model as WatsonManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use \App\ChatbotCategory;
use \App\ChatbotQuestion;
use \App\ChatbotQuestionExample;
use \App\ChatbotKeywordValueTypes;
use App\Customer;
use App\ScheduledMessage;
use Auth;
use DB;
use App\ChatbotQuestionReply;
use App\WatsonAccount;
use App\DeveloperModule;
use App\Github\GithubRepository;
use App\MailinglistTemplate;
use App\ChatbotErrorLog;
use App\ChatbotQuestionErrorLog;
class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $q           = request("q", "");
        $category_id = request("category_id", 0);
        $keyword_or_question = request("keyword_or_question", 'intent');
        $chatQuestions = ChatbotQuestion::leftJoin("chatbot_question_examples as cqe", "cqe.chatbot_question_id", "chatbot_questions.id")
            ->leftJoin("chatbot_categories as cc", "cc.id", "chatbot_questions.category_id")
            ->where('keyword_or_question',$keyword_or_question)
            ->select("chatbot_questions.*", \DB::raw("group_concat(cqe.question) as `questions`"), "cc.name as category_name");
        if (!empty($q)) {
            $chatQuestions = $chatQuestions->where(function ($query) use ($q) {
                $query->where("chatbot_questions.value", "like", "%" . $q . "%")->orWhere("cqe.question", "like", "%" . $q . "%");
            });
        }

        if (!empty($category_id)) {
            $chatQuestions = $chatQuestions->where("cc.id", $category_id);
        }

        $chatQuestions = $chatQuestions->groupBy("chatbot_questions.id")
            ->orderBy("chatbot_questions.id", "desc")
            ->paginate(24)->appends(request()->except(['page','_token']));

        $allCategory = ChatbotCategory::all();
        $allCategoryList = [];
        if (!$allCategory->isEmpty()) {
            foreach ($allCategory as $all) {
                $allCategoryList[] = ["id" => $all->id, "text" => $all->name];
            }
        }

         $task_category = DB::table('task_categories')->select('*')->get();
         $userslist = DB::table('users')->select('*')->get();

         $moduleNames = [];
         // Get all modules
         $modules = DeveloperModule::all();
         // Loop over all modules and store them
         foreach ($modules as $module) {
             $moduleNames[$module->id] = $module->name;
         }
         $respositories = GithubRepository::all();
         
         $templates = MailinglistTemplate::all();
         $watson_accounts = WatsonAccount::all();

        return view('chatbot::question.index', compact('chatQuestions','allCategoryList','watson_accounts','task_category','userslist','modules','respositories','templates'));
    }

    public function create()
    {
        $allCategory = ChatbotCategory::all();
        $allCategoryList = [];
        if (!$allCategory->isEmpty()) {
            foreach ($allCategory as $all) {
                $allCategoryList[] = ["id" => $all->id, "text" => $all->name];
            }
        }
        return view('chatbot::question.create',compact('allCategoryList'));
    }

    public function save(Request $request)
    {
        $params          = $request->all();
        $params["value"] = str_replace(" ", "_", $params["value"]);
        $params["watson_account_id"] = $request->watson_account;
        $validator = Validator::make($params, [
            'value' => 'required|unique:chatbot_questions|max:255',
            'keyword_or_question' => 'required',
            'watson_account'  => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json(["code" => 500, "error" => $validator->errors()]);
        }

        if($request->keyword_or_question  == 'simple' || $request->keyword_or_question  == 'priority-customer') {
            $validator = Validator::make($request->all(), [
                'keyword'      => 'sometimes|nullable|string',
                'suggested_reply'        => 'required|min:3|string',
                'sending_time' => 'sometimes|nullable|date',
                'repeat'       => 'sometimes|nullable|string',
                'is_active'    => 'sometimes|nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(["code" => 500, "error" => $validator->errors()]);
            }
        }
        

        $chatbotQuestion = ChatbotQuestion::create($params);
        if (!empty($params["question"])) {
            foreach($params["question"] as $qu) {
                if($qu) {
                    $params["chatbot_question_id"] = $chatbotQuestion->id;
                    $chatbotQuestionExample        = new ChatbotQuestionExample;
                    $chatbotQuestionExample->question = $qu;
                    $chatbotQuestionExample->chatbot_question_id = $chatbotQuestion->id;
                    $chatbotQuestionExample->save();
                }
            }
        }


        if (array_key_exists("types",$params) && $params["types"] != NULL && array_key_exists("type",$params) && $params["type"] != NULL) {
            $chatbotQuestionExample = null;
            if(!empty($params["value_name"])) {
                $chatbotQuestionExample        = new ChatbotQuestionExample;
                $chatbotQuestionExample->question = $params["value_name"];
                $chatbotQuestionExample->chatbot_question_id = $chatbotQuestion->id;
                $chatbotQuestionExample->types = $params["types"];
                $chatbotQuestionExample->save();
            }

            if($chatbotQuestionExample) {
                $valueType = [];
                $valueType["chatbot_keyword_value_id"] =  $chatbotQuestionExample->id;
                if(!empty($params["type"])) {
                    foreach ($params["type"] as $value) {
                        if($value != NULL) {
                            $valueType["type"] = $value;
                            $chatbotKeywordValueTypes = new ChatbotKeywordValueTypes;
                            $chatbotKeywordValueTypes->fill($valueType);
                            $chatbotKeywordValueTypes->save();
                        }
                    }
                }
            }
        }


        if($request->keyword_or_question  == 'simple' || $request->keyword_or_question  == 'priority-customer') {
            $exploded = explode(',', $request->keyword);
    
            foreach ($exploded as $keyword) {

                $chatbotQuestionExample        = new ChatbotQuestionExample;
                $chatbotQuestionExample->question = trim($keyword);
                $chatbotQuestionExample->chatbot_question_id = $chatbotQuestion->id;
                $chatbotQuestionExample->save();
            }
    
            if ($request->type == 'priority-customer') {
                if ($request->repeat == '') {
                    $customers = Customer::where('is_priority', 1)->get();
    
                    foreach ($customers as $customer) {
                        ScheduledMessage::create([
                            'user_id'      => Auth::id(),
                            'customer_id'  => $customer->id,
                            'message'      => $chatbotQuestion->suggested_reply,
                            'sending_time' => $request->sending_time,
                        ]);
                    }
                }
            }
        }


        if( $params['watson_account'] > 0 ){
            $wotson_account_ids = WatsonAccount::where( 'id', $request->watson_account )->get();
        }else{
            $wotson_account_ids = WatsonAccount::all();
        }
        
        foreach($wotson_account_ids as $id){
            $data_to_insert[] = [
                'suggested_reply' => $params["suggested_reply"],
                'store_website_id' => $id->store_website_id,
                'chatbot_question_id' => $chatbotQuestion->id
            ];
        }
        ChatbotQuestionReply::insert($data_to_insert);


        if($request->erp_or_watson == 'watson') {
            if($request->keyword_or_question == 'intent' || $request->keyword_or_question == 'simple' || $request->keyword_or_question == 'priority-customer') {

                ChatbotQuestion::where( 'id', $chatbotQuestion->id )->update([ 'watson_status' => 'Pending watson send' ]);

                $result = json_decode(WatsonManager::pushQuestion($chatbotQuestion->id, null, $request->watson_account));
            }

            if($request->keyword_or_question == 'entity') {
                
                ChatbotQuestion::where( 'id', $chatbotQuestion->id )->update([ 'watson_status' => 'Pending watson send' ]);

                $result = json_decode(WatsonManager::pushQuestion($chatbotQuestion->id, null, $request->watson_account));
            }

            
            // if (property_exists($result, 'error')) {
            //     $question_error = new ChatbotQuestionErrorLog();
            //     $question_error->chatbot_question_id = $chatbotQuestion->id;
            //     $question_error->type = $request->keyword_or_question;
            //     $question_error->request = json_encode($request->all());
            //     $question_error->response = json_encode($result);
            //     $question_error->response_type = "error";
            //     $question_error->save();
            //     ChatbotQuestion::where("id", $chatbotQuestion->id)->delete();
            //     return response()->json(["code" => $result->code, "error" => $result->error]);
            // }

            // $question_error = new ChatbotQuestionErrorLog();
            // $question_error->chatbot_question_id = $chatbotQuestion->id;
            // $question_error->type = $request->keyword_or_question;
            // $question_error->request = json_encode($request->all());
            // $question_error->response = json_encode($result);
            // $question_error->response_type = "success";
            // $question_error->save();
        }
               

        
        $route = route("chatbot.question.edit", [$chatbotQuestion->id]);
        return response()->json(["code" => 200, "data" => $chatbotQuestion, "redirect" => $route]);
    }

    public function destroy(Request $request, $id)
    {
        if ($id > 0) {

            $chatbotQuestion = ChatbotQuestion::where("id", $id)->first();

            if ($chatbotQuestion) {
                ChatbotQuestionExample::where("chatbot_question_id", $id)->delete();
                $chatbotQuestion->delete();
                WatsonManager::deleteQuestion($chatbotQuestion->id);
                return redirect()->back();
            }
        }

        return redirect()->back();
    }

    public function edit(Request $request, $id)
    {
        $chatbotQuestion = ChatbotQuestion::where("id", $id)->first();
        $allCategory = ChatbotCategory::all();
        $allCategoryList = [];
        if (!$allCategory->isEmpty()) {
            foreach ($allCategory as $all) {
                $allCategoryList[] = ["id" => $all->id, "text" => $all->name];
            }
        }

        $task_category = DB::table('task_categories')->select('*')->get();
         $userslist = DB::table('users')->select('*')->get();

         $moduleNames = [];
         // Get all modules
         $modules = DeveloperModule::all();
         // Loop over all modules and store them
         foreach ($modules as $module) {
             $moduleNames[$module->id] = $module->name;
         }
         $respositories = GithubRepository::all();
         $templates = MailinglistTemplate::all();
         $watson_accounts = WatsonAccount::all();
         if($request->store_website_id) {
            $replies = $chatbotQuestion->chatbotQuestionReplies()->where('store_website_id',$request->store_website_id)->get();
         }
         else {
            $replies = $chatbotQuestion->chatbotQuestionReplies()->get();
         }
         
        return view("chatbot::question.edit", compact('chatbotQuestion','allCategoryList','task_category','userslist','moduleNames','respositories','modules','templates','watson_accounts','replies'));
    }

    public function update(Request $request, $id)
    {
        $params                        = $request->all();
        $params["value"]               = str_replace(" ", "_", $params["value"]);
        $params["chatbot_question_id"] = $id;

        $chatbotQuestion = ChatbotQuestion::where("id", $id)->first();

        if ($chatbotQuestion) {
            $oldvalue = $chatbotQuestion->value;
            if($chatbotQuestion->keyword_or_question == 'intent') {
                $validator = Validator::make($params, [
                    'question' => 'required|unique:chatbot_question_examples',
                ]);
        
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $chatbotQuestion->fill($params);
                $chatbotQuestion->save();
                if (!empty($params["category_id"])) {
                    if (is_numeric($params["category_id"])) {
                        $chatbotQuestion->category_id = $params["category_id"];
                        $chatbotQuestion->save();
                    } else {
                        $catModel = ChatbotCategory::create([
                            "name" => $params["category_id"],
                        ]);
    
                        if ($catModel) {
                            $chatbotQuestion->category_id = $catModel->id;
                            $chatbotQuestion->save();
                        }
                    }
                }

                if (!empty($params["question"])) {
                    $chatbotQuestionExample = new ChatbotQuestionExample;
                    $chatbotQuestionExample->fill($params);
                    $chatbotQuestionExample->save();
                }
                if($chatbotQuestion->erp_or_watson == 'watson') {
                    WatsonManager::pushQuestion($chatbotQuestion->id, $oldvalue);
                }
                
            }
            else if($chatbotQuestion->keyword_or_question == 'entity') {
                $validator = Validator::make($params, [
                    'question' => 'required|unique:chatbot_question_examples',
                ]);
        
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $chatbotQuestion->fill($params);
                $chatbotQuestion->save();
                if (!empty($params["category_id"])) {
                    if (is_numeric($params["category_id"])) {
                        $chatbotQuestion->category_id = $params["category_id"];
                        $chatbotQuestion->save();
                    } else {
                        $catModel = ChatbotCategory::create([
                            "name" => $params["category_id"],
                        ]);
    
                        if ($catModel) {
                            $chatbotQuestion->category_id = $catModel->id;
                            $chatbotQuestion->save();
                        }
                    }
                }

                if(!empty($params["question"])) {
                    $chatbotQuestionExample        = new ChatbotQuestionExample;
                    $chatbotQuestionExample->question = $params["question"];
                    $chatbotQuestionExample->chatbot_question_id = $chatbotQuestion->id;
                    $chatbotQuestionExample->types = $params["types"];
                    $chatbotQuestionExample->save();
                }

                if($chatbotQuestionExample) {
                    $valueType = [];
                    $valueType["chatbot_keyword_value_id"] =  $chatbotQuestionExample->id;
                    if(!empty($params["type"])) {
                        foreach ($params["type"] as $value) {
                            if($value != NULL) {
                                $valueType["type"] = $value;
                                $chatbotKeywordValueTypes = new ChatbotKeywordValueTypes;
                                $chatbotKeywordValueTypes->fill($valueType);
                                $chatbotKeywordValueTypes->save();
                            }
                        }
                    }
                }
                if($chatbotQuestion->erp_or_watson == 'watson') {
                    WatsonManager::pushQuestion($chatbotQuestion->id, $oldvalue);
                }
            }
            if($chatbotQuestion->keyword_or_question == 'simple' || $chatbotQuestion->keyword_or_question == 'priority-customer') {
                $validator = Validator::make($request->all(), [
                    'keyword'      => 'required|string',
                    'sending_time' => 'sometimes|nullable|date',
                    'repeat'       => 'sometimes|nullable|string',
                    'is_active'    => 'sometimes|nullable|integer',
                ]);
        
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $chatbotQuestion->fill($params);
                $chatbotQuestion->save();

                    $chatbotQuestionExample        = new ChatbotQuestionExample;
                    $chatbotQuestionExample->question = trim($params['keyword']);
                    $chatbotQuestionExample->chatbot_question_id = $chatbotQuestion->id;
                    $chatbotQuestionExample->save();
                    if($chatbotQuestion->erp_or_watson == 'watson') {
                        WatsonManager::pushQuestion($chatbotQuestion->id, $oldvalue);
                    }
            }
        }
        return redirect()->back();
    }

    public function destroyValue(Request $request, $id, $valueId)
    {
        $chQuestion = ChatbotQuestion::where("id", $id)->first();
        $cbValue = ChatbotQuestionExample::where("chatbot_question_id", $id)->where("id", $valueId)->first();
        $chatbotQuestion = ChatbotQuestion::where("id", $id)->first();
        if ($cbValue) {
            $cbValue->delete();
            if($chatbotQuestion->keyword_or_question == 'intent' && $chatbotQuestion->erp_or_watson == 'watson') {
            WatsonManager::pushQuestion($id, $chQuestion->value);
            }
            if($chatbotQuestion->keyword_or_question == 'entity' && $chatbotQuestion->erp_or_watson == 'watson') {
                WatsonManager::pushQuestion($id, $chQuestion->value);
            }
        }
        return redirect()->back();
    }

    public function saveAjax(Request $request)
    {
        $groupId     = $request->get("group_id");
        $name        = $request->get("name", "");
        $question    = $request->get("question");
        $category_id = $request->get("category_id");
        $erp_or_watson = $request->get("erp_or_watson");
        $keyword_or_question = $request->get("keyword_or_question");
        if(!$erp_or_watson) {
            $erp_or_watson = 'erp';
        }

        if(!$keyword_or_question) {
            $keyword_or_question = 'intent';
        }
        // if (!empty($groupId) && $groupId > 0) {
        //     $chQuestion = ChatbotQuestion::where("id", $groupId)->first();
        //     $q = ChatbotQuestionExample::updateOrCreate(
        //         ["chatbot_question_id" => $groupId, "question" => $question],
        //         ["chatbot_question_id" => $groupId, "question" => $question]
        //     );
        //     WatsonManager::pushQuestion($groupId);
        //     if($request->suggested_reply && $request->suggested_reply != '') {
        //         $chQuestion->suggested_reply = $request->suggested_reply;
        //         $chQuestion->save();
        //     }
        // } else if (!empty($name)) {
        //     $chQuestion = null;

        //     if (is_numeric($name)) {
        //         $chQuestion = ChatbotQuestion::where("id", $name)->first();
        //     }

        //     if (!$chQuestion) {
        //         $chQuestion = ChatbotQuestion::create([
        //             "value" => str_replace(" ", "_", preg_replace('/\s+/', ' ', $name)),
        //         ]);

        //         if (!empty($category_id)) {
        //             if (is_numeric($category_id)) {
        //                 $chQuestion->category_id = $category_id;
        //                 $chQuestion->save();
        //             } else {
        //                 $catModel = ChatbotCategory::create([
        //                     "name" => $category_id,
        //                 ]);

        //                 if ($catModel) {
        //                     $chQuestion->category_id = $catModel->id;
        //                     $chQuestion->save();
        //                 }
        //             }
        //         }
        //     }

        //     if($request->suggested_reply && $request->suggested_reply != '') {
        //         $chQuestion->suggested_reply = $request->suggested_reply;
        //         $chQuestion->save();
        //     }

        //     $groupId = $chQuestion->id;

        //     if (is_string($question)) {
        //         ChatbotQuestionExample::create(
        //             ["chatbot_question_id" => $chQuestion->id, "question" => preg_replace("/\s+/", " ", $question)]
        //         );
        //     } elseif (is_array($question)) {
        //         foreach ($question as $key => $qRaw) {
        //             ChatbotQuestionExample::create(
        //                 ["chatbot_question_id" => $chQuestion->id, "question" => preg_replace("/\s+/", " ", $qRaw)]
        //             );
        //         }
        //     }
        // }


        $chQuestion = null;

        if (is_numeric($groupId)) {
            $chQuestion = ChatbotQuestion::where("id", $groupId)->first();
        }
        else {
            if($groupId != '') {
                $chQuestion = ChatbotQuestion::create([
                    "value" => str_replace(" ", "_", preg_replace('/\s+/', ' ', $groupId)),
                ]);
            }
        }
        if ($chQuestion) {
            $oldvalue = $chQuestion->value;
            if (!empty($category_id)) {
                if (is_numeric($category_id)) {
                    $chQuestion->category_id = $category_id;
                    $chQuestion->save();
                } else {
                    $catModel = ChatbotCategory::create([
                        "name" => $category_id,
                    ]);

                    if ($catModel) {
                        $chQuestion->category_id = $catModel->id;
                        $chQuestion->save();
                    }
                }
            }
            if(!$chQuestion->keyword_or_question || $chQuestion->keyword_or_question == '') {
                $chQuestion->keyword_or_question = $keyword_or_question;
            }
            $chQuestion->erp_or_watson = $erp_or_watson;
            $chQuestion->save();
            if($request->suggested_reply && $request->suggested_reply != '') {
                $chQuestion->suggested_reply = $request->suggested_reply;
                $chQuestion->save();
            }
    
            $groupId = $chQuestion->id;
    
            if (is_string($question)) {
                ChatbotQuestionExample::create(
                    ["chatbot_question_id" => $chQuestion->id, "question" => preg_replace("/\s+/", " ", $question)]
                );
            } elseif (is_array($question)) {
                foreach ($question as $key => $qRaw) {
                    ChatbotQuestionExample::create(
                        ["chatbot_question_id" => $chQuestion->id, "question" => preg_replace("/\s+/", " ", $qRaw)]
                    );
                }
            }
    
            if ($groupId > 0 && $erp_or_watson == 'watson') {
                WatsonManager::pushQuestion($groupId, $oldvalue);
            }
            $question = ChatbotQuestion::where('keyword_or_question','intent')->select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
            $keywords = ChatbotQuestion::where('keyword_or_question','entity')->select(\DB::raw("concat('@','',value) as value"))->get()->pluck("value", "value")->toArray();
    
    
    
            $allSuggestedOptions = $keywords + $question;
            return response()->json(["code" => 200, 'allSuggestedOptions' => $allSuggestedOptions]);
        }
        else {
            return response()->json(["code" => 500, "message" => 'Please select an intent or write a new one.']);
        }

    }

    public function search(Request $request)
    {
        $keyword     = request("term", "");
        $allquestion = ChatbotQuestion::where("value", "like", "%" . $keyword . "%")->limit(10)->get();

        $allquestionList = [];
        if (!$allquestion->isEmpty()) {
            foreach ($allquestion as $all) {
                $allquestionList[] = ["id" => $all->id, "text" => $all->value, "suggested_reply" => $all->suggested_reply];
            }
        }

        return response()->json(["incomplete_results" => false, "items" => $allquestionList, "total_count" => count($allquestionList)]);

    }


    public function getCategories()
    {
        $allCategory = ChatbotCategory::all();
        $allCategoryList = [];
        if (!$allCategory->isEmpty()) {
            foreach ($allCategory as $all) {
                $allCategoryList[] = ["id" => $all->id, "text" => $all->name];
            }
        }

        return response()->json(["incomplete_results" => false, "items" => $allCategoryList, "total_count" => count($allCategoryList)]);

    }

    public function saveAnnotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chatbot_question_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["code" => 500, "error" => []]);
        }

        $model = \App\ChatbotIntentsAnnotation::updateOrCreate(
            [
                "question_example_id" => $request->get("question_example_id"),
                "chatbot_keyword_id"  => $request->get("chatbot_question_id"),
                "start_char_range"    => $request->get("start_char_range"),
                "end_char_range"      => $request->get("end_char_range"),
            ],
            $request->all()
        );

        if ($model) {
            // $chatbotKeywordValue = \App\ChatbotKeywordValue::create([
            //     "chatbot_keyword_id" => $model->chatbot_keyword_id,
            //     "value"              => $request->get("keyword_value"),
            // ]);
            $chatbotQuestionExample = \App\ChatbotQuestionExample::create([
                "chatbot_question_id" => $model->chatbot_keyword_id,
                "question"              => $request->get("keyword_value"),
            ]);
            
            $model->chatbot_value_id = $chatbotQuestionExample->id;
            $model->save();
            WatsonManager::pushValue($model->question_example_id);
        }

        return response()->json(["code" => 200]);
    }

    public function deleteAnnotation(Request $request)
    {
        $annotationId = $request->get("id");
        $annotation   = \App\ChatbotIntentsAnnotation::where("id", $annotationId)->first();

        if ($annotation) {
            $questionExample = $annotation->question_example_id;
            $annotation->delete();
            WatsonManager::pushValue($questionExample);

            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "message" => "No record founds"]);
    }

    public function searchCategory(Request $request)
    {

        $keyword     = request("term", "");
        $allCategory = ChatbotCategory::where("name", "like", "%" . $keyword . "%")->limit(10)->get();

        $allCategoryList = [];
        if (!$allCategory->isEmpty()) {
            foreach ($allCategory as $all) {
                $allCategoryList[] = ["id" => $all->id, "text" => $all->name];
            }
        }

        return response()->json(["incomplete_results" => false, "items" => $allCategoryList, "total_count" => count($allCategoryList)]);

    }

    public function changeCategory(Request $request) {
        if($request->category_id && $request->id) {
            $chatbotQuestion = ChatbotQuestion::find($request->id);
            if($chatbotQuestion) {
                $chatbotQuestion->category_id = $request->category_id;
                $chatbotQuestion->save();
                return response()->json(['message' => 'Success'],200);
            }
        }
        return response()->json(['message' => 'Question or category not found'],500);
    }

    public function searchKeyword(Request $request)
    {
        $keyword = request("term","");
        $allKeyword = ChatbotQuestion::where("value","like","%".$keyword."%")->limit(10)->get();

        $allKeywordList = [];
        if(!$allKeyword->isEmpty()) {
            foreach($allKeyword as $all) {
                $allKeywordList[] = ["id" => $all->id , "text" => $all->value]; 
            }
        }

        return response()->json(["incomplete_results" => false, "items"=> $allKeywordList, "total_count" => count($allKeywordList)]);
    }


    public function saveAutoreply(Request $request) {
        // $this->validate($request, [
        //     'type'         => 'required|string',
        //     'keyword'      => 'sometimes|nullable|string',
        //     'reply'        => 'required|min:3|string',
        //     'sending_time' => 'sometimes|nullable|date',
        //     'repeat'       => 'sometimes|nullable|string',
        //     'is_active'    => 'sometimes|nullable|integer',
        // ]);

        $validator = Validator::make($request->all(), [
            'type'         => 'required|string',
            'keyword'      => 'sometimes|nullable|string',
            'reply'        => 'required|min:3|string',
            'sending_time' => 'sometimes|nullable|date',
            'repeat'       => 'sometimes|nullable|string',
            'is_active'    => 'sometimes|nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(["code" => 500, "error" => []]);
        }

        $exploded = explode(',', $request->keyword);

        foreach ($exploded as $keyword) {
            $chatbotQuestion               = new ChatbotQuestion;
            $chatbotQuestion->keyword_or_question = $request->type;
            $chatbotQuestion->value      = trim($keyword);
            $chatbotQuestion->suggested_reply = $request->reply;
            $chatbotQuestion->sending_time = $request->sending_time;
            $chatbotQuestion->repeat       = $request->repeat;
            $chatbotQuestion->is_active    = $request->is_active;
            $chatbotQuestion->save();
        }

        if ($request->type == 'priority-customer') {
            if ($request->repeat == '') {
                $customers = Customer::where('is_priority', 1)->get();

                foreach ($customers as $customer) {
                    ScheduledMessage::create([
                        'user_id'      => Auth::id(),
                        'customer_id'  => $customer->id,
                        'message'      => $chatbotQuestion->suggested_reply,
                        'sending_time' => $request->sending_time,
                    ]);
                }
            }
        }

        return redirect()->back()->withSuccess('You have successfully created a new auto-reply!');
    }

    public function saveDynamicTask(Request $request) {
        $params          = $request->all();
        $params["value"] = str_replace(" ", "_", $params["value"]);
        $validator = Validator::make($params, [
            'value' => 'required|unique:chatbot_questions|max:255',
            'question' => 'required',
            'category_id' => 'required',
            'assigned_to' => 'required',
            'task_category_id' => 'required',
            'task_description' => 'required',
            'suggested_reply' => 'required',
            'watson_account'  => 'nullable',
        ]);
        if($params['task_type'] == 'devtask') {
            if(!$params['repository_id'] || !$params['module_id']) {
                return response()->json(["code" => 500, "error" => 'Repository and module is required for Devtask']);
            }
        }
        if ($validator->fails()) {
            return response()->json(["code" => 500, "error" => 'Incomplete data or intent already exists']);
        }
        $params["keyword_or_question"] = 'intent';
        $params["erp_or_watson"] = 'erp';
        $params["auto_approve"] = 1;
        $params["is_active"] = 1;
        $params["watson_account_id"] = $params['watson_account'];
        $chatbotQuestion = ChatbotQuestion::create($params);
        if (!empty($params["question"])) {
                    $chatbotQuestionExample        = new ChatbotQuestionExample;
                    $chatbotQuestionExample->question = $params["question"];
                    $chatbotQuestionExample->chatbot_question_id = $chatbotQuestion->id;
                    $chatbotQuestionExample->save();
        }

        if( $params['watson_account'] > 0 ){
            $wotson_account_ids = WatsonAccount::where( 'id', $params['watson_account'] )->get();
        }else{
            $wotson_account_ids = WatsonAccount::all();
        }


        foreach($wotson_account_ids as $id){
            $data_to_insert[] = [
                'suggested_reply'     => $params["suggested_reply"],
                'store_website_id'    => $id->store_website_id,
                'chatbot_question_id' => $chatbotQuestion->id
            ];
        }
        ChatbotQuestionReply::insert($data_to_insert);

        return response()->json(['message' => 'Successfully created the Intent', 'code' => 200]);
    }




    public function saveDynamicReply(Request $request) {

        $params          = $request->all();
        $params["value"] = str_replace(" ", "_", $params["value"]);
        $validator = Validator::make($params, [
            'value' => 'required|unique:chatbot_questions|max:255',
            'question' => 'required',
            'category_id' => 'required',
            'erp_or_watson' => 'required',
            'suggested_reply' => 'required',
            'watson_account'  => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json(["code" => 500, "error" => 'Incomplete data or intent already exists']);
        }
        $params["keyword_or_question"] = 'intent';
        $params["is_active"] = 1;
        $params["dynamic_reply"] = 1;
        $params["watson_account_id"] = $params['watson_account'];
        $chatbotQuestion = ChatbotQuestion::create($params);
        if (!empty($params["question"])) {
                    $chatbotQuestionExample        = new ChatbotQuestionExample;
                    $chatbotQuestionExample->question = $params["question"];
                    $chatbotQuestionExample->chatbot_question_id = $chatbotQuestion->id;
                    $chatbotQuestionExample->save();
        }
       
        if($params["erp_or_watson"] == 'watson') {
            $result = json_decode(WatsonManager::pushQuestion( $chatbotQuestion->id, null, $params['watson_account'] ));
        }
        else {
            
            if( $params['watson_account'] > 0 ){
                $wotson_account_ids = WatsonAccount::where( 'id', $params['watson_account'] )->get();
            }else{
                $wotson_account_ids = WatsonAccount::all();
            }

            foreach($wotson_account_ids as $id){
                $data_to_insert[] = [
                    'suggested_reply' => $params["suggested_reply"],
                    'store_website_id' => $id->store_website_id,
                    'chatbot_question_id' => $chatbotQuestion->id
                ];
            }
            ChatbotQuestionReply::insert($data_to_insert);
        }
        return response()->json(['message' => 'Successfully created the Intent', 'code' => 200]);
    }


    public function updateReply(Request $request) {
        $reply = ChatbotQuestionReply::find($request->id);
        $reply->suggested_reply = $request->suggested_reply;
        $reply->save();
        return response()->json(['suggested_reply' => $request->suggested_reply,'code' => 200]);
    }

    public function addReply(Request $request) {
        $reply = ChatbotQuestionReply::where('store_website_id',$request->store_website_id)->where('chatbot_question_id', $request->id)->first();
        if($reply) {
            return response()->json(['message' => 'Reply is already available, you can edit the reply', 'code' => 500]);
        }
        $reply = new ChatbotQuestionReply;
        $reply->store_website_id = $request->store_website_id;
        $reply->chatbot_question_id = $request->id;
        $reply->suggested_reply = $request->suggested_reply;
        $reply->save();
        return response()->json(['message' => 'Successfull','suggested_reply' => $request->suggested_reply,'code' => 200]);
    }

    public function onlineUpdate($id) {
        $errorLog = ChatbotErrorLog::find($id);
        $result = WatsonManager::pushQuestionSingleWebsite($errorLog->chatbot_question_id,$errorLog->store_website_id);
        if($result) {
            return response()->json(['message' => 'Created successfully','code' => 200]);
        }
        else {
            return response()->json(['message' => 'Something went wrong, check error log','code' => 500]);
        }
        
    }

    public function showLogById(Request $request){
        $chatbotQuestion = ChatbotQuestionErrorLog::where("chatbot_question_id", $request->id)->get();
        $body = "";
        $i = 0;
        // foreach($chatbotQuestion as $error){
        //     $response = $error->response;
        //     $st = 
        //     $status = $error->status == "Success" ? "<td>".$error->status."</td>" : "";
        //     $body .= "<tr><td>".$i."</td><td>".$response."</td><td>".;
        // }
        return response()->json(['code' => 200,'data' => $chatbotQuestion],200);
    }

    public function searchSuggestion(Request $request)
    {
           $listOfQuestions = ChatbotQuestionExample::join("chatbot_questions as cq","cq.id","chatbot_question_examples.chatbot_question_id")
           ->where("question", "LIKE" , "%".$request->q."%")
           ->where("cq.keyword_or_question", $request->type)
           ->select(["chatbot_question_examples.*","cq.value", "cq.erp_or_watson"])
           ->limit(10)
           ->get()->toArray();

           return response()->json(["code" => 200 , "data" => $listOfQuestions]);
    }

    public function searchSuggestionDelete(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $cbValue = ChatbotQuestionExample::where("id", $id)->first();
        if ($cbValue) {
            $questionModal = $cbValue->questionModal; 
            $cbValue->delete();
            if($questionModal) {
                if($questionModal->keyword_or_question == 'intent' && $questionModal->erp_or_watson == 'watson') {
                   WatsonManager::pushQuestion($id, $questionModal->value);
                }
                if($questionModal->keyword_or_question == 'entity' && $questionModal->erp_or_watson == 'watson') {
                    WatsonManager::pushQuestion($id, $questionModal->value);
                }
            }

            return response()->json(["code" => 200 , "data" => [], "message" => "Example delete successfully"]);
        }

        return response()->json(["code" => 500 , "data" => [], "message" => "Record does not exist in the database"]);
    }

    public function repeatWatson(Request $request)
    {
        $id = $request->id;
        $chatbotQuestion = ChatbotQuestion::whereIn("id", $id)->get();

        if ($chatbotQuestion) {
            
            foreach($chatbotQuestion as $key){

                if( $key->erp_or_watson == 'watson' ){
                    if($key->keyword_or_question == 'intent' || $key->keyword_or_question == 'simple' || $key->keyword_or_question == 'priority-customer' || $key->keyword_or_question == 'entity') {
                        WatsonManager::pushQuestion( $key->id, null, $key->watson_account_id );
                    }
                }
            }
            return response()->json(["code" => 200 , "data" => [], "message" => "send successfully"]);
        }

        return response()->json(["code" => 500 , "data" => [], "message" => "Something went wrong, Please try again!"]);
    }

    public function suggestedResponse(Request $request)
    {
        $reply = \App\ChatbotQuestionReply::get();

        return view('chatbot::question.partial.suggested-response',compact('reply'));
    }
    
}

