<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Crypt;
use App\Password;
use App\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\WhatsAppController;
use App\PasswordHistory;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->website || $request->username || $request->password || $request->registered_with || $request->term || $request->date){
            
            $query =  Password::query();

            //global search term
            if (request('term') != null) {
                $query->where('website', 'LIKE', "%{$request->term}%")
                    ->orWhere('username', 'LIKE', "%{$request->term}%")
                    ->orWhere('password', 'LIKE', "%{$request->term}%")
                    ->orWhere('registered_with', 'LIKE', "%{$request->term}%");
            }


            if (request('date') != null) {
                $query->whereDate('created_at', request('website'));
            }


               //if website is not null 
            if (request('website') != null) {
                $query->where('website','LIKE', '%' . request('website') . '%');
            }

            //If username is not null 
          if (request('username') != null) {
                $query->where('username','LIKE', '%' . request('username') . '%');
            } 

           
            //if password is not null
          if (request('password') != null) {
                $query->where('password', 'LIKE', '%' . Crypt::encrypt(request('password')) . '%');
            } 
           
           //if registered with is not null 
          if (request('registered_with') != null) {
                $query->where('registered_with', 'LIKE', '%' . request('registered_with') . '%');
            }

            $passwords = $query->orderby('website','asc')->paginate(Setting::get('pagination')); 
        
        }else{
            $passwords = Password::latest()->paginate(Setting::get('pagination'));
        }

          if ($request->ajax()) {
            return response()->json([
                'tbody' => view('passwords.data', compact('passwords'))->render(),
                'links' => (string)$passwords->render()
            ], 200);
        }


        $users = User::orderBy('name','asc')->get();
        return view('passwords.index', [
          'passwords' => $passwords,
          'users' => $users,
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'website'   => 'sometimes|nullable|string|max:255',
        'url'       => 'required',
        'username'  => 'required|min:3|max:255',
        'password'  => 'required|min:6|max:255'
      ]);

      $data = $request->except('_token');
      $data['password'] = Crypt::encrypt($request->password);

      Password::create($data);

      return redirect()->route('password.index')->withSuccess('You have successfully stored password');
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
    public function update(Request $request)
    {
        $this->validate($request, [
            'website'   => 'sometimes|nullable|string|max:255',
            'url'       => 'required',
            'username'  => 'required|min:3|max:255',
            'password'  => 'required|min:6|max:255'
        ]);

        $password = Password::findorfail($request->id);
        $data_old['password_id'] = $password->id;
        $data_old['website'] = $password->website;
        $data_old['url'] = $password->url;
        $data_old['username'] = $password->username;
        $old_password =  $password->password;
        $data_old['password'] = $old_password;
        $data_old['registered_with'] = $password->registered_with;
        PasswordHistory::create($data_old);

        $data = $request->except('_token');
        $data['password'] = Crypt::encrypt($request->password);
        $password->update($data);

        if(isset($request->send_message) && $request->send_message == 1){
            $user_id = $request->user_id;
            $user = User::findorfail($user_id);
            $number = $user->phone;
            $whatsappnumber = '971502609192';
            $message = 'Password Change For '. $request->website .'is, Old Password  : ' . Crypt::decrypt($old_password) . ' New Password is : ' . $request->password;

            $whatsappmessage = new WhatsAppController();
            $whatsappmessage->sendWithThirdApi($number, $whatsappnumber , $message);
         }

        return redirect()->route('password.index')->withSuccess('You have successfully changed password');
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

    public function manage()
    {
        $users = User::where('is_active',1)->orderBy('id','desc')->get();
        return view('passwords.change-password',compact('users'));
    }

    public function changePassword(Request $request){

        if( empty( $request->users ) ){
            return redirect()->back()->with('error','Please select user');
        }
        
        $users = explode(",",$request->users);
        $data = array();
        foreach ($users as $key) {
            // Generate new password
            $newPassword = str_random(12);

            // Set hash password
            $hashPassword = Hash::make($newPassword);

            // Update password
            $user = User::findorfail($key);
            $user->password = $hashPassword;
            $user->save();
            $data[$key] = $newPassword;
            // Output new ones
            //echo $user->name . "\t" . $user->email . "\t" . $newPassword . "\n";
        }

        return view("passwords.send-whatsapp", ['data' => $data]);
    }

    public function sendWhatsApp(Request $request){
        if(isset($request->single) && $request->single == 1) {
            $user_id = $request->user_id;
            $password = $request->password;
            $user = User::findorfail($user_id);
            $number = $user->phone;
            $message = 'Your New Password For ERP desk is Username : ' . $user->email . ' Password : ' . $password;

            $whatsappmessage = new WhatsAppController();
            $whatsappmessage->sendWithThirdApi($number, $user->whatsapp_number, $message);
            $params['user_id'] = $user_id;
            $params['message'] = $message;
            $chat_message = ChatMessage::create($params);
            $msg = 'WhatsApp send';
            $data = [
                'success' => true,
                'message' => $msg
            ];
            return response()->json($data);
        }else{
            $user_id = $request->user_id;
            $password = $request->password;
                for ($i=0;$i<count($user_id);$i++){
                    $user = User::findorfail($user_id[$i]);
                    $number = $user->phone;
                    $message = 'Your New Password For ERP desk is Username : ' . $user->email . ' Password : ' . $password[$i];

                    $whatsappmessage = new WhatsAppController();
                    $whatsappmessage->sendWithThirdApi($number, $user->whatsapp_number, $message);
                    $params['user_id'] = $user->id;
                    $params['message'] = $message[$i];
                    $chat_message = ChatMessage::create($params);
                }
            return redirect()->route('password.manage')->with('message', 'SuccessFully Messages Send !');

        }
    }

    public  function getHistory(Request $request){

       $password =  PasswordHistory::where('password_id',$request->password_id)->get();
       $count = 0;
       foreach ($password as $passwords){
        $value[$count]['username'] = $passwords->username;
        $value[$count]['website'] = $passwords->website;
        $value[$count]['url'] = $passwords->url;
        $value[$count]['registered_with'] = $passwords->registered_with;
        $value[$count]['password_decrypt'] = Crypt::decrypt($passwords->password);
        $count++;
       }
       if(count($password) == 0){
           return array();
       }else{
           return $value;
       }
       return $value;


    }
}
