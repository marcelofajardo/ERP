<?php

namespace App\Helpers;
use App\Permission;

class PermissionCheck {


	public function checkUser($link)
	{
		//Check if user is Admin
		$authcheck = auth()->user()->isAdmin();
		//Return True if user is Admin
		if($authcheck == true){
			return true;
		}
		//Check User Role and Permission
		$permission_check = auth()->user()->hasPermission($link);
		//Return True If User Has Role
		if($permission_check == true){
			return true;
		}
		//Return False When user doesnt have permission
		return false;


    }

}