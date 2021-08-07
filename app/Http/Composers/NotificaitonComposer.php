<?php
/**
 * Created by PhpStorm.
 * User: mustafaflexwala
 * Date: 10/08/18
 * Time: 8:04 PM
 */

namespace App\Http\Composers;



use App\Http\Controllers\NotificaitonContoller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\View;

class NotificaitonComposer {


	public function __construct(Guard $auth) {

		$this->auth = $auth;
	}

	public function compose(View $view ){

		$view->with('notifications',NotificaitonContoller::json($this->auth ));

	}

}