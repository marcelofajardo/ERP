<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Helpers;
use App\CustomerOrderCharities;
use App\User;
use App\Charity;
use App\CharityOrderHistory;
use App\CharityStatusMaster;
use DB;
use Session;
use Auth;

class CharityController extends Controller
{
    //
	public function index(Request $request)
	{
		$isAdmin = Auth::user()->isAdmin();
		
		//Get Current loggedin user role
		$currentUserId = Auth::id();
		$loggedInUser = User::find($currentUserId);
		$checkCurrentUserIsCharity = false;
		foreach($loggedInUser->roles as $role)
		{
			if($role->name == 'Charity')
			{
				$checkCurrentUserIsCharity = true;
				break;
			}
		}
		//Get Current loggedin user role
		
		//Get All Users with Charity User Role
		$users = User::all();
		$onlyCharityUser = [];
		foreach($users as $user)
		{
			$isCharityUser = false;
			foreach($user->roles as $role)
			{
				if($role->name == 'Charity')
				{
					$isCharityUser = true;
					break;
				}
			}
			if($isCharityUser)
			{
				$onlyCharityUser[] = $user;
			}
		}
		//Get All Users with Charity User Role
		
		if($isAdmin)
		{
			$query = Charity::query();
		
			if($request->search){
				$query = $query->where('name', 'LIKE','%'.$request->search.'%')->orWhere('email', 'LIKE', '%'.$request->search.'%');
			}
			$charityData = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
		}else{
			$query = Charity::query();
			
			if($checkCurrentUserIsCharity)
			{
				$query->where('assign_to',$currentUserId);
			}
			if($request->search){
				$query = $query->where('name', 'LIKE','%'.$request->search.'%')->orWhere('email', 'LIKE', '%'.$request->search.'%');
			}
			$charityData = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
		}
		
		return view('charity.index', compact('charityData','onlyCharityUser','isAdmin','checkCurrentUserIsCharity'))->with('i', ($request->input('page', 1) - 1) * 5);
	}
	
	
	public function store(Request $request)
	{
		$this->validate($request, [
            'email' => 'required|email',
            'contact_no' => 'required|integer',
            'name' => 'required|string',
            'whatsapp_number' => 'required|integer'
        ]);
		
		$charity = new Charity;
		$charity->name = $request->name;
		$charity->contact_no = $request->contact_no;
		$charity->email = $request->email;
		$charity->whatsapp_number = $request->whatsapp_number;
		$charity->assign_to = $request->assign_to;
		$charity->save();
		return redirect()->route('charity')->with('flash_type','success')->with('message','Data successfully saved');
		
	}
	
	public function update(Request $request, $id = null)
	{
		$charityData = Charity::find($id);
		if($request->post('name') && $request->post('email') && $request->post('contact_no') && $request->post('whatsapp_number') )
		{
			$charityId = $request->post('id');	
			$charityObj = Charity::find($charityId);
			$updateData = array('name'=>$request->name, 'email'=>$request->email, 'contact_no'=>$request->contact_no, 'whatsapp_number'=>$request->whatsapp_number,'assign_to'=>$request->assign_to);
			$charityObj->fill($updateData);
			$charityObj->save();
			return redirect()->route('charity')
		                 ->with('flash_type','success')->with('message','Data updated successfully');
		}
		
		return view('charity.edit', compact('charityData'));
		
	}
	
	
	public function charityOrder(Request $request, $charity_id)
	{
		$charityData = Charity::find($charity_id);
		
		$orderCharityData = CustomerOrderCharities::where('charity_id',$charity_id)->get();
		
		$allCharityStatus = CharityStatusMaster::all();
		
		$charityOrder = [];
		$i = 0;
		foreach($orderCharityData as $data)
		{
			$userDetails = User::where('id',$data->customer_id)->get()->first()->toArray();	
			$charityOrder[$i]['orderData']['id'] = $data->id;
			$charityOrder[$i]['orderData']['customer_id'] = $data->customer_id;
			$charityOrder[$i]['orderData']['order_id'] = $data->order_id;
			$charityOrder[$i]['orderData']['amount'] = $data->amount;
			$charityOrder[$i]['orderData']['customer_contribution'] = $data->customer_contribution;
			$charityOrder[$i]['orderData']['our_contribution'] = $data->our_contribution;
			$charityOrder[$i]['orderData']['status'] = $data->status;
			$charityOrder[$i]['userData'] = $userDetails;
			$i++;
		}
		$query = CustomerOrderCharities::query();
		$query->where('charity_id',$charity_id);
		$charityoOrderPagination = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
		
		//Get Current loggedin user role
		$currentUserId = Auth::id();
		$loggedInUser = User::find($currentUserId);
		$checkCurrentUserIsCharity = false;
		foreach($loggedInUser->roles as $role)
		{
			if($role->name == 'Charity')
			{
				$checkCurrentUserIsCharity = true;
				break;
			}
		}
		//Get Current loggedin user role
		
		if(!$checkCurrentUserIsCharity)
		{
			return view('charity.charity_order', compact('charityOrder','orderCharityData','charityoOrderPagination','allCharityStatus'));
		}else{
			return view('charity.charity_user_order', compact('charityOrder','orderCharityData','charityoOrderPagination','allCharityStatus'));
		}
	}
	
	public function addStatus(Request $request)
	{
		if($request->post('charity_status'))
		{
			$charityStatusObj = new CharityStatusMaster;
			$charityStatusObj->charity_status = $request->charity_status;
			$charityStatusObj->save();
			return redirect()->route('charity')->with('flash_type','success')->with('message','Status successfully saved');
		}else{
			return redirect()->route('charity')->with('flash_type','warning')->with('message','Invalid parameter in request');
		}
	}
	
	
	public function updateCharityOrderStatus(Request $request)
	{
		if($request->post('orderId') && $request->post('status'))
		{	
			$orderCharityData = CustomerOrderCharities::find($request->post('orderId'));
			$updateData = array('status'=>$request->post('status'));
			$orderCharityData->fill($updateData);
			$orderCharityData->save();
			
			return response()->json(["code" => 200, "data" => [], "message" => "Status updated successfully"]);
		}else{
			return response()->json(["code" => 500, "data" => [], "message" => "Incomplete parameters"]);
		}

	}
	
	public function createHistory(Request $request)
	{
		if($request->post('customer_order_charity_id') && $request->post('comment')  && $request->post('amount'))
		{
			$orderCharityData = CustomerOrderCharities::find($request->post('customer_order_charity_id'));
			
			$query = new CharityOrderHistory;
			$query->customer_order_charity_id = $request->post('customer_order_charity_id');
			$query->amount = $request->post('amount');
			$query->comment = $request->post('comment');
			$query->user_id = $orderCharityData->customer_id;
			
			$query->save();
			
			return redirect()->route('charity')->with('flash_type','success')->with('message','History successfully saved');
		}else{
			return redirect()->route('charity')->with('flash_type','warning')->with('message','Invalid parameter in request');
		}
	}
	
	public function viewHistory(Request $request, $order_id)
	{
		
		$orderCharityData = CustomerOrderCharities::find($order_id);
		$historyOrderData = CharityOrderHistory::where('customer_order_charity_id',$order_id)->get();
		
		$userData = User::find($orderCharityData->customer_id);
		
		return view('charity.charity_order_history', compact('orderCharityData','historyOrderData','userData'));
		
	}
	
	
}
