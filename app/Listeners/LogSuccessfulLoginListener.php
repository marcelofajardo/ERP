<?php

namespace App\Listeners;

use App\Http\Controllers\ActivityConroller;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use Carbon\Carbon;
use App\UserLogin;

class LogSuccessfulLoginListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle()
    {
//	    activity()->performedOn(\App\User::getModel())->withProperties(['type' => 'info'])->log('Login');
	    ActivityConroller::create(0,'User','Login');

      // if ($user_login = UserLogin::where('user_id', Auth::id())->latest()->first()) {
      //   if (Carbon::now()->diffInDays($user_login->login_at) != 0) {
      //     UserLogin::create([
      //       'user_id'  => Auth::id(),
      //       'login_at' => Carbon::now()
      //     ]);
      //   }
      // } else {
      //   UserLogin::create([
      //     'user_id'  => Auth::id(),
      //     'login_at' => Carbon::now()
      //   ]);
      // }

    }
}
