<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserSysyemIp;
use App\UserLogin;
use App\Setting;
use App\Helpers;
use App\NotificationQueue;
use App\PushNotification;
use App\ApiKey;
use App\Task;
use App\Product;
use App\Customer;
use App\Hubstaff\HubstaffActivity;
use App\Hubstaff\HubstaffPaymentAccount;
use App\Payment;
use App\PaymentMethod;
use App\UserProduct;
use App\Role;
use App\Permission;
use App\UserRate;
use DB;
use Hash;
use Cache;
use Auth;
use Log;
use Carbon\Carbon;
use DateTime;
use App\UserLoginIp;
use App\EmailNotificationEmailDetails;//Purpose : add MOdal - DEVTASK-4359
use App\WebhookNotification;

class UserController extends Controller
{

	CONST DEFAULT_FOR = 4; //For User

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function __construct()
	{
		//	 	$this->middleware('permission:user-list', ['except' => ['assignProducts']]);
		//	 	$this->middleware('permission:user-create', ['only' => ['create','store']]);
		//	 	$this->middleware('permission:user-edit', ['only' => ['edit','update']]);
		//	 	$this->middleware('permission:user-delete', ['only' => ['destroy']]);
		//	 	$this->middleware('permission:product-lister', ['only' => ['assignProducts']]);
	}



	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$query = User::query();

		if($request->id){
			$query = $query->where('id', $request->id);
		}
		if($request->term){
			$query = $query->where('name', 'LIKE','%'.$request->term.'%')->orWhere('email', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('phone', 'LIKE', '%'.$request->term.'%');
		}

		$data = $query->orderBy('name', 'asc')->paginate(25)->appends(request()->except(['page']));
		if ($request->ajax()) {
            return response()->json([
                'tbody' => view('users.partials.list-users', compact('data'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$data->render(),
                'count' => $data->total(),
            ], 200);
        }
		return view('users.index', compact('data'))
			->with('i', ($request->input('page', 1) - 1) * 5);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$roles = Role::pluck('name', 'name')->all();
		$users = User::all();
		$agent_roles  = array('sales' => 'Sales', 'support' => 'Support', 'queries' => 'Others');
		return view('users.create', compact('roles', 'users', 'agent_roles'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
			'email' => 'required|email|unique:users,email',
			'phone' => 'sometimes|nullable|integer|unique:users,phone',
			'password' => 'required|same:confirm-password',
			'hourly_rate' => 'numeric',
			'currency' => 'string'

		]);
	
		$input = $request->all();
		$userRate = new UserRate();
		
		//get default whatsapp number for vendor from whatsapp config
	
		if(empty($input["whatsapp_number"])) {
			$task_info = DB::table('whatsapp_configs')
						->select('*')
						->whereRaw("find_in_set(".self::DEFAULT_FOR.",default_for)")
						->first();
			$input["whatsapp_number"] = $task_info->number;
		}
		
		
		$userRate->start_date = Carbon::now();
		$userRate->hourly_rate = $input['hourly_rate'];
		$userRate->currency = $input['currency'];

		unset($input['hourly_rate']);
		unset($input['currency']);

		$input['name'] = str_replace(' ', '_', $input['name']);
		$input['password'] = Hash::make($input['password']);
		if (isset($input['agent_role']))
			$input['agent_role'] = implode(',', $input['agent_role']);

		$user = User::create($input);


		$userRate->user_id = $user->id;
		$userRate->save();

		return redirect()->to('/users/' . $user->id . '/edit')->with('success', 'User created successfully');;
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$user = User::find($id);

		if (Auth::id() != $id) {
			return redirect()->route('users.index')->withWarning("You don't have access to this page!");
		}

		$users_array = Helpers::getUserArray(User::all());
		$roles = Role::pluck('name', 'name')->all();
		$users = User::all();
		$userRole = $user->roles->pluck('name', 'name')->all();
		$agent_roles  = array('sales' => 'Sales', 'support' => 'Support', 'queries' => 'Others');
		$user_agent_roles = explode(',', $user->agent_role);
		$api_keys = ApiKey::select('number')->get();

		$pending_tasks = Task::where('is_statutory', 0)
			->whereNull('is_completed')
			->where(function ($query) use ($id) {
				return $query->orWhere('assign_from', $id)
					->orWhere('assign_to', $id);
			})->get();

		// dd($pending_tasks);

		return view('users.show', [
			'user'	=> $user,
			'users_array'	=> $users_array,
			'roles'	=> $roles,
			'users'	=> $users,
			'userRole'	=> $userRole,
			'agent_roles'	=> $agent_roles,
			'user_agent_roles'	=> $user_agent_roles,
			'api_keys'	=> $api_keys,
			'pending_tasks'	=> $pending_tasks,
		]);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$user = User::with('webhookNotification')->find($id);
		$roles = Role::orderBy('name', 'asc')->pluck('name', 'id')->all();
		$permission = Permission::orderBy('name', 'asc')->pluck('name', 'id')->all();

		$users = User::all();
		$userRole = $user->roles->pluck('name', 'id')->all();
		$userPermission = $user->permissions->pluck('name', 'id')->all();
		$agent_roles  = array('sales' => 'Sales', 'support' => 'Support', 'queries' => 'Others');
		$user_agent_roles = explode(',', $user->agent_role);
		$api_keys = ApiKey::select('number')->get();
		$customers_all = Customer::select(['id', 'name', 'email', 'phone', 'instahandler'])->whereRaw("customers.id NOT IN (SELECT customer_id FROM user_customers WHERE user_id != $id)")->get()->toArray();

		$userRate = UserRate::getRateForUser($user->id);
		
		$email_notification_data = EmailNotificationEmailDetails::where('user_id',$id)->first();//Purpose : get email details - DEVTASK-4359



		return view(
			'users.edit',
			compact('user', 'users', 'roles', 'userRole', 'agent_roles', 'user_agent_roles', 'api_keys', 'customers_all', 'permission', 'userPermission', 'userRate','email_notification_data')//Purpose : add email_notification_data - DEVTASK-4359
		);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		// dd($request->all());
		$this->validate($request, [
			'name' => 'required',
			'email' => 'required|email|unique:users,email,' . $id,
			'phone' => 'sometimes|nullable|integer|unique:users,phone,' . $id,
			'password' => 'same:confirm-password',
			'roles' => 'required',
		]);

		$input = $request->all();
		
		$hourly_rate = $input['hourly_rate'];
		$currency = $input['currency'];

		unset($input['hourly_rate']);
		unset($input['currency']);

		$input['name'] = str_replace(' ', '_', $input['name']);
		if (isset($input['agent_role'])) {
			$input['agent_role'] = implode(',', $input['agent_role']);
		} else {
			$input['agent_role'] = '';
		}
		//		$input['name'] = 'solo_admin';
		//		$input['email'] = 'admin@example.com';
		//		$input['password'] = 'admin@example.com';


		if (!empty($input['password'])) {
			$input['password'] = Hash::make($input['password']);
		} else {
			$input = array_except($input, array('password'));
		}

		//START - Purpose : Set Email notification status - DEVTASK-4359
		$input['mail_notification'] = 0;
		if(isset($request->email_notification_chkbox))
		{
			if($request->email_notification_chkbox == 1)
				$input['mail_notification'] = 1;
		}

		if($request->notification_mail_id != ''){
			EmailNotificationEmailDetails::updateOrCreate(
				["user_id" => $id],
				["emails" => $request->notification_mail_id]
			);
		}
		//END - DEVTASK-4359

		$user = User::find($id);
		$user->update($input);

		if ($request->customer != NULL && $request->customer[0] != '') {
			$user->customers()->sync($request->customer);
		}

		//		if (!$user->hasRole('Products Lister') && in_array('Products Lister', $request->roles)) {
		//			$requestData = new Request();
		//			$requestData->setMethod('POST');
		//			$requestData->request->add(['amount_assigned' => 100]);
		//
		//			$this->assignProducts($requestData, Auth::id());
		//		}

		$user->roles()->sync($request->input('roles'));
		$user->permissions()->sync($request->input('permissions'));

		$user->listing_approval_rate = $request->get('listing_approval_rate') ?? '0';
		$user->listing_rejection_rate = $request->get('listing_rejection_rate') ?? '0';
		$user->save();
		
		if($request->webhook && isset($request->webhook['url']) && isset($request->webhook['payload'])){
			WebhookNotification::updateOrCreate([
				'user_id' => $user->id
			], $request->webhook);
		}

		$userRate = new UserRate();
		$userRate->start_date = Carbon::now();
		$userRate->hourly_rate = $hourly_rate;
		$userRate->currency = $currency;
		$userRate->user_id = $user->id;
		$userRate->save();


		return redirect()->back()
			->with('success', 'User updated successfully');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$user = User::find($id);

		// NotificationQueue::where('sent_to', $user->id)->orWhere('user_id', $user->id)->delete();
		// PushNotification::where('sent_to', $user->id)->orWhere('user_id', $user->id)->delete();

		$user->delete();

		return redirect()->route('users.index')
			->with('success', 'User deleted successfully');
	}

	public function unassignProducts(Request $request, $id)
	{
		$user = User::find($id);

		$userProducts = UserProduct::where('user_id', $user->id)->pluck('product_id')->toArray();


		$products = Product::whereIn('id', $userProducts)->where('is_approved', 0)->where('is_listing_rejected', 0)->take($request->get('number') ?? 0)->get();

		foreach ($products as $product) {
			UserProduct::where('user_id', $user->id)->where('product_id', $product->id)->delete();
		}

		return redirect()->back()->with('success', 'Product unassigned successfully!');
	}

	public function showAllAssignedProductsForUser($id)
	{
		$userProducts = UserProduct::where('user_id', $id)->with('product')->orderBy('created_at', 'DESC')->get();

		$user = User::find($id);

		return view('products.assigned_products_list_by_user', compact('userProducts', 'user'));
	}

	public function assignProducts(Request $request, $id)
	{
		$user = User::find($id);
		$amount_assigned = 25;

		$products = Product::where('stock', '>=', 1)
			->where('is_crop_ordered', 1)
			->where('is_order_rejected', 0)
			->where('is_approved', 0)
			->where('is_listing_rejected', 0)
			->where('isUploaded', 0)
			->where('isFinal', 0);

		$user_products = UserProduct::pluck('product_id')->toArray();

		$products = $products->whereNotIn('id', $user_products)
			->whereIn('category', [5, 6, 7, 9, 11, 21, 22, 23, 24, 25, 26, 29, 34, 36, 37, 52, 53, 54, 55, 56, 57, 58, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 76, 78, 79, 80, 81, 83, 84, 85, 87, 97, 98, 99, 100, 105, 109, 110, 111, 114, 117, 118])
			->orderBy('is_on_sale', 'DESC')
			->latest()
			->take($amount_assigned)
			->get();

		$user->products()->attach($products);

		if (count($products) >= $amount_assigned - 1) {
			$message = 'You have successfully assigned ' . count($products) . ' products';
			return redirect()->back()->with('success', $message);
		}

		$remaining = $amount_assigned - count($products);

		$products = Product::where('stock', '>=', 1)
			->where('is_crop_ordered', 1)
			->where('is_order_rejected', 0)
			->where('is_listing_rejected', 0)
			->where('is_approved', 0)
			->where('isUploaded', 0)
			->where('isFinal', 0);

		$user_products = UserProduct::pluck('product_id')->toArray();

		$products = $products->whereNotIn('id', $user_products)->orderBy('is_on_sale', 'DESC')->latest()->take($remaining)->get();
		$user->products()->attach($products);

		if (count($products) > 0) {
			$message = 'You have successfully assigned products';
		} else {
			$message = 'There were no products to assign!';
		}

		return redirect()->back()->withSuccess($message);
	}

	public function login(Request $request)
	{
		$date = $request->date ? $request->date : Carbon::now()->format('Y-m-d');
		$logins = UserLogin::whereBetween('login_at', [$date, Carbon::parse($date)->addDay()])->latest()->paginate(Setting::get('pagination'));

		return view('users.login', [
			'logins'	=> $logins,
			'date'		=> $date
		]);
	}

	public function activate(Request $request, $id)
	{
		$user = User::find($id);

		if ($user->is_active == 1) {
			$user->is_active = 0;
		} else {
			$user->is_active = 1;
		}

		$user->save();

		return redirect()->back()->withSuccess('You have successfully updated the user!');
	}

	private function printLastQuery()
	{
		DB::enableQueryLog();
		$query = DB::getQueryLog();
		$query = end($query);
		print_r($query);
	}


	public function payments(Request $request)
	{

		$params = $request->all();

		$date = new DateTime();

		if (isset($params['year']) && isset($params['week'])) {
			$year = $params['year'];
			$week = $params['week'];
		} else {
			$week = $date->format("W");
			$year = $date->format("Y");
		}

		$result = getStartAndEndDate($week, $year);
		$start = $result['week_start'];
		$end = $result['week_end'];

		$users = User::join('hubstaff_payment_accounts as hpa',"hpa.user_id","users.id")->with(['currentRate'])->get();
		$usersRatesThisWeek = UserRate::ratesForWeek($week, $year);

		$usersRatesPreviousWeek = UserRate::latestRatesForWeek($week - 1, $year);

		$activitiesForWeek = HubstaffActivity::getActivitiesForWeek($week, $year);

		$paymentsDone = Payment::getConsidatedUserPayments();

		$amountToBePaid = HubstaffPaymentAccount::getConsidatedUserAmountToBePaid();

		

		$now = now();

		foreach ($users as $user) {

			$user->secondsTracked = 0;
			$user->currency = '-';
			$user->total = 0;

			

			$userPaymentsDone = 0;
			$userPaymentsDoneModel = $paymentsDone->first(function ($value) use($user) {
				return $value->user_id == $user->id;
			});

			if($userPaymentsDoneModel){
				$userPaymentsDone = $userPaymentsDoneModel->paid;
			}

			$userPaymentsToBeDone = 0;
			$userAmountToBePaidModel = $amountToBePaid->first(function ($value) use($user){
				return $value->user_id == $user->id;
			});

			if($userAmountToBePaidModel){
				$userPaymentsToBeDone = $userAmountToBePaidModel->amount;
			}

			$user->balance = $userPaymentsToBeDone - $userPaymentsDone;

			

			//echo $user->id. ' '.$userPaymentsToBeDone. ' '. $userPaymentsDone. PHP_EOL;


			$invidualRatesPreviousWeek  = $usersRatesPreviousWeek->first(function ($value, $key) use ($user) {
				return $value->user_id == $user->id;
			});




			$weekRates = [];

			if ($invidualRatesPreviousWeek) {
				$weekRates[] = array(
					'start_date' => $start,
					'rate' => $invidualRatesPreviousWeek->hourly_rate,
					'currency' => $invidualRatesPreviousWeek->currency
				);
			}

			$rates = $usersRatesThisWeek->filter(function ($value, $key) use ($user) {
				return $value->user_id == $user->id;
			});

			if ($rates) {

				foreach ($rates as $rate) {
					$weekRates[] = array(
						'start_date' => $rate->start_date,
						'rate' => $rate->hourly_rate,
						'currency' => $rate->currency
					);
				}
			}


			usort($weekRates, function ($a, $b) {
				return strtotime($a['start_date']) - strtotime($b['start_date']);
			});


			if (sizeof($weekRates) > 0) {
				$lastEntry = $weekRates[sizeof($weekRates) - 1];

				$weekRates[] = array(
					'start_date' => $end,
					'rate' => $lastEntry['rate'],
					'currency' => $lastEntry['currency']
				);

				$user->currency = $lastEntry['currency'];
			}

			$activities = $activitiesForWeek->filter(function ($value, $key) use ($user) {
				return $value->system_user_id === $user->id;
			});

			$user->trackedActivitiesForWeek = $activities;

			foreach ($activities as $activity) {
				$user->secondsTracked += $activity->tracked;
				$i = 0;
				while ($i < sizeof($weekRates) - 1) {

					$start = $weekRates[$i];
					$end = $weekRates[$i + 1];

					if ($activity->starts_at >= $start['start_date'] && $activity->start_time < $end['start_date']) {
						// the activity needs calculation for the start rate and hence do it
						$earnings = $activity->tracked * ($start['rate'] / 60 / 60);
						$activity->rate = $start['rate'];
						$activity->earnings = $earnings;
						$user->total += $earnings;
						break;
					}
					$i++;
				}
			}
		}

		//exit;
		$paymentMethods = array();
		foreach (PaymentMethod::all() as $paymentMethod) {
			$paymentMethods[$paymentMethod->id] = $paymentMethod->name;
		}

		return view(
			'users.payments',
			[
				'users' => $users,
				'selectedYear' => $year,
				'selectedWeek' => $week,
				'paymentMethods' => $paymentMethods
			]
		);
	}

	public function makePayment(Request $request)
	{
		$this->validate($request, [
			'amount' => 'required|numeric|min:1',
			'payment_method' => 'required',
			'currency' => 'required'
		]);

		$parameters = $request->all();


		$paymentMethod = PaymentMethod::firstOrCreate([
			'name' => $parameters['payment_method']
		]);

		$payment = new Payment;
		$payment->user_id = $parameters['user_id'];
		$payment->amount = $parameters['amount'];
		$payment->currency = $parameters['currency'];
		$payment->note = $parameters['note'];
		$payment->payment_method_id = $paymentMethod->id;
		$payment->save();

		return redirect('/hubstaff/payments')->withSuccess('Payment saved!');
	}

	public function checkUserLogins()
	{
		Log::channel('customer')->info(Carbon::now() . " begin checking users logins");
		$users = User::all();

		foreach ($users as $user) {
			if ($login = UserLogin::where('user_id', $user->id)->where('created_at', '>', Carbon::now()->format('Y-m-d'))->latest()->first()) {
			} else {
				$login = UserLogin::create(['user_id'	=> $user->id]);
			}

			if (Cache::has('user-is-online-' . $user->id)) {
				if ($login->logout_at) {
					UserLogin::create(['user_id'	=> $user->id, 'login_at'	=> Carbon::now()]);
				} else if (!$login->login_at) {
					$login->update(['login_at'	=> Carbon::now()]);
				}
			} else {
				if ($login->created_at && !$login->logout_at) {
					$login->update(['logout_at'	=> Carbon::now()]);
				}
			}
		}

		Log::channel('customer')->info(Carbon::now() . " end of checking users logins");
	}

	public function searchUser(Request $request)
	{
		$q = $request->input('q');

		$results = User::select('id', 'name')
			->orWhere('name', 'LIKE', '%' . $q . '%')
			->offset(0)
			->limit(15)
			->get();

		return $results;
	}

	public function loginIps(Request $request)
	{
		$user_ips = UserLoginIp::join('users', 'user_login_ips.user_id', '=', 'users.id')
						->select('user_login_ips.*', 'users.email')
						->latest()
						->get();
		if ($request->ajax()) {
			return response()->json( ["code" => 200 , "data" => $user_ips] );
		}else{
			return view('users.ips', compact('user_ips'));
		} 	
		
	}

	public function addSystemIp(Request $request){
		if($request->ip){
			
			$shell_cmd = shell_exec("bash " . getenv('DEPLOYMENT_SCRIPTS_PATH'). "/webaccess-firewall.sh -f add -i ".$request->ip." -c ".$request->get("comment",""));

			UserSysyemIp::create([
				'index_txt'  => $shell_cmd['index']??'null',
				'ip'         => $request->ip,
				'user_id'    => $request->user_id??null,
				'other_user_name' => $request->other_user_name??null,
				'notes'      => $request->comment??null,
			]);

			return response()->json( ["code" => 200 , "data" => "Success"] );
		}
		return response()->json( ["code" => 500 , "data" => "Error occured!"] );
	}

	public function deleteSystemIp(Request $request){
		if($request->usersystemid){
			
			$row = UserSysyemIp::where('id',$request->usersystemid)->first();

			shell_exec("bash " . getenv('DEPLOYMENT_SCRIPTS_PATH'). "/webaccess-firewall.sh -f delete -n ".$row->index??'');
	
			$row->delete();			
		
			return response()->json( ["code" => 200 , "data" => "Success"] );
		}
		return response()->json( ["code" => 500 , "data" => "Error occured!"] );
	}

	public function statusChange(Request $request)
	{
		if($request->status){
			$user_ip_status = UserLoginIp::where('id',$request->id)->get();
			if($request->status == 'Active'){
				$user_ip_status->is_status = UserLoginIp::where('id',$request->id)
														->update(['is_active' => true]);
			}else{
				$user_ip_status->is_status = UserLoginIp::where('id',$request->id)
														->update(['is_active' => false]);
			}
		}
		return $request->status;
	}
}
