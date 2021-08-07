<?php

namespace App\Http\Controllers;

use App\EmailContentHistory;
use Illuminate\Http\Request;

class EmailContentHistoryController extends Controller
{
    public function store(Request $_request){
        if(!empty($_request->id)){
            $history =  EmailContentHistory::find($_request->id);
        }else{
            $history = new EmailContentHistory();
        }

        $history->mailinglist_templates_id = $_request->mail_list_id;
        $history->content =  $_request->content;
        $history->date = !empty($_request->date) ? $_request->date : date('Y-m-d');
        $history->updated_by = auth()->user()->id;
        if($history->save()){
            \Session::flash('message', 'Contenet successfully added'); 
            \Session::flash('alert-class', 'alert-success'); 
            return redirect()->route('mailingList-template');
        }else{
            \Session::flash('message', 'Something went wrong!'); 
            \Session::flash('alert-class', 'alert-danger'); 
            return redirect()->route('mailingList-template');
        }
    }


    public function displayModal(Request $_request){
        
        $history =  EmailContentHistory::with(['addedBy'])->where('mailinglist_templates_id',$_request->id)->first();
        if(empty($history)){
            $history = [];
        }

        $returnHTML = view('marketing.mailinglist.templates.content_modal')->with('data', $history)->with('mail_list_id',$_request->id)->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
        

    }
}
