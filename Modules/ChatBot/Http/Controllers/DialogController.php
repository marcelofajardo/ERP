<?php

namespace Modules\ChatBot\Http\Controllers;

use App\Library\Watson\Model as WatsonManager;
use App\StoreWebsite;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use \App\ChatbotDialog;
use \App\ChatbotDialogResponse;
use \App\ChatbotKeyword;
use \App\ChatbotQuestion;
use App\WatsonAccount;
use App\ChatbotDialogErrorLog;
class DialogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $allSuggestedOptions = ChatbotDialog::allSuggestedOptions();
        return view('chatbot::dialog.index', compact('allSuggestedOptions'));
    }
    public function dialogGrid()
    {
        $allSuggestedOptions = ChatbotDialog::allSuggestedOptions();
        return view('chatbot::dialog-grid.index', compact('allSuggestedOptions'));
    }
    

    public function create()
    {
        return view('chatbot::dialog.create');
    }

    public function save(Request $request)
    {
        $params         = $request->all();
        $params["name"] = str_replace(" ", "_", $params["name"]);

        $validator = Validator::make($params, [
            'name' => 'required|unique:chatbot_dialogs|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(["code" => 500, "error" => []]);
        }

        // check the last id of previous sibling and add it 
        /*$previousSibling = ChatbotDialog::where()*/


        $chatbotDialog = ChatbotDialog::create($params);

        $result        = json_decode(WatsonManager::pushDialog($chatbotDialog->id));

        if (property_exists($result, 'error')) {
            ChatbotDialog::where("id", $chatbotDialog->id)->delete();
            return response()->json(["code" => $result->code, "error" => $result->error]);
        }

        if (property_exists($result, 'error')) {
            ChatbotDialog::where("id", $chatbotDialog->id)->delete();
            return response()->json(["code" => $result->code, "error" => $result->error]);
        }
        return response()->json(["code" => 200, "data" => $chatbotDialog, "redirect" => route("chatbot.dialog.edit", [$chatbotDialog->id])]);
    }

    public function destroy(Request $request, $id)
    {
        if ($id > 0) {

            $chatbotDialog = ChatbotDialog::where("id", $id)->first();

            if ($chatbotDialog) {
                // check if it has any parent 
                $hasChild = ChatbotDialog::where("parent_id",$id)->first();
                if($hasChild) {
                    return redirect()->back();
                }

                WatsonManager::deleteDialog($chatbotDialog->id);
                ChatbotDialogResponse::where("chatbot_dialog_id", $id)->delete();
                $chatbotDialog->delete();
                return redirect()->back();
            }

        }

        return redirect()->back();
    }

    public function edit(Request $request, $id)
    {
        $chatbotDialog       = ChatbotDialog::where("id", $id)->first();
        // $question            = ChatbotQuestion::select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
        // $keywords            = ChatbotKeyword::select(\DB::raw("concat('@','',keyword) as keyword"))->get()->pluck("keyword", "keyword")->toArray();

        $question = ChatbotQuestion::where('keyword_or_question','intent')->select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
        $keywords = ChatbotQuestion::where('keyword_or_question','entity')->select(\DB::raw("concat('@','',value) as value"))->get()->pluck("value", "value")->toArray();
        $allSuggestedOptions = $keywords + $question;

        return view("chatbot::dialog.edit", compact('chatbotDialog', 'allSuggestedOptions'));
    }

    public function update(Request $request, $id)
    {

        $params                      = $request->all();
        $params["name"]              = str_replace(" ", "_", $params["name"]);
        $params["chatbot_dialog_id"] = $id;

        $chatbotDialog = ChatbotDialog::where("id", $id)->first();

        if ($chatbotDialog) {

            $chatbotDialog->fill($params);
            $chatbotDialog->save();

            if (!empty($params["value"])) {

                $params["response_type"]          = "text";
                $params["message_to_human_agent"] = 1;

                $chatbotDialogResponse = new ChatbotDialogResponse;
                $chatbotDialogResponse->fill($params);
                $chatbotDialogResponse->save();

            }

            WatsonManager::pushDialog($chatbotDialog->id);

        }

        return redirect()->back();

    }

    public function destroyValue(Request $request, $id, $valueId)
    {
        $cbValue = ChatbotDialogResponse::where("chatbot_dialog_id", $id)->where("id", $valueId)->first();
        if ($cbValue) {
            $cbValue->delete();
            WatsonManager::pushDialog($id);
        }
        return redirect()->back();
    }

    public function saveAjax(Request $request)
    {
        $params          = $request->all();
        $params["name"]  = str_replace(" ", "_", $params["title"]);
        $responseType    = $request->get("response_type", false);
        $previousSibling = $request->get("previous_sibling",false);
        $parentId = $request->get("parent_id",0);
        $matchCondition = implode(" ", $request->get("conditions"));

        $id               = $request->get("id", 0);
        $multipleResponse = $request->get("response_condition", []);
        $notToDelete      = [];
        if (!empty($multipleResponse)) {
            foreach ($multipleResponse as $k => $idStore) {
                $notToDelete[] = $k;
            }
        }
        $chatbotDialog = ChatbotDialog::find($id);
        if (empty($chatbotDialog)) {

            if(empty($previousSibling)) {
                response()->json(["code" => 500, "message" => "Please selected previous sibling dialog"]);
            }

            $chatbotDialog = new ChatbotDialog;
        } else {
            // delete old values and send new again start
            $responseCondition = $chatbotDialog->parentResponse()->where("response_type", "response_condition")->get();
            if (!$responseCondition->isEmpty()) {
                foreach ($responseCondition as $responseC) {
                    $responseC->response()->delete();
                    if (!in_array($responseC->id, $notToDelete)) {
                        WatsonManager::deleteDialog($responseC->id);
                        $responseC->delete();
                    }
                }
            }
            if(isset($params["store_website_id"])){
                $chatbotDialog->response()->where('store_website_id', $params["store_website_id"] )->delete();
            }
            else {
                $chatbotDialog->response()->delete();
            }
            // delete old values and send new again end
        }

        if(isset($params["store_website_id"])){
            $chatbotDialog->store_website_id  = $params['store_website_id'];
        }

        $chatbotDialog->metadata        = '';
        $chatbotDialog->response_type   = "standard";
        $chatbotDialog->name            = $params["name"];
        $chatbotDialog->title           = $params["title"];
        $chatbotDialog->match_condition = $matchCondition;
        if($parentId > 0) {
            $chatbotDialog->parent_id = $parentId;
        }
        $chatbotDialog->save();

        if (!empty($multipleResponse) && is_array($multipleResponse) && $responseType == "response_condition") {

            $chatbotDialog->metadata = '{"_customization": {"mcr": true}}';
            $chatbotDialog->save();

            foreach ($multipleResponse as $k => $mResponse) {
                $chatbotDialogE = ChatbotDialog::where("id", $k)->first();
                if (!$chatbotDialogE) {
                    $chatbotDialogE       = new ChatbotDialog;
                    $chatbotDialogE->name = "response_" . time()."_".rand();
                }
                $condition = $mResponse["condition"];

                if (!empty($mResponse["condition"]) && !empty($mResponse["condition_value"])) {
                    switch ($mResponse["condition_sign"]) {
                        case ':':
                            $condition .= ":(". $mResponse["condition_value"] .")";
                            break;
                        case '!=':
                            $condition .= '!="'.$mResponse["condition_value"].'"';
                            break;
                        case '>':
                            $condition .= ">". $mResponse["condition_value"];
                            break;
                        case '<':
                            $condition .= "<". $mResponse["condition_value"];
                            break;
                    }
                }
                $chatbotDialogE->response_type   = "response_condition";
                $chatbotDialogE->title           = $params["title"];
                $chatbotDialogE->parent_id       = $chatbotDialog->id;
                $chatbotDialogE->match_condition = $condition;
                $chatbotDialogE->save();


                if(isset($params["store_website_id"])){
                     $chatbotDialogResponse = ChatbotDialogResponse::where('chatbot_dialog_id', $chatbotDialogE->id)->where('store_website_id', $params["store_website_id"])->first();
                     if(!$chatbotDialogResponse) {
                        $chatbotDialogResponse                         = new ChatbotDialogResponse;
                     }
                     $chatbotDialogResponse->response_type          = "text";
                     $chatbotDialogResponse->value                  = !empty($mResponse["value"]) ? $mResponse["value"] : "";
                     $chatbotDialogResponse->chatbot_dialog_id      = $chatbotDialogE->id;
                     $chatbotDialogResponse->message_to_human_agent = 1;
                     $chatbotDialogResponse->condition_sign = $mResponse["condition_sign"];
                     $chatbotDialogResponse->store_website_id = $params["store_website_id"];
                     $chatbotDialogResponse->save();
                }
                else {
                    $wotson_account_ids = WatsonAccount::all();
                    foreach($wotson_account_ids as $acc){
                        $chatbotDialogResponse = ChatbotDialogResponse::where('chatbot_dialog_id', $chatbotDialogE->id)->where('store_website_id', $acc->store_website_id)->first();
                        if(!$chatbotDialogResponse) {
                            $chatbotDialogResponse                         = new ChatbotDialogResponse;
                        }
                        
                        $chatbotDialogResponse->response_type          = "text";
                        $chatbotDialogResponse->value                  = !empty($mResponse["value"]) ? $mResponse["value"] : "";
                        $chatbotDialogResponse->chatbot_dialog_id      = $chatbotDialogE->id;
                        $chatbotDialogResponse->message_to_human_agent = 1;
                        $chatbotDialogResponse->condition_sign = $mResponse["condition_sign"];
                        $chatbotDialogResponse->store_website_id = $acc->store_website_id;
                        $chatbotDialogResponse->save();
                    }   
                }
            }
        } else {
            if(isset($params["store_website_id"])) {
                $chatbotDialogResponse = ChatbotDialogResponse::where('chatbot_dialog_id', $chatbotDialog->id)->where('store_website_id', $params["store_website_id"])->first();
                        if(!$chatbotDialogResponse) {
                            $chatbotDialogResponse                         = new ChatbotDialogResponse;
                        }

                $response  = reset($multipleResponse);
                $chatbotDialogResponse->response_type          = "text";
                $chatbotDialogResponse->value = isset($response["value"]) ? $response["value"] : "";
                $chatbotDialogResponse->chatbot_dialog_id      = $chatbotDialog->id;
                $chatbotDialogResponse->message_to_human_agent = 1;
                $chatbotDialogResponse->store_website_id = $params["store_website_id"];
                $chatbotDialogResponse->save();
            }
            else {
                $wotson_account_ids = WatsonAccount::all();
                foreach($wotson_account_ids as $acc){
                    $chatbotDialogResponse = ChatbotDialogResponse::where('chatbot_dialog_id', $chatbotDialog->id)->where('store_website_id', $acc->store_website_id)->first();
                        if(!$chatbotDialogResponse) {
                            $chatbotDialogResponse                         = new ChatbotDialogResponse;
                        }
                    $response                                      = reset($multipleResponse);
                    $chatbotDialogResponse->response_type          = "text";
                    $chatbotDialogResponse->value                  = isset($response["value"]) ? $response["value"] : "";
                    $chatbotDialogResponse->chatbot_dialog_id      = $chatbotDialog->id;
                    $chatbotDialogResponse->message_to_human_agent = 1;
                    $chatbotDialogResponse->store_website_id = $acc->store_website_id;
                    $chatbotDialogResponse->save();
                }
            }
        }

        if(!empty($previousSibling)) {
            // find the previous sibling and updatewith new
            $current = ChatbotDialog::where("previous_sibling",$previousSibling)->first();
            if($current) {
                $current->previous_sibling = $chatbotDialog->id;
                $current->save();
                if(isset($params["store_website_id"])) {
                    $error = WatsonManager::newPushDialogSingle($current->id,$params["store_website_id"]);
                }
                else {
                    $error = WatsonManager::newPushDialog($current->id);
                }

            }
            $chatbotDialog->previous_sibling = $previousSibling;
            $chatbotDialog->save();
            if(isset($params["store_website_id"])) {
                $response = WatsonManager::newPushDialogSingle($chatbotDialog->id, $params["store_website_id"]);
            }
            else {
                $response = WatsonManager::newPushDialog($chatbotDialog->id);
            }
            
            if(isset($response["code"]) && $response["code"] != 200) {
                return response()->json(["code" => 500, "error" => $response["error"]]);
            }

        }else {
            if(isset($params["store_website_id"])) {
                $response = WatsonManager::newPushDialogSingle($chatbotDialog->id, $params["store_website_id"]);
            }
            else {
                $response = WatsonManager::newPushDialog($chatbotDialog->id);
            }

            if(isset($response["code"]) && $response["code"] != 200) {
                return response()->json(["code" => 500, "error" => $response["error"]]);
            }
        }

        return response()->json(["code" => 200, "redirect" => route("chatbot.dialog.list")]);

    }

    public function restDetails(Request $request, $id)
    {
        $details                        = [];
        $dialog                         = ChatbotDialog::find($id);
        $store_website                         = StoreWebsite::all();
        $question = ChatbotQuestion::where('keyword_or_question','intent')->select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
        $keywords = ChatbotQuestion::where('keyword_or_question','entity')->select(\DB::raw("concat('@','',value) as value"))->get()->pluck("value", "value")->toArray();
        $details["allSuggestedOptions"] = $keywords + $question;

        if (!empty($store_website)) {

            foreach($store_website as $site){

                $details["sites"][] = [
                    "id" => $site->id,
                    "name" => $site->title
                ];
            }

        }
        if (!empty($dialog)) {

            $details["dialog"][] = [
                "id" => $dialog->id,
                "name" => $dialog->name
            ];

            $details["id"]                 = $dialog->id;
            $details["parent_id"]          = $dialog->parent_id;
            $details["name"]               = $dialog->name;
            $details["title"]              = $dialog->title;
            $details["dialog_type"]        = $dialog->dialog_type;
            $details["store_website_id"]        = '';
            $details["response_condition"] = !empty($dialog->metadata) ? true : false;

            $matchCondition = explode(" ", $dialog->match_condition);

            $details["first_condition"] = isset($matchCondition[0]) ? $matchCondition[0] : null;
            if (count($matchCondition) > 1) {
                unset($matchCondition[0]);
                $extraConditions = [];
                $i               = 0;
                foreach ($matchCondition as $key => $condition) {
                    if (isset($extraConditions[$i]) && count($extraConditions[$i]) == 2) {
                        $i++;
                    }
                    $extraConditions[$i][] = $condition;
                }
            }
            $details["extra_condition"] = !empty($extraConditions) ? $extraConditions : [];

            // now need to get data of response
            $assistantReport = [];
            if (!empty($dialog->metadata)) {
                $parentResponse = $dialog->parentResponse;
                if (!$parentResponse->isEmpty()) {
                    foreach ($parentResponse as $pResponse) {
                        $findMatch = false;
                        $explodeMatchCnd = [];
                        if(strpos($pResponse->match_condition,":") !== false) {
                           $findMatch = ":"; 
                        }elseif(strpos($pResponse->match_condition,"!=") !== false) {
                           $findMatch = "!="; 
                        }elseif(strpos($pResponse->match_condition,"<") !== false) {
                           $findMatch = "<"; 
                        }elseif(strpos($pResponse->match_condition,">") !== false) {
                           $findMatch = ">"; 
                        }
                        if($findMatch) {
                            // $hasString   = explode($findMatch, str_replace(['"',"(",")"], '', $pResponse->match_condition));
                            $hasString   = explode($findMatch, str_replace(['"',"(",")"], '', $pResponse->match_condition));
                            $explodeMatchCnd = [
                               !empty($hasString[0]) ? $hasString[0] : "",
                               $findMatch,
                               !empty($hasString[1]) ? $hasString[1] : "", 
                            ];
                        }
                        //$explodeMatchCnd   = explode(" ", str_replace('"', '', $pResponse->match_condition));
                        $assistantReport[] = [
                            "id"              => $pResponse->id,
                            "condition"       => isset($explodeMatchCnd[0]) ? $explodeMatchCnd[0] : "any",
                            "condition_sign"  => isset($explodeMatchCnd[1]) ? $explodeMatchCnd[1] : "",
                            "condition_value" => isset($explodeMatchCnd[2]) ? $explodeMatchCnd[2] : $pResponse->singleResponse->value,
                            "response"        => ($pResponse->singleResponse) ? $pResponse->singleResponse->value : "",
                        ];
                    }
                }

            } else {
                $assistantReport[] = [
                    "id"              => $dialog->id,
                    "condition"       => "",
                    "condition_sign"  => "",
                    "condition_value" => "",
                    "response"        => ($dialog->singleResponse) ? $dialog->singleResponse->value : "",
                ];
            }

            $details["assistant_report"] = $assistantReport;

        }
        return response()->json(["code" => 200, "data" => $details]);

    }

    public function restCreate(Request $request)
    {
        $params = [
            "name"      => $request->get("dialog_type", 'node') == "node" ? "solo_" . time() : "solo_project_" . time(),
            "parent_id" => $request->get("parent_id", 0),
            "dialog_type" => $request->get("dialog_type", 'node')
        ];
        $previousNode = $request->get("previous_node", 0);
        if ($previousNode > 0) {
            $params["previous_sibling"] = $previousNode;
        }else{
        	$params["previous_sibling"] = 0;
        }
        $params["response_type"] = "standard";

        //$siblingNode = ChatbotDialog::where("previous_sibling", 0)->first();
        $dialog = ChatbotDialog::create($params);

        $currentNode = $request->get("current_node", 0);
        if ($currentNode > 0) {
            $current = ChatbotDialog::where("id", $currentNode)->first();
            if ($current) {
                $current->previous_sibling = $dialog->id;
                $current->save();
            }
        }

        /*if($dialog->dialog_type == 'folder' && $siblingNode) {
            $siblingNode->previous_sibling = $dialog->id;
            $siblingNode->save();
        }*/

        // update sort order with previous sibling
        //$this->updateSortOrder();

        return response()->json(["code" => 200, "data" => []]);

    }

    public function restStatus(Request $request)
    {
//        dd('jhkj');
        $parentId = $request->get("parent_id", 0);
        $keyword = $request->get("search", NULL);

        $chatDialog = ChatbotDialog::leftJoin("chatbot_dialog_responses as cdr", "cdr.chatbot_dialog_id", "chatbot_dialogs.id")
            ->select("chatbot_dialogs.*", \DB::raw("count(cdr.chatbot_dialog_id) as `total_response`"), "cdr.value as dialog_response")
            // ->where("chatbot_dialogs.response_type", "standard")
            ->groupBy("chatbot_dialogs.id")
            ->orderBy("chatbot_dialogs.dialog_type", "folder")
            ->orderBy("chatbot_dialogs.previous_sibling", "asc");

        $chatDialog = $chatDialog->where("parent_id", $parentId);

        if(!empty($keyword)) {
            $chatDialog = $chatDialog->where(function($q) use($keyword) {
                $q->orWhere("cdr.value","like","%".$keyword."%")->orWhere("chatbot_dialogs.name","like","%".$keyword."%");
            });
        }

        $chatDialog      = $chatDialog->get();
        // $chatDialogArray = array_column($chatDialog->toArray(), null, 'previous_sibling');
        $chatDialogArray = $chatDialog->toArray();
        $chatDialog = [];
        if (!empty($chatDialogArray)) {
            foreach ($chatDialogArray as $k => $chatDlg) {
                // if ($k == 0) {
                    $chatDialog[] = $chatDlg;
                    // $branch = [];
                    // $more = previous_sibling($chatDialogArray,$chatDlg["id"],$branch);
                    // $chatDialog = array_merge($chatDialog,$more);
                    // break;
                if($chatDlg['parent_id'] == 0 && $chatDlg['dialog_response'] == null){
//                    $response = ChatbotDialogResponse::whereHas('dialog', function ($q) use ($chatDlg){
//                        $q->where('parent_id', $chatDlg['id']);
//                    })->pluck('value')->toArray();

                    $response = ChatbotDialog::with('singleResponse')->where('parent_id', $chatDlg['id'])->pluck('match_condition')->toArray();
                    $str = '';
                    foreach ($response as $r){
                        $str .=  '<hr>' . $r ;
                    }

//dd($response, $str);
                    if(!empty($response)){
                        $chatDialog[$k]['dialog_response'] = $str;
                    }
                }

                // }
            }
        }

        // $question = ChatbotQuestion::select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
        // $keywords = ChatbotKeyword::select(\DB::raw("concat('@','',keyword) as keyword"))->get()->pluck("keyword", "keyword")->toArray();

        $question = ChatbotQuestion::where('keyword_or_question','intent')->select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
        $keywords = ChatbotQuestion::where('keyword_or_question','entity')->select(\DB::raw("concat('@','',value) as value"))->get()->pluck("value", "value")->toArray();



        $allSuggestedOptions = $keywords + $question;
//dd($chatDialog);
        foreach ($chatDialog as $k => $dialogNode) {         
            $childNodeCount = ChatbotDialog::where("parent_id", $dialogNode["id"])->get(); 
            $chatDialog[$k]["childCount"] =  count($childNodeCount );
        }
        $data = [
            "chatDialog"          => $chatDialog,
            "allSuggestedOptions" => $allSuggestedOptions,
        ];

        return response()->json(["code" => 200, "data" => $data]);
    }

    public function restDelete(Request $request, $id)
    {
        $chatbotDialog = ChatbotDialog::find($id);
        if (!empty($chatbotDialog)) {

            // check if it has any parent 
            $hasChild = ChatbotDialog::where("parent_id",$id)->first();
            if($hasChild) {
                return response()->json(["code" => 500 , "error" => "Parent node can not delete before child : {$hasChild->name}"]);
            }

            // delete old values and send new again start
            $responseCondition = $chatbotDialog->parentResponse()->where("response_type", "response_condition")->get();
            if (!$responseCondition->isEmpty()) {
                foreach ($responseCondition as $res) {
                    WatsonManager::deleteDialog($res->id);
                    $res->response()->delete();
                }
                $chatbotDialog->parentResponse()->where("response_type", "response_condition")->delete();
            }

            // update previous_sibling
            $findPrevious = ChatbotDialog::where("previous_sibling",$chatbotDialog->id)->first();
            if($findPrevious) {
                $findPrevious->previous_sibling = $chatbotDialog->previous_sibling;
                $findPrevious->save();
                WatsonManager::newPushDialog($findPrevious->id);
            }

            WatsonManager::deleteDialog($chatbotDialog->id);
            $chatbotDialog->response()->delete();
            $chatbotDialog->delete();
            // delete old values and send new again end
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500]);
    }

    public function search(Request $request)
    {
        $keyword   = request("term","");
        $parentId  = request("parent_id", 0);

        $allDialog = ChatbotDialog::where("name","like","%".$keyword."%");
        if($parentId > 0) {
            $allDialog->where("parent_id",$parentId);
        }
        $allDialog = $allDialog->limit(10)->get();

        $allDialogList = [];
        if(!$allDialog->isEmpty()) {
            foreach($allDialog as $all) {
                $allDialogList[] = ["id" => $all->id , "text" => $all->name]; 
            }
        }

        return response()->json(["incomplete_results" => false, "items"=> $allDialogList, "total_count" => count($allDialogList)]);

    }

    public function log(Request $request)
    {
        $log = WatsonManager::getLog();
        return view('chatbot::dialog.log', compact('log'));
    }

    public function localErrorLog(Request $request)
    {
        // dd($request->all());
        $watson_accounts = WatsonAccount::all();
        $logs = ChatbotDialogErrorLog::query();
        if($request->store_website_id) {
            $logs = $logs->where('store_website_id', $request->store_website_id);
        }
        $logs = $logs->paginate(20);
        
        return view('chatbot::dialog-grid.local-logs', compact('logs','watson_accounts'));
    }

    

    public function getWebsiteResponse(Request $request) {



        $details                        = [];
        $dialog                         = ChatbotDialog::find($request->dialog_id);
        $store_website                         = StoreWebsite::all();
        $question = ChatbotQuestion::where('keyword_or_question','intent')->select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
        $keywords = ChatbotQuestion::where('keyword_or_question','entity')->select(\DB::raw("concat('@','',value) as value"))->get()->pluck("value", "value")->toArray();
        $details["allSuggestedOptions"] = $keywords + $question;

        if (!empty($store_website)) {

            foreach($store_website as $site){

                $details["sites"][] = [
                    "id" => $site->id,
                    "name" => $site->title
                ];
            }

        }
        if (!empty($dialog)) {

            $details["dialog"][] = [
                "id" => $dialog->id,
                "name" => $dialog->name
            ];

            $details["id"]                 = $dialog->id;
            $details["parent_id"]          = $dialog->parent_id;
            $details["name"]               = $dialog->name;
            $details["title"]              = $dialog->title;
            $details["dialog_type"]        = $dialog->dialog_type;
            $details["store_website_id"]        = $request->store_website_id;
            $details["response_condition"] = !empty($dialog->metadata) ? true : false;

            $matchCondition = explode(" ", $dialog->match_condition);

            $details["first_condition"] = isset($matchCondition[0]) ? $matchCondition[0] : null;
            if (count($matchCondition) > 1) {
                unset($matchCondition[0]);
                $extraConditions = [];
                $i               = 0;
                foreach ($matchCondition as $key => $condition) {
                    if (isset($extraConditions[$i]) && count($extraConditions[$i]) == 2) {
                        $i++;
                    }
                    $extraConditions[$i][] = $condition;
                }
            }
            $details["extra_condition"] = !empty($extraConditions) ? $extraConditions : [];

            // now need to get data of response
            $assistantReport = [];
            if (!empty($dialog->metadata)) {
                $parentResponse = $dialog->parentResponse;
                if (!$parentResponse->isEmpty()) {
                    foreach ($parentResponse as $pResponse) {
                        $findMatch = false;
                        $explodeMatchCnd = [];
                        if(strpos($pResponse->match_condition,":") !== false) {
                           $findMatch = ":"; 
                        }elseif(strpos($pResponse->match_condition,"!=") !== false) {
                           $findMatch = "!="; 
                        }elseif(strpos($pResponse->match_condition,"<") !== false) {
                           $findMatch = "<"; 
                        }elseif(strpos($pResponse->match_condition,">") !== false) {
                           $findMatch = ">"; 
                        }
                        if($findMatch) {
                            $hasString   = explode($findMatch, str_replace(['"',"(",")"], '', $pResponse->match_condition));
                            $explodeMatchCnd = [
                               !empty($hasString[0]) ? $hasString[0] : "",
                               $findMatch,
                               !empty($hasString[1]) ? $hasString[1] : "", 
                            ];
                        }
                        $websiteResponse =  ChatbotDialogResponse::where('store_website_id',$request->store_website_id)->where('chatbot_dialog_id', $pResponse->id)
                        ->first();
                        $webResponse = $websiteResponse ? $websiteResponse->value : '';

                        $assistantReport[] = [
                            "id"              => $pResponse->id,
                            "condition"       => isset($explodeMatchCnd[0]) ? $explodeMatchCnd[0] : "any",
                            "condition_sign"  => isset($explodeMatchCnd[1]) ? $explodeMatchCnd[1] : "",
                            "condition_value" => isset($explodeMatchCnd[2]) ? $explodeMatchCnd[2] : $webResponse,
                            "response"        => $webResponse ? $webResponse : "",
                        ];
                    }
                }

            } else {
                $websiteResponse =  ChatbotDialogResponse::where('store_website_id',$request->store_website_id)->where('chatbot_dialog_id', $request->dialog_id)
                ->first();
                $webResponse = $websiteResponse ? $websiteResponse->value : '';
                $assistantReport[] = [
                    "id"              => $dialog->id,
                    "condition"       => "",
                    "condition_sign"  => "",
                    "condition_value" => "",
                    "response"        => $webResponse ? $webResponse : "",
                ];
            }
            $details["assistant_report"] = $assistantReport;

        }
        return response()->json(["code" => 200, "data" => $details]);
    }

    public function getAllResponse($id) {
        $dialogResponses = ChatbotDialogResponse::where("chatbot_dialog_id", $id)->get();
        foreach($dialogResponses as $response) {
            $response->storeWebsite;
        }
        return view('chatbot::dialog.includes.all-response', compact('dialogResponses'));
    }

    public function submitResponse($id, Request $request) {


        $dialogResponse = ChatbotDialogResponse::find($id);
        if($dialogResponse) {
            $current = ChatbotDialog::where("id",$dialogResponse->chatbot_dialog_id)->first();
            if($current && $dialogResponse->store_website_id) {
                $dialogResponse->value = $request->responseData;
                $dialogResponse->save();
                $error = WatsonManager::newPushDialogSingle($current->id,$dialogResponse->store_website_id);
                if($error['code'] != 200) {
                    return response()->json(['message' => 'Not updated in live watson','code' => 500]);
                }
            }
            else {
                return response()->json(['message' => 'Store not found','code' => 500]);
            }
        } 
        return response()->json(['message' => 'Success','code' => 200]);
    }
}
