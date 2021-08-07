<?php

namespace App\Http\Controllers\Marketing;

use App\Customer;
use App\Helpers\InstantMessagingHelper;
use App\Http\Controllers\Controller;
use App\CustomerMarketingPlatform;
use Illuminate\Http\Request;
use App\Setting;
use Auth;
use Validator;
use Response;
use App\Order;
use App\ApiKey;
use App\ErpLeads;
use App\ImQueue;
use App\Marketing\WhatsappConfig;
use App\ColdLeadBroadcasts;
use App\CompetitorPage;

use App\Account; 
use App\Marketing\MarketingPlatform;

class AccountController extends Controller
{

	public function index($type = null , Request $request)
	{
		$query = Account::query();

		if($type){
			$query = $query->where('platform',$type);
		}else{
			$type = '';
		}

		if($request->platform){
			$query = $query->where('platform',$request->platform);
		}

		if($request->term){
			$query = $query->where('last_name','LIKE','%'.$request->term.'%')
							->orWhere('email','LIKE','%'.$request->term.'%')
							->orWhere('platform','LIKE','%'.$request->term.'%');
		}

		if($request->date){
			$query = $query->whereDate('created_at',$request->date);
		}

		$accounts = $query->orderBy('id','desc')->paginate(25);
		
		$platforms = MarketingPlatform::all();

		$websites = \App\StoreWebsite::select('id','title')->get();
		
		if ($request->ajax()) {
            return response()->json([
                'tbody' => view('marketing.accounts.partials.data', compact('accounts','type','platforms','websites'))->render(),
                'links' => (string)$accounts->render(),
                'count' => $accounts->total(),
            ], 200);
        }
		return view('marketing.accounts.index',compact('accounts','type','platforms','websites'));	
	}


	public function store(Request $request)
	{

		$this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'email' => 'required:email',
            'frequency' => 'required',
            'instance_id' => 'required',
            'token' => 'required',
            'platform' => 'required',
		]);

		$check = Account::where('platform',$request->platform)->where('last_name',$request->username)->first();
		if($check){
			return redirect()->back()->with('message', 'Account Already Exist');
		}
		$account = new Account;
		$account->first_name = $request->username;
		$account->last_name = $request->username;
		$account->password = $request->password;
		$account->email = $request->email;
		$account->number = $request->number;
		$account->provider = $request->provider;
		$account->frequency = $request->frequency;
		$account->is_customer_support = $request->customer_support;
		$account->instance_id = $request->instance_id;
		$account->token = $request->token;
		$account->send_start = $request->send_start;
		$account->send_end = $request->send_end;
		$account->platform = $request->platform;
		$account->status = $request->status;
		$account->store_website_id = $request->website;
		$account->proxy = $request->proxy;
		$account->save();
		
		return redirect()->back()->with('message', 'Account Saved');	
	}


	public function edit(Request $request)
	{
		$this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'email' => 'required:email',
            'frequency' => 'required',
            'instance_id' => 'required',
            'token' => 'required',
            'platform' => 'required',
		]);

		$account = Account::find($request->id);
		$account->first_name = $request->username;
		$account->last_name = $request->username;
		$account->password = $request->password;
		$account->email = $request->email;
		$account->number = $request->number;
		$account->provider = $request->provider;
		$account->frequency = $request->frequency;
		$account->is_customer_support = $request->customer_support;
		$account->instance_id = $request->instance_id;
		$account->token = $request->token;
		$account->send_start = $request->send_start;
		$account->send_end = $request->send_end;
		$account->platform = $request->platform;
		$account->store_website_id = $request->website;
		$account->proxy = $request->proxy;
		$account->status = $request->status;
		$account->save();

		return redirect()->back()->with('message', 'Account Updated');
	}
}