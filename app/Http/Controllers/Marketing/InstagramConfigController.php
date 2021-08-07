<?php

namespace App\Http\Controllers\Marketing;

use App\ImQueue;
use App\Marketing\InstagramConfig;
use App\Http\Controllers\Controller;
use App\Services\Whatsapp\ChatApi\ChatApi;
use Illuminate\Http\Request;
use App\Setting;
use Validator;
use Crypt;
use Response;
use App\Customer;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use App\CompetitorPage;

class InstagramConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->number || $request->username || $request->provider || $request->customer_support || $request->customer_support == 0 || $request->term || $request->date) {

            $query = InstagramConfig::query();

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

            $instagramConfigs = $query->orderby('id', 'desc')->paginate(Setting::get('pagination'));

        } else {
            $instagramConfigs = InstagramConfig::latest()->paginate(Setting::get('pagination'));
        }

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('marketing.instagram-configs.partials.data', compact('instagramConfigs'))->render(),
                'links' => (string)$instagramConfigs->render()
            ], 200);
        }

        $competitors = CompetitorPage::select('id','name')->where('platform', 'instagram')->get();

        return view('marketing.instagram-configs.index', [
            'instagramConfigs' => $instagramConfigs,
            'competitors' => $competitors,
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
            'number' => 'required|max:13|unique:whatsapp_configs,number',
            'provider' => 'required',
            'customer_support' => 'required',
            'username' => 'required|min:3|max:255',
            'password' => 'required|min:6|max:255',
            'frequency' => 'required',
            'send_start' => 'required',
            'send_end' => 'required',
        ]);

        $data = $request->except('_token');
        $data['password'] = Crypt::encrypt($request->password);
        $data['is_customer_support'] = $request->customer_support;

        InstagramConfig::create($data);

        return redirect()->back()->withSuccess('You have successfully stored Whats App Config');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\InstagramConfig $InstagramConfig
     * @return \Illuminate\Http\Response
     */
    public function show(InstagramConfig $InstagramConfig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\InstagramConfig $InstagramConfig
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        $this->validate($request, [
            'number' => 'required|max:13',
            'provider' => 'required',
            'customer_support' => 'required',
            'username' => 'required|min:3|max:255',
            'password' => 'required|min:6|max:255',
            'frequency' => 'required',
            'send_start' => 'required',
            'send_end' => 'required',
        ]);
        $config = InstagramConfig::findorfail($request->id);
        $data = $request->except('_token', 'id');
        $data['password'] = Crypt::encrypt($request->password);
        $data['is_customer_support'] = $request->customer_support;
        $config->update($data);

        return redirect()->back()->withSuccess('You have successfully changed Instagram Config');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\InstagramConfig $InstagramConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InstagramConfig $InstagramConfig)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\InstagramConfig $InstagramConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $config = InstagramConfig::findorfail($request->id);
        $config->delete();
        return Response::json(array(
            'success' => true,
            'message' => 'Instagram Config Deleted'
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
        $term = $request->term;
        $date = $request->date;
        $config = InstagramConfig::find($id);
        $number = $config->number;
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

        return view('marketing.instagram-configs.history', compact('data', 'id', 'term', 'date', 'number', 'provider'));
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

        $term = $request->term;
        $date = $request->date;
        $config = InstagramConfig::find($id);
        $number = $config->number;
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
        return view('marketing.instagram-configs.queue', compact('data', 'id', 'term', 'date', 'number', 'provider'));
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
            'message' => 'Instagram Config Deleted'
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
            'message' => 'Instagram Configs Deleted'
        ));
    }

   
    
}
