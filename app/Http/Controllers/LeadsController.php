<?php

namespace App\Http\Controllers;

use App\Category;
use App\Notification;
use App\Leads;
use App\Status;
use App\Setting;
use App\User;
use App\Brand;
use App\Product;
use App\Message;
use App\ChatMessage;
use App\Task;
use App\Image;
use App\AutoReply;
use App\Reply;
use App\Customer;
use App\StatusChange;
use App\CallRecording;
use App\ErpLeads;
use App\ErpLeadStatus;
use App\CommunicationHistory;
use App\ReplyCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Helpers;
use Validator;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use GuzzleHttp\Client as GuzzleClient;

use App\CallBusyMessage;
use App\MessageQueue;
use App\BroadcastImage;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('orderby') == '') {
            $orderby = 'asc';
        } else {
            $orderby = 'desc';
        }

        switch ($request->input('sortby')) {
            case 'client_name':
                $sortby = 'client_name';
                break;
            case 'city':
                $sortby = 'city';
                break;
            case 'assigned_user':
                $sortby = 'assigned_user';
                break;
            case 'rating':
                $sortby = 'rating';
                break;
            case 'communication':
                $sortby = 'communication';
                break;
            case 'status':
                $sortby = 'status';
                break;
            case 'created_at':
                $sortby = 'created_at';
                break;
            default:
                $sortby = 'communication';
        }

        $term = $request->input('term');
        $brand = $request->input('brand');
        $rating = $request->input('rating');

        $type = false;
        $leads = ((new Leads())->newQuery()->with('customer'));

        if ($request->type == 'multiple') {
            $type = true;
        }

        if ($request->brand[0] != null) {
            $implode = implode(',', $request->brand);
            $leads->where('multi_brand', 'LIKE', "%$implode%");

            $brand = $request->brand;
        }

        if ($request->rating[0] != null) {
            $leads->whereIn('rating', $request->rating);

            $rating = $request->rating;
        }

        $category = request()->get("multi_category", null);

        if (!is_null($category) && $category != '' && $category != 1) {
            $leads->where('multi_category', 'LIKE', '%"' . $category . '"%');
        }

        $status = request()->get("status", null);

        if (!is_null($status) && $status != '') {
            $leads->where('status', '=', $status);
        }

        if (helpers::getadminorsupervisor()) {
            if ($sortby != 'communication') {
                $leads = $leads->orderBy($sortby, $orderby);
            }
        } else {
            if (helpers::getmessagingrole()) {
                $leads = $leads->oldest();
            } else {
                $leads = $leads->oldest()->where('assigned_user', '=', Auth::id());
            }
        }
        if (!empty($term)) {
            $leads = $leads->whereHas('customer', function ($query) use ($term) {
                return $query->where('name', 'LIKE', "%$term%");
            })->where(function ($query) use ($term) {
                return $query
                    ->orWhere('client_name', 'like', '%' . $term . '%')
                    ->orWhere('id', 'like', '%' . $term . '%')
                    ->orWhere('contactno', $term)
                    ->orWhere('city', 'like', '%' . $term . '%')
                    ->orWhere('instahandler', $term)
                    ->orWhere('assigned_user', Helpers::getUserIdByName($term))
                    ->orWhere('assigned_user', Helpers::getUserIdByName($term))
                    ->orWhere('userid', Helpers::getUserIdByName($term))
                    ->orWhere('status', (new Status())->getIDCaseInsensitive($term));
            });
        }
        $leads_array = $leads->whereNull('deleted_at')->get()->toArray();
        if ($sortby == 'communication') {
            if ($orderby == 'asc') {
                $leads_array = array_values(array_sort($leads_array, function ($value) {
                    return $value['communication']['created_at'];
                }));

                $leads_array = array_reverse($leads_array);
            } else {
                $leads_array = array_values(array_sort($leads_array, function ($value) {
                    return $value['communication']['created_at'];
                }));
            }
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = Setting::get('pagination');
        $currentItems = array_slice($leads_array, $perPage * ($currentPage - 1), $perPage);

        $leads_array = new LengthAwarePaginator($currentItems, count($leads_array), $perPage, $currentPage);
        $leads = $leads->whereNull('deleted_at')->paginate(Setting::get('pagination'));

        if ($request->ajax()) {
            $html = view('leads.lead-item', ['leads_array' => $leads_array, 'leads' => $leads, 'orderby' => $orderby, 'term' => $term, 'brand' => http_build_query(['brand' => $brand]), 'rating' => http_build_query(['rating' => $rating]), 'type' => $type])->render();

            return response()->json(['html' => $html]);
        }

        $category_select = Category::attr(['name' => 'multi_category', 'class' => 'form-control select-multiple', 'id' => 'multi_category'])->selected()->renderAsDropdown();
        $status = array_flip((new status)->all());


        return view('leads.index', compact('leads', 'leads_array', 'term', 'orderby', 'brand', 'rating', 'type', 'category_select', 'status'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = new status;
        $data['status'] = $status->all();
        $users = User::oldest()->get()->toArray();
        $data['users'] = $users;
        $brands = Brand::oldest()->get()->toArray();
        $data['brands'] = $brands;
        $data['products_array'] = [];

        $data['category_select'] = Category::attr(['name' => 'multi_category', 'class' => 'form-control', 'id' => 'multi_category'])
            ->selected()
            ->renderAsDropdown();

        $customer_suggestions = [];
        $customers = (new Customer())->newQuery()
            ->latest()->select('name')->get()->toArray();

        foreach ($customers as $customer) {
            array_push($customer_suggestions, $customer['name']);
        }

        $data['customers'] = Customer::all();

        $data['customer_suggestions'] = $customer_suggestions;

        return view('leads.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $leads = $this->validate(request(), [
            'customer_id' => 'required',
            //          'contactno' => 'required',
            //          'city' => 'required',
            'instahandler' => '',
            'rating' => 'required',
            'status' => 'required',
            'solophone' => '',
            'comments' => '',
            'userid' => '',
            'address' => '',
            'multi_brand' => '',
            'email' => '',
            'source' => '',
            'assigned_user' => '',
            'selected_product',
            'size',
            'leadsourcetxt',
            'created_at' => 'required|date_format:"Y-m-d H:i"',
            'whatsapp_number'
        ]);

        $data = $request->except('_token');
        // dd($data);
        //
        // if ($customer = Customer::where('name', $data['client_name'])->first()) {
        //   $data['customer_id'] = $customer->id;
        // } else {
        //   $customer = new Customer;
        //   $customer->name = $data['client_name'];
        //
        //   $validator = Validator::make($data, [
        //     'contactno' => 'unique:customers,phone'
        //   ]);
        //
        //   if ($validator->fails()) {
        //     return back()->with('phone_error', 'The phone already exists')->withInput();
        //   }
        //   $customer->phone = $data['contactno'];
        //
        //   if ($data['source'] == 'instagram') {
        //     $customer->instahandler = $data['leadsourcetxt'];
        //   }
        //
        //   $customer->rating = $data['rating'];
        //   $customer->address = $data['address'];
        //   $customer->city = $data['city'];
        //
        //   $customer->save();
        //
        //   $data['customer_id'] = $customer->id;
        // }
        $customer = Customer::find($request->customer_id);

        $lead = null;
        if ($request->type == 'product-lead') {
            $brand_array = [];
            $category_array = [];

            foreach ($request->selected_product as $product_id) {
                $product = Product::find($product_id);

                //array_push($brand_array, $product->brand);
                //array_push($category_array, $product->category);
                $lead = \App\ErpLeads::create([
                    "customer_id"       => $request->customer_id,
                    "product_id"        => $product_id,
                    "brand_id"          => $product->brand,
                    "brand_segment"     => !empty($product->brands->brand_segment) ? $product->brands->brand_segment : '',
                    "category_id"       => $product->category,
                    "color"             => $product->color,
                    "size"              => $product->size_value,
                    "lead_status_id"    => 1
                ]);

                if ($request->hasfile('image')) {
                    foreach ($request->file('image') as $image) {
                        $media = MediaUploader::fromSource($image)->upload();
                        $lead->attachMedia($media, config('constants.media_tags'));
                    }
                }
            }

            //$data[ 'multi_brand' ] = $brand_array ? json_encode($brand_array) : null;
            //$data[ 'multi_category' ] = $category_array ? json_encode($category_array) : null;
        } else {
            $data['client_name'] = $customer->name;
            $data['contactno'] = $customer->phone;
            $data['userid'] = Auth::id();
            $data['selected_product'] = json_encode($request->input('selected_product'));
            $data['multi_brand'] = $request->input('multi_brand') ? json_encode($request->input('multi_brand')) : null;
            $data['multi_category'] = $request->input('multi_category');
            $data['multi_category'] = json_encode($request->input('multi_category'));

            $lead = Leads::create($data);
            if ($request->hasfile('image')) {
                foreach ($request->file('image') as $image) {
                    $media = MediaUploader::fromSource($image)
                        ->toDirectory('leads/' . floor($lead->id / config('constants.image_per_folder')))
                        ->upload();
                    $lead->attachMedia($media, config('constants.media_tags'));
                }
            }
        }




        // if(!empty($request->input('assigned_user'))){
        //
        //   NotificationQueueController::createNewNotification([
        //     'type' => 'button',
        //     'message' => $data['client_name'],
        //     // 'timestamps' => ['+0 minutes','+15 minutes','+30 minutes','+45 minutes'],
        //     'timestamps' => ['+0 minutes'],
        //     'model_type' => Leads::class,
        //     'model_id' =>  $lead->id,
        //     'user_id' => Auth::id(),
        //     'sent_to' => $request->input('assigned_user'),
        //     'role' => '',
        //   ]);
        // }
        // else{
        //
        //   NotificationQueueController::createNewNotification([
        //     'type' => 'button',
        //     'message' => $data['client_name'],
        //     // 'timestamps' => ['+0 minutes','+15 minutes','+30 minutes','+45 minutes'],
        //     'timestamps' => ['+0 minutes'],
        //     'model_type' => Leads::class,
        //     'model_id' =>  $lead->id,
        //     'user_id' => Auth::id(),
        //     'sent_to' => '',
        //     'role' => 'crm',
        //   ]);
        // }

        // NotificationQueueController::createNewNotification([
        //   'message' => $data['client_name'],
        //   'timestamps' => ['+0 minutes'],
        //   'model_type' => Leads::class,
        //   'model_id' =>  $lead->id,
        //   'user_id' => Auth::id(),
        //   'sent_to' => '',
        //   'role' => 'Admin',
        // ]);

        if ($request->ajax()) {
            return response()->json(['lead' => $lead]);
        }

        return redirect()->route('leads.create')
            ->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leads = Leads::find($id);
        $status = new status;
        $data = $status->all();
        $sales_persons = Helpers::getUsersArrayByRole('Sales');
        $leads['statusid'] = $data;
        $users = User::all()->toArray();
        $leads['users'] = $users;
        $brands = Brand::all()->toArray();
        $leads['brands'] = $brands;
        $leads['selected_products_array'] = json_decode($leads['selected_product']);
        $leads['products_array'] = [];
        $leads['recordings'] = CallRecording::where('lead_id', $leads->id)->get()->toArray();
        $leads['customers'] = Customer::all();
        $tasks = Task::where('model_type', 'leads')->where('model_id', $id)->get()->toArray();
        // $approval_replies = Reply::where('model', 'Approval Lead')->get();
        // $internal_replies = Reply::where('model', 'Internal Lead')->get();
        $reply_categories = ReplyCategory::all();

        $leads['multi_brand'] = is_array(json_decode($leads['multi_brand'], true)) ? json_decode($leads['multi_brand'], true) : [];
        // $selected_categories = is_array(json_decode( $leads['multi_category'],true)) ? json_decode( $leads['multi_category'] ,true) : [] ;
        $data['category_select'] = Category::attr(['name' => 'multi_category', 'class' => 'form-control', 'id' => 'multi_category'])
            ->selected($leads->multi_category)
            ->renderAsDropdown();
        $leads['remark'] = $leads->remark;

        $messages = Message::all()->where('moduleid', '=', $leads['id'])->where('moduletype', '=', 'leads')->sortByDesc("created_at")->take(10)->toArray();
        $leads['messages'] = $messages;

        if (!empty($leads['selected_products_array'])) {
            foreach ($leads['selected_products_array'] as $product_id) {
                $skuOrName = $this->getProductNameSkuById($product_id);

                $data['products_array'][$product_id] = $skuOrName;
            }
        }

        $users_array = Helpers::getUserArray(User::all());

        $selected_categories = $leads['multi_category'];
        return view('leads.show', compact('leads', 'id', 'data', 'tasks', 'sales_persons', 'selected_categories', 'users_array', 'reply_categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $leads = Leads::find($id);

        if ($request->type != 'customer') {
            $this->validate(request(), [
                'customer_id' => 'required',
                'client_name' => '',
                'contactno' => 'sometimes|nullable|numeric|regex:/^[91]{2}/|digits:12',
                //          'city' => 'required',
                'instahandler' => '',
                'rating' => 'required',
                'status' => 'required',
                'solophone' => '',
                'comments' => '',
                'userid' => '',
                'created_at' => 'required|date_format:"Y-m-d H:i"',

            ]);
        }


        // if (  $request->input( 'assigned_user' ) != $leads->assigned_user && !empty($request->input( 'assigned_user' ))  ) {
        //
        //   NotificationQueueController::createNewNotification([
        //     'type' => 'button',
        //     'message' => $leads->client_name,
        //     'timestamps' => ['+0 minutes'],
        //     // 'timestamps' => ['+0 minutes','+15 minutes','+30 minutes','+45 minutes'],
        //     'model_type' => Leads::class,
        //     'model_id' =>  $id,
        //     'user_id' => Auth::id(),
        //     'sent_to' => $request->input('assigned_user'),
        //     'role' => '',
        //   ]);
        //
        //   // NotificationQueueController::createNewNotification([
        //   //   'message' => $leads->client_name,
        //   //   'timestamps' => ['+45 minutes'],
        //   //   'model_type' => Leads::class,
        //   //   'model_id' =>  $id,
        //   //   'user_id' => Auth::id(),
        //   //   'sent_to' => Auth::id(),
        //   //   'role' => '',
        //   // ]);
        // }

        if ($request->type != 'customer') {
            $leads->customer_id = $request->customer_id;
            $leads->client_name = $request->get('client_name');
            $leads->contactno = $request->get('contactno');
            $leads->city = $request->get('city');
            $leads->source = $request->get('source');
            $leads->rating = $request->get('rating');
            $leads->solophone = $request->get('solophone');
            $leads->userid = $request->get('userid');
            $leads->email = $request->get('email');
            $leads->address = $request->get('address');
            $leads->leadsourcetxt = $request->get('leadsourcetxt');
            $leads->created_at = $request->created_at;
            $leads->whatsapp_number = $request->whatsapp_number;
        }


        if ($request->status != $leads->status) {
            $lead_status = (new status)->all();
            StatusChange::create([
                'model_id' => $id,
                'model_type' => Leads::class,
                'user_id' => Auth::id(),
                'from_status' => array_search($leads->status, $lead_status),
                'to_status' => array_search($request->status, $lead_status)
            ]);
        }

        $leads->status = $request->get('status');
        $leads->comments = $request->get('comments');
        $leads->assigned_user = $request->get('assigned_user');

        $leads->multi_brand = $request->input('multi_brand') ? json_encode($request->get('multi_brand')) : null;
        // $leads->multi_category = json_encode($request->get('multi_category'));
        $leads->multi_category = $request->get('multi_category');

        $leads->selected_product = json_encode($request->input('selected_product'));

        $leads->save();

        $messages = Message::where('moduletype', 'leads')->where('moduleid', $leads->id)->get();

        foreach ($messages as $message) {
            $message->customer_id = $leads->customer_id;
            $message->save();
        }

        $chats = ChatMessage::where('lead_id', $leads->id)->get();

        foreach ($chats as $chat) {
            $chat->customer_id = $leads->customer_id;
            $chat->save();
        }

        $count = 0;
        foreach ($request->oldImage as $old) {
            if ($old > 0) {
                self::removeImage($old);
            } elseif ($old == -1) {
                foreach ($request->file('image') as $image) {
                    $media = MediaUploader::fromSource($image)
                        ->toDirectory('leads/' . floor($leads->id / config('constants.image_per_folder')))
                        ->upload();
                    $leads->attachMedia($media, config('constants.media_tags'));
                }
            } elseif ($old == 0) {
                $count++;
            }
        }

        if ($count > 0) {
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $media = MediaUploader::fromSource($image)
                        ->toDirectory('leads/' . floor($leads->id / config('constants.image_per_folder')))
                        ->upload();
                    $leads->attachMedia($media, config('constants.media_tags'));
                }
            }
        }

        return redirect()->back()->with('success', 'Lead has been updated');
    }

    public function sendPrices(Request $request, GuzzleClient $client)
    {
        $params = [
            'number' => null,
            'user_id' => Auth::id() ?? 6,
            'approved' => 0,
            'status' => 8
        ];
        if($request->lead_id){
            $params['lead_id']= $request->lead_id;
        }
        $customer = Customer::find($request->customer_id);
        //$lead = Customer::find($request->lead_id);
        $product_names = '';

        $params['customer_id'] = $customer->id;
        \Log::channel('customer')->info("Lead send price started : " . $customer->id);
        foreach ($request->selected_product as $product_id) {

            $product = Product::find($product_id);
            $brand_name = $product->brands->name ?? '';
            $special_price = (int) $product->price_special_offer > 0 ? (int) $product->price_special_offer : $product->price_inr_special;

            if ($request->has('dimension')) {

                $product_names .= "$brand_name $product->name" . ' (' . "Length: $product->lmeasurement cm, Height: $product->hmeasurement cm & Depth: $product->dmeasurement cm) \n";
                $params['message'] = 'The products with their respective dimensions are: : ' . $product_names . '.';
                $chat_message = ChatMessage::create($params);
            } else {
                if ($request->has('detailed')) {

                    $params['message'] = 'The product images for : : ' . $brand_name . ' ' . $product->name . ' are.';
                    $chat_message = ChatMessage::create($params);
                    $chat_message->attachMedia($product->getMedia(config('constants.attach_image_tag')), config('constants.media_tags'));
                } else {

                    $auto_message = "$brand_name $product->name" . ' - ' . "$special_price";
                    //$auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'lead-product-prices')->first();
                    //$auto_message = preg_replace("/{product_names}/i", $product_names, $auto_reply->reply);
                    $params['message'] = ""; //$auto_message;
                    $chat_message = ChatMessage::create($params);

                    $mediaImage = $product->getMedia(config('constants.attach_image_tag'))->first();

                    //$chat_message->attachMedia($mediaImage,  config('constants.media_tags'));
                    // create text image to null first so no issue ahead
                    $textImage = null;
                    if ($mediaImage) {
                        // define seperator
                        if (!defined("DSP")) {
                            define("DSP", DIRECTORY_SEPARATOR);
                        }
                        // add text message and create image
                        $textImage = self::createProductTextImage(
                            $mediaImage->getAbsolutePath(),
                            "instant_message_" . $chat_message->id,
                            $auto_message,
                            "545b62",
                            "40",
                            true
                        );

                        if (!empty($textImage)) {
                            $mediaPrice = MediaUploader::fromSource($textImage)
                                ->toDirectory('chatmessage/' . floor($chat_message->id / config('constants.image_per_folder')))->upload();
                            //$chat_message->media_url = $textImage;
                            $chat_message->attachMedia($mediaPrice,  config('constants.media_tags'));
                            $chat_message->save();
                        }
                    }
                    // send message now
                    // uncomment this one to send message immidiatly
                    app(WhatsAppController::class)->sendRealTime($chat_message, 'customer_' . $customer->id, $client, $textImage);
                }
            }

            $autoApprove = \App\Helpers\DevelopmentHelper::needToApproveMessage();
            \Log::channel('customer')->info("Send price started : " . $chat_message->id);

            if ($autoApprove && !empty($chat_message->id)) {
                // send request if auto approve
                $approveRequest = new Request();
                $approveRequest->setMethod('GET');
                $approveRequest->request->add(['messageId' => $chat_message->id]);

                app(WhatsAppController::class)->approveMessage("customer", $approveRequest);
            }
        }

        if ($request->has('dimension') || $request->has('detailed')) {
            app(WhatsAppController::class)->sendRealTime($chat_message, 'customer_' . $customer->id, $client);
        }


        $histories = CommunicationHistory::where('model_id', $customer->id)->where('model_type', Customer::class)->where('type', 'initiate-followup')->where('is_stopped', 0)->get();

        foreach ($histories as $history) {
            $history->is_stopped = 1;
            $history->save();
        }

        CommunicationHistory::create([
            'model_id' => $customer->id,
            'model_type' => Customer::class,
            'type' => 'initiate-followup',
            'method' => 'whatsapp'
        ]);

        return response('success');
    }

    public function removeImage($old_image)
    {


        if ($old_image != 0) {

            $results = Media::where('id', $old_image)->get();

            $results->each(function ($media) {
                Image::trashImage($media->basename);
                $media->delete();
            });
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $lead = Leads::find($id);
        $lead_status = (new status)->all();
        StatusChange::create([
            'model_id' => $id,
            'model_type' => Leads::class,
            'user_id' => Auth::id(),
            'from_status' => array_search($lead->status, $lead_status),
            'to_status' => array_search($request->status, $lead_status)
        ]);

        $lead->status = $request->status;
        $lead->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leads = Leads::findOrFail($id);
        $leads->delete();
        return redirect('leads')->with('success', 'Lead has been archived');
    }

    public function permanentDelete(Leads $leads)
    {

        $leads->forceDelete();
        return redirect('leads')->with('success', 'Lead has been  deleted');
    }

    public function getProductNameSkuById($product_id)
    {

        $product = new Product();

        $product_instance = $product->find($product_id);

        return $product_instance->name ? $product_instance->name : $product_instance->sku;
    }

    public function imageGrid()
    {
        $leads_array = Leads::whereNull('deleted_at')->where('status', '!=', 1)->get()->toArray();
        $leads = Leads::whereNull('deleted_at')->where('status', '!=', 1)->get();
        $new_leads = [];

        foreach ($leads_array as $key => $lead) {
            if ($leads[$key]->getMedia(config('constants.media_tags'))->first() !== null) {
                $new_leads[$key]['id'] = $lead['id'];
                $new_leads[$key]['image'] = $leads[$key]->getMedia(config('constants.media_tags'));
                $new_leads[$key]['status'] = $lead['status'];
                $new_leads[$key]['rating'] = $lead['rating'];
            }
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = Setting::get('pagination');

        if (count($new_leads) > $perPage) {
            $currentItems = array_slice($new_leads, $perPage * ($currentPage - 1), $perPage);
        } else {
            $currentItems = $new_leads;
        }

        $new_leads = new LengthAwarePaginator($currentItems, count($new_leads), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        return view('leads.image-grid')->withLeads($new_leads);
    }


    public function saveLeaveMessage(Request $request)
    {
        $callBusyMessage = new CallBusyMessage();
        $callBusyMessage->lead_id = $request->input('lead_id');
        $callBusyMessage->message = $request->input('message');
        $callBusyMessage->save();
    }

    /**
     * Create images with text from product
     *
     */

    public static function createProductTextImage($path, $name = "", $text = "", $color = "545b62", $fontSize = "40", $abs = false)
    {
        $text = wordwrap(strtoupper($text), 24, "\n");
        $img = \IImage::make($path);
        $img->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        // use callback to define details
        $img->text($text, 5, 50, function ($font) use ($fontSize, $color) {
            $font->file(public_path('/fonts/HelveticaNeue.ttf'));
            $font->size($fontSize);
            $font->color("#" . $color);
            $font->align('top');
        });

        $name = !empty($name) ? $name . "_watermarked" : time() . "_watermarked";

        if (!\File::isDirectory(public_path() . '/uploads/chat-price-image/')) {
            \File::makeDirectory(public_path() . '/uploads/chat-price-image/', 0777, true, true);
        }

        $path = 'uploads/chat-price-image/' . $name . '.jpg';

        $img->save(public_path($path));

        if ($abs) {
            return public_path($path);
        }

        return url('/') . "/" . $path;
    }

    public function erpLeads(Request $request)
    {
        /*$shoe_size_group = Customer::selectRaw('shoe_size, count(id) as counts')
                                    ->whereNotNull('shoe_size')
                                    ->groupBy('shoe_size')
                                    ->pluck('counts', 'shoe_size');

        $clothing_size_group = Customer::selectRaw('clothing_size, count(id) as counts')
                                        ->whereNotNull('clothing_size')
                                        ->groupBy('clothing_size')
                                        ->pluck('counts', 'clothing_size');*/
        $brands = Brand::all()->toArray();
        $sourcePaginateArr = array();
        // print_r($brands);
        $erpLeadStatus = \App\ErpLeadStatus::all()->toArray();
        $source = \App\ErpLeads::leftJoin('products', 'products.id', '=', 'erp_leads.product_id')
            ->leftJoin("customers as c", "c.id", "erp_leads.customer_id")
            ->leftJoin("erp_lead_status as els", "els.id", "erp_leads.lead_status_id")
            ->leftJoin("categories as cat", "cat.id", "erp_leads.category_id")
            ->leftJoin("brands as br", "br.id", "erp_leads.brand_id")
            ->orderBy("erp_leads.id", "desc")
            ->select(["erp_leads.*", "products.name as product_name", "cat.title as cat_title", "br.name as brand_name", "els.name as status_name", "c.name as customer_name", "c.id as customer_id", "c.whatsapp_number as customer_whatsapp_number"]);


        /*$term = $request->get('term');
        if (!empty($term)) {
            $source = $source->where(function($q) use($term){
                $q->where("c.name","like","%{$term}%")
                  ->orWhere("c.phone","like","%{$term}%")
                  ->orWhere("c.instahandler","like","%{$term}%")
                  ->orWhere("products.name","like","%{$term}%")
                  ->orWhere("products.name","like","%{$term}%")
                  ->orWhere("erp_leads.id","like","%{$term}%");
            });
        }

        if ($request->get('shoe_size')) {
            $source = $source->where('c.shoe_size', '=', $request->get('shoe_size'));
        }

        if ($request->get('clothing_size')) {
            $source = $source->where('c.clothing_size', '=', $request->get('clothing_size'));
        }

        if ($request->get('shoe_size_group')) {
            $source = $source->where('c.shoe_size', '=', $request->get('shoe_size_group'));
        }

        if ($request->get('clothing_size_group')) {
            $source = $source->where('c.clothing_size', '=', $request->get('clothing_size_group'));
        }*/

        if ($request->get('lead_customer')) {
            $source = $source->where('c.name', 'like', "%" . $request->get('lead_customer') . "%");
        }

        if ($request->get('lead_brand')) {
            $source = $source->whereIn('erp_leads.brand_id', $request->get('lead_brand'));
        }

        if ($request->get('lead_status')) {
            $source = $source->whereIn('erp_leads.lead_status_id', $request->get('lead_status'));
        }

        if ($request->get('lead_category')) {
            $source = $source->where('cat.title', 'like', "%" . $request->get('lead_category') . "%");
        }

        if ($request->get('lead_color')) {
            $source = $source->where('erp_leads.color', '=', $request->get('lead_color'));
        }

        if ($request->get('lead_shoe_size')) {
            $source = $source->where('erp_leads.size', '=', $request->get('lead_shoe_size'));
        }

        if ($request->get('brand_segment')) {
            $source = $source->where('erp_leads.brand_segment', '=', $request->get('brand_segment'));
        }

        $total = $source->count();
        $source2 = clone $source;
        $allLeadCustomersId = $source2->select('erp_leads.customer_id')->pluck('customer_id', 'customer_id')->toArray();



        $source = $source->get();

        foreach ($source as $key => $value) {
            $source[$key]->media_url = null;
            $media = $value->getMedia(config('constants.media_tags'))->first();
            if ($media) {
                $source[$key]->media_url = $media->getUrl();
            }

            if (empty($source[$key]->media_url) && $value->product_id) {
                $product = Product::find($value->product_id);
                // $media = $product->getMedia(config('constants.media_tags'))->first();
                // if ($media) {
                //     $source[$key]->media_url = $media->getUrl();
                // }
            }
        }



        foreach ($source as $value) {
            $srcArr = json_decode(json_encode($value), true);
            array_push($sourcePaginateArr, $srcArr);
        }
        // echo "<pre>";print_r($sourcePaginateArr);die('ss');

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = Setting::get('pagination');
        if (request()->get('select_all') == 'true') {
            $perPage = count($sourcePaginateArr);
            $currentPage = 1;
        }

        if (!is_numeric($perPage)) {
            $perPage = 2;
        }


        $currentItems = array_slice($sourcePaginateArr, $perPage * ($currentPage - 1), $perPage);

        $sourcePaginateArr = new LengthAwarePaginator($currentItems, count($sourcePaginateArr), $perPage, $currentPage, [
            'path'  => LengthAwarePaginator::resolveCurrentPath()
        ]);
        // echo "<pre>";print_r($sourcePaginateArr);die;
        return view("leads.erp.index", [
            //'shoe_size_group' => $shoe_size_group,
            //'clothing_size_group' => $clothing_size_group,
            'brands'   => $brands,
            'erpLeadStatus'   => $erpLeadStatus,
            'recordsTotal' => $total,
            'sourceData' => $sourcePaginateArr,
            'allLeadCustomersId' => $allLeadCustomersId,
        ]);
    }

    public function filterErpLeads()
    {
        echo "filter";
        print_r($_POST);
    }
    public function erpLeadsResponse(Request $request)
    {

        $source = \App\ErpLeads::leftJoin('products', 'products.id', '=', 'erp_leads.product_id')
            ->leftJoin("customers as c", "c.id", "erp_leads.customer_id")
            ->leftJoin("erp_lead_status as els", "els.id", "erp_leads.lead_status_id")
            ->leftJoin("categories as cat", "cat.id", "erp_leads.category_id")
            ->leftJoin("brands as br", "br.id", "erp_leads.brand_id")
            ->orderBy("erp_leads.id", "desc")
            ->select(["erp_leads.*", "products.name as product_name", "cat.title as cat_title", "br.name as brand_name", "els.name as status_name", "c.name as customer_name", "c.id as customer_id"]);


        /*$term = $request->get('term');
        if (!empty($term)) {
            $source = $source->where(function($q) use($term){
                $q->where("c.name","like","%{$term}%")
                  ->orWhere("c.phone","like","%{$term}%")
                  ->orWhere("c.instahandler","like","%{$term}%")
                  ->orWhere("products.name","like","%{$term}%")
                  ->orWhere("products.name","like","%{$term}%")
                  ->orWhere("erp_leads.id","like","%{$term}%");
            });
        }

        if ($request->get('shoe_size')) {
            $source = $source->where('c.shoe_size', '=', $request->get('shoe_size'));
        }

        if ($request->get('clothing_size')) {
            $source = $source->where('c.clothing_size', '=', $request->get('clothing_size'));
        }

        if ($request->get('shoe_size_group')) {
            $source = $source->where('c.shoe_size', '=', $request->get('shoe_size_group'));
        }

        if ($request->get('clothing_size_group')) {
            $source = $source->where('c.clothing_size', '=', $request->get('clothing_size_group'));
        }*/

        if ($request->get('lead_customer')) {
            $source = $source->where('c.name', 'like', "%" . $request->get('lead_customer') . "%");
        }

        if ($request->get('lead_brand')) {
            $source = $source->whereIn('erp_leads.brand_id', $request->get('lead_brand'));
        }

        if ($request->get('lead_status')) {
            $source = $source->whereIn('erp_leads.lead_status_id', $request->get('lead_status'));
        }

        if ($request->get('lead_category')) {
            $source = $source->where('cat.title', 'like', "%" . $request->get('lead_category') . "%");
        }

        if ($request->get('lead_color')) {
            $source = $source->where('erp_leads.color', '=', $request->get('lead_color'));
        }

        if ($request->get('lead_shoe_size')) {
            $source = $source->where('erp_leads.size', '=', $request->get('lead_shoe_size'));
        }

        if ($request->get('brand_segment')) {
            $source = $source->where('erp_leads.brand_segment', '=', $request->get('brand_segment'));
        }

        $total = $source->count();
        $source2 = clone $source;
        $allLeadCustomersId = $source2->select('erp_leads.customer_id')->pluck('customer_id', 'customer_id')->toArray();

        $source = $source->offset($request->get('start', 0));
        $source = $source->limit($request->get('length', 10));
        $source = $source->get();

        foreach ($source as $key => $value) {
            $source[$key]->media_url = null;
            $media = $value->getMedia(config('constants.media_tags'))->first();
            if ($media) {
                $source[$key]->media_url = $media->getUrl();
            }

            if (empty($source[$key]->media_url) && $value->product_id) {
                $product = Product::find($value->product_id);
                // $media = $product->getMedia(config('constants.media_tags'))->first();
                // if ($media) {
                //     $source[$key]->media_url = $media->getUrl();
                // }
            }
        }

        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $source,
            'allLeadCustomersId' => $allLeadCustomersId,
        ]);
    }
    public function blockcustomerlead(Request $request)
    {



        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            $is_blocked_lead = !$customer->is_blocked_lead;

            $lead_product_freq = (isset($request->lead_product_freq)) ? $request->lead_product_freq : '';
            if ($request->column == 'delete') {
                $customer->is_blocked_lead = $is_blocked_lead;
            }
            if ($request->column == 'update') {

                $customer->lead_product_freq = $lead_product_freq;
            }

            $customer->save();
            return response()->json([
                'status' => 200,
                'message' => 'Leads for Customer are blocked',
            ]);
        }
    }
    public function erpLeadsCreate()
    {
        $customerList = []; //\App\Customer::pluck("name","id")->toArray();
        $brands = Brand::all();
        $category = Category::attr(['name' => 'category_id', 'class' => 'form-control', 'id' => 'category_id'])->selected()->renderAsDropdown();
        $colors = \App\ColorNamesReference::pluck("erp_name", "erp_name")->toArray();
        $status = \App\ErpLeadStatus::pluck("name", "id")->toArray();
        return view("leads.erp.create", compact('customerList', 'brands', 'category', 'colors', 'status'));
    }

    public function erpLeadsEdit()
    {
        $id = request()->get("id", 0);
        $erpLeads = \App\ErpLeads::where("id", $id)->first();
        if ($erpLeads) {
            $customerList = [$erpLeads->customer_id => $erpLeads->customer->name]; //\App\Customer::pluck("name","id")->toArray();
            $brands = Brand::pluck("name", "id")->toArray();
            $category = Category::attr(['name' => 'category_id', 'class' => 'form-control', 'id' => 'category_id'])->selected($erpLeads->category_id)->renderAsDropdown();
            $products = \App\Product::where("id", $erpLeads->product_id)->get()->pluck("name", "id")->toArray();
            $colors = \App\ColorNamesReference::pluck("erp_name", "erp_name")->toArray();
            $status = \App\ErpLeadStatus::pluck("name", "id")->toArray();
            return view("leads.erp.edit", compact('erpLeads', 'customerList', 'brands', 'category', 'products', 'colors', 'status'));
        }
    }

    public function erpLeadsStore(Request $request)
    {
        $id = request()->get("id", 0);
        $productId =  request()->get("product_id", 0);

        $customer = \App\Customer::where("id", request()->get("customer_id", 0))->first();
        if (!$customer) {
            return response()->json(["code" => 0, "data" => [], "message" => "Please select valid customer"]);
        }

        $product = \App\Product::where("id", $productId)->first();
        $productId = null;
        if ($product) {
            $productId = $product->id;
        }
        $params = request()->all();
        $params["product_id"] = $productId;
        if (isset($params["brand_segment"])) {
            $params["brand_segment"] = implode(",", (array)$params["brand_segment"]);
        }

        if ($product) {
            if (empty($params["brand_id"])) {
                $params["brand_id"] = $product->brand;
                if (empty($params["brand_segment"])) {
                    $brand = \App\Brand::where("id", $product->brand)->first();
                    if ($brand) {
                        $params["brand_segment"] = $brand->brand_segment;
                    }
                }
            }

            if (empty($params["category_id"])) {
                $params["category_id"] = $product->category;
            }
        }

        if (empty($params["color"])) {
            $params["color"] = $customer->color;
        }

        if (empty($params["size"])) {
            $params["size"] = $customer->size;
        }

        $erpLeads = \App\ErpLeads::where("id", $id)->first();
        if (!$erpLeads) {
            $erpLeads = new \App\ErpLeads;
        }
        $erpLeads->fill($params);
        $erpLeads->save();

        $count = 0;
        if ($request->oldImage) {
            foreach ($request->oldImage as $old) {
                if ($old > 0) {
                    self::removeImage($old);
                } elseif ($old == -1) {
                    if ($request->hasFile('image')) {
                        foreach ($request->file('image') as $image) {
                            $media = MediaUploader::fromSource($image)->upload();
                            $erpLeads->attachMedia($media, config('constants.media_tags'));
                        }
                    }
                } elseif ($old == 0) {
                    $count++;
                }
            }
        }

        if ($count > 0) {
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $media = MediaUploader::fromSource($image)->upload();
                    $erpLeads->attachMedia($media, config('constants.media_tags'));
                }
            }
        }

        foreach ($request->get('product_media_list', []) as $id) {
            $media = Media::find($id);
            $erpLeads->attachMedia($media, config('constants.media_tags'));
        }

        return response()->json(["code" => 1, "data" => []]);
    }

    public function erpLeadDelete()
    {
        $id = request()->get("id", 0);

        $erpLeads = \App\ErpLeads::where("id", $id)->first();
        if ($erpLeads) {
            $erpLeads->delete();
        }

        return response()->json(["code" => 1, "data" => []]);
    }

    public function customerSearch()
    {
        $term = request()->get("q", null);
        $search = \App\Customer::where("name", "like", "%{$term}%")->orWhere("phone", "like", "%{$term}%")->orWhere("id", "like", "%{$term}%")->get();
        return $search;
    }

    public function sendMessage(Request $request)
    {
        $customerIds = array_unique($request->get('customers', []));
        $customerArr = Customer::whereIn('id', $customerIds)->where('do_not_disturb', 0)->get();
        if (!empty($customerArr)) {
            $productIds = array_unique($request->get('products', []));

            // check if the data has more values for the prmotions
            $startTime = $request->get("product_start_date", "");
            $endTime   = $request->get("product_end_date", "");

            $product =  new \App\Product;

            $fireQ = false;
            if (!empty($startTime)) {
                $fireQ = true;
                $product = $product->where("created_at", ">=", $startTime);
            }
            if (!empty($endTime)) {
                $fireQ = true;
                $product = $product->where("created_at", "<=", $endTime);
            }

            if ($fireQ) {
                $productQueryIds = $product->select("id")->get()->pluck('id')->toArray();
                if (!empty($productQueryIds)) {
                    $productIds = array_merge($productIds, $productQueryIds);
                }
            }

            $broadcast_image =  new BroadcastImage();
            $broadcast_image->products =  json_encode($productIds);
            $broadcast_image->save();
            $max_group_id = MessageQueue::max('group_id') + 1;

            $sendingData = [
                "message"  => $request->get('message', ''),
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $media = MediaUploader::fromSource($image)->upload();
                $broadcast_image->attachMedia($media, config('constants.media_tags'));
                foreach ($broadcast_image->getMedia(config('constants.media_tags')) as $key2 => $brod_image) {
                    $sendingData["image"][] = [
                        "key" => $brod_image->getKey(),
                        "url" => $brod_image->getUrl()
                    ];
                }
            } else {
                $sendingData['linked_images'][] = $broadcast_image->id;
            }

            $params = [
                'sending_time'  => $request->get('sending_time', ''),
                'user_id' => Auth::id(),
                'phone' => null,
                'type' => 'message_all',
                'data' => json_encode($sendingData),
                'group_id' => $max_group_id
            ];

            foreach ($customerArr as  $customer) {
                $params['customer_id'] = $customer->id;
                MessageQueue::create($params);
            }
        }

        return response()->json(["code" => 1, "data" => []]);
    }

    public function updateErpStatus(Request $request, $id)
    {
        $lead = \App\ErpLeads::find($id);
        if ($lead->lead_status_id != $request->status) {
            $lead_status = \App\ErpLeadStatus::pluck("name", "id")->toArray();
            StatusChange::create([
                'model_id' => $id,
                'model_type' => \App\ErpLeads::class,
                'user_id' => Auth::id(),
                'from_status' => $lead_status[$lead->lead_status_id],
                'to_status' => $lead_status[$request->status]
            ]);

            $lead->lead_status_id = $request->status;
            $lead->save();
        }
    }

    public function leadAutoFillInfo(Request $request)
    {
        $product = Product::find($request->get('product_id'));
        $customer = Customer::find($request->get('customer_id'));
        $mediaArr =  $product ? $product->getMedia(config('constants.media_tags')) : [];
        $media = [];

        foreach ($mediaArr as $value) {
            $media[] = ['url' => $value->getUrl(), 'id' => $value->id];
        }

        $price = 0;
        if ($product) {
            $price = (int) $product->price_special_offer > 0 ? (int) $product->price_special_offer : $product->price_inr_special;
        }

        return response()->json([
            'brand' => $product ? $product->brand : '',
            'category' => $product ? $product->category : '1',
            'brand_segment' => $product && $product->brands ? $product->brands->brand_segment : '',
            'shoe_size' => $customer ? $customer->shoe_size : '',
            'gender' => $customer ? $customer->gender : '',
            'media' => $media,
            'price' => $price
        ]);
    }

    public function erpLeadsHistory(request $request)
    {
        $erpLeadStatus = \App\ErpLeadStatus::all()->toArray();
        // \DB::enableQueryLog();
        $source =  \App\ErpLeadSendingHistory::leftjoin("products", "products.id", "erp_lead_sending_histories.product_id")
            ->leftJoin("customers as c", "c.id", "erp_lead_sending_histories.customer_id")
            ->leftJoin("erp_leads", "erp_leads.id", "erp_lead_sending_histories.lead_id")
            ->leftJoin("erp_lead_status", "erp_leads.lead_status_id", "erp_lead_status.id")
            ->orderBy("erp_lead_sending_histories.id", "desc")
            ->select(["erp_lead_sending_histories.*", "products.name as product_name", "c.name as customer_name", "c.id as customer_id", "erp_lead_status.name as lead_status"]);

        if ($request->get('lead_customer')) {
            $source = $source->where('c.name', 'like', "%" . $request->get('lead_customer') . "%");
        }

        if ($request->get('product_name')) {
            $source = $source->where('products.name', 'like',  "%" . $request->get('product_name') . "%");
        }

        if ($request->get('lead_status')) {
            $source = $source->where('erp_leads.lead_status_id', '=', $request->get('lead_status'));
        }

        if ($request->get('created_at')) {
            $source = $source->whereDate('erp_lead_sending_histories.created_at', '=',  $request->get('created_at'));
        }
        $source = $source->paginate(5);
        session()->flashInput($request->input());
        return view("leads.erp.history", [
            'sourceData' => $source,
            'erpLeadStatus' => $erpLeadStatus
        ]);
    }

    public function erpLeadsStatusCreate(Request $request){
        $status = new ErpLeadStatus;
        $status->name = $request->add_status;
        $status->save();
        return redirect()->back()->with('success','Status Added Successsfully');
    }
    public function erpLeadsStatusUpdate(Request $request){
        $statusModal = ErpLeadStatus::where("id", $request->status_id)->first()->name;
        
        $template = "Greetings from Solo Luxury Ref: order number $request->id we have updated your order with status : $statusModal.";
        $erp_leads = ErpLeads::find($request->id);

            $history = new \App\ErpLeadStatusHistory;
            $history->lead_id = $request->id;
            $history->old_status = $erp_leads->lead_status_id;
            $history->new_status = $request->status_id;
            $history->user_id = Auth::id();
            $history->save();
        
        $erp_leads->lead_status_id = $request->status_id;
        $erp_leads->save();

        // $user = Auth::user();
        // $watsapp_number = $user->whatsapp_number;
        // $params['message'] = "Status Updated Successsfully";

        // if($watsapp_number !== null){
        //     app('App\Http\Controllers\WhatsAppController')
        //                         ->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'], false);
        // }

        return response()->json(['code' => 200,'message' => "Status Updated Successsfully", 'template' => $template]);
    }

    public function erpLeadStatusChange(Request $request)
    {
        $id     = $request->get("id");
        $status = $request->get("status");

        if (!empty($id) && !empty($status)) {
            $order   = \App\ErpLeads::find($id);
            $statuss = ErpLeadStatus::find($status);

            if ($order->customer->email) {
                if (isset($request->sendmessage) && $request->sendmessage == '1') {
                    //Sending Mail on changing of order status
                    try {
                            $emailClass = (new \App\Mails\Manual\OrderStatusChangeMail($order))->build();

                            $email             = \App\Email::create([
                                'model_id'         => $order->id,
                                'model_type'       => ErpLeads::class,
                                'from'             => $emailClass->fromMailer,
                                'to'               => $order->customer->email,
                                'subject'          => $emailClass->subject,
                                'message'          => $emailClass->render(),
                                'template'         => 'erp-lead-status-update',
                                'additional_data'  => $order->id,
                                'status'           => 'pre-send',
                                'is_draft'         => 0,
                            ]);

                            \App\Jobs\SendEmail::dispatch($email);

                    } catch (\Exception $e) {
                        \Log::info("Sending mail issue at the ordercontroller #2215 ->" . $e->getMessage());
                    }

                } else {
                    $emailClass = (new \App\Mails\Manual\OrderStatusChangeMail($order))->build();

                    $email             = \App\Email::create([
                        'model_id'         => $order->id,
                        'model_type'       => ErpLeads::class,
                        'from'             => $emailClass->fromMailer,
                        'to'               => $order->customer->email,
                        'subject'          => $emailClass->subject,
                        'message'          => $emailClass->render(),
                        'template'         => 'erp-lead-status-update',
                        'additional_data'  => $order->id,
                        'status'           => 'pre-send',
                        'is_draft'         => 0,
                    ]);

                    \App\Jobs\SendEmail::dispatch($email);

                }

                // }catch(\Exception $e) {
                //   \Log::info("Sending mail issue at the ordercontroller #2215 ->".$e->getMessage());
                // }
            }
           
        }
        return response()->json('Sucess', 200);

    }
}
