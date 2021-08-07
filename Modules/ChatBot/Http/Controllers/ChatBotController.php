<?php

namespace Modules\ChatBot\Http\Controllers;

use App\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \App\ChatbotDialog;
use \App\ChatbotDialogResponse;
use App\Library\Watson\Model as WatsonManager;

class ChatBotController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('chatbot::index');
    }

    public function editMessage(Request $request)
    {
        $messageId = $request->get("id");
        $message   = $request->get("message");

        $chatMessage = ChatMessage::where("id", $messageId)->first();

        if ($chatMessage) {
            $oldMessage           = $chatMessage->message;
            $chatMessage->message = $message;
            $chatMessage->save();

            // find the old message into dilog and update the new one
            $dialogResponse = ChatbotDialogResponse::where("value", $oldMessage)->get();
            if(!$dialogResponse->isEmpty()) {
            	foreach($dialogResponse as $response) {
            		$response->value = $message;
            		$response->save();
            		WatsonManager::pushDialog($response->chatbot_dialog_id);
            	}
            }

        }

        return response()->json(["code" => 200]);

    }
}
