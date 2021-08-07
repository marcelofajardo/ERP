<?php

namespace Modules\ChatBot\Http\Controllers;

use App\Library\Watson\Model as WatsonManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $params               = []; //$request->all();
        $params['cursor']     = $request->get("cursor", "");
        $params['sort']       = $request->get("sort", "-request_timestamp");
        $params['page_limit'] = $request->get("page_limit", 10);
        $search = $request->get("search");
        if(!empty($search)) {
            $params['filter']    = "request.input.text:".$search;
        }

        $responseLog = WatsonManager::getLog($params);

        $eLog = [];
        
        if (isset($responseLog->logs)) {
            
            foreach ($responseLog->logs as $key => $log) {

                $eLog[$key]["user_input"] = $log->request->input->text;
                $eLog[$key]["bot_response"] = !empty($log->response->output->text) ? implode(",", $log->response->output->text) : "";

                $reIntents  = [];
                $reEntities = [];

                if (!empty($log->response->intents)) {
                    foreach ($log->response->intents as $intents) {
                        $reIntents[] = $intents->intent;
                    }
                }

                $eLog[$key]["intents"] = $reIntents;

                if (!empty($log->response->entities)) {
                    foreach ($log->response->entities as $entities) {
                        $reEntities[] = $entities->entity . ":" . $entities->value;
                    }
                }

                $eLog[$key]["entities"] = $reEntities;

                $eLog[$key]["warning"]      = !empty($log->response->output->warning) ? $log->response->output->warning : "";
                $eLog[$key]["requested_at"] = date("Y-m-d H:i:s", strtotime($log->request_timestamp));
                $eLog[$key]["responded_at"] = date("Y-m-d H:i:s", strtotime($log->response_timestamp));
            }
        }

        $nextUrl     = !empty($responseLog->pagination->next_cursor) ? $responseLog->pagination->next_cursor : "";
        $previousUrl = !empty($responseLog->pagination->previous_url) ? $responseLog->pagination->previous_url : "";

        return view('chatbot::analytics.log', compact('eLog', 'nextUrl', 'previousUrl'));
    }
}
