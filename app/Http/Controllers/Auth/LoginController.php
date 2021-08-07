<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\UserLoginIp;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function login(Request $request) {
    //
    // }
  
    public function login(Request $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        $credentials =['email'=>$request->email, 'password'=>$request->password];
        if($this->guard()->attempt($credentials,$request->has('remember'))){
            $this->validateLogin($request);
            $user = auth()->user();
            $user_ip = UserLoginIp::where('ip',$request->getClientIp())->where('user_id',$user->id)->orderBy('created_at','DESC')->first();
            if(is_null($user_ip)){
                $user_ip_add = New UserLoginIp();
                $user_ip_add->user_id = $user->id;
                $user_ip_add->ip = $request->getClientIp();
                $user_ip_add->is_active = (auth()->user()->isAdmin()) ? 0 : 1;
                $user_ip_add->save();
            }
            if(auth()->user()->is_active !=1){ //Account has not being activated!
                $this->logout($request);
                return back()
                    ->withInput()
                    ->withErrors(['email'=>'Your account is inactive. You are not authorized to access this erp']);
            }
            if(!$user->isAdmin() && $user->is_auto_approval != 1) {
                $date =  date('Y-m-d', strtotime('-2 days'));
                $hubstaff_activities = \App\Hubstaff\HubstaffActivity::join('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->whereDate('hubstaff_activities.starts_at',$date)->where('hubstaff_members.user_id',$user->id)->count();
                //if(!auth()->user()->isAdmin()) {
                
                if($user_ip){
                    if($user_ip->is_active == false){
                        $this->logout($request);
                        return back()
                            ->withInput()
                            ->withErrors(['message'=>'Please ask admin for login approval.']);
                    }
                }
                if($hubstaff_activities) {
                    $activity = \App\Hubstaff\HubstaffActivitySummary::where('user_id',$user->id)->where('date',$date)->first();
                    if(!$activity) {
                        if($user->approve_login != date('Y-m-d')) {
                            $this->logout($request);
                            return back()
                                ->withInput()
                                ->withErrors(['message'=>'You haven\'t approved your last hubstaff activities, Please ask admin for login approval']);
                        }
                    }
                }
            }
            return $this->sendLoginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.


            $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
