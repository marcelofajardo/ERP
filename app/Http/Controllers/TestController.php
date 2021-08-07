<?php

namespace App\Http\Controllers;

use InstagramAPI\Instagram;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
    	$instagram = new Instagram();
	    $instagram->login('satyam_t', "Schoolrocks93");
	    $this->instagram = $instagram;
	    //$inbox = $this->instagram->direct->getInbox()->asArray();

	    $info = $this->instagram->people->getInfoById('846181417')->asArray();
	    dd($info);

    }

}
