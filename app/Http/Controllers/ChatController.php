<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Pusher\Laravel\Facades\Pusher;
use App\Http\Controllers\DB;
use App\Chat;
use App\User;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $userid = $request->input('userid');
        $chat = new Chat();    
        $chats =  $chat->where('sourceid','=',Auth::id())->orWhere('userid','=',Auth::id())->get()->toArray();

        $chat_converstaion = array();
      
        $chat_converstaion[] = '<table>';
              foreach ($chats as $message) {
                $msg = htmlentities($message['messages'], ENT_NOQUOTES);
                $users = User::find($message['sourceid']);
                if(Auth::id() == $message['sourceid'] ) 
                    $style="selfs";
                else
                    $style="noselfs";    
                $user_name = $users['name'];
                $sent = date('F j, Y, g:i a', strtotime($message['created_at']));
                 if((Auth::id() == $message['sourceid'] and $userid == $message['userid']) or ($message['sourceid'] == $userid and $message['userid'] == Auth::id()) ) 
                $chat_converstaion[] = '
                  <tr class="msg-row-container '.$style.'" >
                    <td>
                      <div class="msg-row">
                        <div class="avatar"><img src="https://ui-avatars.com/api/?name='.$user_name.'" width="32px"/></div>
                        <div class="message">
                          <span class="user-label"><a href="#" style="color: #6D84B4;">'.$user_name.'</a> <span class="msg-time">'.$sent.'</span></span><br/>'.$msg.'
                        </div>
                      </div>
                    </td>
                  </tr>';
            
                }
        $chat_converstaion[] = '</table>';
        echo implode('',$chat_converstaion);
       
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
         $request->merge(array('sourceid' => Auth::id()));
         $chat = $this->validate(request(), [
          'userid' => 'required',
          'messages' => 'required',
          'sourceid' =>'',
          
          
          
        ]);      
        $messages = $request->input('messages');
        $userid = $request->input('userid');
        $sourceid = $request->input('sourceid');
        Chat::create($chat);
        Pusher::trigger('solo-chat-channel', 'chat', ['message' => $messages , 'userid'=> $userid ,'sourceid'=>$sourceid]);
        return 1;
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

    // this is custom controller to add messages.
    public function addmessages(Request $request)
    {
        $request->merge(array('userid' => Auth::id()));
        $chat = $this->validate(request(), [
          'userid' => 'required',
          'messages' => 'required'         

           ]);
    }

    public function checkfornew(Request $request)
    {
        //$userid = $request->input('userid');
        $users = new User();
        //$allusers =  $users->where('id', '=', Auth::id())->get();   
        $loggedinuser =  $users->find(Auth::id()); 
        $lastcheck =$loggedinuser['last_checked'];
        $allusers = $users->all();
        $newmessage = array();
        $chat = new Chat(); 
        foreach ($allusers as $user) {
            $chats = '';
            
            $userid = $user['id'];
            $chats =  $chat->where('sourceid','=',$userid )->where('userid','=',Auth::id())->where('created_at','>',$lastcheck)->get()->toArray();
            if(!empty($chats))
            {
                $newmessage[] = array('userid' => $userid , 'new' => 'true');
               
            }
            else
            {
                $newmessage[] = array('userid' => $userid , 'new' => 'false');
            }
        }
           
       
      //var_dump($chats);
      echo json_encode($newmessage);

    }    

    public function updatefornew(Request $request)
    {
        $user = Auth::User();
        $user->last_checked = date('Y-m-d H:i:s');
       // echo $user->last_checked;
        $user->save();
    }
}