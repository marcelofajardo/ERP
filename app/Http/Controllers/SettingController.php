<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Helpers;
use App\User;
use App\ApiKey;
use Illuminate\Http\Request;
use DB;
use Session;

class SettingController extends Controller
{

	public function __construct() {

	//	$this->middleware('permission:setting-list',['only' => ['index']]);
	//	$this->middleware('permission:setting-create',['only' => ['store']]);
	}


	public function index(Request $request)
	{
		$setting = [];
		
		$query = Setting::query();
		if($request->name){
			$query = $query->where('name', 'LIKE','%'.$request->name.'%');
		}
		if($request->value){
			$query = $query->orWhere('val', 'LIKE','%'.$request->value.'%');
		}
		if($request->type){
			$query = $query->orWhere('type', 'LIKE','%'.$request->type.'%');
		}
		$data = $query->orderBy('id', 'asc')->paginate(10)->appends(request()->except(['page']));
		return view('setting.index',compact('data'));
	}


	
	public function update(Request $request)
	{
		$data = array('name'=>$request->post('name'), 'val'=>$request->post('val'), 'type'=>$request->post('type'), 'welcome_message'=>$request->post('welcome_message'));
		if($request->post('id'))
		{
			Setting::whereId($request->post('id'))->update($data);
			Session::flash('message', 'Settings Updated Successfully'); 
		}else{
			//Setting::add('disable_twilio', $disable_twilio, 'tinyint');
			Setting::create($data);
			Session::flash('message', 'Settings Created Successfully'); 
		}
		
		return redirect('/settings');
	}


	public function store(Request $request)
	{
		$euro_to_inr = $request->input('euro_to_inr');
//		$special_price_discount = $request->input('special_price_discount');
		$pagination = $request->input('pagination');
		$disable_twilio = $request->disable_twilio ? 1 : 0;
		$incoming_calls_yogesh = $request->incoming_calls_yogesh ? 1 : 0;
		$incoming_calls_andy = $request->incoming_calls_andy ? 1 : 0;
		$forward_messages = $request->forward_messages ? 1 : 0;
		$whatsapp_number_change = $request->whatsapp_number_change ? 1 : 0;

//		Setting::add('euro_to_inr', $euro_to_inr, 'double');
//		Setting::add('special_price_discount', $special_price_discount, 'int');
		Setting::add('pagination', $pagination, 'int');

		// Twilio
		Setting::add('disable_twilio', $disable_twilio, 'tinyint');
		Setting::add('incoming_calls_yogesh', $incoming_calls_yogesh, 'tinyint');
		Setting::add('incoming_calls_andy', $incoming_calls_andy, 'tinyint');

		// Whatsapp
		Setting::add('whatsapp_number_change', $whatsapp_number_change, 'tinyint');
		Setting::add('forward_messages', $forward_messages, 'tinyint');
		Setting::add('forward_start_date', $request->forward_start_date, 'string');
		Setting::add('forward_end_date', $request->forward_end_date, 'string');
		Setting::add('forward_users', json_encode($request->forward_users), 'string');

		// Shortcuts
		Setting::add('image_shortcut', $request->image_shortcut, 'tinyint');
		Setting::add('price_shortcut', $request->price_shortcut, 'tinyint');
		Setting::add('call_shortcut', $request->call_shortcut, 'tinyint');
		Setting::add('screenshot_shortcut', $request->screenshot_shortcut, 'tinyint');
		Setting::add('details_shortcut', $request->details_shortcut, 'tinyint');
		Setting::add('purchase_shortcut', $request->purchase_shortcut, 'tinyint');

		// Shipping Details
		Setting::add('consignor_name', $request->consignor_name, 'string');
		Setting::add('consignor_address', $request->consignor_address, 'string');
		Setting::add('consignor_city', $request->consignor_city, 'string');
		Setting::add('consignor_country', $request->consignor_country, 'string');
		Setting::add('consignor_phone', $request->consignor_phone, 'string');

		// Working Hours
		Setting::add('start_time', $request->start_time, 'string');
		Setting::add('end_time', $request->end_time, 'string');

		//Welcome Message
		Setting::add('welcome_message', $request->welcome_message, 'string');
		

		$old_api_keys = ApiKey::all();

		foreach ($old_api_keys as $api_key) {
			$api_key->delete();
		}

		if ($request->number[0] != null) {
			foreach ($request->number as $key => $number) {
				$api_key = new ApiKey;
				$api_key->number = $number;
				$api_key->key = $request->key[$key];
				$api_key->default = $request->default == ($key + 1) ? 1 : 0;
				$api_key->save();
			}
		}

		return redirect()->back()->with('status', 'Settings has been saved.');
	}

	public function updateAutoMessages(Request $request)
	{
		Setting::add('show_automated_messages', $request->value, 'int');

		return response('success');
	}
}
