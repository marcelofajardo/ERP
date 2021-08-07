<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Config;
use App\Customer;
use GuzzleHttp\Client;

class MailchimpController extends Controller
{
    public $mailchimp;
    public $list;

    public function __construct(\Mailchimp $mailchimp)
    {
        $this->mailchimp = $mailchimp;
        $this->listId = getenv('LIST_ID');
    }


    public function manageMailChimp()
    {
        return view('mailchimp.mailchimp');
    }


    public function subscribe(Request $request)
    {
    	$this->validate($request, [
	    	'email' => 'required|email',
        ]);

        try {

            $this->mailchimp
            ->lists
            ->subscribe(
                $this->listId,
                ['email' => $request->input('email')]
            );

            return redirect()->back()->with('success','Email Subscribed successfully');

        } catch (\Mailchimp_List_AlreadySubscribed $e) {
            return redirect()->back()->with('error','Email is Already Subscribed');
        } catch (\Mailchimp_Error $e) {
            return redirect()->back()->with('error','Error from MailChimp');
        }
    }


    public function sendCompaign(Request $request)
    {
    	$this->validate($request, [
	    	'subject' => 'required',
	    	'to_email' => 'required',
	    	'from_email' => 'required',
	    	'message' => 'required',
        ]);

        try {

	        $options = [
		        'list_id'   => $this->listId,
		        'subject' => $request->input('subject'),
		        'from_name' => 'Solo lux ERP',
		        'from_email' => $request->input('from_email'),
		        'to_name' => $request->input('to_email')
	        ];

	        $content = [
		        'html' => $request->input('message'),
		        'text' => strip_tags($request->input('message'))
	        ];

	        $campaign = $this->mailchimp->campaigns->create('regular', $options, $content);
	        $this->mailchimp->campaigns->send($campaign['id']);

        	return redirect()->back()->with('success','Campaign sent successfully');

        } catch (Exception $e) {
        	return redirect()->back()->with('error','Error from MailChimp');
        }
    }

}
