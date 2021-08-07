<?php

namespace App\Facades;



use Illuminate\Support\Facades\Facade;



class PermissionCheckClass extends Facade{

	protected static function getFacadeAccessor() { return 'permissioncheck'; }


}