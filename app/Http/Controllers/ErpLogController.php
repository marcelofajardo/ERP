<?php

namespace App\Http\Controllers;


use App\ErpLog;

class ErpLogController extends Controller
{

    public function index() {

        $erpLogData = ErpLog::all()->toArray();

        return view('erp-log.index', compact('erpLogData'));
    }

}
