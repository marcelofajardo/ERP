<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brotzka\DotenvEditor\DotenvEditor;

class EnvController extends Controller
{
      public function loadEnvManager(){
       return view('env_manager.overview-adminlte');
    }
}
