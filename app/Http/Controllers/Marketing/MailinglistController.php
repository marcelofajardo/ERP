<?php

namespace App\Http\Controllers\Marketing;

use App\Customer;
use App\CustomerMarketingPlatform;
use App\Mailinglist;
use App\MailinglistEmail;
use App\MailingRemark;
use App\Service;
use App\StoreWebsite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MailinglistController extends Controller
{
    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
		$services = Service::all();
        $list = Mailinglist::paginate(15);
        $websites = StoreWebsite::select('id','title')->orderBy('id','desc')->get();
        return view('marketing.mailinglist.index', compact('services', 'list','websites'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $website_id = $request->website_id;
        //FInd Service 
        $service = Service::find($request->service_id);

        if($service){
            //dd($service->name);
            if (strpos(strtolower($service->name), strtolower('SendInBlue')) !== false) {
                $curl = curl_init();
                $data = [
                    "folderId" => 1,
                    "name" => $request->name
                ];
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/lists",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => array(
                        // "api-key: ".getenv('SEND_IN_BLUE_API'),
                        "api-key: ".config('env.SEND_IN_BLUE_API'),
                        "Content-Type: application/json"
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                \Log::info($response);
                $res = json_decode($response);
                

                Mailinglist::create([
                    'id' => $res->id,
                    'name' => $request->name,
                    'website_id' => $website_id,
                    'service_id' => $request->service_id,
                    'remote_id' => $res->id,
                ]);
            
            }

            if (strpos($service->name, 'AcelleMail') !== false) {

                
                $curl = curl_init();

                curl_setopt_array($curl, array(
                //   CURLOPT_URL => "http://165.232.42.174/api/v1/lists?api_token=".getenv('ACELLE_MAIL_API_TOKEN'),
                CURLOPT_URL => "http://165.232.42.174/api/v1/lists?api_token=".config('env.ACELLE_MAIL_API_TOKEN'),
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => array('contact[company]' => '.','contact[state]' => 'afdf','name' => $request->name,'from_email' => $request->email,'from_name' => 'dsfsd','contact[address_1]' => 'af','contact[country_id]' => '219','contact[city]' => 'sdf','contact[zip]' => 'd','contact[phone]' => 'd','contact[email]' => $request->email),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $res = json_decode($response);
                if($res->status == 1){
                    //getting last id
                    $list = Mailinglist::orderBy('id','desc')->first();
                    if($list){
                        $id = ($list->id + 1);
                    }else{
                        $id = 1;
                    }
                    Mailinglist::create([
                        'id' => $id,
                        'name' => $request->name,
                        'website_id' => $website_id,
                        'email' => $request->email,
                        'service_id' => $request->service_id,
                        'remote_id' => $res->list_uid,
                    ]); 
                    return response()->json(true);
                }
                

                
            }



        }else{
             return response()->json(false);
        }
            
        

        return response()->json(true);
    }

    /**
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, Request $request)
    {
        $customers = Customer::whereNotNull('email');
        if (!is_null($request->term)) {
            $customers = $customers->where('email', 'LIKE', "%{$request->term}%");
        }
        //Total Result
        if (request('total') != null) {

            //search with date
            if (request('total') == 1 && request('customrange') != null) {
                $range = explode(' - ', request('customrange'));
                if ($range[0] == end($range)) {
                    $customers->whereHas('customerMarketingPlatformActive', function ($qu) use ($range) {
                        $qu->whereDate('created_at', end($range))->where('active', 1);
                    })->where('do_not_disturb', 0);
                } else {
                    $customers->whereHas('customerMarketingPlatformActive', function ($qu) use ($range) {
                        $qu->whereBetween('created_at', [$range[0], end($range)])->where('active', 1);
                    })->where('do_not_disturb', 0);
                }
            } elseif (request('total') == 1) {
                $customers->whereHas('customerMarketingPlatformActive', function ($qu) use ($request) {
                    $qu->where('active', 1);
                })->where('do_not_disturb', 0);
            }

            if (request('total') == 2 && request('customrange') != null) {
                $range = explode(' - ', request('customrange'));
                if ($range[0] == end($range)) {
                    $customers->doesntHave('customerMarketingPlatformActive')->whereDate('created_at', end($range))->where('do_not_disturb', 0);
                } else {
                    $customers->doesntHave('customerMarketingPlatformActive')->whereBetween('created_at', [$range[0], end($range)])->where('do_not_disturb', 0);
                }
            }

            if (request('total') == 2) {
                $customers->doesntHave('customerMarketingPlatformActive')->where('do_not_disturb', 0);
            }

            if (request('total') == 3 && request('customrange') != null) {
                $range = explode(' - ', request('customrange'));
                if ($range[0] == end($range)) {
                    $customers->where('do_not_disturb', 1)->whereDate('updated_at', end($range));
                } else {
                    $customers->where('do_not_disturb', 1)->whereBetween('updated_at', [$range[0], end($range)]);
                }
            } elseif (request('total') == 3) {
                $customers->where('do_not_disturb', 1);
            }

            if (request('total') == 4 && request('customrange') != null) {
                $range = explode(' - ', request('customrange'));
                if ($range[0] == end($range)) {

                    $customers->whereHas('leads', function ($qu) use ($range) {
                        $qu->whereDate('created_at', end($range));
                    });

                } else {
                    $customers->whereHas('leads', function ($qu) use ($range) {
                        $qu->whereBetween('created_at', [$range[0], end($range)]);
                    });
                }
            } elseif (request('total') == 4) {
                $customers->whereHas('leads');
            }

            if (request('total') == 5 && request('customrange') != null) {
                $range = explode(' - ', request('customrange'));
                if ($range[0] == end($range)) {

                    $customers->whereHas('orders', function ($qu) use ($range) {
                        $qu->whereDate('created_at', end($range));
                    });

                } else {
                    $customers->whereHas('orders', function ($qu) use ($range) {
                        $qu->whereBetween('created_at', [$range[0], end($range)]);
                    });
                }
            } elseif (request('total') == 5) {
                $customers->whereHas('orders');
            }

            if (request('total') == 6 && request('customrange') != null) {
                $range = explode(' - ', request('customrange'));
                if ($range[0] == end($range)) {

                    $customers->whereHas('customerMarketingPlatformActive', function ($qu) use ($range) {
                        $qu->where('active', 1);
                    })->where('broadcast_number', null)->whereDate('created_at', end($range));;

                } else {
                    $customers->whereHas('customerMarketingPlatformActive', function ($qu) use ($range) {
                        $qu->where('active', 1);
                    })->where('broadcast_number', null)->whereBetween('created_at', [$range[0], end($range)]);
                }
            } elseif (request('total') == 6) {
                $customers->whereHas('customerMarketingPlatformActive', function ($qu) use ($request) {
                    $qu->where('active', 1);
                })->where('broadcast_number', null)->where('do_not_disturb', 0);
            }

            if (request('total') == 7 && request('customrange') != null) {
                $range = explode(' - ', request('customrange'));
                if ($range[0] == end($range)) {
                    $customers->whereHas('notDelieveredImQueueMessage', function ($qu) use ($range) {
                        $qu->whereDate('send_after', end($range));
                    });
                } else {
                    $customers->whereHas('notDelieveredImQueueMessage', function ($qu) use ($range) {
                        $qu->whereBetween('send_after', [$range[0], end($range)]);
                    });
                }
            } elseif (request('total') == 7) {
                $customers->whereHas('notDelieveredImQueueMessage');
            }

        }
        
        if( !empty( $request->store_id ) ){
            $customers = $customers->where( 'store_website_id', $request->store_id );
        }

        $customers = $customers->select('email', 'id', 'name', 'do_not_disturb','source')->paginate(20);
        $list = Mailinglist::where('remote_id', $id)->with('listCustomers')->first();

        $contacts = $list->listCustomers->pluck('id')->toArray();

        $countDNDCustomers = Customer::where('do_not_disturb', '1')->count();

        return view('marketing.mailinglist.show', compact('customers', 'id', 'contacts', 'list', 'countDNDCustomers'));
    }


    /**
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $services = Service::all();
        $websites = StoreWebsite::select('id','title')->orderBy('id','desc')->get();
        $list = Mailinglist::where('remote_id',$id)->first();
        return view('marketing.mailinglist.edit', compact('list','services','websites'));
    }

    /**
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        $mailing_list = Mailinglist::find($id);
        $mailing_list->website_id = $request->website_id;
        $mailing_list->service_id = $request->service_id;
        $mailing_list->name = $request->name;
        $mailing_list->email = $request->email;
        $mailing_list->save();
        return response()->json(true);
    }


    /**
     * @param $id
     * @param $email
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToList($id, $email)
    {
        //getting mailing list 
        $list = Mailinglist::where('remote_id',$id)->first();

        if($list->service && isset($list->service->name) ){
            if($list->service->name == 'AcelleMail'){
                // $url = "http://165.232.42.174/api/v1/subscribers/email/'.$email.'?api_token=".getenv('ACELLE_MAIL_API_TOKEN');
                $url = "http://165.232.42.174/api/v1/subscribers/email/'.$email.'?api_token=".config('env.ACELLE_MAIL_API_TOKEN');
                $headers = array('Content-Type: application/json');
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($curl);
                curl_close($curl);
                $res = json_decode($response);
                if($res->subscribers){
                    foreach ($res->subscribers as $subscriber) {
                        if($subscriber->list_uid == $id){
                            return response()->json(['status' => 'success']);
                        }
                    }
                }

                //Assign Customer to list

                $curl = curl_init();

                curl_setopt_array($curl, array(
                //   CURLOPT_URL => "http://165.232.42.174/api/v1/lists/".$id."/subscribers/store?api_token=".getenv('ACELLE_MAIL_API_TOKEN'),
                CURLOPT_URL => "http://165.232.42.174/api/v1/lists/".$id."/subscribers/store?api_token=".config('env.ACELLE_MAIL_API_TOKEN'),
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => array('EMAIL' => $email,'name' => ' '),
                ));

                $response = curl_exec($curl);

                $response = json_decode($response);
                //dd($response);
                //subscribe to emial
                // $url =  "http://165.232.42.174/api/v1/lists/".$id."/subscribers/".$response->subscriber_uid."/subscribe?api_token=".getenv('ACELLE_MAIL_API_TOKEN');
                $url =  "http://165.232.42.174/api/v1/lists/".$id."/subscribers/".$response->subscriber_uid."/subscribe?api_token=".config('env.ACELLE_MAIL_API_TOKEN');
                $headers = array('Content-Type: application/json');
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($curl);
                
                $customer = Customer::where('email', $email)->first();
                \DB::table('list_contacts')->where('customer_id', $customer->id)->delete();
                $list->listCustomers()->attach($customer->id);
                return response()->json(['status' => 'success']);
            }    
        }
        

        $curl = curl_init();
        $data = [
            "email" => $email,
            "listIds" => [intval($id)]
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendinblue.com/v3/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // "api-key: ".getenv('SEND_IN_BLUE_API'),
                "api-key: ".config('env.SEND_IN_BLUE_API'),
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);

        if (isset($res->message)) {
            if($res->message == 'Contact already exist'){
                $curl3 = curl_init();
                curl_setopt_array($curl3, array(
                    CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/".$email,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "DELETE",
                    CURLOPT_HTTPHEADER => array(
                        // "api-key: ".getenv('SEND_IN_BLUE_API'),
                        "api-key: ".config('env.SEND_IN_BLUE_API'),
                        "Content-Type: application/json"
                    ),
                ));
                $respw = curl_exec($curl3);
                curl_close($curl3);
                $respw = json_decode($respw);

                $curl2 = curl_init();
                curl_setopt_array($curl2, array(
                    CURLOPT_URL => "https://api.sendinblue.com/v3/contacts",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => array(
                        // "api-key: ".getenv('SEND_IN_BLUE_API'),
                        "api-key: ".config('env.SEND_IN_BLUE_API'),
                        "Content-Type: application/json"
                    ),
                ));
                $resp = curl_exec($curl2);
                curl_close($curl2);
                $ress = json_decode($resp);
                if(isset($ress->message)){
                    return response()->json(['status' => 'error']);
                }
                $customer = Customer::where('email', $email)->first();
                $mailinglist = Mailinglist::find($id);
                \DB::table('list_contacts')->where('customer_id', $customer->id)->delete();
                $mailinglist->listCustomers()->attach($customer->id);

                return response()->json(['status' => 'success']);
            }
        } else {
            $customer = Customer::where('email', $email)->first();
            $mailinglist = Mailinglist::find($id);
            $mailinglist->listCustomers()->attach($customer->id);

            return response()->json(['status' => 'success']);
        }
    }

    /**
     * @param $email
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id, $email)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/" . $email,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                // "api-key: ".getenv('SEND_IN_BLUE_API'),
                "api-key: ".config('env.SEND_IN_BLUE_API'),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $res = json_decode($response);

        if (isset($res->message)) {
            return redirect()->back()->withErrors($res->message);
        } else {
            $customer = Customer::where('email', $email)->first();
            $mailinglist = Mailinglist::find($id);
            $mailinglist->listCustomers()->detach($customer->id);

            return response()->json(['status' => 'success']);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteList($id)
    {
        //getting mailing list 
        $list = Mailinglist::where('remote_id',$id)->first();
        
        if($list->service && isset($list->service->name) ){
            if($list->service->name == 'AcelleMail'){
                
                $curl = curl_init();

                curl_setopt_array($curl, array(
                //   CURLOPT_URL => "http://165.232.42.174/api/v1/lists/".$list->remote_id."/delete?api_token=".getenv('ACELLE_MAIL_API_TOKEN'),
                CURLOPT_URL => "http://165.232.42.174/api/v1/lists/".$list->remote_id."/delete?api_token=".config('env.ACELLE_MAIL_API_TOKEN'),
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => [],
                ));

                $res = curl_exec($curl);

                curl_close($curl);
            }else{
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/lists/" . $id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "DELETE",
                    CURLOPT_HTTPHEADER => array(
                        // "api-key: ".getenv('SEND_IN_BLUE_API'),
                        "api-key: ".config('env.SEND_IN_BLUE_API'),
                        "Content-Type: application/json"
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $res = json_decode($response);
            }
        

            if (isset($res->message)) {
                return redirect()->back()->with('error',$res->message);
            } else {
                Mailinglist::where('remote_id', $id)->delete();
                return redirect()->back()->with('success', 'Removed successfully.');
            }
        }
    }

    /**
     *
     */
    public function addRemark(Request $request)
    {
        $remark = $request->input('remark');
        $id = $request->input('id');
        MailingRemark::create([
            'customer_id' => $id,
            'text' => $remark,
            'user_name' => \Auth::user()->name,
            'user_id' => \Auth::user()->id,
        ]);
        return response()->json(['remark' => $remark], 200);
    }

    public function getBroadCastRemark(Request $request)
    {
        $id = $request->input('id');

        $remark = MailingRemark::where('customer_id', $id)->whereNotNull('text')->get();

        return response()->json($remark, 200);
    }

    public function addManual(Request $request)
    {
        $email = $request->email;
        $id = $request->id;
        $curl = curl_init();
        $data = [
            "email" => $email,
            "listIds" => [intval($id)]
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendinblue.com/v3/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // "api-key: ".getenv('SEND_IN_BLUE_API'),
                "api-key: ".config('env.SEND_IN_BLUE_API'),
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);

        if (isset($res->message)) {
            if($res->message == 'Contact already exist'){
                $curl3 = curl_init();
                curl_setopt_array($curl3, array(
                    CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/".$email,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "DELETE",
                    CURLOPT_HTTPHEADER => array(
                        // "api-key: ".getenv('SEND_IN_BLUE_API'),
                        "api-key: ".config('env.SEND_IN_BLUE_API'),
                        "Content-Type: application/json"
                    ),
                ));
                $respw = curl_exec($curl3);
                curl_close($curl3);
                $respw = json_decode($respw);

                $curl2 = curl_init();
                curl_setopt_array($curl2, array(
                    CURLOPT_URL => "https://api.sendinblue.com/v3/contacts",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => array(
                        // "api-key: ".getenv('SEND_IN_BLUE_API'),
                        "api-key: ".config('env.SEND_IN_BLUE_API'),
                        "Content-Type: application/json"
                    ),
                ));
                $resp = curl_exec($curl2);
                curl_close($curl2);
                $ress = json_decode($resp);
                if(isset($ress->message)){
                    return response()->json(['status' => 'error']);
                }
                $customer = Customer::where('email', $email)->first();
                $mailinglist = Mailinglist::find($id);
                \DB::table('list_contacts')->where('customer_id', $customer->id)->delete();
                $mailinglist->listCustomers()->attach($customer->id);

                return response()->json(['status' => 'success']);
            }
        } else {
            $customer = Customer::where('email', $email)->first();
            $mailinglist = Mailinglist::find($id);
            $mailinglist->listCustomers()->attach($customer->id);

            return response()->json(['status' => 'success']);
        }
    }

    /**
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateCustomerSource($id, Request $request)
    {
        $customer = Customer::find($id);
        $customer->source = $request->source;
        $customer->save();
        return response()->json(true);
    }
}
