<?php

namespace App\Http\Controllers\Marketing;

use App\Customer;
use App\Http\Controllers\Controller;
use App\ImQueue;
use App\Marketing\WhatsappConfig;
use App\Notification;
use App\Services\Whatsapp\ChatApi\ChatApi;
use App\Setting;
use App\StoreWebsite;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Response;

class WhatsappConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->number || $request->username || $request->provider || $request->customer_support || $request->customer_support == 0 || $request->term || $request->date) {

            $query = WhatsappConfig::query();

            //Added store data to put dropdown in form  to add store website id to whatsapp config table
            $storeData = StoreWebsite::all()->toArray();

            //global search term
            if (request('term') != null) {
                $query->where('number', 'LIKE', "%{$request->term}%")
                    ->orWhere('username', 'LIKE', "%{$request->term}%")
                    ->orWhere('password', 'LIKE', "%{$request->term}%")
                    ->orWhere('provider', 'LIKE', "%{$request->term}%");
            }

            if (request('date') != null) {
                $query->whereDate('created_at', request('website'));
            }

            //if number is not null
            if (request('number') != null) {
                $query->where('number', 'LIKE', '%' . request('number') . '%');
            }

            //If username is not null
            if (request('username') != null) {
                $query->where('username', 'LIKE', '%' . request('username') . '%');
            }

            //if provider with is not null
            if (request('provider') != null) {
                $query->where('provider', 'LIKE', '%' . request('provider') . '%');
            }

            //if provider with is not null
            if (request('customer_support') != null) {
                $query->where('is_customer_support', request('customer_support'));
            }

            $whatsAppConfigs = $query->orderby('id', 'desc')->paginate(Setting::get('pagination'));

        } else {
            $whatsAppConfigs = WhatsappConfig::latest()->paginate(Setting::get('pagination'));
        }

        //Fetch Store Details

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('marketing.whatsapp-configs.partials.data', compact('whatsAppConfigs', 'storeData'))->render(),
                'links' => (string) $whatsAppConfigs->render(),
            ], 200);
        }

        return view('marketing.whatsapp-configs.index', [
            'whatsAppConfigs' => $whatsAppConfigs,
            'storeData'       => $storeData,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'number'           => 'required|max:13|unique:whatsapp_configs,number',
            'provider'         => 'required',
            'customer_support' => 'required',
            'username'         => 'required|min:3|max:255',
            //'password'         => 'required|min:6|max:255',
            'frequency'        => 'required',
            'send_start'       => 'required',
            'send_end'         => 'required',
        ]);
        $requestData = $request->all();
        $defaultFor  = implode(",", isset($requestData['default_for']) ? $requestData['default_for'] : []);

        $data                        = $request->except('_token', 'default_for');
        //$data['password']            = Crypt::encrypt($request->password);
        $data['is_customer_support'] = $request->customer_support;
        $data['default_for']         = $defaultFor;
        WhatsappConfig::create($data);


        \Artisan::call('config:clear');


        return redirect()->back()->withSuccess('You have successfully stored Whats App Config');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\WhatsappConfig $whatsAppConfig
     * @return \Illuminate\Http\Response
     */
    public function show(WhatsappConfig $whatsAppConfig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\WhatsappConfig $whatsAppConfig
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        $this->validate($request, [
            'number'           => 'required|max:13',
            'provider'         => 'required',
            'customer_support' => 'required',
            'username'         => 'required|min:3|max:255',
            //'password'         => 'required|min:6|max:255',
            'frequency'        => 'required',
            'send_start'       => 'required',
            'send_end'         => 'required',
        ]);
        $config = WhatsappConfig::findorfail($request->id);

        $requestData = $request->all();

        $defaultFor = implode(",", isset($requestData['default_for']) ? $requestData['default_for'] : []);

        $data                        = $request->except('_token', 'id', 'default_for');
        //$data['password']            = Crypt::encrypt($request->password);
        $data['is_customer_support'] = $request->customer_support;
        $data['default_for']         = $defaultFor;

        $config->update($data);

        \Artisan::call('config:clear');

        return redirect()->back()->withSuccess('You have successfully changed Whats App Config');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\WhatsappConfig $whatsAppConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WhatsappConfig $whatsAppConfig)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\WhatsappConfig $whatsAppConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $config = WhatsappConfig::findorfail($request->id);
        $config->delete();

        \Artisan::call('config:clear');

        return Response::json(array(
            'success' => true,
            'message' => 'WhatsApp Config Deleted',
        ));
    }

    /**
     * Show history page
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history($id, Request $request)
    {
        $term     = $request->term;
        $date     = $request->date;
        $config   = WhatsappConfig::find($id);
        $number   = $config->number;
        $provider = $config->provider;

        if ($config->provider === 'py-whatsapp') {

            $data = ImQueue::whereNotNull('sent_at')->where('number_from', $config->number)->orderBy('sent_at', 'desc');
            if (request('term') != null) {
                $data = $data->where('number_to', 'LIKE', "%{$request->term}%");
                $data = $data->orWhere('text', 'LIKE', "%{$request->term}%");
                $data = $data->orWhere('priority', 'LIKE', "%{$request->term}%");
            }
            if (request('date') != null) {
                $data = $data->whereDate('send_after', request('date'));
            }
            $data = $data->get();
        } elseif ($config->provider === 'Chat-API') {
            $data = ChatApi::chatHistory($config->number);
        }

        return view('marketing.whatsapp-configs.history', compact('data', 'id', 'term', 'date', 'number', 'provider'));
    }

    /**
     * Show queue page
     *
     * @param $id
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function queue($id, Request $request)
    {

        $term     = $request->term;
        $date     = $request->date;
        $config   = WhatsappConfig::find($id);
        $number   = $config->number;
        $provider = $config->provider;
        if ($config->provider === 'py-whatsapp') {

            $data = ImQueue::whereNull('sent_at')->with('marketingMessageTypes')->where('number_from', $config->number)->orderBy('created_at', 'desc');
            if (request('term') != null) {
                $data = $data->where('number_to', 'LIKE', "%{$request->term}%");
                $data = $data->orWhere('text', 'LIKE', "%{$request->term}%");
                $data = $data->orWhere('priority', 'LIKE', "%{$request->term}%");
            }
            if (request('date') != null) {
                $data = $data->whereDate('send_after', request('date'));
            }
            $data = $data->get();
        } elseif ($config->provider === 'Chat-API') {
            $data = ChatApi::chatQueue($config->number);

        }
/*        dd($data);*/
        return view('marketing.whatsapp-configs.queue', compact('data', 'id', 'term', 'date', 'number', 'provider'));
    }

    /**
     *
     * Delete all queues from Chat-Api
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */

    public function clearMessagesQueue($id)
    {
        $config = WhatsappConfig::find($id);
        $data   = ChatApi::deleteQueues($config->number);

        return redirect('/marketing/whatsapp-config');
    }

    /**
     * Delete single queue
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyQueue(Request $request)
    {

        $config = ImQueue::findorfail($request->id);
        $config->delete();
        return Response::json(array(
            'success' => true,
            'message' => 'WhatsApp Config Deleted',
        ));

    }

    /**
     * Delete all queues from Whatsapp
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyQueueAll(Request $request)
    {
        $config = ImQueue::where('number_from', $request->id)->delete();
        return Response::json(array(
            'success' => true,
            'message' => 'WhatsApp Configs Deleted',
        ));
    }

    public function getBarcode(Request $request)
    {

        $id = $request->id;

        $whatsappConfig = WhatsappConfig::find($id);

        $ch = curl_init();

//        $url = env('WHATSAPP_BARCODE_IP').':'.$whatsappConfig->username.'/get-barcode';
        $url = 'http://136.244.118.102:81/get-barcode';

        if($whatsappConfig->is_use_own == 1) {
            $url = 'http://167.86.89.241:81/get-barcode?instanceId='.$whatsappConfig->instance_id;
        }


        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $barcode = $output;

        if ($barcode) {

            if ($barcode == 'No Barcode Available') {
                return Response::json(array('nobarcode' => true));
            }
            $content = base64_decode($barcode);

            $media = MediaUploader::fromString($content)->toDirectory('/barcode')->useFilename('barcode-' . Str::random(4))->upload();

            return Response::json(array('success' => true, 'media' => $media->getUrl()));
        } else {

            return Response::json(array('error' => true));
        }
    }

    public function getScreen(Request $request)
    {

        $id = $request->id;

        $whatsappConfig = WhatsappConfig::find($id);

        if ($whatsappConfig) {

            $ch = curl_init();

            if ($whatsappConfig->is_use_own == 1) {
                $url = "http://167.86.89.241:81/get-screen?instanceId=".$whatsappConfig->instance_id;
            } else {
                $url = env('WHATSAPP_BARCODE_IP') . $whatsappConfig->username . '/get-screen';
            }

            // set url
            curl_setopt($ch, CURLOPT_URL, $url);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $output = curl_exec($ch);

            // close curl resource to free up system resources
            curl_close($ch);

            if ($whatsappConfig->is_use_own = 1) {
                $content = base64_decode($output);
            }else{
                $barcode = json_decode($output);
                if ($barcode->barcode == 'No Screen Available') {
                    return Response::json(array('nobarcode' => true));
                }
                $content = base64_decode($barcode->barcode);
            }

            $media = MediaUploader::fromString($content)->toDirectory('/barcode')->useFilename('screen'.uniqid(true))->upload();

            return Response::json(array('success' => true, 'media' => $media->getUrl()));

        }

        //if($barcode){

        // }else{

        //      return Response::json(array('error' => true));
        // }
    }

    public function deleteChromeData(Request $request)
    {
        $id = $request->id;

        $whatsappConfig = WhatsappConfig::find($id);

        $ch = curl_init();

        $url = env('WHATSAPP_BARCODE_IP') . ':' . $whatsappConfig->username . '/delete-chrome-data';

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $barcode = json_decode($output);

        if ($barcode) {

            if ($barcode->barcode == 'Directory Deleted') {
                return Response::json(array('nobarcode' => true));
            }
            return Response::json(array('success' => true, 'media' => 'Directory Can not be Deleted'));
        } else {

            return Response::json(array('error' => true));
        }
    }

    public function restartScript(Request $request)
    {
        $id = $request->id;

        $whatsappConfig = WhatsappConfig::find($id);

        $ch = curl_init();

        $url = env('WHATSAPP_BARCODE_IP') . $whatsappConfig->username . '/restart-script';

        if ($whatsappConfig->is_use_own == 1) { 
            $url = 'http://167.86.89.241:81/restart?instanceId='.$whatsappConfig->instance_id;
        }    

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $response = json_decode($output);

        if ($response) {

            if ($response->barcode == 'Process Killed') {
                return Response::json(array('nobarcode' => true));
            }
            return Response::json(array('success' => true, 'media' => 'No Process Found'));
        } else {

            return Response::json(array('error' => true));
        }
    }

    public function blockedNumber()
    {
        $whatsappNumbers = WhatsappConfig::where('status', 2)->get();

        foreach ($whatsappNumbers as $whatsappNumber) {

            $queues = ImQueue::where('number_from', $whatsappNumber->number)->whereNotNull('sent_at')->orderBy('sent_at', 'desc')->get();

            //Making DND for last 30 numbers
            $maxCount = 30;
            $count    = 0;
            //Making 30 customer numbers to DND
            foreach ($queues as $queue) {
                $customer = Customer::where('phone', $queue->number_to)->first();
                if ($count == $maxCount) {
                    break;
                }
                if (!empty($customer)) {
                    $customer->do_not_disturb = 1;
                    $customer->phone          = '-' . $customer->phone;
                    $customer->update();
                    $count++;
                }
            }

        }

        return Response::json(array('success' => true, 'message' => 'Last 30 Customer disabled'));
    }

    public function checkInstanceAuthentication()
    {
        //get all providers
        $allWhatsappInstances = WhatsappConfig::select()->where(['provider' => "Chat-API"])->get();
        try
        {
            foreach ($allWhatsappInstances as $instanceDetails) {
                $instanceId = $instanceDetails->instance_id;
                $token      = $instanceDetails->token;
                $sentTo     = 6;
                if ($instanceId) {
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL            => "https://api.chat-api.com/instance$instanceId/status?token=$token",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING       => "",
                        CURLOPT_MAXREDIRS      => 10,
                        CURLOPT_TIMEOUT        => 300,
                        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST  => "GET",
                        CURLOPT_HTTPHEADER     => array(
                            "content-type: application/json",
                            // "token: $wa_token"
                        ),
                    ));

                    $response = curl_exec($curl);
                    if (curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                    } else {
                        $resInArr = json_decode($response, true);
                        if (isset($resInArr) && isset($resInArr['accountStatus']) && $resInArr['accountStatus'] != 'authenticated') {
                            Notification::create([
                                'role'       => 'Whatsapp Config Proivders Authentication',
                                'message'    => "Current Status : " . $resInArr['accountStatus'],
                                'product_id' => '',
                                'user_id'    => $instanceDetails->id,
                                'sale_id'    => '',
                                'task_id'    => '',
                                'sent_to'    => $sentTo,
                            ]);
                        }
                    }
                    curl_close($curl);
                }
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public function logoutScript(Request $request)
    {
        $id = $request->id;
        $whatsappConfig = WhatsappConfig::find($id);
        $ch = curl_init();
        if ($whatsappConfig->is_use_own == 1) {
            $url = "http://167.86.89.241:83/logout?instanceId=".$whatsappConfig->instance_id;
            curl_setopt($ch, CURLOPT_URL, $url);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $output = curl_exec($ch);
            // close curl resource to free up system resources
            curl_close($ch);
            $response = json_decode($output);
            if ($response) {
                return Response::json(array('success' => true, 'message' => 'Logout Script called'));
            } else {
                return Response::json(array('error' => true));
            }
        }

        return Response::json(array('error' => true));
    }

    public function getStatusInfo(Request $request) 
    {
        $id = $request->id;
        $whatsappConfig = WhatsappConfig::find($id);
        $ch = curl_init();
        if ($whatsappConfig->is_use_own == 1) {
            $url = "http://167.86.89.241:81/get-status?instanceId=".$whatsappConfig->instance_id;
            curl_setopt($ch, CURLOPT_URL, $url);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $output = curl_exec($ch);
            // close curl resource to free up system resources
            curl_close($ch);
            if (!empty($output)) {
                return Response::json(array('success' => true, 'message' => $output));
            } else {
                return Response::json(array('error' => true));
            }
        }

        return Response::json(array('error' => true));
    }
}
