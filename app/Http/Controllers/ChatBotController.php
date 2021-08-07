<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Library\Watson\Language\Assistants\V2\AssistantsService;
use App\Library\Watson\Language\Workspaces\V1\EntitiesService;
use App\Library\Watson\Language\Workspaces\V1\IntentService;
use App\Library\Watson\Model as WatsonManager;



class ChatBotController extends Controller
{

    public function connection(Request $request) 
    {
        $customer       = \App\Customer::find(2001);
        $watsonManager  = WatsonManager::sendMessage($customer,"weather");

        echo '<pre>'; print_r($watsonManager); echo '</pre>';exit;

        die;
    	   

        $chatSesssion = "60421530-8204-4dad-a342-662b95ab74e7";//$request->session()->get("chat_session");
        

        $watson = new IntentService(
        	"apiKey", 
        	"9is8bMkHLESrkNJvcMNNeabUeXRGIK8Hxhww373MavdC"
        );
        $result = $watson->create("19cf3225-f007-4332-8013-74443d36a3f7",[
        	"intent" => "hello",
        	"examples" => [
        		["text" => "Good morning"],
        		["text" => "hi there"],
        		["text" => "howdy ?"]
        	]
        ]);

        $result = json_decode($result->getContent());

        echo '<pre>'; print_r($result); echo '</pre>';exit;

        // create entities
        /*$watson = new EntitiesService(
        	"apiKey", 
        	"9is8bMkHLESrkNJvcMNNeabUeXRGIK8Hxhww373MavdC"
        );
        $result = $watson->create("19cf3225-f007-4332-8013-74443d36a3f7",[
        	"entity" => "beverage",
        	"values" => [
        		["value" => "Water"],
        		["value" => "orange juice"],
        		["value" => "soda"]
        	]
        ]);

        $result = json_decode($result->getContent());

        echo '<pre>'; print_r($result); echo '</pre>';exit;*/

    	/** create chat and session service
    	if(empty($chatSesssion)) {

	        $session = $watson->createSession("28754e1c-6281-42e6-82af-eec6e87618a6");
	        $result = json_decode($session->getContent());
	        if(isset($result->session_id)) {
	        	session()->put('chat_session', $result->session_id);
	        	$chatSesssion = $result->session_id;
	        }
    	}

    	$chatMessage = $watson->sendMessage("28754e1c-6281-42e6-82af-eec6e87618a6",$chatSesssion, [
    		"input" => [
    			"text" => "gucci"
    		]
    	]);
    	$result = json_decode($chatMessage->getContent());

    	echo '<pre>'; print_r([$chatSesssion,$result]); echo '</pre>';exit;
		**/

		// start for create entities



    }
}