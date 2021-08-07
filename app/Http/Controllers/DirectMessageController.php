<?php

namespace App\Http\Controllers;

use InstagramAPI\Instagram;
use Illuminate\Http\Request;
use \App\Account;
use \App\Customer;
use \App\InstagramDirectMessages;
use \App\InstagramUsersList;
use \App\InstagramThread;
use Plank\Mediable\Media;
use App\ChatMessage;
use App\Brand;
use DB;
use App\ReadOnly\SoloNumbers;
use InstagramAPI\Media\Photo\InstagramPhoto;
use App\ScrapInfluencer;
use App\InstagramDirectMessagesHistory;
Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class DirectMessageController extends Controller
{
    public function index()
    {
     
    	$threads = InstagramThread::select('instagram_threads.*')->whereNotNull('instagram_threads.instagram_user_id')
                        ->whereNotNull('instagram_threads.account_id')
                        ->leftJoin('instagram_users_lists', 'instagram_threads.instagram_user_id', '=', 'instagram_users_lists.id')
                        ->leftJoin('chat_messages', 'instagram_threads.thread_id', '=', 'chat_messages.unique_id');
        if( request('form_account') ){
            $threads->where('instagram_threads.account_id', request('form_account') );
        }
        if( request('keyword') ){
            $threads->where('username','like', '%'.request('keyword').'%');
            $threads->orWhere('message','like', '%'.request('keyword').'%');
        }

        $threads = $threads->groupBy('instagram_threads.id')->get();
        $select_brands = Brand::pluck('name','id');
        $solo_numbers = (new SoloNumbers)->all();

        $accounts = Account::where('platform','instagram')->whereNotNull('proxy')->get();

    	// if ($request->ajax()) {
     //        return response()->json([
     //            'tbody' => view('instagram.direct.data', compact('threads'))->render(),
     //            'links' => (string)$documents->render()
     //        ], 200);
     //    }

    	return view('instagram.direct.index',['threads' => $threads,'select_brands' => $select_brands,'solo_numbers' => $solo_numbers, 'accounts' => $accounts ]);
    }

    /**
     * Show the history.
     *
     * @return \Illuminate\Http\Response
     */
	public function history( Request $request ){

		if( $request->id ){
			$history = InstagramDirectMessagesHistory::where('thread_id', $request->id )->orderBy("created_at","desc")->get();
			return response()->json( ["code" => 200 , "data" => $history] );
		}
	}

    public function incomingPendingRead(Request $request)
    {
    	$accounts = Account::where('platform','instagram')->whereNotNull('proxy')->get();
        $messege = [] ;
    	foreach ($accounts as $account) {
            
    		try {
                	$instagram = new Instagram();
                    //$instagram->setProxy($account->proxy);
				   $instagram->login($account->email, $account->password);
				   $this->instagram = $instagram;
                } catch (\Exception $e) {
                   \Log::error($account->last_name.' :: '.$e->getMessage());
                    array_push($messege, $account->last_name.' :: '.$e->getMessage());
                    continue;
                }
                //getting inbpx
                $inbox = $this->instagram->direct->getInbox('',20,'','')->asArray();
                //$pending = $this->instagram->direct->getPendingInbox()->asArray();
                
                
                //getting inbox
                
                if (isset($inbox['inbox']['threads'])) {
                	 $incomingThread = $inbox['inbox'];
                	if($incomingThread['unseen_count'] != 0){
	                    $threads = $inbox['inbox']['threads'];
	                    foreach ($threads as $thread) {
	                        $user = $thread['users'];

							//check instagram Users
	                        $userInstagram = InstagramUsersList::where('user_id',$user[0]['pk'])->first();
	                       
	                        if(!$userInstagram){
	                        	$info = $user[0];
	                        	$userInstagram = new InstagramUsersList();
                                $userInstagram->fullname = $info['full_name'];
	                        	$userInstagram->username = $info['username'];
		                        $userInstagram->user_id = $user[0]['pk'];
		                        $userInstagram->image_url = $info['profile_pic_url'];
		                        $userInstagram->bio = '';
		                        $userInstagram->rating = 0;
		                        $userInstagram->location_id = 0;
		                        $userInstagram->because_of = 'instagram_dm';
		                        $userInstagram->posts = 0;
		                        $userInstagram->followers = 0;
		                        $userInstagram->following = 0;
		                        $userInstagram->location = '';
		                        $userInstagram->save(); 
			                    
	                        }
	                        
	                        $threadId = self::createThread($userInstagram , $thread , $account->id);

	                        $currentUser = $this->instagram->account_id;
	                        self::createDirectMessage($thread,$threadId,$currentUser);

	                        $account->new_message = 1;
	                		$account->save();
	                        
	                	}
                    }
                }

                //getting pending inbox message
                // $inbox = $this->instagram->direct->getPendingInbox()->asArray();
                // $incomingThread = $inbox['inbox'];
                // if($incomingThread['unseen_count'] != 0){
                // 	$account->new_message = 1;
                // 	$account->save();
                		
                // }
                
    	}

    	$threads = InstagramThread::whereNotNull('instagram_user_id')->whereNotNull('account_id')->paginate(25);

    	if ($request->ajax()) {
            return response()->json([
                'tbody' => view('instagram.direct.data', compact('threads','accounts'))->render(),
                'links' => (string)$threads->render(),
                'message' => (string)implode("\n", $messege),
            ], 200);
        }

    	return response()->json([
            	'status' => 'success',
                'message' => (string)implode("\n", $messege),
        ]);	

    }
    public function getDirectMessagesFromAccounts()
    {
    	$accounts = Account::where('platform','instagram')->whereNotNull('proxy')->where('new_message',1)->get();

    	foreach ($accounts as $account) {

                try {
                	$instagram = new Instagram();
				    $instagram->login($account->last_name, $account->password);
				    $this->instagram = $instagram;
                } catch (\Exception $e) {
                    \Log::error($account->last_name.'::'.$e->getMessage());
                    echo "ERROR $account->last_name \n";
                    continue;
                }

                $inbox = $this->instagram->direct->getInbox()->asArray();
                
                if (isset($inbox['inbox']['threads'])) {
                    $threads = $inbox['inbox']['threads'];
                    foreach ($threads as $thread) {
                        $user = $thread['users'];

						//check instagram Users
                        $userInstagram = InstagramUsersList::where('user_id',$user[0]['pk'])->first();
                        
                        if(!$userInstagram){
                        	$info = $user[0];
                        	$userInstagram = new InstagramUsersList();
                        	$userInstagram->username = $info['username'];
	                        $userInstagram->user_id = $user[0]['pk'];
	                        $userInstagram->image_url = $info['profile_pic_url'];
	                        $userInstagram->bio = '';
	                        $userInstagram->rating = 0;
	                        $userInstagram->location_id = 0;
	                        $userInstagram->because_of = 'instagram_dm';
	                        $userInstagram->posts = 0;
	                        $userInstagram->followers = 0;
	                        $userInstagram->following = 0;
	                        $userInstagram->location = '';
	                        $userInstagram->save(); 
		                    
                        }
                        
                        $threadId = self::createThread($userInstagram , $thread , $account->id);

                        $currentUser = $this->instagram->account_id;
                        self::createDirectMessage($thread,$threadId,$currentUser);

                       
                        

                    }
                }
            }
    }

     /**
     * @param $user
     * @return Customer|void
     */
    private function createDirectMessage($t,$id,$userId)
    {
    	$thread = $this->instagram->direct->getThread($t['thread_id'])->asArray();
    	$thread = $thread['thread'];
        $isSeen = 0;
    	foreach ($thread['items'] as $chat) {
            if($isSeen == 0){
                $this->instagram->direct->markItemSeen($t['thread_id'],$chat['item_id'])->asArray();
                $isSeen = 1;
            }
            
            $type = 0;
    		if ($chat['item_type'] == 'text') {
    			$type = 1;
                $text = $chat['text'];
            } else if ($chat['item_type'] == 'like') {
                continue;
                $text = $chat['like'];
                $type = 2;
            } else if ($chat['item_type'] == 'media') {
            	$type = 3;
                $text = $chat['media']['image_versions2']['candidates'][0]['url'];
            }
            if($chat['user_id'] == $userId){
                $isSent = 1;
            }else{
                $isSent = 0;
            }

            $thread = InstagramThread::where('thread_id',$t['thread_id'])->first();

            $instagramUser = InstagramUsersList::where('user_id',$thread->instagramUser->user_id)->first();

            if($instagramUser){

                if($type == 1){
                    $chatMessageCheck = ChatMessage::where('account_id',$thread->account->id)->where('unique_id',$t['thread_id'])->where('instagram_user_id',$instagramUser->id)->where('message',$text)->where('sent',$isSent)->first();
                }else{
                    $chatMessageCheck = ChatMessage::where('account_id',$thread->account->id)->where('unique_id',$t['thread_id'])->where('instagram_user_id',$instagramUser->id)->where('media',$text)->where('sent',$isSent)->first();
                }
                


                if(!$chatMessageCheck){

                    $message = new ChatMessage();
                    $message->account_id = $thread->account->id;
                    $message->unique_id = $t['thread_id'];
                    $message->instagram_user_id = $instagramUser->id;
                    $message->sent = $isSent;
                    $message->user_id = \Auth::id();
                    if($type == 1){
                        $message->message = $text;
                    }else{
                        $message->media = $text;
                    }
                    $message->save();
                }
                
            }
            
            //Marking seen chat message
            
            


       //     $directMessage = InstagramDirectMessages::where('instagram_thread_id',$id)->where('message',$text)->first();
       // if(!$directMessage){
       //     		$directMessage = new InstagramDirectMessages();
       //     		$directMessage->instagram_thread_id = $id;
       //     		$directMessage->message = $text;
       //     		$directMessage->message_type = $type;
       //     		$directMessage->sender_id = $chat['user_id'];
       //     		$directMessage->receiver_id = $userId;
       //          $directMessage->is_send = $isSent;
       //     		$directMessage->status = 1;
       //     		$directMessage->save();
       //     }
    	}
    }

    private function createThread($userInstagram, $t , $accountId)
    {
    	$thread = InstagramThread::where('thread_id',$t['thread_id'])->first();
    	if(!$thread){
    		$thread = new InstagramThread();
	        $thread->instagram_user_id  = $userInstagram->id;
	        $thread->account_id  = $accountId;
	        $thread->thread_id    = $t['thread_id'];
	        $thread->thread_v2_id = $t['thread_v2_id'];
	        $thread->save();
		}
        
        return $thread->id;

    }

    public function sendMessage(Request $request) {

        $thread = InstagramThread::find($request->thread_id);
        $agent = $thread->account;
        $messageType = 1;

        if( !empty($request->from_account_id) ){
            $thread->account = Account::where('id',$request->from_account_id)->whereNotNull('proxy')->first();
        }

        if($agent){
        	$status = $this->sendMessageToInstagramUser($thread->account->last_name, $thread->account->password, $thread->account->proxy, $thread->instagramUser->username, $request->message, $thread);
		}
		
        if ($status === false) {
            return response()->json([
                'status' => 'error', 'code' => 413
            ], 413);
        }

        $instagramUser = InstagramUsersList::where('user_id',$thread->instagramUser->user_id)->first();
        if($instagramUser){
            $message = new ChatMessage();
            $message->account_id = $thread->account->id;
            $message->unique_id = $thread->thread_id;
            $message->instagram_user_id = $instagramUser->id;
            $message->sent = 1;
            $message->user_id = \Auth::id();
            $message->message = $request->message;
            $message->save();
        }
        


        // $dm = new InstagramDirectMessages();
        // $dm->instagram_thread_id = $thread->id;
        // $dm->message_type = $messageType;
        // $dm->sender_id = $status[1];
        // $dm->message = $status[2];
        // $dm->receiver_id = $thread->instagramUser->user_id;
        // $dm->status = 1;
        // $dm->is_send = 1;
        // $dm->save();

        //updating account status

        $thread->account->new_message = 0;
        $thread->account->save();

        return response()->json([
            'status' => 'success',
            'receiver_id' => $thread->instagramUser->user_id,
            'sender_id' => $status[1],
            'message' => $status[2]
        ]);

    }


    public function sendImage(Request $request)
    {   
        try {
            if($request->nothing){
                $id = $request->nothing;
                $thread = InstagramThread::find($id);
                if( !empty($request->from_account) ){
                    $account = Account::where('id',$request->from_account)->whereNotNull('proxy')->first();
                    if( $account ){
                        $thread->account = $account;
                    }
                }
                $agent = $thread->account;
                $messageType = 1;
                if($agent){
                    $images = json_decode($request->get("images"), true);
                    if($images){
                        foreach ($images as $image) {
                            $image = Media::find($image);
                            $status = $this->sendFileToInstagramUser($thread->account->last_name, $thread->account->password, $thread->account->proxy, $thread->instagramUser->username, $image, $thread);
                        }
                    }
                    
                }
                
            }
            
            return redirect()->route('direct.index')->with('success','Images send successfully');
        } catch (\Throwable $th) {
            return redirect()->route('direct.index')->with('error',$th->getMessage());
        }

        return redirect()->route('direct.index')->with('error','Something went wrong, Please try again later');
       
    }

    private function sendFileToInstagramUser($sender, $password, $proxy , $receiver, $file, $thread) {
        $i = new Instagram();

        try {
            $i->setProxy($proxy);
            $i->login($sender, $password);
        } catch (\Exception $exception) {
            return false;
        }

        try {
            $receiver = $i->people->getUserIdForName($receiver);
        } catch (Exception $e) {
            return false;
        }


        //$fileName = Storage::disk('uploads')->putFile('', $file);

        $photo = new InstagramPhoto($file->getAbsolutePath());
       
        $imageInfo = $i->direct->sendPhoto([
            'users' => [
                $receiver
            ]
        ], $photo->getFile());

        $history = [
            'thread_id'   => $thread->thread_id,
            'title'       => 'Send image',
            'description' => 'Message send successfully',
        ];

        InstagramDirectMessagesHistory::insert( $history );
        return [true, $i->account_id, $file->filename];


    }

    private function sendMessageToInstagramUser($sender, $password, $proxy, $receiver, $message, $thread) {
        $i = new Instagram();

        try {
        	$i->setProxy($proxy);
            $i->login($sender, $password);
        } catch (\Exception $exception) {
            \Log::error( $sender.'::'.$exception->getMessage() );
            return false;
        }


        try {
        	$receiver = $i->people->getUserIdForName($receiver);
        } catch (Exception $e) {
        	return false;
        }
            
        

        try {
            $resp = $i->direct->sendText([
                'users' => [
                    $receiver
                ]
            ], $message);

            $history = [
                'thread_id'   => $thread->thread_id,
                'title'       => 'Send text message',
                'description' => 'Message send successfully',
            ];
        } catch (\Exception $exception) {
            $history = [
                'thread_id'   => $thread->thread_id,
                'title'       => 'Send text message',
                'description' => $exception->getMessage()
            ];
        }
        InstagramDirectMessagesHistory::insert( $history );
        return [true, $i->account_id, $message];

    }

    public function messages(Request $request)
    {
        $id = $request->id;
        $thread = InstagramThread::find($id);
        if($thread){
            $chats = $thread->conversation;
            $html = '<div style="overflow-x:auto;"><input type="text" id="click-to-clipboard-message" class="link" style="position: absolute; left: -5000px;"><table class="table table-bordered"><tbody><tr class="in-background"><tr>';
            foreach ($chats as $chat) {

                if(isset($chat->getRecieverUsername->username)){
                    $receiver = $chat->getRecieverUsername->username;
                }else{
                    $receiver = 'unknown';
                }

                if(isset($chat->getSenderUsername->username)){
                    $sender = $chat->getSenderUsername->username;
                }else{
                    $sender = 'unknown';
                }


                if($chat->message_type == 3){
                    $message = '<img src="'.$chat->message.'" height="200px" width="200px">';
                }else{
                    $message = $chat->message;
                }


                $html .= '<td style="width:5%"><input data-id="{{ $chat->id }}" data-message="" type="checkbox" class="click-to-clipboard"></td><td style="width:45%"><div class="speech-wrapper "><div class="bubble"><div class="txt"><p class="name"></p><p class="message" data-message="">'. $message .'</p></div></div></div></td><td style="width:30%"><a title="Remove" href="javascript:;" class="btn btn-xs btn-secondary ml-1 delete-message" data-id="505729"><i class="fa fa-trash" aria-hidden="true"></i></a><a title="Dialog" href="javascript:;" class="btn btn-xs btn-secondary ml-1 create-dialog"><i class="fa fa-plus" aria-hidden="true"></i></a></td><td style="width:20%"><span class="timestamp" style="color:black; text-transform: capitalize;font-size: 14px;">From '. $sender .' to '. $receiver  .' on '.
                    $chat->created_at.'</span></td></tr><tr class="in-background">';
            }

            $html .= '</tr></tbody></table></div>';

            return response()->json([
            'status' => 'success',
            'messages' => $html
            ]);
            
        }
    }


    public function influencerMessages(Request $request)
    {
        $id = $request->id;
        $thread = InstagramThread::find($id);
        if($thread){
            $chats = $thread->influencerConversation;
            $html = '<div style="overflow-x:auto;"><input type="text" id="click-to-clipboard-message" class="link" style="position: absolute; left: -5000px;"><table class="table table-bordered"><tbody><tr class="in-background"><tr>';
            foreach ($chats as $chat) {
                if(isset($chat->getRecieverUsername->username)){
                    $receiver = $chat->getRecieverUsername->username;
                }else{
                    $receiver = 'unknown';
                }

                if(isset($chat->getSenderUsername->last_name)){
                    $sender = $chat->getSenderUsername->last_name;
                }else{
                    $sender = 'unknown';
                }


                if($chat->message_type == 3){
                    $message = '<img src="'.$chat->message.'" height="200px" width="200px">';
                }else{
                    $message = $chat->message;
                }


                $html .= '<td style="width:5%"><input data-id="{{ $chat->id }}" data-message="" type="checkbox" class="click-to-clipboard"></td><td style="width:45%"><div class="speech-wrapper "><div class="bubble"><div class="txt"><p class="name"></p><p class="message" data-message="">'. $message .'</p></div></div></div></td><td style="width:30%"><a title="Remove" href="javascript:;" class="btn btn-xs btn-secondary ml-1 delete-message" data-id="505729"><i class="fa fa-trash" aria-hidden="true"></i></a><a title="Dialog" href="javascript:;" class="btn btn-xs btn-secondary ml-1 create-dialog"><i class="fa fa-plus" aria-hidden="true"></i></a></td><td style="width:20%"><span class="timestamp" style="color:black; text-transform: capitalize;font-size: 14px;">From '. $sender .' to '. $receiver  .' on '.
                    $chat->created_at.'</span></td></tr><tr class="in-background">';
            }

            $html .= '</tr></tbody></table></div>';

            return response()->json([
            'status' => 'success',
            'messages' => $html
            ]);
            
        }
    }

    public function sendMessageMultiple(Request $request) {
        if(!$request->message || $request->message == '') {
            return response()->json(['message' => 'Message field is required.'],500);
        }
        if(!$request->account_id || $request->account_id == '') {
            return response()->json(['message' => 'account id is required.'],500);
        }
        $ids = explode(",",$request->selectedInfluencers);
        $ig = new \InstagramAPI\Instagram();
    
        try {
            $ig->login('satyam_t', 'Schoolrocks93');
        } catch (\Exception $e) {
            $msg = 'Instagram login failed: '.$e->getMessage();
            return response()->json(['message' => $msg, 'code' => 413],413);
        }

        foreach($ids as $id) {
            $thread = InstagramThread::where('scrap_influencer_id',$id)->first();
            if($thread) {
                $thread->account_id = $request->account_id;
            }
            else {
                $thread = new InstagramThread;
                $thread->account_id = $request->account_id;
                $thread->scrap_influencer_id = $request->influencer_id;
            }
            $influencer  = ScrapInfluencer::find($id);

            $userInstagram = InstagramUsersList::where('username',$influencer->name)->first();
            if(!$userInstagram) {
                try {
                    $instaInfo = @$ig->people->getInfoByName($influencer->name);
                } catch (\Exception $e) {
                    $msg = 'Something went wrong: '.$e->getMessage();
                    $influencer->delete();
                    continue;
                    //return response()->json(['message' => $msg, 'code' => 413],413);
                }
                $instaInfo = $instaInfo->asArray();
    
                if(is_array($instaInfo) && array_key_exists("user",$instaInfo)) {
                    $info = $instaInfo['user'];
                    $userInstagram = new InstagramUsersList();
                    $userInstagram->fullname = $info['full_name'];
                    $userInstagram->username = $info['username'];
                    $userInstagram->user_id = $info['pk'];
                    $userInstagram->image_url = $info['profile_pic_url'];
                    $userInstagram->bio = $info['biography'];
                    $userInstagram->rating = 0;
                    $userInstagram->location_id = 0;
                    $userInstagram->because_of = 'instagram_dm';
                    $userInstagram->posts = 0;
                    $userInstagram->followers = 0;
                    $userInstagram->following = 0;
                    $userInstagram->location = '';
                    $userInstagram->save();
                }
                else {
                    $msg = 'Instagram user info not found for '.$influencer->name;
                    continue;
                    //return response()->json(['message' => $msg, 'code' => 413],413); 
                }
            }
            $thread->instagram_user_id = $userInstagram->id;
            $thread->save();

            $requestData = new Request();
            $requestData->setMethod('POST');
            $params['message'] = $request->message;
            $params['thread_id'] = $thread->id;
            $params['to'] = 'scrap_influencer';
            $params['receiver'] = $influencer->name;
            $requestData->request->add($params);
            $result = app('App\Http\Controllers\DirectMessageController')->sendMessage($requestData);
        }
        return response()->json(['message' => 'Successfull.'],200);
    }

    public function prepareAndSendMessage(Request $request) {
        if(!$request->message || $request->message == '') {
            return response()->json(['message' => 'Message field is required.'],500);
        }
        if(!$request->account_id || $request->account_id == '') {
            return response()->json(['message' => 'Select account.'],500);
        }
        if(!$request->influencer_id || $request->influencer_id == '') {
            return response()->json(['message' => 'No influencer available with that id.'],500);
        }
        $thread = InstagramThread::where('scrap_influencer_id',$request->influencer_id)->first();
        if($thread) {
            $thread->account_id = $request->account_id;
        }
        else {
            $thread = new InstagramThread;
            $thread->account_id = $request->account_id;
            $thread->scrap_influencer_id = $request->influencer_id;
        }
        //find account 
        $account = \App\Account::find($request->account_id);
        $influencer  = ScrapInfluencer::find($request->influencer_id);
        $userInstagram = InstagramUsersList::where('username',$influencer->name)->first();
        if(!$userInstagram) {
            $ig = new \InstagramAPI\Instagram();

            try {
                $ig->login($account->last_name, $account->password);
            } catch (\Exception $e) {
                $msg = 'Instagram login failed: '.$e->getMessage();
                return response()->json(['message' => $msg, 'code' => 413],413);
            }
            try {
                $instaInfo = @$ig->people->getInfoByName($influencer->name);
            } catch (\Exception $e) {
                $msg = 'Something went wrong: '.$e->getMessage();
                $influencer->delete();
                return response()->json(['message' => $msg, 'code' => 413],413);
            }
            $instaInfo = $instaInfo->asArray();

            if(is_array($instaInfo) && array_key_exists("user",$instaInfo)) {
                $info = $instaInfo['user'];
                $userInstagram = new InstagramUsersList();
                $userInstagram->fullname = $info['full_name'];
                $userInstagram->username = $info['username'];
                $userInstagram->user_id = $info['pk'];
                $userInstagram->image_url = $info['profile_pic_url'];
                $userInstagram->bio = $info['biography'];
                $userInstagram->rating = 0;
                $userInstagram->location_id = 0;
                $userInstagram->because_of = 'instagram_dm';
                $userInstagram->posts = 0;
                $userInstagram->followers = 0;
                $userInstagram->following = 0;
                $userInstagram->location = '';
                $userInstagram->save();
            }
            else {
                $msg = 'Instagram user info not found for '.$influencer->name;
                return response()->json(['message' => $msg, 'code' => 413],413);  
            }
        }
        $thread->instagram_user_id = $userInstagram->id;
        $thread->save();

        $requestData = new Request();
        $requestData->setMethod('POST');
        $params['message'] = $request->message;
        $params['thread_id'] = $thread->id;
        $requestData->request->add($params);
        $result = app('App\Http\Controllers\DirectMessageController')->sendMessage($requestData);
        $data = $result->getData();
        if($data->status == 'error') {
            return response()->json(['message' => 'Message sending failed.'],500);
        }
        return response()->json(['message' => 'Successfull.'],200);
    }

    public function latestPosts(Request $request) {
        $influencer  = ScrapInfluencer::find($request->id);

        $ig = new \InstagramAPI\Instagram();

        try {
            $ig->login('satyam_t', 'Schoolrocks93');
        } catch (\Exception $e) {
            $msg = 'Instagram login failed: '.$e->getMessage();
        	return response()->json(['message' => $msg, 'code' => 413],413);
        }
        try {
        	$user_id = $ig->people->getUserIdForName($influencer->name);
        } catch (\Exception $e) {
        	$msg = 'Something went wrong: '.$e->getMessage();
        	return response()->json(['message' => $msg, 'code' => 413],413);
        }

        try {
            $feed = $ig->timeline->getUserFeed($user_id);
        } catch (\Exception $e) {
            $msg = 'Something went wrong: '.$e->getMessage();
        	return response()->json(['message' => $msg, 'code' => 413],413);
        }

        $medias = $feed->asArray();
        $medias = $medias['items'];
        
        
        foreach ($medias as $media) {
            $postId = $media['id'];
            $caption = $media['caption']['text'];
            $user_id = $user_id;
            $mediaDetail = [];
            if ($media['media_type'] === 1) {
                $mediaDetail[] = [
                    'media_type' => 1,
                    'url' => $media['image_versions2']['candidates'][1]['url']
                ];
            } else if ($media['media_type'] === 2) {
                $mediaDetail[] = [
                    'media_type' => 2,
                    'url' => $media['video_versions'][0]['url']
                ];
            } else if ($media['media_type'] === 8) {
                $crousal = $media['carousel_media'];
                $mediaDetail = [];
                foreach ($crousal as $cro) {
                    if ($cro['media_type'] === 1) {
                        $mediaDetail[] = [
                            'media_type' => 1,
                            'url' => $cro['image_versions2']['candidates'][0]['url']
                        ];
                    } else if ($cro['media_type'] === 2) {
                        $mediaDetail[] = [
                            'media_type' => 2,
                            'url' => $cro['video_versions'][0]['url']
                        ];
                    }
                }
            }
            $mediaType = $media['media_type'];
            $comment_count = $media['comment_count'];
            $likes = $media['like_count'];
            $code = $media['code'];
            if(!$caption) {
                $caption = '';
            }
            $influencer->post_id    = $postId;
            $influencer->post_caption    = $caption;
            $influencer->instagram_user_id    = $user_id;
            $influencer->post_media_type = $mediaType;
            $influencer->post_code       = $code;
            $influencer->post_location   = '';
            $influencer->post_hashtag_id = 0;
            $influencer->post_likes = $likes;
            $influencer->post_comments_count = $comment_count;
            $influencer->post_media_url = json_encode($mediaDetail);
            $influencer->posted_at = '';


            $comments = $ig->media->getComments($postId)->asArray();
                    
                    if(isset($comments['comments'])){
                        foreach ($comments['comments'] as $comment) {
                            $influencer->comment_user_id = $comment['user']['pk'];
                            $influencer->comment_user_full_name = $comment['user']['full_name'];
                            $influencer->comment_username = $comment['user']['username'];
                            $influencer->instagram_post_id = '';
                            $influencer->comment_id = $comment['pk'];
                            $influencer->comment = $comment['text'];
                            $influencer->comment_profile_pic_url = $comment['user']['profile_pic_url'];
                            $influencer->comment_posted_at = \Carbon\Carbon::createFromTimestamp($comment['created_at'])->toDateTimeString();
                        break;
                        }
            }
            $influencer->save(); 
            break;
        }
        return response()->json(['message' => 'Successfull', 'code' => 200],200);
    }
}
