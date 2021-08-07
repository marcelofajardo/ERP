<?php

    namespace Modules\ChatBot\Http\Controllers;
    
    use App\Library\Watson\Model as WatsonManager;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Facades\Validator;
    use \App\ChatbotDialog;
    use \App\ChatbotDialogResponse;
    use \App\ChatbotKeyword;
    use \App\ChatbotQuestion;
    
    class DialogGridController extends Controller
    {
        /**
         * Display a listing of the resource.
         * @return Response
         */
        public function index()
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
            $question            = ChatbotQuestion::select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
            $keywords            = ChatbotKeyword::select(\DB::raw("concat('@','',keyword) as keyword"))->get()->pluck("keyword", "keyword")->toArray();
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
                $chatbotDialog->response()->delete();
                // delete old values and send new again end
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
    
                    $chatbotDialogResponse                         = new ChatbotDialogResponse;
                    $chatbotDialogResponse->response_type          = "text";
                    $chatbotDialogResponse->value                  = !empty($mResponse["value"]) ? $mResponse["value"] : "";
                    $chatbotDialogResponse->chatbot_dialog_id      = $chatbotDialogE->id;
                    $chatbotDialogResponse->message_to_human_agent = 1;
                    $chatbotDialogResponse->save();
                }
            } else {
                $response                                      = reset($multipleResponse);
                $chatbotDialogResponse                         = new ChatbotDialogResponse;
                $chatbotDialogResponse->response_type          = "text";
                $chatbotDialogResponse->value                  = isset($response["value"]) ? $response["value"] : "";
                $chatbotDialogResponse->chatbot_dialog_id      = $chatbotDialog->id;
                $chatbotDialogResponse->message_to_human_agent = 1;
                $chatbotDialogResponse->save();
    
            }
    
            if(!empty($previousSibling)) {
                // find the previous sibling and updatewith new
                $current = ChatbotDialog::where("previous_sibling",$previousSibling)->first();
                if($current) {
                    $current->previous_sibling = $chatbotDialog->id;
                    $current->save();
                    $error = WatsonManager::newPushDialog($current->id);
    
                }
                $chatbotDialog->previous_sibling = $previousSibling;
                $chatbotDialog->save();
                $response = WatsonManager::newPushDialog($chatbotDialog->id);
                if(isset($response["code"]) && $response["code"] != 200) {
                    return response()->json(["code" => 500, "error" => $response["error"]]);
                }
    
            }else {
                $response = WatsonManager::newPushDialog($chatbotDialog->id);
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
            $question                       = ChatbotQuestion::select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
            $keywords                       = ChatbotKeyword::select(\DB::raw("concat('@','',keyword) as keyword"))->get()->pluck("keyword", "keyword")->toArray();
            $details["allSuggestedOptions"] = $keywords + $question;
    
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
                                $hasString   = explode(":", str_replace(['"',"(",")"], '', $pResponse->match_condition));
                                $explodeMatchCnd = [
                                   !empty($hasString[0]) ? $hasString[0] : "",
                                   ":",
                                   !empty($hasString[1]) ? $hasString[1] : "", 
                                ];
                            }
                            //$explodeMatchCnd   = explode(" ", str_replace('"', '', $pResponse->match_condition));
                            $assistantReport[] = [
                                "id"              => $pResponse->id,
                                "condition"       => isset($explodeMatchCnd[0]) ? $explodeMatchCnd[0] : "",
                                "condition_sign"  => isset($explodeMatchCnd[1]) ? $explodeMatchCnd[1] : "",
                                "condition_value" => isset($explodeMatchCnd[2]) ? $explodeMatchCnd[2] : "",
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
            $parentId = $request->get("parent_id", 0);

            $chatDialog = ChatbotDialog::leftJoin("chatbot_dialog_responses as cdr", "cdr.chatbot_dialog_id", "chatbot_dialogs.id")
                ->select("chatbot_dialogs.*", \DB::raw("count(cdr.chatbot_dialog_id) as `total_response`"))
                ->where("chatbot_dialogs.response_type", "standard")
                ->groupBy("chatbot_dialogs.id")
                ->orderBy("chatbot_dialogs.previous_sibling", "asc");
    
            $chatDialog = $chatDialog->where("parent_id", $parentId);
    
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
                    // }
                }
            }
    
            // $question = ChatbotQuestion::select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
            // $keywords = ChatbotKeyword::select(\DB::raw("concat('@','',keyword) as keyword"))->get()->pluck("keyword", "keyword")->toArray();
    
            $question = ChatbotQuestion::where('keyword_or_question','intent')->select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
            $keywords = ChatbotQuestion::where('keyword_or_question','entity')->select(\DB::raw("concat('@','',value) as value"))->get()->pluck("value", "value")->toArray();
    
    
    
            $allSuggestedOptions = $keywords + $question;
    
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
    }
    
