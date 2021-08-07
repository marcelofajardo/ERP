<?php

namespace App\Http\Controllers;

use App\Blogger;
use App\DeveloperTask;
use App\Issue;
use App\Lawyer;
use App\LegalCase;
use App\Marketing\WhatsappConfig;
use App\Old;
use App\Services\BulkCustomerMessage\KeywordsChecker;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Bugsnag\PsrLogger\BugsnagLogger;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\URL;
use Plank\Mediable\MediaUploaderFacade;
use Twilio\Jwt\ClientToken;
use Twilio\Twiml;
use Twilio\Rest\Client;
use App\Jobs\SendMessageToAll;
use App\Jobs\SendMessageToSelected;
use App\Category;
use App\Notification;
use App\AutoReply;
use App\BroadcastImage;
use App\Leads;
use App\Order;
use App\Task;
use App\Status;
use App\Supplier;
use App\Vendor;
use App\Setting;
use App\Dubbizle;
use App\User;
use App\Brand;
use App\Product;
use App\Contact;
use App\CommunicationHistory;
use App\ApiKey;
use App\Message;
use App\Instruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers;
use App\ChatMessage;
use App\PushNotification;
use App\NotificationQueue;
use App\Purchase;
use App\Customer;
use App\AutoCompleteMessage;
use App\MessageQueue;
use App\Jobs\SendImagesWithWhatsapp;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use IlluminUserFeedbackStatuspport\Facades\DB;
use Validator;
use Image;
use GuzzleHttp\Client as GuzzleClient;
use File;
use App\Document;
use App\WhatsAppGroup;
use App\DocumentSendHistory;
use App\QuickSellGroup;
use App\ProductQuicksellGroup;
use App\Helpers\InstantMessagingHelper;
use App\Library\Watson\Model as WatsonManager;
use Response;
use \App\Helpers\TranslationHelper;
use App\ImQueue;
use App\Account;
use App\BrandFans;
use App\ChatMessagesQuickData;
use App\ColdLeads;
use App\ChatbotQuestion;
use Google\Cloud\Translate\TranslateClient;
use App\Hubstaff\HubstaffActivitySummary;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use App\Hubstaff\HubstaffMember;
use App\Helpers\HubstaffTrait;
use Tickets;
use App\Email;
use App\EmailAddress;
use App\EmailNotificationEmailDetails;//Purpose : Add Modal - DEVTASK-4359
use App\Mails\Manual\PurchaseExport;//Purpose : Add Modal - DEVTASK-4236
use App\Helpers\MessageHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Imports\CustomerNumberImport;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class WhatsAppController extends FindByNumberController
{

    use hubstaffTrait;
    CONST MEDIA_PDF_CHUNKS = 50;
    CONST AUTO_LEAD_SEND_PRICE = 281;
    private $githubClient;


    public function __construct()
    {
        $this->githubClient = new GuzzleClient([
            // 'auth' => [getenv('GITHUB_USERNAME'), getenv('GITHUB_TOKEN')]
            'auth' => [config('env.GITHUB_USERNAME'), config('env.GITHUB_TOKEN')],
        ]);
        // $this->init(getenv('HUBSTAFF_SEED_PERSONAL_TOKEN'));
        $this->init(config('env.HUBSTAFF_SEED_PERSONAL_TOKEN'));
    }
    /**
     * Incoming message URL for whatsApp
     *
     * @return \Illuminate\Http\Response
     */
    public function incomingMessage(Request $request, GuzzleClient $client)
    {
        $data = $request->json()->all();

        if ($data['event'] == 'INBOX') {
            $to = $data['to'];
            $from = $data['from'];
            $text = $data['text'];
            $lead = $this->findLeadByNumber($from);
            $user = $this->findUserByNumber($from);
            $supplier = $this->findSupplierByNumber($from);
            $customer = $this->findCustomerByNumber($from);

            $params = [
                'number' => $from
            ];

            if ($user) {
                $params = $this->modifyParamsWithMessage($params, $data);
                // $instruction = Instruction::where('assigned_to', $user->id)->latest()->first();
                // $myRequest = new Request();
                // $myRequest->setMethod('POST');
                // $myRequest->request->add(['remark' => $params['message'], 'id' => $instruction->id, 'module_type' => 'instruction', 'user_name' => "User from Whatsapp"]);
                //
                // app('App\Http\Controllers\TaskModuleController')->addRemark($myRequest);
                //
                // NotificationQueueController::createNewNotification([
                //   'message' => $params['message'],
                //   'timestamps' => ['+0 minutes'],
                //   'model_type' => Instruction::class,
                //   'model_id' =>  $instruction->id,
                //   'user_id' => '6',
                //   'sent_to' => $instruction->assigned_from,
                //   'role' => '',
                // ]);

                $params['erp_user'] = $user->id;

                $params = $this->modifyParamsWithMessage($params, $data);

                if (array_key_exists('message', $params) && (preg_match_all("/#([\d]+)/i", $params['message'], $match))) {
                    $params['task_id'] = $match[1][0];
                }

                $message = ChatMessage::create($params);
                $model_type = 'user';
                $model_id = $user->id;

                if (array_key_exists('task_id', $params)) {
                    $this->sendRealTime($message, 'task_' . $match[1][0], $client);
                } else {
                    $this->sendRealTime($message, 'erp_user_' . $user->id, $client);
                }
            }

            if ($supplier) {
                $params['erp_user'] = null;
                $params['task_id'] = null;
                $params['supplier_id'] = $supplier->id;

                $params = $this->modifyParamsWithMessage($params, $data, $supplier->id);
                $message = ChatMessage::create($params);
                $model_type = 'supplier';
                $model_id = $supplier->id;

                $this->sendRealTime($message, 'supplier_' . $supplier->id, $client);
            }

            if ($customer) {
                $params['erp_user'] = null;
                $params['supplier_id'] = null;
                $params['task_id'] = null;
                $params['customer_id'] = $customer->id;

                $params = $this->modifyParamsWithMessage($params, $data);
                $message = ChatMessage::create($params);

                if ($params['message']) {
                    (new \App\KeywordsChecker())->assignCustomerAndKeywordForNewMessage($params['message'], $customer);
                }

                $model_type = 'customers';
                $model_id = $customer->id;
                $customer->update([
                    'whatsapp_number' => $to
                ]);

                $this->sendRealTime($message, 'customer_' . $customer->id, $client);

                if (Setting::get('forward_messages') == 1) {
                    if (Setting::get('forward_start_date') != null && Setting::get('forward_end_date') != null) {
                        $time = Carbon::now();
                        $start_date = Carbon::parse(Setting::get('forward_start_date'));
                        $end_date = Carbon::parse(Setting::get('forward_end_date'));

                        if ($time->between($start_date, $end_date, true)) {
                            $forward_users_ids = json_decode(Setting::get('forward_users'));
                            $second_message = '';

                            if ($message->message == null) {
                                $forwarded_message = "FORWARDED from $customer->name";
                                $second_message = $message->media_url;
                            } else {
                                $forwarded_message = "FORWARDED from $customer->name - " . $message->message;
                            }

                            foreach ($forward_users_ids as $user_id) {
                                $user = User::find($user_id);

                                $this->sendWithWhatsApp($user->phone, $user->whatsapp_number, $forwarded_message, false, $message->id);

                                if ($second_message != '') {
                                    $this->sendWithWhatsApp($user->phone, $user->whatsapp_number, $second_message, false, $message->id);
                                }
                            }
                        }
                    } else {
                        $forward_users_ids = json_decode(Setting::get('forward_users'));
                        $second_message = '';

                        if ($message->message == null) {
                            $forwarded_message = "FORWARDED from $customer->name";
                            $second_message = $message->media_url;
                        } else {
                            $forwarded_message = "FORWARDED from $customer->name - " . $message->message;
                        }

                        foreach ($forward_users_ids as $user_id) {
                            $user = User::find($user_id);

                            $this->sendWithWhatsApp($user->phone, $user->whatsapp_number, $forwarded_message, false, $message->id);

                            if ($second_message != '') {
                                $this->sendWithWhatsApp($user->phone, $user->whatsapp_number, $second_message, false, $message->id);
                            }
                        }
                    }
                }

                // Auto DND Keyword Stop Added By Satyam
                if (array_key_exists('message', $params) && strtoupper($params['message']) == 'DND' || strtoupper($params['message']) == 'STOP') {
                    if ($customer = Customer::find($params['customer_id'])) {
                        $customer->do_not_disturb = 1;
                        $customer->save();
                        \Log::channel('customerDnd')->debug("(Customer ID " . $customer->id . " line " . $customer->name . " " . $customer->number . ": Added To DND");

                        $dnd_params = [
                            'number' => null,
                            'user_id' => 6,
                            'approved' => 1,
                            'status' => 9,
                            'customer_id' => $customer->id,
                            'message' => AutoReply::where('type', 'auto-reply')->where('keyword', 'customer-dnd')->first()->reply
                        ];

                        $auto_dnd_message = ChatMessage::create($dnd_params);

                        $this->sendWithWhatsApp($customer->phone, $customer->whatsapp_number, $dnd_params['message'], false, $auto_dnd_message->id);
                    }
                }

                // Auto Instruction
                if (array_key_exists('message', $params) && (preg_match("/price/i", $params['message']) || preg_match("/you photo/i", $params['message']) || preg_match("/pp/i", $params['message']) || preg_match("/how much/i", $params['message']) || preg_match("/cost/i", $params['message']) || preg_match("/rate/i", $params['message']))) {
                    if ($customer = Customer::find($params['customer_id'])) {
                        $two_hours = Carbon::now()->subHours(2);
                        $latest_broadcast_message = ChatMessage::where('customer_id', $customer->id)->where('created_at', '>', $two_hours)->where('status', 8)->orderBy('id', 'DESC')->first();

                        if ($latest_broadcast_message) {
                            if (!$latest_broadcast_message->isSentBroadcastPrice()) {
                                if ($latest_broadcast_message->hasMedia(config('constants.media_tags'))) {
                                    $selected_products = [];

                                    foreach ($latest_broadcast_message->getMedia(config('constants.media_tags')) as $image) {
                                        $image_key = $image->getKey();
                                        $mediable_type = "BroadcastImage";

                                        $broadcast = BroadcastImage::with('Media')
                                            ->whereRaw("broadcast_images.id IN (SELECT mediables.mediable_id FROM mediables WHERE mediables.media_id = $image_key AND mediables.mediable_type LIKE '%$mediable_type%')")
                                            ->first();

                                        if ($broadcast) {
                                            $brod_products = json_decode($broadcast->products, true);

                                            if (count($brod_products) > 0) {
                                                foreach ($brod_products as $brod_pro) {
                                                    $selected_products[] = $brod_pro;
                                                }
                                            }
                                        }
                                    }

                                    if (isset($broadcast)) {
                                        if (!empty($selected_products)) {
                                            foreach ($selected_products as $pid) {
                                                $product = \App\Product::where("id", $pid)->first();
                                                $quick_lead = \App\ErpLeads::create([
                                                    'customer_id' => $customer->id,
                                                    //'rating' => 1,
                                                    'lead_status_id' => 3,
                                                    //'assigned_user' => 6,
                                                    'product_id' => $pid,
                                                    'brand_id' => $product ? $product->brand : null,
                                                    'category_id' => $product ? $product->category : null,
                                                    'brand_segment' => $product && $product->brands ? $product->brands->brand_segment : null,
                                                    'color' => $customer->color,
                                                    'size' => $customer->size,
                                                    'created_at' => Carbon::now()
                                                ]);
                                            }
                                            $requestData = new Request();
                                            $requestData->setMethod('POST');
                                            $requestData->request->add(['customer_id' => $customer->id, 'lead_id' => $quick_lead->id, 'selected_product' => $selected_products]);

                                            app('App\Http\Controllers\LeadsController')->sendPrices($requestData);
                                        }

                                        CommunicationHistory::create([
                                            'model_id' => $latest_broadcast_message->id,
                                            'model_type' => ChatMessage::class,
                                            'type' => 'broadcast-prices',
                                            'method' => 'whatsapp'
                                        ]);
                                    } else {
                                        // Instruction::create([
                                        //   'customer_id' => $customer->id,
                                        //   'instruction' => 'Please send the prices',
                                        //   'category_id' => 1,
                                        //   'assigned_to' => 7,
                                        //   'assigned_from' => 6
                                        // ]);
                                    }
                                }
                            }
                        }

                        Instruction::create([
                            'customer_id' => $customer->id,
                            'instruction' => 'Please send the prices',
                            'category_id' => 1,
                            'assigned_to' => 7,
                            'assigned_from' => 6
                        ]);
                    }
                }

                // Auto Replies
                $auto_replies = AutoReply::all();

                foreach ($auto_replies as $auto_reply) {
                    if (array_key_exists('message', $params) && $params['message'] != '') {
                        $keyword = $auto_reply->keyword;

                        if (preg_match("/{$keyword}/i", $params['message'])) {
                            $temp_params = $params;
                            $temp_params['message'] = $auto_reply->reply;
                            $temp_params['status'] = 1;

                            ChatMessage::create($temp_params);

                            $this->sendRealTime($message, 'customer_' . $customer->id, $client);
                        }
                    }
                }
            }

            if (!isset($user) && !isset($purchase) && !isset($customer)) {
                $modal_type = 'leads';
                // $new_name = "whatsapp lead " . uniqid( TRUE );
                $user = User::get()[0];
                $validate_phone['phone'] = $from;

                $validator = Validator::make($validate_phone, [
                    'phone' => 'unique:customers,phone'
                ]);

                if ($validator->fails()) {

                } else {
                    $customer = new Customer;
                    $customer->name = $from;
                    $customer->phone = $from;
                    $customer->rating = 2;
                    $customer->save();

                    $lead = \App\ErpLeads::create([
                        'customer_id' => $customer->id,
                        //'client_name' => $from,
                        //'contactno' => $from,
                        //'rating' => 2,
                        'lead_status_id' => 1,
                        //'assigned_user' => $user->id,
                        //'userid' => $user->id,
                        //'whatsapp_number' => $to
                    ]);

                    $params['lead_id'] = $lead->id;
                    $params['customer_id'] = $customer->id;
                    $params = $this->modifyParamsWithMessage($params, $data);
                    $message = ChatMessage::create($params);
                    $model_type = 'leads';
                    $model_id = $lead->id;

                    $this->sendRealTime($message, 'customer_' . $customer->id, $client);
                }
            }

            // Auto Respond
            $today_date = Carbon::now()->format('Y-m-d');
            $time = Carbon::now();
            $start_time = Setting::get('start_time');
            $start_time_exploded = explode(':', $start_time);
            $end_time = Setting::get('end_time');
            $end_time_exploded = explode(':', $end_time);
            $morning = Carbon::create($time->year, $time->month, $time->day, $start_time_exploded[0], $start_time_exploded[1], 0);
            $not_morning = Carbon::create($time->year, $time->month, $time->day, 0, 0, 0);
            $evening = Carbon::create($time->year, $time->month, $time->day, $end_time_exploded[0], $end_time_exploded[1], 0);
            $not_evening = Carbon::create($time->year, $time->month, $time->day, 23, 59, 0);
            $saturday = Carbon::now()->endOfWeek()->subDay()->format('Y-m-d');
            $sunday = Carbon::now()->endOfWeek()->format('Y-m-d');

            $chat_messages_query = ChatMessage::where('customer_id', $params['customer_id'])->whereBetween('created_at', [$morning, $evening])->whereNotNull('number');
            $chat_messages_count = $chat_messages_query->count();

            $chat_messages_evening_query = ChatMessage::where('customer_id', $params['customer_id'])->where(function ($query) use ($not_morning, $morning, $evening, $not_evening) {
                $query->whereBetween('created_at', [$not_morning, $morning])->orWhereBetween('created_at', [$evening, $not_evening]);
            })->whereNotNull('number');
            $chat_messages_evening_count = $chat_messages_evening_query->count();

            if ($chat_messages_count == 1) {
                $chat_messages_query_first = $chat_messages_query->first();
            }

            if ($chat_messages_evening_count == 1) {
                $chat_messages_evening_query_first = $chat_messages_evening_query->first();
            }

            if ($chat_messages_count == 1 && (isset($chat_messages_query_first) && $chat_messages_query_first->id == $message->id) && ($saturday != $today_date && $sunday != $today_date)) {
                $customer = Customer::find($params['customer_id']);
                $params = [
                    'number' => null,
                    'user_id' => 6,
                    'approved' => 1,
                    'status' => 9,
                    'customer_id' => $params['customer_id']
                ];

                if ($time->between($morning, $evening, true)) {
                    $params['message'] = AutoReply::where('type', 'auto-reply')->where('keyword', 'work-hours-greeting')->first()->reply;

                    sleep(1);
                    $additional_message = ChatMessage::create($params);
                    $this->sendWithWhatsApp($message->customer->phone, $customer->whatsapp_number, $additional_message->message, false, $additional_message->id);
                }
            } else {
                if (($chat_messages_evening_count == 1 && (isset($chat_messages_evening_query_first) && $chat_messages_evening_query_first->id == $message->id)) || ($chat_messages_count == 1 && ($saturday == $today_date || $sunday == $today_date))) {
                    $customer = Customer::find($params['customer_id']);

                    $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'office-closed-message')->first();

                    $auto_message = preg_replace("/{start_time}/i", $start_time, $auto_reply->reply);
                    $auto_message = preg_replace("/{end_time}/i", $end_time, $auto_message);

                    $params = [
                        'number' => null,
                        'user_id' => 6,
                        'approved' => 1,
                        'status' => 9,
                        'customer_id' => $params['customer_id'],
                        'message' => $auto_message
                    ];

                    sleep(1);
                    $additional_message = ChatMessage::create($params);
                    $this->sendWithWhatsApp($message->customer->phone, $customer->whatsapp_number, $additional_message->message, false, $additional_message->id);
                }
            }
        } else {
            $custom_data = json_decode($data['custom_data'], true);

            $chat_message = ChatMessage::find($custom_data['chat_message_id']);

            if ($chat_message) {
                $chat_message->sent = 1;
                $chat_message->save();
            }
        }

        return response("");
    }

    public function sendRealTime($message, $model_id, $client, $customFile = null)
    {


        return;
        $realtime_params = [
            'realtime_id' => $model_id,
            'id' => $message->id,
            'number' => $message->number,
            'assigned_to' => $message->assigned_to ?? '',
            'created_at' => Carbon::parse($message->created_at)->format('Y-m-d H:i:s'),
            'approved' => $message->approved ?? 0,
            'status' => $message->status ?? 0,
            'user_id' => $message->user_id ?? 0,
            'erp_user' => $message->erp_user ?? 0,
            'sent' => $message->sent ?? 0,
            'resent' => $message->resent ?? 0,
            'error_status' => $message->error_status ?? 0,
        ];

        // attach custom image or file here if not want to send original
        $mediaUrl = ($customFile && !empty($customFile)) ? $customFile : $message->media_url;

        if ($mediaUrl) {

            $realtime_params['media_url'] = $mediaUrl;
            $headers = get_headers($mediaUrl, 1);
            $realtime_params['content_type'] = is_string($headers["Content-Type"]) ? $headers["Content-Type"] : $headers["Content-Type"][1];

        }

        if ($message->message) {
            $realtime_params['message'] = $message->message;
        }

        $response = $client->post('https://sololuxury.co/deliver-message', [
            'form_params' => $realtime_params
        ]);

        return response('success', 200);
    }

    public function incomingMessageNew(Request $request, GuzzleClient $client)
    {
        $data = $request->json()->all();

        if ($data['event'] == 'message:in:new') {
            $to = str_replace('+', '', $data['data']['toNumber']);
            $from = str_replace('+', '', $data['data']['fromNumber']);
            $text = $data['data']['body'];
            $lead = $this->findLeadByNumber($from);
            $user = $this->findUserByNumber($from);
            $supplier = $this->findSupplierByNumber($from);
            $customer = $this->findCustomerByNumber($from);
            $dubbizle = $this->findDubbizleByNumber($from);

            $params = [
                'number' => $from,
                'message' => ''
            ];

            if ($data['data']['type'] == 'text') {
                $params['message'] = $text;
            } else {
                if ($data['data']['type'] == 'image') {
                    $image_data = $data['data']['media']['preview']['image'];
                    $image_path = public_path() . '/uploads/temp_image.png';
                    $img = Image::make(base64_decode($image_data))->encode('jpeg')->save($image_path);

                    $media = MediaUploader::fromSource($image_path)->upload();

                    File::delete('uploads/temp_image.png');
                }
            }

            if ($user) {
                $instruction = Instruction::where('assigned_to', $user->id)->latest()->first();

                if ($instruction) {
                    $myRequest = new Request();
                    $myRequest->setMethod('POST');
                    $myRequest->request->add(['remark' => $params['message'], 'id' => $instruction->id, 'module_type' => 'instruction', 'user_name' => "User from Whatsapp"]);

                    app('App\Http\Controllers\TaskModuleController')->addRemark($myRequest);
                }

                NotificationQueueController::createNewNotification([
                    'message' => $params['message'],
                    'timestamps' => ['+0 minutes'],
                    'model_type' => Instruction::class,
                    'model_id' => $instruction->id,
                    'user_id' => '6',
                    'sent_to' => $instruction->assigned_from,
                    'role' => '',
                ]);

                $params['erp_user'] = $user->id;

                if ($params['message'] != '' && (preg_match_all("/TASK ID ([\d]+)/i", $params['message'], $match))) {
                    $params['task_id'] = $match[1][0];
                }


                $params = $this->modifyParamsWithMessage($params, $data);
                $message = ChatMessage::create($params);
                $model_type = 'user';
                $model_id = $user->id;

                if (array_key_exists('task_id', $params)) {
                    $this->sendRealTime($message, 'task_' . $match[1][0], $client);
                } else {
                    $this->sendRealTime($message, 'erp_user_' . $user->id, $client);
                }

                // if ($user->id == 3) {
                //   file_put_contents(__DIR__."/response.txt", json_encode($data));
                //
                //   if (array_key_exists('quoted', $data['data'])) {
                //     $quoted_id = $data['data']['quoted']['wid'];
                //
                //     $configs = \Config::get("wassenger.api_keys");
                //     // $encodedNumber = "+" . $number;
                //     // $encodedText = $message;
                //     $wa_token = $configs[0]['key'];
                //     $wa_device = $configs[1]['device'];
                //
                //     // $array = [
                //     //   'phone' => $encodedNumber,
                //     //   'message' => (string) $encodedText,
                //     //   'reference' => (string) $chat_message_id,
                //     //   'device'  => "$wa_device",
                //     //   'enqueue' => "$enqueue",
                //     // ];
                //
                //     $curl = curl_init();
                //
                //     curl_setopt_array($curl, array(
                //       CURLOPT_URL => "https://api.wassenger.com/v1/io/$wa_device/messages/$quoted_id",
                //       CURLOPT_RETURNTRANSFER => true,
                //       CURLOPT_ENCODING => "",
                //       CURLOPT_MAXREDIRS => 10,
                //       CURLOPT_TIMEOUT => 30,
                //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //       CURLOPT_CUSTOMREQUEST => "GET",
                //       // CURLOPT_POSTFIELDS => json_encode($array),
                //       CURLOPT_HTTPHEADER => array(
                //         // "content-type: application/json",
                //         "token: $wa_token"
                //       ),
                //     ));
                //
                //     $response = curl_exec($curl);
                //     $err = curl_error($curl);
                //     $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                //
                //     curl_close($curl);
                //
                //     file_put_contents(__DIR__."/wow.txt", json_encode($response));
                //
                //     if ($err) {
                //       throw new \Exception("cURL Error #:" . $err);
                //     } else {
                //       $result = json_decode($response, true);
                //
                //       if ($http_code != 201) {
                //         throw new \Exception("Something was wrong with message: " . $result['message']);
                //       }
                //     }
                //   }
                // }
            }

            if ($supplier) {
                $params['erp_user'] = null;
                $params['task_id'] = null;
                $params['supplier_id'] = $supplier->id;

                $message = ChatMessage::create($params);
                $model_type = 'supplier';
                $model_id = $supplier->id;

                $this->sendRealTime($message, 'supplier_' . $supplier->id, $client);
            }

            if ($dubbizle) {
                $params['erp_user'] = null;
                $params['task_id'] = null;
                $params['supplier_id'] = null;
                $params['dubbizle_id'] = $dubbizle->id;

                $message = ChatMessage::create($params);
                $model_type = 'dubbizle';
                $model_id = $dubbizle->id;

                $this->sendRealTime($message, 'dubbizle_' . $dubbizle->id, $client);
            }

            if ($customer) {
                $params['erp_user'] = null;
                $params['supplier_id'] = null;
                $params['task_id'] = null;
                $params['dubbizle_id'] = null;
                $params['customer_id'] = $customer->id;

                $message = ChatMessage::create($params);

                if ($params['message']) {
                    (new KeywordsChecker())->assignCustomerAndKeywordForNewMessage($params['message'], $customer);
                }

                $model_type = 'customers';
                $model_id = $customer->id;
                $customer->update([
                    'whatsapp_number' => $to
                ]);

                $this->sendRealTime($message, 'customer_' . $customer->id, $client);

                if (Setting::get('forward_messages') == 1) {
                    if (Setting::get('forward_start_date') != null && Setting::get('forward_end_date') != null) {
                        $time = Carbon::now();
                        $start_date = Carbon::parse(Setting::get('forward_start_date'));
                        $end_date = Carbon::parse(Setting::get('forward_end_date'));

                        if ($time->between($start_date, $end_date, true)) {
                            $forward_users_ids = json_decode(Setting::get('forward_users'));
                            $second_message = '';

                            if ($message->message == null) {
                                $forwarded_message = "FORWARDED from $customer->name";
                                $second_message = $message->media_url;
                            } else {
                                $forwarded_message = "FORWARDED from $customer->name - " . $message->message;
                            }

                            foreach ($forward_users_ids as $user_id) {
                                $user = User::find($user_id);

                                // $this->sendWithWhatsApp($user->phone, $user->whatsapp_number, $forwarded_message, FALSE, $message->id);
                                $this->sendWithNewApi($user->phone, $user->whatsapp_number, $forwarded_message, null, $message->id);

                                if ($second_message != '') {
                                    // $this->sendWithWhatsApp($user->phone, $user->whatsapp_number, $second_message, FALSE, $message->id);
                                    $this->sendWithNewApi($user->phone, $user->whatsapp_number, null, $second_message, $message->id);
                                }
                            }
                        }
                    } else {
                        $forward_users_ids = json_decode(Setting::get('forward_users'));
                        $second_message = '';

                        if ($message->message == null) {
                            $forwarded_message = "FORWARDED from $customer->name";
                            $second_message = $message->media_url;
                        } else {
                            $forwarded_message = "FORWARDED from $customer->name - " . $message->message;
                        }

                        foreach ($forward_users_ids as $user_id) {
                            $user = User::find($user_id);

                            // $this->sendWithWhatsApp($user->phone, $user->whatsapp_number, $forwarded_message, FALSE, $message->id);
                            $this->sendWithNewApi($user->phone, $user->whatsapp_number, $forwarded_message, null, $message->id);

                            if ($second_message != '') {
                                // $this->sendWithWhatsApp($user->phone, $user->whatsapp_number, $second_message, FALSE, $message->id);
                                $this->sendWithNewApi($user->phone, $user->whatsapp_number, null, $second_message, $message->id);
                            }
                        }
                    }
                }

                // Auto DND
                if (array_key_exists('message', $params) && strtoupper($params['message']) == 'DND') {
                    if ($customer = Customer::find($params['customer_id'])) {
                        $customer->do_not_disturb = 1;
                        $customer->save();
                        \Log::channel('customerDnd')->debug("(Customer ID " . $customer->id . " line " . $customer->name . " " . $customer->number . ": Added To DND");

                        $dnd_params = [
                            'number' => null,
                            'user_id' => 6,
                            'approved' => 1,
                            'status' => 9,
                            'customer_id' => $customer->id,
                            'message' => AutoReply::where('type', 'auto-reply')->where('keyword', 'customer-dnd')->first()->reply
                        ];

                        $auto_dnd_message = ChatMessage::create($dnd_params);

                        // $this->sendWithWhatsApp($customer->phone, $customer->whatsapp_number, $dnd_params['message'], FALSE, $auto_dnd_message->id);
                        $this->sendWithNewApi($customer->phone, $customer->whatsapp_number, $dnd_params['message'], null, $auto_dnd_message->id);
                    }
                }

                // Auto Instruction
                if (array_key_exists('message', $params) && (preg_match("/price/i", $params['message']) || preg_match("/you photo/i", $params['message']) || preg_match("/pp/i", $params['message']) || preg_match("/how much/i", $params['message']) || preg_match("/cost/i", $params['message']) || preg_match("/rate/i", $params['message']))) {
                    if ($customer = Customer::find($params['customer_id'])) {
                        $two_hours = Carbon::now()->subHours(2);
                        $latest_broadcast_message = ChatMessage::where('customer_id', $customer->id)->where('created_at', '>', $two_hours)->where('status', 8)->latest()->first();

                        if ($latest_broadcast_message) {
                            if (!$latest_broadcast_message->isSentBroadcastPrice()) {
                                if ($latest_broadcast_message->hasMedia(config('constants.media_tags'))) {
                                    $selected_products = [];

                                    foreach ($latest_broadcast_message->getMedia(config('constants.media_tags')) as $image) {
                                        $image_key = $image->getKey();
                                        $mediable_type = "BroadcastImage";

                                        $broadcast = BroadcastImage::with('Media')
                                            ->whereRaw("broadcast_images.id IN (SELECT mediables.mediable_id FROM mediables WHERE mediables.media_id = $image_key AND mediables.mediable_type LIKE '%$mediable_type%')")
                                            ->first();

                                        if ($broadcast) {
                                            $brod_products = json_decode($broadcast->products, true);

                                            if (count($brod_products) > 0) {
                                                foreach ($brod_products as $brod_pro) {
                                                    $selected_products[] = $brod_pro;
                                                }
                                            }
                                        }
                                    }

                                    if (isset($broadcast)) {
                                        foreach ($selected_products as $pid) {
                                            $product = \App\Product::where("id", $pid)->first();
                                            $quick_lead = \App\ErpLeads::create([
                                                'customer_id' => $customer->id,
                                                //'rating' => 1,
                                                'lead_status_id' => 3,
                                                //'assigned_user' => 6,
                                                'product_id' => $pid,
                                                'brand_id' => $product ? $product->brand : null,
                                                'category_id' => $product ? $product->category : null,
                                                'brand_segment' => $product && $product->brands ? $product->brands->brand_segment : null,
                                                'color' => $customer->color,
                                                'size' => $customer->size,
                                                'created_at' => Carbon::now()
                                            ]);
                                        }

                                        $requestData = new Request();
                                        $requestData->setMethod('POST');
                                        $requestData->request->add(['customer_id' => $customer->id, 'lead_id' => $quick_lead->id, 'selected_product' => $selected_products]);

                                        app('App\Http\Controllers\LeadsController')->sendPrices($requestData);

                                        CommunicationHistory::create([
                                            'model_id' => $latest_broadcast_message->id,
                                            'model_type' => ChatMessage::class,
                                            'type' => 'broadcast-prices',
                                            'method' => 'whatsapp'
                                        ]);
                                    } else {
                                        // Instruction::create([
                                        //   'customer_id' => $customer->id,
                                        //   'instruction' => 'Please send the prices',
                                        //   'category_id' => 1,
                                        //   'assigned_to' => 7,
                                        //   'assigned_from' => 6
                                        // ]);
                                    }
                                }
                            }
                        }

                        Instruction::create([
                            'customer_id' => $customer->id,
                            'instruction' => 'Please send the prices',
                            'category_id' => 1,
                            'assigned_to' => 7,
                            'assigned_from' => 6
                        ]);
                    }
                }

                // Auto Replies
                $auto_replies = AutoReply::all();

                foreach ($auto_replies as $auto_reply) {
                    if (array_key_exists('message', $params) && $params['message'] != '') {
                        $keyword = $auto_reply->keyword;

                        if (preg_match("/{$keyword}/i", $params['message'])) {
                            $temp_params = $params;
                            $temp_params['message'] = $auto_reply->reply;
                            $temp_params['status'] = 1;

                            ChatMessage::create($temp_params);

                            $this->sendRealTime($message, 'customer_' . $customer->id, $client);
                        }
                    }
                }
            }

            if (!isset($user) && !isset($purchase) && !isset($customer)) {
                $modal_type = 'leads';
                // $new_name = "whatsapp lead " . uniqid( TRUE );
                $user = User::get()[0];
                $validate_phone['phone'] = $from;

                $validator = Validator::make($validate_phone, [
                    'phone' => 'unique:customers,phone'
                ]);

                if ($validator->fails()) {

                } else {
                    $customer = new Customer;
                    $customer->name = $from;
                    $customer->phone = $from;
                    $customer->rating = 2;
                    $customer->save();

                    $lead = \App\ErpLeads::create([
                        'customer_id' => $customer->id,
                        //'client_name' => $from,
                        //'contactno' => $from,
                        //'rating' => 2,
                        'lead_status_id' => 1,
                        //'assigned_user' => $user->id,
                        //'userid' => $user->id,
                        //'whatsapp_number' => $to
                    ]);

                    $params['lead_id'] = $lead->id;
                    $params['customer_id'] = $customer->id;

                    $message = ChatMessage::create($params);
                    $model_type = 'leads';
                    $model_id = $lead->id;

                    $this->sendRealTime($message, 'customer_' . $customer->id, $client);
                }
            }

            // Auto Respond
            $today_date = Carbon::now()->format('Y-m-d');
            $time = Carbon::now();
            $start_time = Setting::get('start_time');
            $start_time_exploded = explode(':', $start_time);
            $end_time = Setting::get('end_time');
            $end_time_exploded = explode(':', $end_time);
            $morning = Carbon::create($time->year, $time->month, $time->day, $start_time_exploded[0], $start_time_exploded[1], 0);
            $not_morning = Carbon::create($time->year, $time->month, $time->day, 0, 0, 0);
            $evening = Carbon::create($time->year, $time->month, $time->day, $end_time_exploded[0], $end_time_exploded[1], 0);
            $not_evening = Carbon::create($time->year, $time->month, $time->day, 23, 59, 0);
            $saturday = Carbon::now()->endOfWeek()->subDay()->format('Y-m-d');
            $sunday = Carbon::now()->endOfWeek()->format('Y-m-d');

            $chat_messages_query = ChatMessage::where('customer_id', $params['customer_id'])->whereBetween('created_at', [$morning, $evening])->whereNotNull('number');
            $chat_messages_count = $chat_messages_query->count();

            $chat_messages_evening_query = ChatMessage::where('customer_id', $params['customer_id'])->where(function ($query) use ($not_morning, $morning, $evening, $not_evening) {
                $query->whereBetween('created_at', [$not_morning, $morning])->orWhereBetween('created_at', [$evening, $not_evening]);
            })->whereNotNull('number');
            $chat_messages_evening_count = $chat_messages_evening_query->count();

            if ($chat_messages_count == 1) {
                $chat_messages_query_first = $chat_messages_query->first();
            }

            if ($chat_messages_evening_count == 1) {
                $chat_messages_evening_query_first = $chat_messages_evening_query->first();
            }

            if ($chat_messages_count == 1 && (isset($chat_messages_query_first) && $chat_messages_query_first->id == $message->id) && ($saturday != $today_date && $sunday != $today_date)) {
                $customer = Customer::find($params['customer_id']);
                $params = [
                    'number' => null,
                    'user_id' => 6,
                    'approved' => 1,
                    'status' => 9,
                    'customer_id' => $params['customer_id']
                ];

                if ($time->between($morning, $evening, true)) {
                    $params['message'] = AutoReply::where('type', 'auto-reply')->where('keyword', 'work-hours-greeting')->first()->reply;

                    sleep(1);
                    $additional_message = ChatMessage::create($params);
                    // $this->sendWithWhatsApp($message->customer->phone, $customer->whatsapp_number, $additional_message->message, FALSE, $additional_message->id);
                    $this->sendWithNewApi($message->customer->phone, $customer->whatsapp_number, $additional_message->message, null, $additional_message->id);
                }
            } else {
                if (($chat_messages_evening_count == 1 && (isset($chat_messages_evening_query_first) && $chat_messages_evening_query_first->id == $message->id)) || ($chat_messages_count == 1 && ($saturday == $today_date || $sunday == $today_date))) {
                    $customer = Customer::find($params['customer_id']);

                    $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'office-closed-message')->first();

                    $auto_message = preg_replace("/{start_time}/i", $start_time, $auto_reply->reply);
                    $auto_message = preg_replace("/{end_time}/i", $end_time, $auto_message);

                    $params = [
                        'number' => null,
                        'user_id' => 6,
                        'approved' => 1,
                        'status' => 9,
                        'customer_id' => $params['customer_id'],
                        'message' => $auto_message
                    ];

                    sleep(1);
                    $additional_message = ChatMessage::create($params);
                    // $this->sendWithWhatsApp($message->customer->phone, $customer->whatsapp_number, $additional_message->message, FALSE, $additional_message->id);
                    $this->sendWithNewApi($message->customer->phone, $customer->whatsapp_number, $additional_message->message, null, $additional_message->id);
                }
            }

            if ($data['data']['type'] == 'image') {
                $media->move('chatmessage/' . floor($message->id / config('constants.image_per_folder')));
                $message->attachMedia($media, config('constants.media_tags'));
            }
        } else {
            $custom_data = json_decode($data['custom_data'], true);

            $chat_message = ChatMessage::find($custom_data['chat_message_id']);

            if ($chat_message) {
                $chat_message->sent = 1;
                $chat_message->save();
            }
        }

        return response("success", 200);
    }

    public function webhook(Request $request, GuzzleClient $client)
    {
        // Get json object
        $data = $request->json()->all();
        $needToSendLeadPrice = false;
        $isReplied = false;
        // Log incoming webhook
        \Log::channel('chatapi')->debug('Webhook: ' . json_encode($data));
        // Check for ack
        if (array_key_exists('ack', $data)) {
            ChatMessage::handleChatApiAck($data);
        }

        // Check for messages
        if (!array_key_exists('messages', $data)) {
            return Response::json('ACK', 200);
        }
        // Loop over messages
        foreach ($data['messages'] as $chatapiMessage) {
            $quoted_message_id = null;
            // Convert false and true text to false and true
            if ($chatapiMessage['fromMe'] === "false") {
                $chatapiMessage['fromMe'] = false;
            }
            if ($chatapiMessage['fromMe'] === "true") {
                $chatapiMessage['fromMe'] = true;
            }

            $parentMessage = null;

            try {
                // check if quotedMsgId is available, if available then we will search for parent message
                if (isset($chatapiMessage['quotedMsgId'])) {
                    $parentMessage = ChatMessage::where('unique_id', $chatapiMessage['quotedMsgId'])->first();
                    if ($parentMessage) {
                        $quoted_message_id = $parentMessage->id;
                    }
                }
            } catch (\Exception $e) {
                //continue
            }

            // Set default parameters
            $from = str_replace('@c.us', '', $chatapiMessage['author']);
            $instanceId = $data['instanceId'];
            $text = $chatapiMessage['body'];
            $contentType = $chatapiMessage['type'];
            $numberPath = substr($from, 0, 3) . '/' . substr($from, 3, 1);

            // Check if message already exists
            $chatMessage = ChatMessage::where('unique_id', $chatapiMessage['id'])->first();
            if ($chatMessage != null) {
                //continue;
            }

            // Find connection with this number in our database
            if ($chatapiMessage['fromMe'] == true) {
                $searchNumber = str_replace('@c.us', '', $chatapiMessage['chatId']);
            } else {
                $searchNumber = $from;
            }

            // Find objects by number
            $supplier = $this->findSupplierByNumber($searchNumber);
            $vendor = $this->findVendorByNumber($searchNumber);
            $user = $this->findUserByNumber($searchNumber);
            $dubbizle = $this->findDubbizleByNumber($searchNumber);
            $contact = $this->findContactByNumber($searchNumber);
            $customer = $this->findCustomerByNumber($searchNumber);

            // check the message related to the supplier 
            $sendToSupplier = false;
            if (!empty($text)) {
                $matchSupplier = explode("-", $text);
                if (
                    isset($matchSupplier[0]) && $matchSupplier[0] == "S"
                    && isset($matchSupplier[1]) && is_numeric($matchSupplier[1])
                ) {
                    $sendToSupplier = true;
                    $supplier = Supplier::find($matchSupplier[1]);
                }
            }

            if(!empty($customer)) {
                /*try {
                    $customerDetails = is_object($customer) ? Customer::find($customer->id) : $customer;
                    $language = $customerDetails->language;

                    if(empty($language)){
                        //Translate Google API
                        $translate = new TranslateClient([
                            'key' => getenv('GOOGLE_TRANSLATE_API_KEY')
                        ]);
                        $result = $translate->detectLanguage($text);
                        $language = $result['languageCode'] ? $result['languageCode'] : 'en';
                        $customerDetails->language = $language;
                        $customerDetails->update();
                    }

                    $fromLang = $language;
                    $toLang = "en";
                    if($sendToSupplier) {
                        $fromLang   = "en";
                        $toLang     = $language;
                    }

                    $result = TranslationHelper::translate($fromLang, $toLang, $text);
                    if($sendToSupplier) {
                        $text = $result;
                    }else {
                        $text = $result.' -- '.$text;
                    }
                }catch(\Exception $e) {
                    \Log::info("Message with google api ".self::class."__".__FUNCTION__."_".__LINE__);
                }*/
            }
            
            if (!empty($supplier) && $contentType !== 'image') {
                $supplierDetails = is_object($supplier) ? Supplier::find($supplier->id) : $supplier;
                $language = $supplierDetails->language;
                if ($language != null) {
                    $fromLang = $language;
                    $toLang = "en";

                    if ($sendToSupplier) {
                        $fromLang = "en";
                        $toLang = $language;
                    }

                    $result = TranslationHelper::translate($fromLang, $toLang, $text);
                    if ($sendToSupplier) {
                        $text = $result;
                    } else {
                        $text = $result . ' -- ' . $text;
                    }
                }
            }
            $originalMessage = $text;
            // Set params
            $params = [
                'number' => $from,
                'unique_id' => $chatapiMessage['id'],
                'message' => '',
                'media_url' => null,
                'approved' => $chatapiMessage['fromMe'] ? 1 : 0,
                'status' => $chatapiMessage['fromMe'] ? 2 : 0,
                'contact_id' => null,
                'erp_user' => null,
                'supplier_id' => null,
                'task_id' => null,
                'dubizzle_id' => null,
                'vendor_id' => null,
                'customer_id' => null,
                'quoted_message_id' => $quoted_message_id
            ];

            try {
                // check if time exist then convert and assign it
                if (isset($chatapiMessage['time'])) {
                    $params['created_at'] = date("Y-m-d H:i:s", $chatapiMessage['time']);
                }
            } catch (\Exception $e) {
                //If the date format is causing issue from whats app script messages
                $params['created_at'] = $chatapiMessage['time'];
            }

            // Check if the message is a URL
            if (filter_var($text, FILTER_VALIDATE_URL)) {
                if (substr($text, 0, 23) == 'https://firebasestorage') {
                    // Try to download the image
                    try {
                        // Get file extension
                        $extension = preg_replace("#\?.*#", "", pathinfo($text, PATHINFO_EXTENSION)) . "\n";

                        // Set tmp file
                        $filePath = public_path() . '/uploads/tmp_' . rand(0, 100000) . '.' . trim($extension);

                        // Copy URL to file path
                        copy($text, $filePath);

                        // Upload media
                        $media = MediaUploader::fromSource($filePath)->useFilename(uniqid(true, true))->toDisk('uploads')->toDirectory('chat-messages/' . $numberPath)->upload();

                        // Delete the file
                        unlink($filePath);

                        // Update media URL
                        $params['media_url'] = $media->getUrl();
                        $params['message'] = isset($chatapiMessage['caption']) ? $chatapiMessage['caption'] : '';
                    } catch (\Exception $exception) {
                        \Log::error($exception);
                        //
                    }
                } else {
                    try {
                        $extension = preg_replace("#\?.*#", "", pathinfo($text, PATHINFO_EXTENSION)) . "\n";
                        // Set tmp file
                        $filePath = public_path() . '/uploads/tmp_' . rand(0, 100000) . '.' . trim($extension);
                        // Copy URL to file path
                        copy($text, $filePath);
                        // Upload media
                        $media = MediaUploader::fromSource($filePath)->useFilename(uniqid(true, true))->toDisk('uploads')->toDirectory('chat-messages/' . $numberPath)->upload();
                        // Delete the file
                        unlink($filePath);
                        // Update media URL
                        $params['media_url'] = $media->getUrl();
                        $params['message'] = isset($chatapiMessage['caption']) ? $chatapiMessage['caption'] : '';
                    } catch (\Exception $exception) {
                        \Log::error($exception);
                        $params['message'] = $text;
                    }
                }
            } else {
                $params['message'] = $text;
            }

// From me? Only store, nothing else
            if ($chatapiMessage['fromMe'] == true) {
                // Set objects
                $params['erp_user'] = isset($user->id) ? $user->id : null;
                $params['supplier_id'] = isset($supplier->id) ? $supplier->id : null;
                $params['task_id'] = null;
                $params['dubbizle_id'] = isset($dubbizle->id) ? $dubbizle->id : null;
                $params['vendor_id'] = isset($vendor->id) && !isset($customer->id) ? $vendor->id : null;
                $params['customer_id'] = isset($customer->id) ? $customer->id : null;

                // Remove number
                $params['number'] = null;

                // Set unique ID
                $params['unique_id'] = $chatapiMessage['id'];

                // Check for duplicate vendor message
                if (isset($vendor->id)) {
                    // Find duplicate message
                    $duplicateChatMessage = ChatMessage::where('vendor_id', $vendor->id)->where('message', $params['message'])->first();

                    // Set vendor ID to null if message is found
                    if ($duplicateChatMessage != null) {
                        $params['vendor_id'] = null;
                    }
                }
                // Create message
                $message = ChatMessage::create($params);

                // Continue to the next record
                continue;
            }
            
            $userId = $supplierId = $contactId = $vendorId = $dubbizleId = $customerId = null;

            if ($user != null) {
                $userId = $user->id;
            }

            if ($contact != null) {
                $contactId = $contact->id;
            }

            if ($supplier != null) {
                $supplierId = $supplier->id;
            }

            if ($vendor != null) {
                $vendorId = $vendor->id;
            }

            if ($dubbizle != null) {
                $dubbizleId = $dubbizle->id;
            }

            if ($customer != null) {
                $customerId = $customer->id;
            }

            $params['user_id'] = $userId;
            $params['contact_id'] = $contactId;
            $params['supplier_id'] = $supplierId;
            $params['vendor_id'] = $vendorId;
            $params['dubbizle_id'] = $dubbizleId;
            $params['customer_id'] = $customerId;

            if( $vendor ){
                $params['user_type'] = 1;
            }
            


            if (!empty($user) || !empty($contact) || !empty($supplier) || !empty($vendor) || !empty($dubbizle) || !empty($customer)) {

                // check that if message comes from customer,supplier,vendor
                if (!empty($customer)) {
                    $blockCustomer = \App\BlockWebMessageList::where("object_id", $customer->id)->where("object_type", Customer::class)->first();
                    if ($blockCustomer) {
                        $blockCustomer->delete();
                    }
                }
                // check for vendor and remvove from the list
                if (!empty($vendor)) {
                    $blockVendor = \App\BlockWebMessageList::where("object_id", $vendor->id)->where("object_type", Vendor::class)->first();
                    if ($blockVendor) {
                        $blockVendor->delete();
                    }
                }
                // check for supplier and remove from the list
                if (!empty($supplier)) {
                    $blockSupplier = \App\BlockWebMessageList::where("object_id", $supplier->id)->where("object_type", Supplier::class)->first();
                    if ($blockSupplier) {
                        $blockSupplier->delete();
                    }
                }
                $message = ChatMessage::create($params);


            } else {
                // create a customer here
                $customer = Customer::create([
                    "name" => $from,
                    "phone" => $from
                ]);
                $params["customer_id"] = $customer->id;
                $message = ChatMessage::create($params);
            }

            if ($customer != null) {

                ChatMessagesQuickData::updateOrCreate([
                    'model' => \App\Customer::class,
                    'model_id' => $params['customer_id']
                ], [
                    'last_unread_message' => @$params['message'],
                    'last_unread_message_at' => Carbon::now(),
                    'last_unread_message_id' => $message->id,
                ]);

                // this is for testing only please do not proceed with the below line
                // WatsonManager::sendMessage($customer,$params['message'],false , null , $message);
                // die;
            }

            // Is there a user linked to this number?
            if ($user) {
                // Add user ID to params

                // Check for task
                if ($params['message'] != '' && (preg_match_all("/#([\d]+)/i", $params['message'], $match))) {
                    // If task is found
                    if ($task = Task::find($match[1][0])) {
                        // Set the task_id parameter
                        $params['task_id'] = $match[1][0];

                        // Check for task users and set ERP user
                        if (count($task->users) > 0) {
                            if ($task->assign_from == $user->id) {
                                $params['erp_user'] = $task->assign_to;
                            } else {
                                $params['erp_user'] = $task->assign_from;
                            }
                        }

                        // Check for task contacts and set contact_id
                        if (count($task->contacts) > 0) {
                            if ($task->assign_from == $user->id) {
                                $params['contact_id'] = $task->assign_to;
                            } else {
                                $params['contact_id'] = $task->assign_from;
                            }
                        }
                    }
                }

                // Set media_url parameter
                if (isset($media)) {
                    $params['media_url'] = $media->getUrl();
                }


                // Attach media to message
                if (isset($media)) {
                    $message->attachMedia($media, config('constants.media_tags'));
                }

                // Send realtime message (???) if there is a task ID
                if (array_key_exists('task_id', $params) && !empty($params['task_id'])) {
                    $this->sendRealTime($message, 'task_' . $task->id, $client);
                } else {
                    $this->sendRealTime($message, 'user_' . $user->id, $client);
                }
            }

            // Is there a contact linked to this number?
            if ($contact) {


                // Check for task ID
                if ($params['message'] != '' && (preg_match_all("/#([\d]+)/i", $params['message'], $match))) {
                    $params['task_id'] = $match[1][0];
                }

                if (array_key_exists('task_id', $params) && !empty($params['task_id'])) {
                    $this->sendRealTime($message, 'task_' . $match[1][0], $client);
                } else {
                    $this->sendRealTime($message, 'user_' . $contact->id, $client);
                }
            }

            if ($supplier) {

                if ($params['media_url'] != null) {
                    self::saveProductFromSupplierIncomingImages($supplier->id, $params['media_url']);
                }
            }

            // Check for vendor
            if ($vendor) {
                // Set vendor category
                $category = $vendor->category;

                // Send message if all required data is set
                if ($category && $category->user_id && ($params['message'] || $params['media_url'])) {
                    $user = User::find($category->user_id);
                    $sendResult = $this->sendWithThirdApi($user->phone, $user->whatsapp_number, 'V-' . $vendor->id . '-(' . $vendor->name . ')=> ' . $params['message'], $params['media_url']);
                    if ($sendResult) {
                        $message->unique_id = $sendResult['id'] ?? '';
                        $message->save();
                    }
                }
                
                $vendor->store_website_id = 1;
                //\App\Helpers\MessageHelper::sendwatson( $vendor, $params['message'], true , $message, null, null, $userType = 'vendor');
            }

            // check if the supplier message has been set then we need to send that message to erp user
            if ($supplier) {

                $phone = $supplier->phone;
                $whatsapp = $supplier->whatsapp_number;
                if (!$sendToSupplier) {
                    $phone = ChatMessage::getSupplierForwardTo();
                }

                $textMessage = ($sendToSupplier) ? $params['message'] : 'S-' . $supplier->id . '-(' . $supplier->supplier . ')=> ' . $params['message'];
                $sendResult = $this->sendWithThirdApi($phone, $whatsapp, $textMessage, $params['media_url']);
                if ($sendResult) {
                    $message->unique_id = $sendResult['id'] ?? '';
                    $message->save();
                }
            }

            if ($dubbizle) {

                $model_type = 'dubbizle';
                $model_id = $dubbizle->id;

                $this->sendRealTime($message, 'dubbizle_' . $dubbizle->id, $client);

                $params['dubbizle_id'] = null;
            }

            if($supplier && $message) {
                \App\ChatbotReply::create([
                    'question'=> $params['message'],
                    'replied_chat_id' => $message->id,
                    'reply_from' => 'database'
                ]);
            }else if ($vendor && $message) {
                \App\ChatbotReply::create([
                    'question'=> $params['message'],
                    'replied_chat_id' => $message->id,
                    'reply_from' => 'database'
                ]);
            }
            
            // }

            // Get all numbers from config
            $config = \Config::get("apiwha.instances");

            // Set isCustomerNumber to false by default
            $isCustomerNumber = false;
            // Loop over instance IDs to check if the whatsapp number is used for incoming messages from customers
            foreach ($config as $whatsAppNumber => $arrNumber) {
                if ($arrNumber['instance_id'] == $instanceId) {
                    $to = $whatsAppNumber;
                    $isCustomerNumber = $arrNumber['customer_number'];
                    $instanceNumber = $whatsAppNumber;
                }
            }

            /// Also get all numbers from database
            if (!$isCustomerNumber && $customer != null) {
                $whatsappConfigs = WhatsappConfig::where('is_customer_support', 0)->get();

                // Loop over whatsapp configs
                if ($whatsappConfigs !== null) {
                    foreach ($whatsappConfigs as $whatsappConfig) {
                        if ($whatsappConfig->username == $instanceId) {
                            $isCustomerNumber = $whatsappConfig->number;
                            $instanceNumber = $whatsappConfig->number;
                        }
                    }
                }
            }

            // No to?
            if (empty($to)) {
                $to = $config[0]['number'];
            }
            if ($customer) {
                (new \App\Services\Products\SendImagesOfProduct)->check($message);
                \App\Helpers\MessageHelper::whatsAppSend( $customer, $params['message'], true , $message , false, $parentMessage);
            }
            // Is this message from a customer?
            if ($customer && $isCustomerNumber) {
                if ($params['message']) {
                    (new KeywordsChecker())->assignCustomerAndKeywordForNewMessage($params['message'], $customer);
                }

                if (isset($media))
                    if ($contentType === 'image') {
                        $message->attachMedia($media, $contentType);
                        $message->save();
                    }

                $model_type = 'customers';
                $model_id = $customer->id;
                $customer->update([
                    'whatsapp_number' => $to
                ]);

                $this->sendRealTime($message, 'customer_' . $customer->id, $client);

                if (Setting::get('forward_messages') == 1) {
                    if (Setting::get('forward_start_date') != null && Setting::get('forward_end_date') != null) {
                        $time = Carbon::now();
                        $start_date = Carbon::parse(Setting::get('forward_start_date'));
                        $end_date = Carbon::parse(Setting::get('forward_end_date'));

                        if ($time->between($start_date, $end_date, true)) {
                            $forward_users_ids = json_decode(Setting::get('forward_users'));
                            $second_message = '';

                            if ($message->message == null) {
                                $forwarded_message = "FORWARDED from $customer->name";
                                $second_message = $message->media_url;
                            } else {
                                $forwarded_message = "FORWARDED from $customer->name - " . $message->message;
                            }

                            foreach ($forward_users_ids as $user_id) {
                                $user = User::find($user_id);

                                // $this->sendWithWhatsApp($user->phone, $user->whatsapp_number, $forwarded_message, FALSE, $message->id);
                                $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $forwarded_message, null, $message->id);

                                if ($second_message != '') {
                                    $this->sendWithThirdApi($user->phone, $user->whatsapp_number, null, $second_message, $message->id);
                                }
                            }
                        }
                    } else {
                        $forward_users_ids = json_decode(Setting::get('forward_users'));
                        $second_message = '';

                        if ($message->message == null) {
                            $forwarded_message = "FORWARDED from $customer->name";
                            $second_message = $message->media_url;
                        } else {
                            $forwarded_message = "FORWARDED from $customer->name - " . $message->message;
                        }

                        foreach ($forward_users_ids as $user_id) {
                            $user = User::find($user_id);

                            $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $forwarded_message, null, $message->id);

                            if ($second_message != '') {
                                $this->sendWithThirdApi($user->phone, $user->whatsapp_number, null, $second_message, $message->id);
                            }
                        }
                    }
                }

                // Auto DND
                if (array_key_exists('message', $params) && strtoupper($params['message']) == 'DND') {
                    if ($customer = Customer::find($params['customer_id'])) {
                        $customer->do_not_disturb = 1;
                        $customer->save();
                        \Log::channel('customerDnd')->debug("(Customer ID " . $customer->id . " line " . $customer->name . " " . $customer->number . ": Added To DND");

                        $dnd_params = [
                            'number' => null,
                            'user_id' => 6,
                            'approved' => 1,
                            'status' => 9,
                            'customer_id' => $customer->id,
                            'quoted_message_id' => $quoted_message_id,
                            'message' => AutoReply::where('type', 'auto-reply')->where('keyword', 'customer-dnd')->first()->reply
                        ];
                        $auto_dnd_message = ChatMessage::create($dnd_params);

                        $this->sendWithThirdApi($customer->phone, $customer->whatsapp_number, $dnd_params['message'], null, $auto_dnd_message->id);
                    }
                }

                // Auto Instruction
                if ($params['customer_id'] != '1000' && $params['customer_id'] != '976') {
                    if ($customer = Customer::find($params['customer_id'])) {
                        \Log::info("#1 Price for customer send function started");
                        \App\Helpers\MessageHelper::sendwatson( $customer, $params['message'], true, $message , $params, false, 'customer');
                    }
                }

                
                //Auto reply
                if (isset($customer->id) && $customer->id > 0) {

                    // Auto Replies
                    // $auto_replies = AutoReply::where('is_active', 1)->get();
//                    $auto_replies = ChatbotQuestion::join('chatbot_question_examples', 'chatbot_questions.id', 'chatbot_question_examples.chatbot_question_id')->where('erp_or_watson', 'erp')->select('chatbot_questions.*', 'chatbot_question_examples.question')->get();
//                    foreach ($auto_replies as $auto_reply) {
//                        if ($customer && array_key_exists('message', $params) && $params['message'] != '') {
//                            $keyword = $auto_reply->question;
//                            if ($auto_reply->keyword_or_question == 'intent') {
//                                if ($keyword == $params['message'] && $auto_reply->suggested_reply) {
//                                    $temp_params = $params;
//                                    $temp_params['message'] = $auto_reply->suggested_reply;
//                                    $temp_params['media_url'] = null;
//                                    $temp_params['status'] = 8;
//
//                                    // Create new message
//                                    $message = ChatMessage::create($temp_params);
//
//                                    // Send message if all required data is set
//                                    if ($temp_params['message'] || $temp_params['media_url']) {
//                                        $sendResult = $this->sendWithThirdApi($customer->phone, isset($instanceNumber) ? $instanceNumber : null, $temp_params['message'], $temp_params['media_url']);
//                                        if ($sendResult) {
//                                            $message->unique_id = $sendResult['id'] ?? '';
//                                            $message->save();
//                                        }
//                                        break;
//                                    }
//                                }
//                            } else {
//                                if (preg_match("/{$keyword}/i", $params['message']) && $auto_reply->suggested_reply) {
//                                    $temp_params = $params;
//                                    $temp_params['message'] = $auto_reply->suggested_reply;
//                                    $temp_params['media_url'] = null;
//                                    $temp_params['status'] = 8;
//
//                                    // Create new message
//                                    $message = ChatMessage::create($temp_params);
//
//                                    // Send message if all required data is set
//                                    if ($temp_params['message'] || $temp_params['media_url']) {
//                                        $sendResult = $this->sendWithThirdApi($customer->phone, isset($instanceNumber) ? $instanceNumber : null, $temp_params['message'], $temp_params['media_url']);
//                                        if ($sendResult) {
//                                            $message->unique_id = $sendResult['id'] ?? '';
//                                            $message->save();
//                                        }
//                                        break;
//                                    }
//                                }
//                            }
//                        }
//                    }

                    // start to check with watson api directly
                    /*if(!$isReplied) {
                        if(!empty($params['message'])) {
                            if ($customer && $params[ 'message' ] != '') {
                                WatsonManager::sendMessage($customer,$params['message']);
                            }
                        }
                    }*/
                }
            }
            // Moved to the bottom of this loop, since it overwrites the message
            $fromMe = $chatapiMessage['fromMe'] ?? true;
            $params['message'] = $originalMessage;
            if (!$fromMe && $params['message'] && strpos($originalMessage, 'V-') === 0) {
                $msg = $params['message'];
                $msg = explode(' ', $msg);
                $vendorData = $msg[0];
                $vendorId = trim(str_replace('V-', '', $vendorData));
                $message = str_replace('V-' . $vendorId, '', $params['message']);

                $vendor = Vendor::find($vendorId);
                if (!$vendor) {
                    return response('success');
                }

                $params['vendor_id'] = $vendorId;
                $params['customer_id'] = null;
                $params['approved'] = 1;
                $params['message'] = $message;
                $params['status'] = 2;

                $this->sendWithThirdApi($vendor->phone, $vendor->whatsapp_number, $params['message'], $params['media_url']);
                ChatMessage::create($params);
            }

            if (!$fromMe && strpos($originalMessage, '#ISSUE-') === 0) {
                $m = new ChatMessage();
                $message = str_replace('#ISSUE-', '', $originalMessage);
                $m->issue_id = explode(' ', $message)[0];
                $m->user_id = isset($user->id) ? $user->id : null;
                $m->message = $originalMessage;
                $m->quoted_message_id = $quoted_message_id;
                $m->save();
            }

            if (!$fromMe && strpos($originalMessage, '#DEVTASK-') === 0) {
                $m = new ChatMessage();
                $message = str_replace('#DEVTASK-', '', $originalMessage);
                $m->developer_task_id = explode(' ', $message)[0];
                $m->user_id = isset($user->id) ? $user->id : null;
                $m->message = $originalMessage;
                $m->quoted_message_id = $quoted_message_id;
                $m->save();
            }
        }

        return Response::json('success', 200);
    }

    public function outgoingProcessed(Request $request)
    {
        $data = $request->json()->all();

        // file_put_contents(__DIR__."/outgoing.txt", json_encode($data));

        foreach ($data as $event) {
            $chat_message = ChatMessage::find($event->data->reference);

            if ($chat_message) {
                $chat_message->sent = 1;
                $chat_message->save();
            }
        }

        return response("success", 200);
    }

    public function getAllMessages(Request $request)
    {
        $params = [];
        $result = [];
        // $skip = $request->page && $request->page > 1 ? $request->page * 10 : 0;

        // $messages = ChatMessage::select(['id', 'customer_id', 'number', 'user_id', 'assigned_to', 'approved', 'status', 'sent', 'created_at', 'media_url', 'message'])->where('customer_id', $request->customerId)->latest();
        if ($request->customerId) {
            $column = 'customer_id';
            $value = $request->customerId;
        } else {
            if ($request->supplierId) {
                $column = 'supplier_id';
                $value = $request->supplierId;
            } else {
                if ($request->taskId) {
                    $column = 'task_id';
                    $value = $request->taskId;
                } else {
                    if ($request->erpUser) {
                        $column = 'erp_user';
                        $value = $request->erpUser;
                    } else {
                        if ($request->dubbizleId) {
                            $column = 'dubbizle_id';
                            $value = $request->dubbizleId;
                        } else {
                            $column = 'customer_id';
                            $value = $request->customerId;
                        }
                    }
                }
            }
        }


//      $messages = ChatMessage::select(['id', 'customer_id', 'number', 'user_id', 'erp_user', 'assigned_to', 'approved', 'status', 'sent', 'error_status', 'resent', 'created_at', 'media_url', 'message'])->where($column, $value)->where('status', '!=', 7);
        $messages = DB::select('
                  SELECT chat_messages.id, chat_messages.customer_id, chat_messages.number, chat_messages.user_id, chat_messages.erp_user, chat_messages.assigned_to, chat_messages.approved, chat_messages.status, chat_messages.sent, chat_messages.error_status, chat_messages.resent, chat_messages.created_at, chat_messages.media_url, chat_messages.message,
                  media.filename,
                  mediable_id

                  FROM chat_messages

                  LEFT JOIN (
                    SELECT * FROM media

                    RIGHT JOIN
                    (SELECT * FROM mediables WHERE mediable_type LIKE "%ChatMessage%") as mediables
                    ON mediables.media_id = media.id
                  ) AS media

                  ON mediable_id = chat_messages.id

                  WHERE ' . $column . ' = ' . $value . ' AND status != 7
                  ORDER BY chat_messages.created_at DESC
      ');


        if (Setting::get('show_automated_messages') == 0) {
            $messages = $messages->where('status', '!=', 9);
        }

        if ($request->erpUser) {
            $messages = $messages->whereNull('task_id');
        }

        // IS IT NECESSARY ?
        if ($request->get("elapse")) {
            $elapse = (int)$request->get("elapse");
            $date = new \DateTime;
            $date->modify(sprintf("-%s seconds", $elapse));
            // $messages = $messages->where('created_at', '>=', $date->format('Y-m-d H:i:s'));
        }

        foreach ($messages->latest()->get() as $message) {
            $messageParams = [
                'id' => $message->id,
                'number' => $message->number,
                'assigned_to' => $message->assigned_to,
                'created_at' => Carbon::parse($message->created_at)->format('Y-m-d H:i:s'),
                'approved' => $message->approved,
                'status' => $message->status,
                'user_id' => $message->user_id,
                'erp_user' => $message->erp_user,
                'sent' => $message->sent,
                'resent' => $message->resent,
                'error_status' => $message->error_status
            ];

            if ($message->media_url) {
                $messageParams['media_url'] = $message->media_url;
                $headers = get_headers($message->media_url, 1);
                $messageParams['content_type'] = $headers["Content-Type"][1];
            }

            if ($message->message) {
                $messageParams['message'] = $message->message;
            }

            if ($message->hasMedia(config('constants.media_tags'))) {
                $images_array = [];

                // $images_raw = DB::select('
                //               SELECT * FROM media
                //
                //               RIGHT JOIN
                //               (SELECT * FROM mediables WHERE mediable_type LIKE "%ChatMessage%") as mediables
                //               ON
                // ');

                foreach ($message->getMedia(config('constants.media_tags')) as $key => $image) {
                    $temp_image = [
                        'key' => $image->getKey(),
                        'image' => $image->getUrl(),
                        'product_id' => '',
                        'special_price' => '',
                        'size' => ''
                    ];

                    $image_key = $image->getKey();
                    $mediable_type = "Product";

                    $product_image = Product::with('Media')
                        ->whereRaw("products.id IN (SELECT mediables.mediable_id FROM mediables WHERE mediables.media_id = $image_key AND mediables.mediable_type LIKE '%$mediable_type%')")
                        // ->whereHas('Media', function($q) use($image) {
                        //    $q->where('media.id', $image->getKey());
                        // })
                        ->select(['id', 'price_inr_special', 'supplier', 'size', 'lmeasurement', 'hmeasurement', 'dmeasurement'])->first();


                    if ($product_image) {
                        $temp_image['product_id'] = $product_image->id;
                        $temp_image['special_price'] = $product_image->price_inr_special;

                        $string = $product_image->supplier;
                        $expr = '/(?<=\s|^)[a-z]/i';
                        preg_match_all($expr, $string, $matches);
                        $supplier_initials = implode('', $matches[0]);
                        $temp_image['supplier_initials'] = strtoupper($supplier_initials);

                        if ($product_image->size != null) {
                            $temp_image['size'] = $product_image->size;
                        } else {
                            $temp_image['size'] = (string)$product_image->lmeasurement . ', ' . (string)$product_image->hmeasurement . ', ' . (string)$product_image->dmeasurement;
                        }
                    }

                    array_push($images_array, $temp_image);
                }

                $messageParams['images'] = $images_array;
            }

            $result[] = array_merge($params, $messageParams);
        }

        $result = array_values(collect($result)->sortBy('created_at')->reverse()->toArray());
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        if ($request->page) {
            $currentItems = array_slice($result, $perPage * ($currentPage - 1), $perPage);

        } else {
            $currentItems = array_reverse(array_slice($result, $perPage * ($currentPage - 1), $perPage));
            $result = array_reverse($result);
        }

        $result = new LengthAwarePaginator($currentItems, count($result), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        return response()->json($result);
    }


    /**
     * Send message
     *
     * @param Request $request
     * @param $context
     * @return \Illuminate\Http\Response
     * @throws \Plank\Mediable\Exceptions\MediaUrlException
     */
    public function sendMessage(Request $request, $context, $ajaxNeeded = false)
    {
        // dd($request->all());
        $this->validate($request, [
            'customer_id' => 'sometimes|nullable|numeric',
            'supplier_id' => 'sometimes|nullable|numeric',
            'task_id' => 'sometimes|nullable|numeric',
            'erp_user' => 'sometimes|nullable|numeric',
            'status' => 'required|numeric',
            'assigned_to' => 'sometimes|nullable',
            'lawyer_id' => 'sometimes|nullable|numeric',
            'case_id' => 'sometimes|nullable|numeric',
            'blogger_id' => 'sometimes|nullable|numeric',
            'document_id' => 'sometimes|nullable|numeric',
            'quicksell_id' => 'sometimes|nullable|numeric',
            'old_id' => 'sometimes|nullable|numeric',
            'site_development_id' => 'sometimes|nullable|numeric',
            'social_strategy_id' => 'sometimes|nullable|numeric',
            'store_social_content_id' => 'sometimes|nullable|numeric',
            'payment_receipt_id' => 'sometimes|nullable|numeric',
        ]);
        $data = $request->except('_token');
        // set if there is no queue defaut for all pages
        if (!isset($data["is_queue"])) {
            $data["is_queue"] = 0;
        }
        $data['user_id'] = ((int)$request->get('user_id', 0) > 0) ? (int)$request->get('user_id', 0) : Auth::id();
        $data['number'] = $request->get('number');
        // $params['status'] = 1;

        $loggedUser = $request->user();

        if($request->add_autocomplete == "true"){
            $exist = AutoCompleteMessage::where( 'message' , $request->message)->exists();
            if(!$exist){
                AutoCompleteMessage::create([
                    'message' => $request->message,
                ]);
            }
        }


        if ($context == 'customer') {
            $data['customer_id'] = $request->customer_id;
            $module_id = $request->customer_id;
            //update if the customer message is going to send then update all old message to read
            \App\ChatMessage::updatedUnreadMessage($request->customer_id, $data["status"]);

            // update message for chatbot request->customer_id
            if(!empty($data["status"]) && !in_array($data["status"], \App\ChatMessage::AUTO_REPLY_CHAT)) {
                \DB::table('chat_messages as c')->join('chatbot_replies as cr', 'cr.replied_chat_id', '=', 'c.id')->where('c.customer_id', $request->customer_id)->where('cr.is_read',0)->update([ 'cr.is_read' => 1]);
                \DB::table('chat_messages as c')->join('chatbot_replies as cr', 'cr.chat_id', '=', 'c.id')->where('c.customer_id', $request->customer_id)->where('cr.is_read',0)->update([ 'cr.is_read' => 1]);
            }
        } elseif ($context == "purchase") {
            $data['purchase_id'] = $request->purchase_id;
            $module_id = $request->purchase_id;
        } elseif ($context == 'supplier') {
            $data['supplier_id'] = $request->supplier_id;
            $module_id = $request->supplier_id;
        } elseif ($context == 'chatbot') { //Purpose : Add Chatbotreplay - DEVTASK-18280
            $data['customer_id'] = $request->customer_id;
            $module_id = $request->customer_id;
            \App\ChatMessage::updatedUnreadMessage($request->customer_id, $data["status"]);
        } elseif ($context == 'user-feedback') { 
            $data['user_feedback_id'] = $request->user_id;
            $data['user_feedback_category_id'] = $request->feedback_cat_id;
            $Admin_users = User::get();
            foreach($Admin_users as $u){
                if($u->isAdmin()){
                    $u_id = $u->id;
                    break;
                }
            }
            if(Auth::user()->isAdmin()){
                $u_id = Auth::id();
            }
            $data['user_id'] = $u_id;
            $module_id = $request->user_id;
        }elseif ($context == 'hubstuff') {
            $data['hubstuff_activity_user_id'] = $request->hubstuff_id;
            $module_id = $request->hubstuff_id;
        } else {
            if ($context == 'vendor') {
                $data['vendor_id'] = $request->vendor_id;
                $module_id = $request->vendor_id;
                if ($request->get('is_vendor_user') == 'yes') {
                    $user = User::find($request->get('vendor_id'));
                    $vendor = Vendor::where('phone', $user->phone)->first();
                    $data['vendor_id'] = $vendor->id;
                    $module_id = $vendor->id;
                }
                if ($request->get('message')) {
                    $data['message'] = $request->get('message');
                }

                // update message for chatbot request->vendor_id
                if(!empty($data["status"]) && !in_array($data["status"], \App\ChatMessage::AUTO_REPLY_CHAT)) {
                    \DB::table('chat_messages as c')->join('chatbot_replies as cr', 'cr.replied_chat_id', '=', 'c.id')->where('c.vendor_id', $request->vendor_id)->where('cr.is_read',0)->update([ 'cr.is_read' => 1]);
                    \DB::table('chat_messages as c')->join('chatbot_replies as cr', 'cr.chat_id', '=', 'c.id')->where('c.vendor_id', $request->vendor_id)->where('cr.is_read',0)->update([ 'cr.is_read' => 1]);
                }

            } elseif ($context == 'task') {
                $data['task_id'] = $request->task_id;
                $task = Task::find($request->task_id);

                if ($task->is_statutory != 1) {
                    $data['message'] = "#" . $data['task_id'] . ". " . $task->task_subject . ". " . $data['message'];
                } else {
                    $data['message'] = $task->task_subject . ". " . $data['message'];
                }

                if (count($task->users) > 0) {
                    if ($task->assign_from == Auth::id()) {
                        foreach ($task->users as $key => $user) {
                            if ($key == 0) {
                                $data['erp_user'] = $user->id;
                            } else {
                                $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $data['message']);
                            }
                        }
                    } else if ($task->master_user_id == Auth::id()) {
                        foreach ($task->users as $key => $user) {
                            if ($key == 0) {
                                $data['erp_user'] = $user->id;
                            } else {
                                $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $data['message']);
                            }
                        }
                        $adm = User::find($task->assign_from);
                        if ($adm) {
                            $this->sendWithThirdApi($adm->phone, $adm->whatsapp_number, $data['message']);
                        }
                    } else {
                        if (!$task->users->contains(Auth::id())) {
                            $data['erp_user'] = $task->assign_from;

                            foreach ($task->users as $key => $user) {
                                $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $data['message']);
                            }
                        } else {
                            foreach ($task->users as $key => $user) {
                                if ($key == 0) {
                                    $data['erp_user'] = $task->assign_from;
                                } else {
                                    if ($user->id != Auth::id()) {
                                        $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $data['message']);
                                    }
                                }
                            }
                        }
                    }
                }

                if (count($task->contacts) > 0) {
                    // if ($task->assign_from == Auth::id()) {
                    foreach ($task->contacts as $key => $contact) {
                        if ($key == 0) {
                            $data['contact_id'] = $task->assign_to;
                        } else {
                            $this->sendWithThirdApi($contact->phone, $contact->whatsapp_number, $data['message']);
                        }
                    }
                    // } else {
                    // $data['contact_id'] = $task->assign_from;
                    // }
                }

                // this will send message to the lead developer
                if(!empty($task->master_user_id)) {
                    $userMaster = User::find($task->master_user_id);
                    if($userMaster) {
                        $extraUser = $data;
                        $extraUser['erp_user'] = $task->master_user_id;
                        $extraUser['user_id']  = $task->master_user_id;
                        $this->sendWithThirdApi($userMaster->phone, $userMaster->whatsapp_number, $data['message']);
                    }
                }

                $params['approved'] = 1;
                $params['status'] = 2;
                $chat_message = ChatMessage::create($data);

                $module_id = $request->task_id;

                /** Sent To ChatbotMessage */
                
                $loggedUser = auth()->user();
                $roles = ($loggedUser) ? $loggedUser->roles->pluck('name')->toArray() : [];

                if(!in_array('Admin', $roles)){
                    
                    \App\ChatbotReply::create([
                        'question'=> '#' . $task->id . ' => '. $request->message,
                        'reply' => json_encode([
                            'context' => 'task',
                            'issue_id' => $task->id,
                            'from' => ($loggedUser) ? $loggedUser->id : "cron"
                        ]),
                        'replied_chat_id' => $chat_message->id,
                        'reply_from' => 'database'
                    ]);
                }

                // update message for chatbot request->vendor_id
                if(!empty($data["status"]) && !in_array($data["status"], \App\ChatMessage::AUTO_REPLY_CHAT)) {
                    \DB::table('chat_messages as c')->join('chatbot_replies as cr', 'cr.replied_chat_id', '=', 'c.id')->where('c.task_id', $task->id)->where('cr.is_read',0)->update([ 'cr.is_read' => 1]);
                    \DB::table('chat_messages as c')->join('chatbot_replies as cr', 'cr.chat_id', '=', 'c.id')->where('c.task_id', $task->id)->where('cr.is_read',0)->update([ 'cr.is_read' => 1]);
                }
                
                $message_ = "[ ". @$loggedUser->name ." ] - #". $task->id.' - '. $task->task_subject . "\n\n" . $request->message;

                MessageHelper::sendEmailOrWebhookNotification($task->users->pluck('id')->toArray() , $message_ );

            }elseif($context == 'learning'){
                $learning = \App\Learning::find($request->issue_id);
                if($data['user_id'] == $learning->learning_vendor){
                    $userId = $data['user_id'];
                }else{
                    $userId = $learning->learning_vendor;
                }

                $prefix = null;
                if($learning && $learning->learningUser) {
                    $prefix = "#".$learning->id." ".$learning->learningUser->name ." : ".$learning->learning_subject. " =>";
                }

                $params['message'] = $prefix.$request->get('message');
                $params['erp_user'] = $userId;
                $params['sent_to_user_id'] = $userId;
                // $params['issue_id'] = $request->issue_id;
                $params['learning_id'] = $request->issue_id;//Purpose - Add learning_id - DEVTASK-4020
                $params['user_id'] = $userId;
                $params['approved'] = 1;
                $params['status'] = 2;
                $number = User::find($userId);
                    if (!$number) {
                        return response()->json(['message' => null]);
                    }
                    $whatsapp = $number->whatsapp_number;
                    $number = $number->phone;
                
                $chat_message = ChatMessage::create($params);
                $this->sendWithThirdApi($number, $whatsapp, $params['message'],null,$chat_message->id);

                return response()->json(['message' => $chat_message]);

            }elseif($context == 'ticket'){
                $data['ticket_id'] = $request->ticket_id;
                $module_id = $request->ticket_id;
                $ticket = \App\Tickets::find($request->ticket_id);
                $params['message'] = $request->get('message');
                $params['ticket_id'] = $request->ticket_id; 
                $params['approved'] = 1;
                $params['status'] = 2; 
                $chat_message = ChatMessage::create($params); 

                // check if ticket has customer ?
                $whatsappNo = null;
                if($ticket->user) {
                    $whatsappNo = $ticket->user->whatsapp_number;
                }elseif($ticket->customer) {
                    $whatsappNo = $ticket->customer->whatsapp_number;
                }


                $this->sendWithThirdApi($ticket->phone_no, $whatsappNo, $params['message'],null, $chat_message->id);
                return response()->json(['message' => $chat_message]);

            } else {
                
                if ($context == 'priority') {
                    $params = [];
                    $params['message'] = $request->get('message', '');
                    $params['erp_user'] = $request->get('user_id', 0);
                    $params['user_id'] = $request->get('user_id', 0);
                    $params['approved'] = 1;
                    $params['status'] = 2;


                    $number = User::find($request->get('user_id', 0));

                    if (!$number) {
                        return response()->json(['message' => null]);
                    }
                    $whatsapp_number = $number->whatsapp_number;
                    $number = $number->phone;

                    $chat_message = ChatMessage::create($params);
                    $this->sendWithThirdApi($number, $whatsapp_number, $params['message'],null, $chat_message->id);


                    return response()->json(['message' => $chat_message]);

                } elseif ($context == 'activity') {
                    $data['erp_user'] = $request->user_id;
                    $module_id = $request->user_id;
                    $user = User::find($request->user_id);
                    if ($user && $user->phone) {
                        $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $request->message);
                    }
                } elseif ($context == 'overdue') {
                    $data['erp_user'] = $request->user_id;
                    $user = User::find($request->user_id);
                    $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $request->message);
                } elseif ($context == 'user') {
                    $data['erp_user'] = $request->user_id;
                    $module_id = $request->user_id;
                    $user = User::find($request->user_id);
                    $this->sendWithThirdApi($user->phone, $user->whatsapp_number, null, null);
                } elseif ($context == 'dubbizle') {
                    $data['dubbizle_id'] = $request->dubbizle_id;
                    $module_id = $request->dubbizle_id;
                }
                elseif ($context == 'time_approval') {
                    $summary = HubstaffActivitySummary::find($request->summery_id);
                    if($summary) {
                        $userId = $summary->user_id;
                        $number = User::find($userId);
                        if (!$number) {
                            return response()->json(['message' => null]);
                        }
                        $whatsapp = $number->whatsapp_number;
                        $number = $number->phone;

                        $params[ 'erp_user' ] = $userId;
                        $params[ 'user_id' ]  = $userId;
                        $params[ 'sent_to_user_id' ] = $userId;
                        $params[ 'approved' ] = 1;
                        $params[ 'status' ] = 2;
                        $params[ 'hubstaff_activity_summary_id' ] = $request->summery_id;
                        $params[ 'message' ] = $request->message;
                        $chat_message = ChatMessage::create($params);
                        $this->sendWithThirdApi($number, $whatsapp, $params[ 'message' ],null , $chat_message->id);
                        return response()->json(['message' => $chat_message]);
                    }
                    return response()->json(['message' => null]);
                } elseif ($context == 'issue') {
                    $sendTo = $request->get('sendTo', "to_developer");
                    $params['issue_id'] = $request->get('issue_id');
                    $issue = DeveloperTask::find($request->get('issue_id'));

                    $userId = $issue->assigned_to;

                    if ($sendTo == "to_master") {
                        if ($issue->master_user_id) {
                            $userId = $issue->master_user_id;
                        }
                    }

                    if ($sendTo == "to_team_lead") {
                        if ($issue->team_lead_id) {
                            $userId = $issue->team_lead_id;
                        }
                    }

                    if ($sendTo == "to_tester") {
                        if ($issue->tester_id) {
                            $userId = $issue->tester_id;
                        }
                    }
                    //  if(isset(Auth::user()->id) && Auth::user()->id == $userId) {
                    //     $userId = $issue->created_by;
                    //  }else{
                    //     $userId = 1;
                    //  }
                    $admin = 0;
                    if (!Auth::user() || !Auth::user()->isAdmin()) {
                        $admin = $issue->created_by;
                    }
                    $params['erp_user'] = $userId;
                    $params['user_id'] = $data['user_id'];
                    $params['sent_to_user_id'] = $userId;
                    $params['approved'] = 1;
                    $params['status'] = 2;


                    $number = User::find($userId);
                    if (!$number) {
                        return response()->json(['message' => null]);
                    }
                    $whatsapp = $number->whatsapp_number;
                    $number = $number->phone;
                    
                    if ($request->type == 1) {
                        foreach ($issue->getMedia(config('constants.media_tags')) as $image) {
                            $params['message'] = '#TASK-' . $issue->id . '-' . $issue->subject . '=>' . $image->getUrl();
                            $params['media_url'] = $image->getUrl();

                            if (Auth::user()->id != $userId) {
                                $chat_message = ChatMessage::create($params);
                                $this->sendWithThirdApi($number, $whatsapp, '', $image->getUrl(),$chat_message->id);
                            }
                            if ($admin) {
                                $creator = User::find($admin);
                                if ($creator) {
                                    $num = $creator->phone;
                                    $whatsapp = $creator->whatsapp_number;
                                    $params['erp_user'] = $admin;
                                    $params['user_id'] = $data['user_id'];
                                    $params['sent_to_user_id'] = $admin;
                                    $params['approved'] = 1;
                                    $params['status'] = 2;
                                    $chat_message = ChatMessage::create($params);
                                    $this->sendWithThirdApi($num, $whatsapp, '', $image->getUrl(),$chat_message->id);
                                }
                            }
                            // if(Auth::id() == $issue->assigned_to) {
                            //     $master = User::find($issue->master_user_id);
                            //     if ($master) {
                            //         $num = $master->phone;
                            //         $this->sendWithThirdApi($num, null, '', $image->getUrl());
                            //     }
                            // }
                        }
                    } elseif ($request->type == 2) {
                        $issue = Issue::find($request->get('issue_id'));
                        if ($request->hasfile('images')) {
                            foreach ($request->file('images') as $image) {
                                $media = MediaUploader::fromSource($image)->upload();
                                $issue->attachMedia($media, config('constants.media_tags'));
                                $params['message'] = '#ISSUE-' . $issue->id . '-' . $issue->subject . '=>' . $media->getUrl();
                                $params['media_url'] = $media->getUrl();
                                if (Auth::user()->id != $userId) {
                                    $chat_message = ChatMessage::create($params);
                                    $this->sendWithThirdApi($number, $whatsapp, '', $media->getUrl(),$chat_message->id);
                                }

                                if ($admin) {
                                    $creator = User::find($admin);
                                    if ($creator) {
                                        $num = $creator->phone;
                                        $whatsapp = $creator->whatsapp_number;
                                        $params['erp_user'] = $admin;
                                        $params['user_id'] = $data['user_id'];
                                        $params['sent_to_user_id'] = $admin;
                                        $params['approved'] = 1;
                                        $params['status'] = 2;
                                        $chat_message = ChatMessage::create($params);
                                        $this->sendWithThirdApi($num, $whatsapp, '', $media->getUrl(),$chat_message->id);
                                    }
                                }
                                // if(Auth::id() == $issue->assigned_to) {
                                //     $master = User::find($issue->master_user_id);
                                //     if ($master) {
                                //         $num = $master->phone;
                                //         $this->sendWithThirdApi($num, null, '', $image->getUrl());
                                //     }
                                // }
                            }
                        }
                    } else {
                        $params['developer_task_id'] = $request->get('issue_id');
                        $prefix = ($issue->task_type_id == 1) ? "#DEVTASK-" : "#ISSUE-";
                        $params['message'] = $prefix . $issue->id . '-' . $issue->subject . '=>' . $request->get('message');
                        if (Auth::user() && Auth::user()->id != $userId) {
                            $chat_message = ChatMessage::create($params);
                            $this->sendWithThirdApi($number, $whatsapp, $params['message'],null, $chat_message->id);
                        }

                        if ($admin) {
                            $creator = User::find($admin);
                            if ($creator) {
                                $num = $creator->phone;
                                $whatsapp = $creator->whatsapp_number;
                                $params['erp_user'] = $admin;
                                $params['user_id'] = $data['user_id'];
                                $params['sent_to_user_id'] = $admin;
                                $params['approved'] = 1;
                                $params['status'] = 2;
                                $chat_message = ChatMessage::create($params);
                                $this->sendWithThirdApi($num, $whatsapp, $params['message'],null, $chat_message->id);
                            }
                        }
                        // if(Auth::id() == $issue->assigned_to) {
                        //     $master = User::find($issue->master_user_id);
                        //     if ($master) {
                        //         $num = $master->phone;
                        //         $this->sendWithThirdApi($num, null, $params[ 'message' ]);
                        //     }
                        // }


                        if ($issue->hasMedia(config('constants.media_tags'))) {
                            foreach ($issue->getMedia(config('constants.media_tags')) as $image) {
                                $params['media_url'] = $image->getUrl();
                                if (Auth::user()->id != $userId) {
                                    $chat_message = ChatMessage::create($params);
                                    $this->sendWithThirdApi($number, $whatsapp, '', $image->getUrl(),$chat_message->id);
                                }
                                if ($admin) {
                                    $creator = User::find($admin);
                                    if ($creator) {
                                        $num = $creator->phone;
                                        $whatsapp = $creator->whatsapp_number;
                                        $params['erp_user'] = $admin;
                                        $params['user_id'] = $data['user_id'];
                                        $params['sent_to_user_id'] = $admin;
                                        $params['approved'] = 1;
                                        $params['status'] = 2;
                                        $chat_message = ChatMessage::create($params);
                                        $this->sendWithThirdApi($num, $whatsapp, $params['message'],$chat_message->id);
                                    }
                                }
                                // if(Auth::id() == $issue->assigned_to) {
                                //     $master = User::find($issue->master_user_id);
                                //     if ($master) {
                                //         $num = $master->phone;
                                //         $this->sendWithThirdApi($num, null, '', $image->getUrl());
                                //     }
                                // }

                            }
                        }
                    }

                    ChatMessagesQuickData::updateOrCreate([
                        'model' => \App\DeveloperTask::class,
                        'model_id' => $params['issue_id']
                    ], [
                        'last_communicated_message' => @$params['message'],
                        'last_communicated_message_at' => Carbon::now(),
                        'last_communicated_message_id' => ($chat_message) ? $chat_message->id : null,
                    ]);

                    // update message for chatbot request->vendor_id
                    if(!empty($data["status"]) && !in_array($data["status"], \App\ChatMessage::AUTO_REPLY_CHAT)) {
                        \DB::table('chat_messages as c')->join('chatbot_replies as cr', 'cr.replied_chat_id', '=', 'c.id')->where('c.developer_task_id', $issue->id)->where('cr.is_read',0)->update([ 'cr.is_read' => 1]);
                        \DB::table('chat_messages as c')->join('chatbot_replies as cr', 'cr.chat_id', '=', 'c.id')->where('c.developer_task_id', $issue->id)->where('cr.is_read',0)->update([ 'cr.is_read' => 1]);
                    }

                    if ($sendTo == "to_master") {
                        
                        /* Send to chatbot/messages */
                        
                        \App\ChatbotReply::create([
                            'question'=> '#DEVTASK-' . $issue->id . ' => '. $request->message,
                            'reply' => json_encode([
                                'context' => 'issue',
                                'issue_id' => $issue->id,
                                'from' => $request->user()->id
                            ]),
                            'replied_chat_id' => $chat_message->id,
                            'reply_from' => 'database'
                        ]);
                    }

                    if($request->chat_reply_message_id){

                        $messageReply = \App\ChatbotReply::find($request->chat_reply_message_id);

                        if($messageReply){

                            $prefix = ($issue->task_type_id == 1) ? "#DEVTASK-" : "#ISSUE-";

                            $messageReply->chat_id = $chat_message->id;
                            
                            $messageReply->save();

                        }

                    }

                    //START - Purpose : Email notification - DEVTASK-4359

                    $message_ = ($issue->task_type_id == 1 ? "[ ". auth()->user()->name ." ] - #DEVTASK-" : "#ISSUE-"). $issue->id.' - '. $issue->subject . "\n\n" . $request->message;

                
                    MessageHelper::sendEmailOrWebhookNotification([$issue->assigned_to, $issue->team_lead_id, $issue->tester_id] , $message_ );
                    //END - DEVTASK-4359

                    return response()->json(['message' => $chat_message]);

                } elseif ($context == 'auto_task') {
                    $params[ 'issue_id' ] = $request->get('issue_id');
                    $issue = DeveloperTask::find($request->get('issue_id'));
                    $userId  = $issue->assigned_to;

                    
                    $admin = $issue->created_by;

                    $params[ 'erp_user' ] = $userId;
                    $params[ 'user_id' ]  = $data['user_id'];
                    $params[ 'sent_to_user_id' ] = $userId;
                    $params[ 'approved' ] = 1;
                    $params[ 'status' ] = 2;


                    $number = User::find($userId);
                    if (!$number) {
                        return response()->json(['message' => null]);
                    }
                    $whatsapp = $number->whatsapp_number;
                    $number = $number->phone;
                        $params[ 'developer_task_id' ] = $request->get('issue_id');
                        $prefix = ($issue->task_type_id == 1) ? "#DEVTASK-" : "#ISSUE-";
                        $params[ 'message' ] = $prefix . $issue->id . '-' . $issue->subject . '=>' . $request->get('message');
                        $chat_message = ChatMessage::create($params);
                        $this->sendWithThirdApi($number, $whatsapp, $params[ 'message' ],null , $chat_message->id);
                        
                        if($admin) {
                            $creator = User::find($admin);
                            if ($creator) {
                                $num = $creator->phone;
                                $whatsapp = $creator->whatsapp_number;
                                $params[ 'erp_user' ] = $admin;
                                $params[ 'user_id' ]  = $data['user_id'];
                                $params[ 'sent_to_user_id' ] = $admin;
                                $params[ 'approved' ] = 1;
                                $params[ 'status' ] = 2;
                                $chat_message = ChatMessage::create($params);
                                $this->sendWithThirdApi($num, $whatsapp, $params[ 'message' ],null, $chat_message->id);
                            }
                        }
                    ChatMessagesQuickData::updateOrCreate([
                        'model' => \App\DeveloperTask::class,
                        'model_id' => $params['issue_id']
                        ], [
                        'last_communicated_message' => @$params['message'],
                        'last_communicated_message_at' => Carbon::now(),
                        'last_communicated_message_id' => ($chat_message) ? $chat_message->id : null,
                    ]);

                    
                    $message_ = ($issue->task_type_id == 1 ? "[ ". $loggedUser->name ." ]- #DEVTASK-" : "#ISSUE-"). $issue->id.' - '. $issue->subject . "\n\n" . $request->message;

                    $this->sendEmailOrWebhookNotification([$userId] , $message_ );

                    return response()->json(['message' => $chat_message]);

                } elseif ($context == 'document') {

                    //Sending Documents To User / Vendor / Contacts
                    $data['document_id'] = $request->document_id;
                    $module_id = $request->document_id;

                    //Getting User For Sending Documents
                    if ($request->user_type == 1) {
                        $document = Document::findOrFail($module_id);
                        $document_url = $document->getDocumentPathById($document->id);

                        foreach ($request->users as $key) {
                            $user = User::findOrFail($key);

                            // User ID For Chat Message
                            $data['user_id'] = $user->id;

                            //Creating Chat Message
                            $chat_message = ChatMessage::create($data);

                            //History
                            $history['send_by'] = Auth::id();
                            $history['send_to'] = $user->id;
                            $history['type'] = 'User';
                            $history['via'] = 'WhatsApp';
                            $history['document_id'] = $document->id;
                            DocumentSendHistory::create($history);

                            //Sending Document
                            $this->sendWithThirdApi($user->phone, $request->whatsapp_number, '', $document_url, $chat_message->id, '');
                            //Sending Text
                            $this->sendWithThirdApi($user->phone, $request->whatsapp_number, $request->message, '', $chat_message->id, '');
                        }


                        //Getting Vendor For Sending Documents
                    } elseif ($request->user_type == 2) {
                        $document = Document::findOrFail($module_id);
                        $document_url = $document->getDocumentPathById($document->id);
                        foreach ($request->users as $key) {
                            $vendor = Vendor::findOrFail($key);

                            // Vendor ID For Chat Message
                            $data['vendor_id'] = $vendor->id;

                            //Creating Chat Message
                            $chat_message = ChatMessage::create($data);

                            //History
                            $history['send_by'] = Auth::id();
                            $history['send_to'] = $vendor->id;
                            $history['type'] = 'Vendor';
                            $history['via'] = 'WhatsApp';
                            $history['document_id'] = $document->id;
                            DocumentSendHistory::create($history);

                            //Sending Document
                            $this->sendWithThirdApi($vendor->phone, $request->whatsapp_number, '', $document_url, $chat_message->id, '');
                            //Sending Text
                            $this->sendWithThirdApi($vendor->phone, $request->whatsapp_number, $request->message, '', $chat_message->id, '');
                        }


                        //Getting Contact For Sending Documents
                    } elseif ($request->user_type == 3) {
                        $document = Document::findOrFail($module_id);
                        $document_url = $document->getDocumentPathById($document->id);
                        foreach ($request->users as $key) {
                            $contact = Contact::findOrFail($key);

                            // Contact ID For Chat Message
                            $data['contact_id'] = $contact->id;

                            //Creating Chat Message
                            $chat_message = ChatMessage::create($data);

                            //History
                            $history['send_by'] = Auth::id();
                            $history['send_to'] = $contact->id;
                            $history['type'] = 'Contact';
                            $history['via'] = 'WhatsApp';
                            $history['document_id'] = $document->id;
                            DocumentSendHistory::create($history);

                            //Sending Document
                            $this->sendWithThirdApi($contact->phone, $request->whatsapp_number, '', $document_url, $chat_message->id, '');
                            //Sending Text
                            $this->sendWithThirdApi($contact->phone, $request->whatsapp_number, $request->message, '', $chat_message->id, '');
                        }


                    } elseif (isset($request->contact) && $request->contact != null) {
                        $document = Document::findOrFail($module_id);
                        $document_url = $document->getDocumentPathById($document->id);
                        // $document_url = 'http://www.africau.edu/images/default/sample.pdf';

                        foreach ($request->contact as $contacts) {

                            // Contact ID For Chat Message
                            $data['number'] = $contacts;

                            //Creating Chat Message
                            $chat_message = ChatMessage::create($data);

                            //History
                            $history['send_by'] = Auth::id();
                            $history['send_to'] = $contacts;
                            $history['type'] = 'Manual Contact';
                            $history['via'] = 'WhatsApp';
                            $history['document_id'] = $document->id;
                            DocumentSendHistory::create($history);

                            //Sending Document
                            $this->sendWithThirdApi($contacts, $request->whatsapp_number, '', $document_url, $chat_message->id, '');
                            //Sending Text
                            $this->sendWithThirdApi($contacts, $request->whatsapp_number, $request->message, '', $chat_message->id, '');
                        }
                    }

                    return redirect()->back()->with('message', 'Document Send SucessFully');

                } elseif ($context == 'quicksell') {
                    $product = Product::findorfail($request->quicksell_id);
                    $image = $product->getMedia(config('constants.media_tags'))->first()
                        ? $product->getMedia(config('constants.media_tags'))->first()->getUrl()
                        : '';
                    foreach ($request->customers as $key) {
                        $customer = Customer::findOrFail($key);

                        // User ID For Chat Message
                        $data['customer_id'] = $customer->id;

                        //Creating Chat Message
                        $chat_message = ChatMessage::create($data);
                        //$image = 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png';
                        //Sending Document
                        if ($customer->whatsapp_number == null) {
                            $this->sendWithThirdApi($customer->phone, $customer->whatsapp_number, '', $image, $chat_message->id, '');
                        } else {
                            $this->sendWithThirdApi($customer->phone, $request->whatsapp_number, '', $image, $chat_message->id, '');
                        }
                    }
                    return redirect()->back()->with('message', 'Images Send SucessFully');

                } elseif ($context == 'quicksell_group') {
                    $products = $request->products;
                    if ($products != null) {
                        $products = explode(",", $products);
                        foreach ($products as $product) {
                            $product = Product::findorfail($product);
                            $image = $product->getMedia(config('constants.media_tags'))->first()
                                ? $product->getMedia(config('constants.media_tags'))->first()->getUrl()
                                : '';
                            // $image = 'https://cdn.vox-cdn.com/thumbor/Pkmq1nm3skO0-j693JTMd7RL0Zk=/0x0:2012x1341/1200x800/filters:focal(0x0:2012x1341)/cdn.vox-cdn.com/uploads/chorus_image/image/47070706/google2.0.0.jpg';
                            if (isset($request->to_all)) {
                                $customers = Customer::all();
                            } elseif (!empty($request->customers_id) && is_array($request->customers_id)) {
                                $customers = Customer::whereIn('id', $request->customers_id)->get();
                            } elseif ($request->customers != null) {
                                $customers = Customer::whereIn('id', $request->customers)->get();
                            } elseif ($request->rating != null && $request->gender == null) {
                                $customers = Customer::where('rating', $request->rating)->get();
                            } elseif ($request->rating != null && $request->gender != null) {
                                $customers = Customer::where('rating', $request->rating)->where('gender', $request->gender)->get();
                            } else {
                                return redirect(route('quicksell.index'))->with('message', 'Please select Category');
                            }

                            if ($customers != null) {
                                foreach ($customers as $customer) {
                                    $data['customer_id'] = $customer->id;
                                    $chat_message = ChatMessage::create($data);
                                    $this->sendWithThirdApi($customer->phone, $customer->whatsapp_number, '', $image, $chat_message->id, '');
                                }
                            }
                        }
                    } else {
                        if (!empty($request->redirect_back)) {
                            return redirect($request->redirect_back)->with('message', 'Please Select Products');
                        }
                        return redirect(route('quicksell.index'))->with('message', 'Please Select Products');
                    }

                    if ($request->redirect_back) {
                        return redirect($request->redirect_back)->with('message', 'Images Send SucessFully');
                    }

                    return redirect(route('quicksell.index'))->with('message', 'Images Send SucessFully');

                } elseif ($context == 'quicksell_group_send') {

                    if ($request->customerId != null && $request->groupId != null) {
                        //Find Group id
                        foreach ($request->groupId as $id) {
                            //got group
                            $groups = QuickSellGroup::select('id', 'group')->where('id', $id)->get();

                            //getting product id from group
                            if ($groups != null) {
                                foreach ($groups as $group) {

                                    //$productsQuickSell = ProductQuicksellGroup::where('quicksell_group_id', $group->group)->get();
                                    $medias = [];

                                    $products = Product::with('media')
                                        ->select('products.*')
                                        ->join('product_quicksell_groups', 'product_quicksell_groups.product_id', '=', 'products.id')
                                        ->groupBy('products.id')
                                        ->where('quicksell_group_id', $group->group)
                                        ->get();


                                    foreach ($products as $product) {
                                        $image = $product->media()->whereIn("tag",config('constants.attach_image_tag'))->first();
                                        if ($image) {
                                            array_push($medias, $image);
                                        }

                                    }

                                    /*foreach ($productsQuickSell as $product) {
                                        if ($product != null) {

                                            //Getting product from id
                                            $products = Product::where('id', $product->product_id)->first();

                                            if ($products != null) {

                                                // $image = 'https://cdn.vox-cdn.com/thumbor/Pkmq1nm3skO0-j693JTMd7RL0Zk=/0x0:2012x1341/1200x800/filters:focal(0x0:2012x1341)/cdn.vox-cdn.com/uploads/chorus_image/image/47070706/google2.0.0.jpg';

                                                $image = $products && $products->getMedia(config('constants.media_tags'))->first()
                                                    ? $products->getMedia(config('constants.media_tags'))->first()
                                                    : '';
                                                if ($image) {
                                                    array_push($images, $image->filename);
                                                }

                                            }
                                        }
                                    }*/

                                    if (isset($medias) && count($medias) > 0) {
                                        /*$temp_chat_message = ChatMessage::create($data);
                                        foreach ($images as $image) {
                                            $media = Media::where('filename', $image)->first();
                                            $isExists = DB::table('mediables')->where('media_id', $media->id)->where('mediable_id', $temp_chat_message->id)->where('mediable_type', 'App\ChatMessage')->count();
                                            if (!$isExists) {
                                                $temp_chat_message->attachMedia($media, config('constants.media_tags'));
                                            }
                                        }*/

                                        if (!empty($request->send_pdf) && $request->send_pdf == 1) {
                                            $fn = '';
                                            if ($context == 'customer') {
                                                $fn = '_product';
                                            }

                                            $folder = "temppdf_view_" . time();

                                            //$medias = Media::whereIn('id', $images)->get();
                                            $pdfView = view('pdf_views.images' . $fn, compact('medias', 'folder'));
                                            $pdf = new Dompdf();
                                            $pdf->setPaper([0, 0, 1000, 1000], 'portrait');
                                            $pdf->loadHtml($pdfView);
                                            if (!empty($request->pdf_file_name)) {
                                                $random = str_replace(" ", "-", $request->pdf_file_name . "-" . date("Y-m-d-H-i-s-") . rand());
                                            } else {
                                                $random = uniqid('sololuxury_', true);
                                            }
                                            if (!File::isDirectory(public_path() . '/pdf/')) {
                                                File::makeDirectory(public_path() . '/pdf/', 0777, true, true);
                                            }
                                            $fileName = public_path() . '/pdf/' . $random . '.pdf';
                                            $pdf->render();

                                            File::put($fileName, $pdf->output());


                                            $media = MediaUploader::fromSource($fileName)->upload();

                                            if ($request->customerId != null) {
                                                $customer = Customer::findorfail($request->customerId);
                                                if (!empty($request->send_pdf)) {
                                                    // $file = env('APP_URL') . '/pdf/' . $random . '.pdf';
                                                    $file = config('env.APP_URL') . '/pdf/' . $random . '.pdf';
                                                }
                                                $data['customer_id'] = $customer->id;
                                                $chat_message = ChatMessage::create($data);
                                                $this->sendWithThirdApi($customer->phone, $customer->whatsapp_number, '', $file, $chat_message->id, '');

                                            }
                                        } else {
                                            //$medias = Media::whereIn('id', $images)->get();
                                            if ($medias != null) {
                                                if ($request->customerId != null) {
                                                    $customer = Customer::findorfail($request->customerId);
                                                    foreach ($medias as $media) {
                                                        $file = $media->getUrl();
                                                        $data['customer_id'] = $customer->id;
                                                        $chat_message = ChatMessage::create($data);
                                                        $this->sendWithThirdApi($customer->phone, $customer->whatsapp_number, '', $file, $chat_message->id, '');
                                                    }
                                                }
                                            }
                                        }
                                    }

                                }

                            }
                        }
                        return response()->json(['success']);
                    }


                } elseif ($context == 'old') {

                    $old = Old::findorfail($request->old_id);

                    if ($old != null) {

                        $data['old_id'] = $old->serial_no;
                        //Creating Chat Message
                        $data['message'] = $request->message;
                        $chat_message = ChatMessage::create($data);

                        $this->sendWithThirdApi($old->phone, $old->whatsapp_number, $request->message);

                        return response()->json([
                            'data' => $data
                        ], 200);

                    }

                } elseif ($context == 'site_development') {
                    $chat_message = null;
                    $users = $request->get('users', [$request->get("user_id", 6)]);
                    if (!empty($users)) {
                        foreach ($users as $user) {
                            $user = User::find($user);
                            if ($user) {
                                $params['message'] = $request->get('message');
                                $params['site_development_id'] = $request->get('site_development_id');
                                $params['approved'] = 1;
                                $params['status'] = 2;
                                $chat_message = ChatMessage::create($params);
                                $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'],null, $chat_message->id);
                                return response()->json(['message' => $chat_message], 200);
                            }
                        }
                    }
                    return response()->json(['message' => 'No user selected'], 500);

                } elseif ($context == 'content_management') {
                    $chat_message = null;
                    $users = $request->get('users', [$request->get("user_id", 0)]);

                    if (!empty($users)) {
                        foreach ($users as $user) {
                            $user = User::find($user);
                            $params['message'] = $request->get('message');
                            $params['store_social_content_id'] = $request->get('store_social_content_id');
                            $params['approved'] = 1;
                            $params['status'] = 2;
                            $chat_message = ChatMessage::create($params);
                            $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'],null, $chat_message->id);
                        }
                    }

                    return response()->json(['message' => $chat_message]);

                } elseif ($context == 'task_lead') {
                    $params['task_id'] = $request->get('task_id');
                    $params['message'] = $request->get('message');

                    $params['approved'] = 1;
                    $params['status'] = 2;
                    $task = Task::find($request->get('task_id'));
                    $user = User::find($task->master_user_id);

                    if (!$user) {
                        return response()->json(['message' => 'Master user not found'], 500);
                    }
                    $params['user_id'] = $user->id;
                    if ($task->is_statutory != 1) {
                        $params['message'] = "#" . $task->id . ". " . $task->task_subject . ". " . $params['message'];
                    } else {
                        $params['message'] = $task->task_subject . ". " . $params['message'];
                    }

                    $number = $user->phone;
                    $whatsapp_number = $user->whatsapp_number;
                    if (!$number) {
                        return response()->json(['message' => 'User whatsapp no not available'], 500);
                    }
                    $chat_message = ChatMessage::create($params);
                    $this->sendWithThirdApi($number, $whatsapp_number, $params['message'],null, $chat_message->id);
                    // return response()->json(['message' => $chat_message]);
                } elseif ($context == 'social_strategy') {
                    $user = User::find($request->get('user_id'));

                    $params['message'] = $request->get('message');

                    $params['social_strategy_id'] = $request->get('social_strategy_id');
                    $params['approved'] = 1;

                    $params['status'] = 2;

                    $chat_message = ChatMessage::create($params);
                    $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'],null , $chat_message->id);

                    return response()->json(['message' => $chat_message]);

                } elseif ($context == 'payment-receipts') {
                    $user = null;    
                    $paymentReceipt = \App\PaymentReceipt::find($request->get('payment_receipt_id'));
                    if($paymentReceipt) {
                        if(auth()->user()->isAdmin()) {
                            $user = User::find($paymentReceipt->user_id);
                        }
                    }
                    if(!$user) {
                        $user = User::find(6);
                    }
                    
                    $params['erp_user'] = $user->id;
                    $params['user_id'] = $user->id;
                    $params['message'] = $request->get('message');
                    $params['payment_receipt_id'] = $request->get('payment_receipt_id');
                    $params['approved'] = 1;
                    $params['status'] = 2;
                    
                    $chat_message = ChatMessage::create($params);

                    $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'],null , $chat_message->id);

                    return response()->json(['message' => $chat_message]);

                } else {
                    if ($context == 'developer_task') {
                        $params['developer_task_id'] = $request->get('developer_task_id');
                        $task = DeveloperTask::find($request->get('developer_task_id'));
                        $params['erp_user'] = $task->user_id;
                        $params['approved'] = 1;
                        $params['message'] = '#DEVTASK-' . $task->id . ' ' . $request->get('message');
                        $params['status'] = 2;

                        $user = User::find($task->user_id);
                        $number = $user->phone;
                        $whatsapp_number = $user->whatsapp_number;
                        $chat_message = ChatMessage::create($params);
                        $this->sendWithThirdApi($number, $whatsapp_number, $params['message'],null, $chat_message->id);


                        return response()->json(['message' => $chat_message]);
                    } else {
                        if ($context == 'lawyer') {
                            $data['lawyer_id'] = $request->lawyer_id;
                            $module_id = $request->lawyer_id;
                        } else {
                            if ($context == 'case') {
                                $data['case_id'] = $request->case_id;
                                $data['lawyer_id'] = $request->lawyer_id;
                                $module_id = $request->case_id;
                            } else {
                                if ($context == 'blogger') {
                                    $data['blogger_id'] = $request->blogger_id;
                                    $module_id = $request->blogger_id;
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($context != 'task') {
            $params['approved'] = 0;
            $params['status'] = 1;
            $chat_message = ChatMessage::create($data);
        }

        //START - Purpose : Add ChatbotMessage entry - DEVTASK-4203
        if($context == 'vendor')
        {
            /** Sent To ChatbotMessage */
                    
            $loggedUser = $request->user();

            $roles = $loggedUser->roles->pluck('name')->toArray();

            if(!in_array('Admin', $roles)){
                
                \App\ChatbotReply::create([
                    'question'=> $request->message,
                    'reply' => json_encode([
                        'context' => 'vendor',
                        'issue_id' => $data['vendor_id'],
                        'from' => $loggedUser->id
                    ]),
                    'replied_chat_id' => $chat_message->id,
                    'reply_from' => 'database'
                ]);
            }

            $messageReply = \App\ChatbotReply::find($request->chat_reply_message_id);

            if($messageReply){

                $messageReply->chat_id = $chat_message->id;
                
                $messageReply->save();

            }
        }
        //END - DEVTASK-4203

        //STRAT - Purpose : Add record in chatbotreplay - DEVTASK-18280
        if($context == 'chatbot')
        {
            if($request->chat_reply_message_id){
                $messageReply = \App\ChatbotReply::find($request->chat_reply_message_id);

                if($messageReply){
                    
                    $messageReply->chat_id = $chat_message->id;
                    
                    $messageReply->save();

                }
            }
            return response()->json(['message' => $chat_message]);
        }
         //END - DEVTASK-18280

        if ($context == 'customer') {

            ChatMessagesQuickData::updateOrCreate([
                'model' => \App\Customer::class,
                'model_id' => $data['customer_id']
            ], [
                'last_communicated_message' => @$data['message'],
                'last_communicated_message_at' => Carbon::now(),
                'last_communicated_message_id' => ($chat_message) ? $chat_message->id : null,
            ]);

        }

        if ($context == 'task') {
            ChatMessagesQuickData::updateOrCreate([
                'model' => \App\Task::class,
                'model_id' => $data['task_id']
            ], [
                'last_communicated_message' => @$data['message'],
                'last_communicated_message_at' => Carbon::now(),
                'last_communicated_message_id' => ($chat_message) ? $chat_message->id : null,
            ]);

            if($request->chat_reply_message_id){

                $messageReply = \App\ChatbotReply::find($request->chat_reply_message_id);

                if($messageReply){
                    
                    $messageReply->chat_id = $chat_message->id;
                    
                    $messageReply->save();

                }

            }
        }

        if ($context == 'task_lead') {
            ChatMessagesQuickData::updateOrCreate([
                'model' => \App\Task::class,
                'model_id' => $data['task_id']
            ], [
                'last_communicated_message' => @$params['message'],
                'last_communicated_message_at' => Carbon::now(),
                'last_communicated_message_id' => ($chat_message) ? $chat_message->id : null,
            ]);
        }
        // $data['status'] = 1;

        // if ($context == 'task' && $data['erp_user'] != Auth::id()) {
        //   $data['erp_user'] = Auth::id();
        //
        //   $another_message = ChatMessage::create($data);
        // }

        if ($request->hasFile('image')) {
            $media = MediaUploader::fromSource($request->file('image'))
                ->toDirectory('chatmessage/' . floor($chat_message->id / config('constants.image_per_folder')))
                ->upload();
            $chat_message->attachMedia($media, config('constants.media_tags'));

            // if ($context == 'task' && $data['erp_user'] != Auth::id()) {
            //   $another_message->attachMedia($media,config('constants.media_tags'));
            // }

            if ($context == 'task') {
                if (count($task->users) > 0) {
                    if ($task->assign_from == Auth::id()) {
                        foreach ($task->users as $key => $user) {
                            if ($key == 0) {
                                $data['erp_user'] = $user->id;
                            } else {
                                $this->sendWithThirdApi($user->phone, $user->whatsapp_number, null, $media->getUrl(),$chat_message->id);
                            }
                        }
                    } else {
                        foreach ($task->users as $key => $user) {
                            if ($key == 0) {
                                $data['erp_user'] = $task->assign_from;
                            } else {
                                if ($user->id != Auth::id()) {
                                    $this->sendWithThirdApi($user->phone, $user->whatsapp_number, null, $media->getUrl(),$chat_message->id);
                                }
                            }
                        }
                    }
                }

                if (count($task->contacts) > 0) {
                    foreach ($task->contacts as $key => $contact) {
                        if ($key == 0) {
                            $data['contact_id'] = $task->assign_to;
                        } else {
                            $this->sendWithThirdApi($contact->phone, $contact->whatsapp_number, null, $media->getUrl(),$chat_message->id);
                        }
                    }
                }
            }
        }

        // get the status for approval
        $approveMessage = \App\Helpers\DevelopmentHelper::needToApproveMessage();
        $isNeedToBeSend = false;
        if (
        ((int)$approveMessage == 1
            || (Auth::id() == 49 && empty($chat_message->customer_id))
            || Auth::id() == 56
            || Auth::id() == 3
            || Auth::id() == 65
            || $context == 'task'
            || $request->get('is_vendor_user') == 'yes'
        )) {
            $isNeedToBeSend = true;
        }

        $isNeedToBeSend = true;

        if ($request->images) {
            $imagesDecoded = json_decode($request->images, true);
            if (!empty($request->send_pdf) && $request->send_pdf == 1) {
                $fn = ($context == 'customer' || $context == 'customers') ? '_product' : '';
                $folder = "temppdf_view_" . time();
                $mediasH = Media::whereIn('id', $imagesDecoded)->get();
                $number = 0;
                $chunkedMedia = $mediasH->chunk(self::MEDIA_PDF_CHUNKS);

                foreach ($chunkedMedia as $key => $medias) {

                    $pdfView = (string)view('pdf_views.images' . $fn, compact('medias', 'folder', 'chat_message'));
                    $pdf = new Dompdf();
                    $pdf->setPaper([0, 0, 1000, 1000], 'portrait');
                    $pdf->loadHtml($pdfView);

                    if (!empty($request->pdf_file_name)) {
                        $random = str_replace(" ", "-", $request->pdf_file_name . "-" . ($key + 1) . "-" . date("Y-m-d-H-i-s-") . rand());
                    } else {
                        $random = uniqid('sololuxury_', true);
                    }

                    $fileName = public_path() . '/' . $random . '.pdf';
                    $pdf->render();

                    File::put($fileName, $pdf->output());

                    // send images in chunks to chat media
                    try {
                        if ($number == 0) {
                            $media = MediaUploader::fromSource($fileName)
                                ->toDirectory('chatmessage/' . floor($chat_message->id / config('constants.image_per_folder')))
                                ->upload();
                            $chat_message->attachMedia($media, config('constants.media_tags'));
                        } else {
                            $extradata = $data;
                            $extradata['is_queue'] = 0;
                            $extra_chat_message = ChatMessage::create($extradata);
                            $media = MediaUploader::fromSource($fileName)
                                ->toDirectory('chatmessage/' . floor($extra_chat_message->id / config('constants.image_per_folder')))
                                ->upload();
                            $extra_chat_message->attachMedia($media, config('constants.media_tags'));
                        }

                        File::delete($fileName);

                        $number++;
                    } catch (\Exception $e) {
                        \Log::channel('whatsapp')->error($e);
                    }
                }

            } else {
                if(!empty($imagesDecoded) && is_array($imagesDecoded)) {
                    if($request->type == 'customer-attach') {
                        foreach($imagesDecoded as $iimg => $listedImage) {
                            $productList = \App\SuggestedProductList::find($listedImage);
                            $product = Product::find($productList->product_id);
                            $imageDetails = $product->getMedia(config('constants.attach_image_tag'))->first();
                            $image_key = $imageDetails->getKey();
                            $media = Media::find($image_key);
                            if($media) {
                                $mediable = \App\Mediables::where('media_id',$media->id)->where('mediable_type','App\Product')->first();
                                //$data['media_url'] = $media->getUrl();
                                try{
                                    if($iimg != 0) {
                                        $chat_message = ChatMessage::create($data);
                                    }
                                    $chat_message->attachMedia($media, config('constants.media_tags'));
                                    if($mediable) {
                                        $productList->update(['chat_message_id' => $chat_message->id]);
                                   }
                                    // if this message is not first then send to the client
                                    if ($iimg != 0 && $isNeedToBeSend && $chat_message->status != 0 && $chat_message->is_queue == '0') {
                                        $myRequest = new Request();
                                        $myRequest->setMethod('POST');
                                        $myRequest->request->add(['messageId' => $chat_message->id]);
                                        $this->approveMessage($context, $myRequest);
                                        if($mediable) {
                                            $productList->update(['chat_message_id' => $chat_message->id]);
                                        }
                                    }
    
                                } catch (\Exception $e) {
                                    \Log::channel('whatsapp')->error($e);
                                }
                            }
                        }
                    }
                    else {
                        $medias = Media::whereIn("id",array_unique($imagesDecoded))->get();
                        if(!$medias->isEmpty()) {
                            foreach($medias as $iimg => $media) {
                                $mediable = \App\Mediables::where('media_id',$media->id)->where('mediable_type','App\Product')->first();
                                //$data['media_url'] = $media->getUrl();
                                try{
                                    if($iimg != 0) {
                                        $chat_message = ChatMessage::create($data);
                                    }
                                    $chat_message->attachMedia($media, config('constants.media_tags'));
                                    // if this message is not first then send to the client
                                    if ($iimg != 0 && $isNeedToBeSend && $chat_message->status != 0 && $chat_message->is_queue == '0') {
                                        $myRequest = new Request();
                                        $myRequest->setMethod('POST');
                                        $myRequest->request->add(['messageId' => $chat_message->id]);
                                        $this->approveMessage($context, $myRequest);
                                    }
    
                                } catch (\Exception $e) {
                                    \Log::channel('whatsapp')->error($e);
                                }
                            }
                        }
                    }
                    // added above code optimized
                    // foreach (array_unique($imagesDecoded) as $image) {
                    //     $media = Media::find($image);
                    //     if (!empty($media)) {
                    //         removed code for checking the existing image
                    //         $isExists = DB::table('mediables')->where('media_id', $media->id)->where('mediable_id', $chat_message->id)->where('mediable_type', 'App\ChatMessage')->count();
                    //         if (!$isExists) {
                    //             // check first barcode image exist or not
                    //             $barcode = Media::where("filename", $image)->orderBy("id", "desc")->first();
                    //             if ($barcode) {
                    //                 $media = $barcode;
                    //             }
                    //             // check first barcode exist end
                    //         }
                    //     }
                    // }
                }
            }

        }

        if ($request->screenshot_path != '') {
            $image_path = public_path() . '/uploads/temp_screenshot.png';
            $img = substr($request->screenshot_path, strpos($request->screenshot_path, ",") + 1);
            $img = Image::make(base64_decode($img))->encode('png')->save($image_path);

            $media = MediaUploader::fromSource($image_path)
                ->toDirectory('chatmessage/' . floor($chat_message->id / config('constants.image_per_folder')))
                ->upload();
            $chat_message->attachMedia($media, config('constants.media_tags'));

            // if ($context == 'task' && $data['erp_user'] != Auth::id()) {
            //   $another_message->attachMedia($media,config('constants.media_tags'));
            // }

            File::delete('uploads/temp_screenshot.png');
        }

        // get the status for approval
        if ($isNeedToBeSend && $chat_message->status != 0 && $chat_message->is_queue == '0') {
            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['messageId' => $chat_message->id]);
            $this->approveMessage($context, $myRequest);
        }

        if ($request->ajax() || $ajaxNeeded) {
            return response()->json(['message' => $chat_message]);
        }

        return redirect('/' . $context . '/' . $module_id);
    }

    public function sendMultipleMessages(Request $request)
    {
        $selected_leads = json_decode($request->selected_leads, true);
        $leads = \App\ErpLeads::whereIn('id', $selected_leads)->get();

        if (count($leads) > 0) {
            foreach ($leads as $lead) {
                try {
                    $params = [];
                    $model_type = 'leads';
                    $model_id = $lead->id;
                    $params = [
                        'lead_id' => $lead->id,
                        'number' => null,
                        'message' => $request->message,
                        'user_id' => Auth::id()
                    ];

                    if ($lead->customer) {
                        $params['customer_id'] = $lead->customer->id;
                    }

                    $message = ChatMessage::create($params);

                    // NotificationQueueController::createNewNotification([
                    //   'message' => 'WAA - ' . $message->message,
                    //   'timestamps' => ['+0 minutes'],
                    //   'model_type' => $model_type,
                    //   'model_id' =>  $model_id,
                    //   'user_id' => Auth::id(),
                    //   'sent_to' => '',
                    //   'role' => 'message',
                    // ]);
                    //
                    // NotificationQueueController::createNewNotification([
                    //   'message' => 'WAA - ' . $message->message,
                    //   'timestamps' => ['+0 minutes'],
                    //  'model_type' => $model_type,
                    //   'model_id' =>  $model_id,
                    //   'user_id' => Auth::id(),
                    //   'sent_to' => '',
                    //   'role' => 'Admin',
                    // ]);
                } catch (\Exception $ex) {
                    return response($ex->getMessage(), 500);
                }
            }
        }

        return redirect()->route('leads.index');
    }

    public function updateAndCreate(Request $request)
    {
        $result = 'success';

        $message = Message::find($request->message_id);
        $params = [
            'number' => null,
            'status' => 1,
            'user_id' => Auth::id(),
        ];

        if ($message) {
            $params = [
                'approved' => 1,
                'status' => 2,
                'created_at' => Carbon::now()
            ];

            if ($request->moduletype == 'leads') {
                $params['lead_id'] = $message->moduleid;
                if ($lead = \App\ErpLeads::find($message->moduleid)) {
                    if ($lead->customer) {
                        $params['customer_id'] = $lead->customer->id;
                    }
                }
            } elseif ($request->moduletype == 'orders') {
                $params['order_id'] = $message->moduleid;
                if ($order = Order::find($message->moduleid)) {
                    if ($order->customer) {
                        $params['customer_id'] = $order->customer->id;
                    }
                }
            } elseif ($request->moduletype == 'customer') {
                $customer = Customer::find($message->customer_id);
                $params['customer_id'] = $customer->id;
            } elseif ($request->moduletype == 'purchase') {
                $params['purchase_id'] = $message->moduleid;
            }

            $images = $message->getMedia(config('constants.media_tags'));

            if ($images->first()) {
                $params['message'] = null;
                $chat_message = ChatMessage::create($params);

                foreach ($images as $img) {
                    $chat_message->attachMedia($img, config('constants.media_tags'));
                }
            } else {
                $params['message'] = $message->body;

                $chat_message = ChatMessage::create($params);
            }

            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['messageId' => $chat_message->id]);

            $result = $this->approveMessage($request->moduletype, $myRequest);

        } else {
            if ($request->moduletype == 'customer') {
                $params['customer_id'] = $request->moduleid;
                $params['order_id'] = null;
            } elseif ($request->moduletype == 'leads') {
                $params['lead_id'] = $request->moduleid;
                if ($lead = \App\ErpLeads::find($request->moduleid)) {
                    if ($lead->customer) {
                        $params['customer_id'] = $lead->customer->id;
                    }
                }
            } else {
                $params['order_id'] = $request->moduleid;
                if ($order = Order::find($request->moduleid)) {
                    if ($order->customer) {
                        $params['customer_id'] = $order->customer->id;
                    }
                }
            }

            if ($request->images) {
                $params['message'] = null;
                $chat_message = ChatMessage::create($params);
                foreach (json_decode($request->images) as $image) {
                    // $product = Product::find($product_id);
                    // $media = $product->getMedia(config('constants.media_tags'))->first();
                    // $params['media_url'] = $media->getUrl();
                    $media = Media::find($image);
                    $chat_message->attachMedia($media, config('constants.media_tags'));
                }
            }

            return redirect('/' . (!empty($request->moduletype) ? $request->moduletype : 'customer') . '/' . $request->moduleid);
        }

        return response()->json(['status' => $result]);
    }

    public function forwardMessage(Request $request)
    {
        $message = ChatMessage::find($request->message_id);

        foreach ($request->customer_id as $customer_id) {
            $new_message = new ChatMessage;
            $new_message->number = $message->number;
            $new_message->message = $message->message;
            $new_message->lead_id = $message->lead_id;
            $new_message->order_id = $message->order_id;
            $new_message->user_id = $message->user_id;
            $new_message->customer_id = $customer_id;
            $new_message->status = 1;
            $new_message->media_url = $message->media_url;

            $new_message->save();

            if ($images = $message->getMedia(config('constants.media_tags'))) {
                foreach ($images as $image) {
                    $new_message->attachMedia($image, config('constants.media_tags'));
                }
            }
        }

        return redirect()->back();
    }

    /**
     * poll messages
     *
     * @return \Illuminate\Http\Response
     */
    public function pollMessages(Request $request, $context)
    {
        $params = [];
        $result = [];
        $skip = $request->page && $request->page > 1 ? $request->page * 10 : 0;

        switch ($context) {
            case 'customer':
                $column = 'customer_id';
                $column_value = $request->customerId;
                break;
            case 'purchase':
                $column = 'purchase_id';
                $column_value = $request->purchaeId;
                break;
            default :
                $column = 'customer_id';
                $column_value = $request->customerId;
        }

        $messages = ChatMessage::select(['id', "$column", 'number', 'user_id', 'assigned_to', 'approved', 'status', 'sent', 'resent', 'created_at', 'media_url', 'message'])->where($column, $column_value)->latest();

        // IS IT NECESSARY ?
        if ($request->get("elapse")) {
            $elapse = (int)$request->get("elapse");
            $date = new \DateTime;
            $date->modify(sprintf("-%s seconds", $elapse));
            // $messages = $messages->where('created_at', '>=', $date->format('Y-m-d H:i:s'));
        }

        foreach ($messages->get() as $message) {
            $messageParams = [
                'id' => $message->id,
                'number' => $message->number,
                'assigned_to' => $message->assigned_to,
                'created_at' => Carbon::parse($message->created_at)->format('Y-m-d H:i:s'),
                'approved' => $message->approved,
                'status' => $message->status,
                'user_id' => $message->user_id,
                'sent' => $message->sent,
                'resent' => $message->resent,
            ];

            if ($message->media_url) {
                $messageParams['media_url'] = $message->media_url;
                $headers = get_headers($message->media_url, 1);
                $messageParams['content_type'] = $headers["Content-Type"][1];
            }

            if ($message->message) {
                $messageParams['message'] = $message->message;
            }

            if ($message->hasMedia(config('constants.media_tags'))) {
                $images_array = [];

                foreach ($message->getMedia(config('constants.media_tags')) as $key => $image) {
                    $temp_image = [
                        'key' => $image->getKey(),
                        'image' => $image->getUrl(),
                        'product_id' => '',
                        'special_price' => '',
                        'size' => ''
                    ];

                    $image_key = $image->getKey();

                    $product_image = Product::with('Media')
                        ->whereRaw("products.id IN (SELECT mediables.mediable_id FROM mediables WHERE mediables.media_id = $image_key)")
                        // ->whereHas('Media', function($q) use($image) {
                        //    $q->where('media.id', $image->getKey());
                        // })
                        ->select(['id', 'price_inr_special', 'supplier', 'size', 'lmeasurement', 'hmeasurement', 'dmeasurement'])->first();

                    if ($product_image) {
                        $temp_image['product_id'] = $product_image->id;
                        $temp_image['special_price'] = $product_image->price_inr_special;

                        $string = $product_image->supplier;
                        $expr = '/(?<=\s|^)[a-z]/i';
                        preg_match_all($expr, $string, $matches);
                        $supplier_initials = implode('', $matches[0]);
                        $temp_image['supplier_initials'] = strtoupper($supplier_initials);

                        if ($product_image->size != null) {
                            $temp_image['size'] = $product_image->size;
                        } else {
                            $temp_image['size'] = (string)$product_image->lmeasurement . ', ' . (string)$product_image->hmeasurement . ', ' . (string)$product_image->dmeasurement;
                        }
                    }

                    array_push($images_array, $temp_image);
                }

                $messageParams['images'] = $images_array;
            }

            $result[] = array_merge($params, $messageParams);
        }

        $result = array_values(collect($result)->sortBy('created_at')->reverse()->toArray());
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        if ($request->page) {
            $currentItems = array_slice($result, $perPage * ($currentPage - 1), $perPage);
        } else {
            $currentItems = array_reverse(array_slice($result, $perPage * ($currentPage - 1), $perPage));
        }

        $result = new LengthAwarePaginator($currentItems, count($result), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
        return response()->json($result);
    }

    public function pollMessagesCustomer(Request $request)
    {
        // Remove time limit
        set_time_limit(0);

        $params = [];
        $result = [];
        // $skip = $request->page && $request->page > 1 ? $request->page * 10 : 0;

        // $messages = ChatMessage::select(['id', 'customer_id', 'number', 'user_id', 'assigned_to', 'approved', 'status', 'sent', 'created_at', 'media_url', 'message'])->where('customer_id', $request->customerId)->latest();
        if ($request->customerId) {
            $column = 'customer_id';
            $value = $request->customerId;
        } else {
            if ($request->supplierId) {
                $column = 'supplier_id';
                $value = $request->supplierId;
            } else {
                if ($request->vendorId) {
                    $column = 'vendor_id';
                    $value = $request->vendorId;
                } else {
                    if ($request->taskId) {
                        $column = 'task_id';
                        $value = $request->taskId;
                    } else {
                        if ($request->erpUser) {
                            $column = 'erp_user';
                            $value = $request->erpUser;
                        } else {
                            if ($request->dubbizleId) {
                                $column = 'dubbizle_id';
                                $value = $request->dubbizleId;
                            } else {
                                if ($request->lawyerId) {
                                    $column = 'lawyer_id';
                                    $value = $request->lawyerId;
                                } else {
                                    if ($request->caseId) {
                                        $column = 'case_id';
                                        $value = $request->caseId;
                                    } else {
                                        if ($request->bloggerId) {
                                            $column = 'blogger_id';
                                            $value = $request->bloggerId;
                                        } else {
                                            if ($request->customerId) {
                                                $column = 'customer_id';
                                                $value = $request->customerId;
                                            } else {
                                                if ($request->oldID) {
                                                    $column = 'old_id';
                                                    $value = $request->oldId;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $select_fields = ['id', 'customer_id', 'number', 'user_id', 'erp_user', 'assigned_to', 'approved', 'status', 'sent', 'error_status', 'resent', 'created_at', 'media_url', 'message'];
        if ($request->caseId) {
            array_push($select_fields, 'lawyer_id');
        }
        $messages = ChatMessage::select($select_fields)->where($column, $value)->where('status', '!=', 7);

        if ($request->caseId) {
            $messages = $messages->with('lawyer:id,name');
        }

        if (Setting::get('show_automated_messages') == 0) {
            $messages = $messages->where('status', '!=', 9);
        }

        if ($request->erpUser) {
            $messages = $messages->whereNull('task_id');
        }
        // ->join(DB::raw('(SELECT mediables.media_id, mediables.mediable_type, mediables.mediable_id FROM `mediables`) as mediables'), 'chat_messages.id', '=', 'mediables.mediable_id', 'RIGHT')
        // ->selectRaw('id, customer_id, number, user_id, assigned_to, approved, status, sent, created_at, media_url, message, mediables.media_id, mediables.mediable_id')->where('customer_id', $request->customerId)->latest();


        // foreach ($messages->get() as $message) {
        //   foreach ($message->media_id as $med) {
        //     dump($med);
        //   }
        // }


        // IS IT NECESSARY ?
        if ($request->get("elapse")) {
            $elapse = (int)$request->get("elapse");
            $date = new \DateTime;
            $date->modify(sprintf("-%s seconds", $elapse));
            // $messages = $messages->where('created_at', '>=', $date->format('Y-m-d H:i:s'));
        }

        foreach ($messages->latest()->get() as $message) {
            $messageParams = [
                'id' => $message->id,
                'number' => $message->number,
                'assigned_to' => $message->assigned_to,
                'created_at' => Carbon::parse($message->created_at)->format('Y-m-d H:i:s'),
                'approved' => $message->approved,
                'status' => $message->status,
                'user_id' => $message->user_id,
                'erp_user' => $message->erp_user,
                'sent' => $message->sent,
                'resent' => $message->resent,
                'error_status' => $message->error_status
            ];
            if ($request->caseId) {
                $messageParams['lawyer'] = optional($message->lawyer)->name;
            }

            if ($message->media_url) {
                $messageParams['media_url'] = $message->media_url;
                $headers = get_headers($message->media_url, 1);
                $messageParams['content_type'] = $headers["Content-Type"][1];
            }

            if ($message->message) {
                $messageParams['message'] = $message->message;
            }

            if ($message->hasMedia(config('constants.media_tags'))) {
                $images_array = [];

                foreach ($message->getMedia(config('constants.media_tags')) as $key => $image) {
                    $temp_image = [
                        'key' => $image->getKey(),
                        'image' => $image->getUrl(),
                        'product_id' => '',
                        'special_price' => '',
                        'size' => ''
                    ];

                    $image_key = $image->getKey();
                    $mediable_type = "Product";

                    $product_image = Product::with('Media')
                        ->whereRaw("products.id IN (SELECT mediables.mediable_id FROM mediables WHERE mediables.media_id = $image_key AND mediables.mediable_type LIKE '%$mediable_type%')")
                        // ->whereHas('Media', function($q) use($image) {
                        //    $q->where('media.id', $image->getKey());
                        // })
                        ->select(['id', 'price_inr_special', 'supplier', 'size', 'lmeasurement', 'hmeasurement', 'dmeasurement'])->first();


                    if ($product_image) {
                        $temp_image['product_id'] = $product_image->id;
                        $temp_image['special_price'] = $product_image->price_inr_special;

                        $string = $product_image->supplier;
                        $expr = '/(?<=\s|^)[a-z]/i';
                        preg_match_all($expr, $string, $matches);
                        $supplier_initials = implode('', $matches[0]);
                        $temp_image['supplier_initials'] = strtoupper($supplier_initials);

                        if ($product_image->size != null) {
                            $temp_image['size'] = $product_image->size;
                        } else {
                            $temp_image['size'] = (string)$product_image->lmeasurement . ', ' . (string)$product_image->hmeasurement . ', ' . (string)$product_image->dmeasurement;
                        }
                    }

                    array_push($images_array, $temp_image);
                }

                $messageParams['images'] = $images_array;
            }

            $result[] = array_merge($params, $messageParams);
        }

        $result = array_values(collect($result)->sortBy('created_at')->reverse()->toArray());
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10000;

        if ($request->page) {
            $currentItems = array_slice($result, $perPage * ($currentPage - 1), $perPage);

        } else {
            $currentItems = array_reverse(array_slice($result, $perPage * ($currentPage - 1), $perPage));
            $result = array_reverse($result);
        }

        $result = new LengthAwarePaginator($currentItems, count($result), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        return response()->json($result);
    }

    public function approveMessage($context, Request $request)
    {
        $defCustomer = '971547763482';
        $message = ChatMessage::findOrFail($request->get("messageId"));
        $today_date = Carbon::now()->format('Y-m-d');

        if ($context == "customer") {
            // check the customer message
            $customer = \App\Customer::find($message->customer_id);

            // Check the message is email message
            if( $message->is_email == 1 ){
                
                if( !empty( $customer ) ){

                    $botReply          = \App\ChatbotReply::where( 'chat_id', $message->id)->get();
                    $storeEmailAddress = EmailAddress::whereNotNull('store_website_id')->where( 'store_website_id', $customer->store_website_id )->first();
                    // $from_address      = env('MAIL_FROM_ADDRESS');
                    $from_address      = config('env.MAIL_FROM_ADDRESS');

                    $subject = null;
                    $message_body = $message->message;

                    if( !empty( $storeEmailAddress ) && !empty( $storeEmailAddress->from_address )  ){
                        $from_address = $storeEmailAddress->from_address;
                    }
                    
                    $template = \App\MailinglistTemplate::getBotEmailTemplate( $customer->store_website_id );

                    if( empty( $template ) ){
                        $template = \App\MailinglistTemplate::getBotEmailTemplate();
                    }
                    
                    if( $template ){
                        $subject      = $template->subject;
                        $message_body = str_replace( array("{{customer_name}}","{{content}}"),array( $customer->name,  $message_body ),$template->static_template );
                    }

                    $email             = \App\Email::create([
                        'model_id'         => $customer->id,
                        'model_type'       => \App\Customer::class,
                        'from'             => $from_address ?? '',
                        'to'               => $customer->email,
                        'subject'          => $subject,
                        'message'          => $message_body,
                        'template'         => 'customer-simple',
                        'additional_data'  => $customer->id,
                        'status'           => 'pre-send',
                        'store_website_id' => null,
                        'is_draft' => 1,
                    ]);

                    \App\Jobs\SendEmail::dispatch($email);
                    
                    $message->update([
                        'approved' => 1,
                        'is_queue' => 0,
                        'is_draft' => 0,
                        'status' => 2,
                        'created_at' => Carbon::now()
                    ]);
                }

                return response()->json([
                    'data' => []
                ], 200);
            }
            

            if ($customer && $customer->hasDND()) {
                $message->update([
                    'approved' => 1,
                    'is_queue' => 0,
                    'status' => 2,
                    'created_at' => Carbon::now()
                ]);

                return response()->json([
                    'data' => []
                ], 200);
            }

            // disable first intro message of the day
            /*$chat_messages_count = ChatMessage::where('customer_id', $message->customer_id)->where('created_at', 'LIKE', "%$today_date%")->whereNull('number')->count();

            if ($chat_messages_count == 1) {
                $customer = Customer::find($message->customer_id);
                $params = [
                    'number' => null,
                    'user_id' => Auth::id(),
                    'approved' => 1,
                    'status' => 9,
                    'customer_id' => $message->customer_id,
                    'message' => AutoReply::where('type', 'auto-reply')->where('keyword', 'customer-info-message')->first()->reply
                ];

                $additional_message = ChatMessage::create($params);

                $sendResult = $this->sendWithThirdApi($message->customer->phone, $customer->whatsapp_number ?? $defCustomer, $additional_message->message, null, $additional_message->id);
                if ($sendResult) {
                    $additional_message->unique_id = $sendResult[ 'id' ] ?? '';
                    $additional_message->save();
                }

                sleep(5);
            }*/

            if (Setting::get('whatsapp_number_change') == 1) {
                $customer = Customer::find($message->customer_id);
                $default_api = ApiKey::where('default', 1)->first();

                if (!$customer->whatsapp_number_change_notified() && $default_api->number != $customer->whatsapp_number) {
                    $params = [
                        'number' => null,
                        'user_id' => Auth::id(),
                        'approved' => 1,
                        'status' => 9,
                        'customer_id' => $message->customer_id,
                        'message' => 'Our whatsapp number has changed'
                    ];

                    $additional_message = ChatMessage::create($params);

                    $sendResult = $this->sendWithThirdApi($customer->phone, $default_api->number, $additional_message->message, null, $additional_message->id);
                    // Store send result
                    if ($sendResult) {
                        $additional_message->unique_id = $sendResult['id'] ?? '';
                        $additional_message->save();
                    }


                    sleep(5);

                    CommunicationHistory::create([
                        'model_id' => $customer->id,
                        'model_type' => Customer::class,
                        'type' => 'number-change',
                        'method' => 'whatsapp'
                    ]);
                }
            }
            if (isset($customer)) {
                $phone = $customer->phone;
                $whatsapp_number = $customer->whatsapp_number;
            } else {
                $customer = Customer::find($message->customer_id);
                $phone = $customer->phone;
                $whatsapp_number = $customer->whatsapp_number;
            }
        } else {
            if ($context == 'supplier') {
                $supplier = Supplier::find($message->supplier_id);
                $phone = $supplier->default_phone;
                if (empty($supplier->whatsapp_number)) {
                    $whatsapp_number = '971502609192';
                } else {
                    $whatsapp_number = $supplier->whatsapp_number;
                }

            } else {
                if ($context == 'vendor') {
                    $vendor = Vendor::find($message->vendor_id);
                    $phone = $vendor->default_phone;
                    $whatsapp_number = $vendor->whatsapp_number;
                } else {
                    if ($context == 'task') {
                        $sender = User::find($message->user_id);

                        $isUser = false;
                        if ($message->erp_user == '') {
                            $receiver = Contact::find($message->contact_id);
                        } else {
                            $isUser = true;
                            $receiver = User::find($message->erp_user);
                        }

                        $phone = @$receiver->phone;
                        $whatsapp_number = ($receiver && $isUser) ? $receiver->whatsapp_number : $sender->whatsapp_number;
                    } else {
                        if ($context == 'user') {
                            $sender = User::find($message->user_id);
                            $isUser = false;
                            if ($message->erp_user != '') {
                                $isUser = true;
                                $receiver = User::find($message->erp_user);
                            } else {
                                $receiver = Contact::find($message->contact_id);
                            }

                            $phone = $receiver->phone;
                            $whatsapp_number = ($receiver && $isUser) ? $receiver->whatsapp_number : $sender->whatsapp_number;
                        } else {
                            if ($context == 'dubbizle') {
                                $dubbizle = Dubbizle::find($message->dubbizle_id);
                                $phone = $dubbizle->phone_number;
                                $whatsapp_number = '971502609192';
                            } else {
                                if ($context == 'lawyer') {
                                    $lawyer = Lawyer::find($message->lawyer_id);
                                    $phone = $lawyer->default_phone;
                                    $whatsapp_number = $lawyer->whatsapp_number;
                                } else {
                                    if ($context == 'case') {
                                        $case = LegalCase::find($message->case_id);
                                        $lawyer = $case->lawyer;
                                        if ($lawyer) {
                                            $phone = $lawyer->default_phone;
                                        } else {
                                            $phone = '';
                                        }
                                        $whatsapp_number = $case->whatsapp_number;
                                    } else {
                                        if ($context == 'blogger') {
                                            $blogger = Blogger::find($message->blogger_id);
                                            $phone = $blogger->default_phone;
                                            $whatsapp_number = $blogger->whatsapp_number;
                                        } else {
                                            if ($context == 'old') {
                                                $old = Old::find($message->old_id);
                                                $phone = $old->phone;
                                                $whatsapp_number = '';
                                            }

                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $data = '';
        if ($message->message != '') {

            if ($context == 'supplier' || $context == 'vendor' || $context == 'task' || $context == 'dubbizle' || $context == 'lawyer' || $context == 'case' || $context == 'blogger' || $context == 'old' || $context == 'hubstuff' || $context == 'user-feedback') {
                if ($context == 'supplier') {
                    $supplierDetails = Supplier::find($message->supplier_id);
                    $language = $supplierDetails->language;
                    if ($language != null) {
                        try {
                            $result = TranslationHelper::translate('en', $language, $message->message);
                            $history = array(
                                'msg_id' => $message->id,
                                'supplier_id' => $message->supplier_id,
                                'original_msg' => $message->message,
                                'translate_msg' => '('.$language.') '.$result,
                                'error_log' => 'N/A',
                            );
                            \App\SupplierTranslateHistory::insert( $history );
                        } catch (\Throwable $e) {
                            $history = array(
                                'msg_id' => $message->id,
                                'supplier_id' => $message->supplier_id,
                                'original_msg' => $message->message,
                                'translate_msg' => null,
                                'error_log' => $e->getMessage(),
                            );
                            \App\SupplierTranslateHistory::insert( $history );
                            throw new \Exception($e->getMessage(), 1);
                            
                        }
                        $message->message = $result;
                    }
                }
                if ($context == 'customer') {
                    $supplierDetails = Customer::find($message->supplier_id);
                    $language = $supplierDetails->language;
                    if ($language != null) {
                        $result = TranslationHelper::translate('en', $language, $message->message);
                        $message->message = $result;
                    }
                }

                if ($context == 'user-feedback') {
                    $userDetails = User::find($message->user_id);
                    $phone = $userDetails->phone;
                    $user = \Auth::user();
                    $whatsapp_number = $user->whatsapp_number;
                    $language = $userDetails->language;
                    if ($language != null) {
                        $result = TranslationHelper::translate('en', $language, $message->message);
                        $message->message = $result;
                    }
                }
                if ($context == 'hubstuff') { 
                    $user = User::find($message->hubstuff_activity_user_id);
                    $phone = $user->phone;
                    $whatsapp_number = Auth::user()->whatsapp_number;
                }
                $sendResult = $this->sendWithThirdApi($phone, $whatsapp_number, $message->message, null, $message->id);
            } else {
                $sendResult = $this->sendWithThirdApi($phone, $whatsapp_number ?? $defCustomer, $message->message, null, $message->id);
            }

            // Store send result
            if ($sendResult) {
                $message->unique_id = $sendResult['id'] ?? '';
                $message->save();
            }
        }

        $sendMediaFile = true;
        if ($message->media_url != '') {
            $sendResult = $this->sendWithThirdApi($phone, $whatsapp_number ?? $defCustomer, null, $message->media_url,$message->id);
            // Store send result
            if ($sendResult) {
                $message->unique_id = $sendResult['id'] ?? '';
                $message->save();
            }
            // check here that image media url is temp created if so we can delete that
            if (strpos($message->media_url, 'instant_message_') !== false) {
                $sendMediaFile = false;
                $path = parse_url($message->media_url, PHP_URL_PATH);
                if (file_exists(public_path($path)) && strpos($message->media_url, $path) !== false) {
                    @unlink(public_path($path));
                    $message->media_url = null;
                    $message->save();
                }
            }
        }

        $images = $message->getMedia(config('constants.media_tags'));
        if (!empty($images) && $sendMediaFile) {
            $count = 0;
            foreach ($images as $key => $image) {
                $send = str_replace(' ', '%20', $image->getUrl());

                if ($context == 'task' || $context == 'vendor' || $context == 'supplier') {
                    $sendResult = $this->sendWithThirdApi($phone, $whatsapp_number, null, $send,$message->id);
                    // Store send result
                    if ($sendResult) {
                        $message->unique_id = $sendResult['id'] ?? '';
                        $message->save();
                    }
                } else {
                    // $data = $this->sendWithNewApi($phone, $whatsapp_number, NULL, $image->getUrl(), $message->id);
                    if ($count < 5) {
                        $count++;
                    } else {
                        sleep(5);

                        $count = 0;
                    }

                    $sendResult = $this->sendWithThirdApi($phone, $whatsapp_number ?? 919152731483, null, $send,$message->id);
                    // Store send result
                    if ($sendResult) {
                        $message->unique_id = $sendResult['id'] ?? '';
                        $message->save();
                    }
                }
            }
        }

        $message->update([
            'approved' => 1,
            'is_queue' => 0,
            'status' => 2,
            'created_at' => Carbon::now()
        ]);

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function sendToAll(Request $request, $validate = true)
    {

        set_time_limit(0);
        if ($validate) {
            $this->validate($request, [
                'sending_time' => 'required|date',
                'frequency' => 'required|numeric',
                'rating' => 'sometimes|nullable|numeric',
                'gender' => 'sometimes|nullable|string',
            ]);
        }

        $frequency = $request->frequency;

        // if ($request->moduletype == 'customers') {
        //     $content[ 'message' ] = $request->body;

        //     foreach (json_decode($request->images) as $key => $image) {
        //         $media = Media::find($image);

        //         $content[ 'image' ][ $key ][ 'key' ] = $media->getKey();
        //         $content[ 'image' ][ $key ][ 'url' ] = $media->getUrl();
        //     }
        // } else {
        //     $content[ 'message' ] = $request->message;

        //     if ($request->hasFile('images')) {
        //         foreach ($request->file('images') as $key => $image) {
        //             $media = MediaUploader::fromSource($image)->upload();
        //             $content[ 'image' ][ $key ][ 'key' ] = $media->getKey();
        //             $content[ 'image' ][ $key ][ 'url' ] = $media->getUrl();
        //         }
        //     }
        // }

        if ($request->image_id != '') {
            $broadcast_image = BroadcastImage::find($request->image_id);
            if ($broadcast_image->hasMedia(config('constants.media_tags'))) {
                foreach ($broadcast_image->getMedia(config('constants.media_tags')) as $key2 => $brod_image) {
                    $content['image']['url'] = $brod_image->getUrl();
                    $content['image']['key'] = $brod_image->getKey();
                }
            }
        }
        //Broadcast For Whatsapp
        if (($request->to_all || $request->moduletype == 'customers') && $request->platform == 'whatsapp') {

            // Create empty array for checking numbers
            $arrCustomerNumbers = [];

            // Get all numbers from config
            //$config = \Config::get("apiwha.instances");
            $configs = WhatsappConfig::where('is_customer_support', 0)->get();

            //Loop over numbers
            foreach ($configs as $arrNumber) {
                if ($arrNumber['number']) {
                    $arrBroadcastNumbers[] = $arrNumber['number'];
                }
            }

            $minutes = round(60 / $frequency);
            $max_group_id = ChatMessage::where('status', 8)->max('group_id') + 1;

            $data = Customer::whereNotNull('phone')->where('do_not_disturb', 0);


            if ($request->rating != '') {
                $data = $data->where('rating', $request->rating);
            }

            if ($request->gender != '') {
                $data = $data->where('gender', $request->gender);
            }

            if ($request->shoe_size != '') {
                $data = $data->where('shoe_size', $request->shoe_size);
            }

            if ($request->clothing_size != '') {
                $data = $data->where('clothing_size', $request->clothing_size);
            }

            $data = $data->get()->groupBy('broadcast_number');

            foreach ($data as $broadcastNumber => $customers) {

                $now = $request->sending_time ? Carbon::parse($request->sending_time) : Carbon::now();
                $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);

                if (!$now->between($morning, $evening, true)) {
                    if (Carbon::parse($now->format('Y-m-d'))->diffInWeekDays(Carbon::parse($morning->format('Y-m-d')), false) == 0) {
                        // add day
                        $now->addDay();
                        $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                    } else {
                        // dont add day
                        $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                        $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                    }
                }

                if (in_array($broadcastNumber, $arrBroadcastNumbers)) {

                    foreach ($customers as $customer) {

                        //Changes put by satyam for connecting Old BroadCast with New BroadCast page
                        if (isset($customer->customerMarketingPlatformActive)) {
                            if ($customer->customerMarketingPlatformActive->active == 1) {

                                //Checking For DND
                                if ($customer->do_not_disturb == 1) {
                                    continue;
                                }

                                //Checking For Last Message Send 24 hours
                                if (isset($customer->lastImQueueSend) && $customer->lastImQueueSend->sent_at >= Carbon::now()->subDay()->toDateTimeString()) {
                                    continue;
                                }

                                //Check if customer has Phone
                                if ($customer->phone == '' || $customer->phone == null) {
                                    continue;
                                }

                                //Check if customer has broadcast
                                if ($customer->broadcast_number == '' || $customer->broadcast_number == null) {
                                    continue;
                                }

                                $params = [
                                    'number' => null,
                                    'user_id' => Auth::id(),
                                    'customer_id' => $customer->id,
                                    'approved' => 0,
                                    'status' => 8, // status for Broadcast messages
                                    'group_id' => $max_group_id
                                ];

                                $priority = null; // Priority for broadcast messages, now the same as for normal messages
                                if ($request->image_id != null) {

                                    if ($content['image'] != null) {

                                        //Saving Message In Chat Message
                                        $chatMessage = ChatMessage::create($params);
                                        foreach ($content as $url) {
                                            //Attach image to chat message
                                            $chatMessage->attachMedia($url['key'], config('constants.media_tags'));
                                            $priority = 1;
                                            $send = InstantMessagingHelper::scheduleMessage($customer->phone, $customer->broadcast_number, $request->message, $url['url'], $priority, $now, $max_group_id);
                                            if ($send != false) {
                                                $now->addMinutes($minutes);
                                                $now = InstantMessagingHelper::broadcastSendingTimeCheck($now);

                                            } else {
                                                continue;
                                            }
                                        }

                                    }
                                } elseif ($request->linked_images == null) {
                                    $chatMessage = ChatMessage::create($params);

                                    $send = InstantMessagingHelper::scheduleMessage($customer->phone, $customer->broadcast_number, $request->message, '', $priority, $now, $max_group_id);
                                    if ($send != false) {
                                        $now->addMinutes($minutes);
                                        $now = InstantMessagingHelper::broadcastSendingTimeCheck($now);
                                    }
                                } else {
                                    continue;
                                }


                                //DO NOT REMOVE THIS CODE
                                // MessageQueue::create([
                                //     'user_id' => Auth::id(),
                                //     'customer_id' => $customer->id,
                                //     'phone' => null,
                                //     'type' => 'message_all',
                                //     'data' => json_encode($content),
                                //     'sending_time' => $now,
                                //     'group_id' => $max_group_id
                                // ]);


                            }
                        }

                    }

                }
            }
            //Broadcast for Facebook
        } elseif (strtolower($request->platform) == 'facebook') {
            //Getting Frequency
            $minutes = round(60 / $frequency);
            //Getting Max Id
            $max_group_id = ChatMessage::where('status', 8)->max('group_id') + 1;

            //Getting All Brand Fans
            $brands = BrandFans::all();

            $count = 0;

            //Scheduling Time based on frequency
            $now = $request->sending_time ? Carbon::parse($request->sending_time) : Carbon::now();
            $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
            $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);

            if (!$now->between($morning, $evening, true)) {
                if (Carbon::parse($now->format('Y-m-d'))->diffInWeekDays(Carbon::parse($morning->format('Y-m-d')), false) == 0) {
                    // add day
                    $now->addDay();
                    $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                } else {
                    // dont add day
                    $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                }
            }
            $sendingTime = '';

            //Getting Last Broadcast Id
            $broadcastId = ImQueue::groupBy('broadcast_id')->orderby('broadcast_id', 'desc')->first();

            foreach ($brands as $brand) {

                $count++;

                // Convert maxTime to unixtime
                if (empty($sendingTime)) {
                    $maxTime = strtotime($now);
                } else {
                    $now = $sendingTime ? Carbon::parse($sendingTime) : Carbon::now();
                    $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);

                    if (!$now->between($morning, $evening, true)) {
                        if (Carbon::parse($now->format('Y-m-d'))->diffInWeekDays(Carbon::parse($morning->format('Y-m-d')), false) == 0) {
                            // add day
                            $now->addDay();
                            $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                        } else {
                            // dont add day
                            $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                        }
                    }
                    $sendingTime = $now;
                    $maxTime = strtotime($sendingTime);
                }


                // Add interval
                $maxTime = $maxTime + (3600 / $request->frequency);

                // Check if it's in the future
                if ($maxTime < time()) {
                    $maxTime = time();
                }

                $sendAfter = date('Y-m-d H:i:s', $maxTime);
                $sendingTime = $sendAfter;
                //Getting Least Number of Messages Send Per Account
                $accounts = Account::where('platform', 'facebook')->where('status', 1)->get();
                $count = [];
                foreach ($accounts as $account) {
                    $count[] = array($account->imQueueBroadcast->count() => $account->last_name);
                }
                //Arranging In Ascending Order
                ksort($count);
                if (!isset($broadcastId->broadcast_id)) {
                    $broadcastIdLast = 0;
                } else {
                    $broadcastIdLast = $broadcastId->broadcast_id;
                }
                //Just Sending Text To Facebook
                if (isset($content)) {
                    foreach ($content as $url) {
                        if (isset($count[0][key($count[0])])) {
                            $username = $count[0][key($count[0])];
                            $queue = new ImQueue();
                            $queue->im_client = 'facebook';
                            $queue->number_to = str_replace('https://www.facebook.com/', '', $brand->profile_url);
                            $queue->number_from = $username;
                            $queue->text = $request->message;
                            $queue->priority = null;
                            $queue->image = $url['url'];
                            $queue->marketing_message_type_id = 1;
                            $queue->priority = 1;
                            $queue->broadcast_id = ($broadcastIdLast + 1);
                            $queue->send_after = $sendAfter;
                            $queue->save();
                        }
                    }
                } else {
                    //Sending Text with Image
                    if (isset($count[0][key($count[0])])) {
                        $username = $count[0][key($count[0])];
                        $queue = new ImQueue();
                        $queue->im_client = 'facebook';
                        $queue->number_to = str_replace('https://www.facebook.com/', '', $brand->profile_url);
                        $queue->number_from = $username;
                        $queue->text = $request->message;
                        $queue->priority = null;
                        $queue->priority = 1;
                        $queue->marketing_message_type_id = 1;
                        $queue->broadcast_id = ($broadcastId->broadcast_id + 1);
                        $queue->send_after = $sendAfter;
                        $queue->save();
                    }
                }


            }

        } elseif (strtolower($request->platform) == 'instagram') {
            //Getting Cold Leads to Send Message
            $query = ColdLeads::query();
            $competitor = $request->competitor;
            $limit = 100;
            //Check if competitor is selected
            if (!empty($competitor)) {
                $comp = CompetitorPage::find($competitor);
                $query = $query->where('because_of', 'LIKE', '%via ' . $comp->name . '%');
            }
            //check for gender
            if (!empty($request->gender)) {
                $query = $query->where('gender', $request->gender);
            }
            //Get Cold Leads to be send
            $coldleads = $query->where('status', 1)->where('messages_sent', '<', 5)->take($limit)->orderBy('messages_sent', 'ASC')->orderBy('id', 'ASC')->get();
            //Schedulaing Message based on frequency
            $minutes = round(60 / $frequency);

            $count = 0;

            //Scheduling Time based on frequency
            $now = $request->sending_time ? Carbon::parse($request->sending_time) : Carbon::now();
            $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
            $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);

            if (!$now->between($morning, $evening, true)) {
                if (Carbon::parse($now->format('Y-m-d'))->diffInWeekDays(Carbon::parse($morning->format('Y-m-d')), false) == 0) {
                    // add day
                    $now->addDay();
                    $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                } else {
                    // dont add day
                    $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                }
            }
            $sendingTime = '';
            //Getting Last Broadcast Id
            $broadcastId = ImQueue::groupBy('broadcast_id')->orderby('broadcast_id', 'desc')->first();

            foreach ($coldleads as $coldlead) {

                $count++;

                // Convert maxTime to unixtime
                if (empty($sendingTime)) {
                    $maxTime = strtotime($now);
                } else {
                    $now = $sendingTime ? Carbon::parse($sendingTime) : Carbon::now();
                    $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                    $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);

                    if (!$now->between($morning, $evening, true)) {
                        if (Carbon::parse($now->format('Y-m-d'))->diffInWeekDays(Carbon::parse($morning->format('Y-m-d')), false) == 0) {
                            // add day
                            $now->addDay();
                            $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                        } else {
                            // dont add day
                            $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $evening = Carbon::create($now->year, $now->month, $now->day, 19, 0, 0);
                        }
                    }
                    $sendingTime = $now;
                    $maxTime = strtotime($sendingTime);
                }


                // Add interval
                $maxTime = $maxTime + (3600 / $request->frequency);

                // Check if it's in the future
                if ($maxTime < time()) {
                    $maxTime = time();
                }

                $sendAfter = date('Y-m-d H:i:s', $maxTime);
                $sendingTime = $sendAfter;

                //Getting Least Number of Messages Send Per Account
                $accounts = Account::where('platform', 'instagram')->where('status', 1)->get();
                $count = [];
                foreach ($accounts as $account) {
                    $count[] = array($account->imQueueBroadcast->count() => $account->last_name);
                }
                //Arranging In Ascending Order
                ksort($count);

                if (!isset($broadcastId->broadcast_id)) {
                    $broadcastIdLast = 0;
                } else {
                    $broadcastIdLast = $broadcastId->broadcast_id;
                }
                //Sending Text with Image
                if (isset($count[0][key($count[0])])) {
                    $username = $count[0][key($count[0])];
                    $queue = new ImQueue();
                    $queue->im_client = 'instagram';
                    $queue->number_to = $coldlead->platform_id;
                    $queue->number_from = $username;
                    $queue->text = $request->message;
                    $queue->priority = null;
                    $queue->priority = 1;
                    $queue->marketing_message_type_id = 1;
                    $queue->broadcast_id = ($broadcastIdLast + 1);
                    $queue->send_after = $sendAfter;
                    $queue->save();
                }

            }

        } else {
            $minutes = round(60 / $frequency);
            $now = $request->sending_time ? Carbon::parse($request->sending_time) : Carbon::now();
            $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
            $evening = Carbon::create($now->year, $now->month, $now->day, 18, 0, 0);
            $max_group_id = MessageQueue::max('group_id') + 1;
            $array = Excel::toArray(new CustomerNumberImport, $request->file('file'));

            foreach ($array as $item) {
                foreach ($item as $it) {
                    $number = (int)$it[0];

                    if (!$now->between($morning, $evening, true)) {
                        if (Carbon::parse($now->format('Y-m-d'))->diffInWeekDays(Carbon::parse($morning->format('Y-m-d')), false) == 0) {
                            // add day
                            $now->addDay();
                            $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $evening = Carbon::create($now->year, $now->month, $now->day, 18, 0, 0);
                        } else {
                            // dont add day
                            $now = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $morning = Carbon::create($now->year, $now->month, $now->day, 9, 0, 0);
                            $evening = Carbon::create($now->year, $now->month, $now->day, 18, 0, 0);
                        }
                    }

                    MessageQueue::create([
                        'user_id' => Auth::id(),
                        'customer_id' => null,
                        'phone' => $number,
                        'whatsapp_number' => $request->whatsapp_number,
                        'type' => 'message_selected',
                        'data' => json_encode($content),
                        'sending_time' => $now,
                        'group_id' => $max_group_id
                    ]);

                    $now->addMinutes($minutes);
                }
            }
        }

        return redirect()->route('broadcast.images')->with('success', 'Messages are being sent in the background!');
    }

    public function resendMessage2(Request $request)
    {
        $messageId = $request->get('message_id');
        $message = ChatMessage::find($messageId);

        $requestData = new Request();
        $requestData->setMethod('POST');
        $requestData->request->add(['customer_id' => $message->customer_id, 'message' => $message->message, 'status' => 1]);

        return $this->sendMessage($requestData, 'customer', true);
    }

    public function stopAll()
    {
        $message_queues = ImQueue::whereNull('sent_at')->get();

        foreach ($message_queues as $message_queue) {
            $message_queue->send_after = null;
            $message_queue->save();
        }

        return redirect()->back()->with('success', 'Messages stopped processing!');
    }

    public function sendWithWhatsApp($number, $sendNumber, $text, $validation = true, $chat_message_id = null)
    {
        $logDetail = [
            'number' => $number,
            'whatsapp_number' => $sendNumber,
            'message' => $text,
            'validation' => $validation,
            'chat_message_id' => $chat_message_id,
        ];
        if ($validation == true) {
            if (Auth::id() != 3) {
                if (strlen($number) != 12 || !preg_match('/^[91]{2}/', $number)) {
                    // DON'T THROW EXCEPTION
                    // throw new \Exception("Invalid number format. Must be 12 digits and start with 91");
                    \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Invalid number format. Must be 12 digits and start with 91: " . $number. ' ['. json_encode($logDetail). '] ');
                    return false;
                }
            }
        }

        // foreach (\Config::get("apiwha.api_keys") as $config_key) {
        //   if ($config_key['number'] == $number) {
        //     return;
        //   }
        // }

        $api_keys = ApiKey::all();

        foreach ($api_keys as $api_key) {
            if ($api_key->number == $number) {
                return;
            }
        }

        $curl = curl_init();

        if (Setting::get('whatsapp_number_change') == 1) {
            $keys = \Config::get("apiwha.api_keys");
            $key = $keys[0]['key'];

            foreach ($api_keys as $api_key) {
                if ($api_key->default == 1) {
                    $key = $api_key->key;
                }
            }
        } else {
            if (is_null($sendNumber)) {
                $keys = \Config::get("apiwha.api_keys");
                $key = $keys[0]['key'];

                foreach ($api_keys as $api_key) {
                    if ($api_key->default == 1) {
                        $key = $api_key->key;
                    }
                }
            } else {
                // $config = $this->getWhatsAppNumberConfig($sendNumber);
                // $key = $config['key'];

                $keys = \Config::get("apiwha.api_keys");
                $key = $keys[0]['key'];

                foreach ($api_keys as $api_key) {
                    if ($api_key->default == 1) {
                        $key = $api_key->key;
                    }
                }

                foreach ($api_keys as $api_key) {
                    if ($api_key->number == $sendNumber) {
                        $key = $api_key->key;
                    }
                }
            }
        }

        $encodedNumber = urlencode($number);
        $encodedText = urlencode($text);

        if ($chat_message_id) {
            $custom_data = [
                'chat_message_id' => $chat_message_id
            ];

            $encodedCustomData = urlencode(json_encode($custom_data));
        } else {
            $encodedCustomData = '';
        }
        //$number = "";
        $url = "https://panel.apiwha.com/send_message.php?apikey=" . $key . "&number=" . $encodedNumber . "&text=" . $encodedText . "&custom_data=" . $encodedCustomData;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // DON'T THROW EXCEPTION
            // throw new \Exception("cURL Error #:" . $err);
            \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") cURL Error for number " . $number . ":" . $err. ' ['. json_encode($logDetail). '] ');
            return false;
        } else {
            $result = json_decode($response);
            if (!$result->success) {
                // DON'T THROW EXCEPTION
                //throw new \Exception("whatsapp request error: " . $result->description);
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") WhatsApp request error for number " . $number . ": " . $result->description. ' ['. json_encode($logDetail). '] ');
                return false;
            } else {
                // Log successful send
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Message was sent to number " . $number . ":" . $response. ' ['. json_encode($logDetail). '] ');
            }
        }
    }

    public function pullApiwha()
    {
        // $api_keys = ApiKey::all();
        //
        // foreach ($api_keys as $api_key) {
        //   if ($api_key->number == $number) {
        //     return;
        //   }
        // }

        $curl = curl_init();

        // $keys = \Config::get("apiwha.api_keys");
        // $key = $keys[0]['key'];
        //
        // foreach ($api_keys as $api_key) {
        //   if ($api_key->default == 1) {
        //     $key = $api_key->key;
        //   }
        // }

        // 917534013101 -
        // 919604019000 +
        // 919582881540 +
        // 919811906360 +
        // 919819119863 +
        // 916370347484 +
        // 919717147814 +
        // 919820026342 +
        // 919811455004 +
        // 919825766685 -
        // 919881790007 -
        // 919818156656 -
        // 919819749796 -
        // 919833049260 -
        // 919811912233 -
        // 919650780018 -
        // 917011450395 -
        // 919623737289 -
        // 919819363304 -
        // 919819882253 -

        $key = "Z802FWHI8E2OP0X120QR";

        $encodedNumber = urlencode('917534013101');
        // $encodedText = urlencode($text);
        $encodedType = urlencode('IN');

        //$number = "";
        $url = "https://panel.apiwha.com/get_messages.php?apikey=" . $key . "&type=" . $encodedType . "&number=" . $encodedNumber;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // DON'T THROW EXCEPTION
            // throw new \Exception( "cURL Error #:" . $err );
            \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") cURL Error for number " . $number . ":" . $err);
            return false;
        } else {
            $result = json_decode($response, true);
            // if (!$result->success) {
            //   throw new \Exception("whatsapp request error: " . $result->description);
            //  }
        }


        $filtered_data = [];
        //
        // foreach ($result as $item) {
        //   if (Carbon::parse($item['creation_date'])->gt(Carbon::parse("2019-06-17 00:00:00"))) {
        //     $filtered_data[] = $item;
        //   }
        // }
        //

        foreach ($result as $item) {
            if (Carbon::parse($item['creation_date'])->gt(Carbon::parse("2019-06-17 00:00:00"))) {
                $filtered_data[] = $item;
                $customer = $this->findCustomerByNumber($item['from']);


                if ($customer) {
                    $params = [
                        'number' => $item['from'],
                        'customer_id' => $customer->id,
                        'message' => $item['text'],
                        'created_at' => $item['creation_date']
                    ];

                    // ChatMessage::create($params);
                }
            }
        }
        // array_reverse($result);
        // $result = array_values(array_sort($result, function ($value) {
        //              return $value['creation_date'];
        //      }));
        // //
        //      $result = array_reverse($result);


        return $result;
    }

    public function sendWithNewApi($number, $whatsapp_number = null, $message = null, $file = null, $chat_message_id = null, $enqueue = 'opportunistic')
    {

        $logDetail = [
            'number' => $number,
            'whatsapp_number' => $whatsapp_number,
            'message' => $message,
            'file' => $file,
            'chat_message_id' => $chat_message_id,
            'enqueue' => $enqueue,
        ];

        $configs = \Config::get("wassenger.api_keys");
        $encodedNumber = "+" . $number;
        $encodedText = $message;
        $wa_token = $configs[0]['key'];

        if ($whatsapp_number != null) {
            foreach ($configs as $key => $config) {
                if ($config['number'] == $whatsapp_number) {
                    $wa_device = $config['device'];

                    break;
                }

                $wa_device = $configs[0]['device'];
            }
        } else {
            $wa_device = $configs[0]['device'];
        }

        if ($file != null) {
            $file_exploded = explode('/', $file);
            $encoded_part = str_replace('%25', '%', urlencode(str_replace(' ', '%20', $file_exploded[count($file_exploded) - 1])));
            array_pop($file_exploded);
            array_push($file_exploded, $encoded_part);

            $file_encoded = implode('/', $file_exploded);

            $array = [
                'url' => "$file_encoded",
                // 'reference' => "$chat_message_id"
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.wassenger.com/v1/files?reference=$chat_message_id",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 180,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($array),
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "token: $wa_token"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            // throw new \Exception("cURL Error #: whatttt");
            if ($err) {
                // DON'T THROW EXCEPTION
                //throw new \Exception( "cURL Error #:" . $err );
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") cURL Error for number " . $number . ":" . $err. ' ['. json_encode($logDetail). '] ');
                return false;
            } else {
                $result = json_decode($response, true);

                if (is_array($result)) {
                    if (array_key_exists('status', $result)) {
                        if ($result['status'] == 409) {
                            $image_id = $result['meta']['file'];
                        } else {
                            // DON'T THROW EXCEPTION
                            // throw new \Exception( "Something was wrong with image: " . $result[ 'message' ] );
                            \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Something was wrong with the image for number " . $number . ":" . $result['message']. ' ['. json_encode($logDetail). '] ');
                            return false;
                        }
                    } else {
                        $image_id = $result[0]['id'];
                    }
                }
            }
        }
        // if (isset($response)) {
        //   throw new \Exception($response);
        // }


        $array = [
            'phone' => $encodedNumber,
            'message' => (string)$encodedText,
            'reference' => (string)$chat_message_id,
            'device' => "$wa_device",
            'enqueue' => "$enqueue",
        ];

        if (isset($image_id)) {
            $array['media'] = [
                'file' => "$image_id"
            ];
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wassenger.com/v1/messages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "token: $wa_token"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            // DON'T THROW EXCEPTION
            // throw new \Exception( "cURL Error #:" . $err );
            \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") cURL Error for number " . $number . ":" . $err. ' ['. json_encode($logDetail). '] ');
            return false;
        } else {
            $result = json_decode($response, true);

            if ($http_code != 201) {
                // DON'T THROW EXCEPTION
                // throw new \Exception( "Something was wrong with message: " . $response );
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Something was wrong with the message for number " . $number . ":" . $response. ' ['. json_encode($logDetail). '] ');
                return false;
            } else {
                // Log successful send
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Message was sent to number " . $number . ":" . $response . ' ['. json_encode($logDetail). '] ');
            }
        }

        return $result;
    }

    public function sendWithThirdApi($number, $whatsapp_number = null, $message = null, $file = null, $chat_message_id = null, $enqueue = 'opportunistic', $customer_id = null)
    {
        $logDetail = [
            'number' => $number,
            'whatsapp_number' => $whatsapp_number,
            'message' => $message,
            'file' => $file,
            'chat_message_id' => $chat_message_id,
            'enqueue' => $enqueue,
            'customer_id' => $customer_id,
            ];

        // Get configs
        $config = \Config::get("apiwha.instances");

        // check if number is set or not then call from the table
        if (!isset($config[$whatsapp_number])) { 
            $whatsappRecord = \App\Marketing\WhatsappConfig::where('provider','Chat-API')
            ->where("instance_id", "!=", "")
            ->where("token", "!=", "")
            ->where("status", 1)
            ->where("number", $whatsapp_number)
            ->first();

            if($whatsappRecord) {
                $config[$whatsapp_number] = [
                    "instance_id" => $whatsappRecord->instance_id,
                    "token" => $whatsappRecord->token,
                    "is_use_own" => $whatsappRecord->is_use_own
                ];
            }
        }

        $chatMessage = null;
        if($chat_message_id > 0) {
            $chatMessage = \App\ChatMessage::find($chat_message_id);
        }
        
        // Set instanceId and token
        $isUseOwn = false;
        if (isset($config[$whatsapp_number])) {
            $instanceId = $config[$whatsapp_number]['instance_id'];
            $token = $config[$whatsapp_number]['token'];
            $isUseOwn = isset($config[$whatsapp_number]['is_use_own']) ? $config[$whatsapp_number]['is_use_own'] : 0;
        } else {
            \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Whatsapp config not found for number " . $whatsapp_number . " while sending to number " . $number. ' ['. json_encode($logDetail). '] ');
            $instanceId = $config[0]['instance_id'];
            $token = $config[0]['token'];
            $isUseOwn = isset($config[0]['is_use_own']) ? $config[0]['is_use_own'] : 0;
        }
        
        if (isset($customer_id) && $message != null && $message != '') {
            $customer = Customer::findOrFail($customer_id);
            
            $fields = array('[[NAME]]' => $customer->name, '[[CITY]]' => $customer->city, '[[EMAIL]]' => $customer->email, '[[PHONE]]' => $customer->phone, '[[PINCODE]]' => $customer->pincode, '[[WHATSAPP_NUMBER]]' => $customer->whatsapp_number, '[[SHOESIZE]]' => $customer->shoe_size, '[[CLOTHINGSIZE]]' => $customer->clothing_size);
            
            preg_match_all("/\[[^\]]*\]]/", $message, $matches);
            $values = $matches[0];
            
            foreach ($values as $value) {
                if (isset($fields[$value])) {
                    $message = str_replace($value, $fields[$value], $message);
                }
            }
        }
        
        $encodedNumber = '+' . $number;
        if($isUseOwn == 1) { 
            $encodedNumber = $number;
        }
        
        $encodedText = $message;
        
        $array = [
            'phone' => $encodedNumber
        ];

        if ($encodedText != null && $file == null) {
            $array['body'] = $encodedText;
            $link = 'sendMessage';
        } else {
            $exploded = explode('/', $file);
            $filename = end($exploded);
            $array['body'] = $file;
            $array['filename'] = $filename;
            $link = 'sendFile';
            $array['caption'] = $encodedText;
        }
        
        $array['instanceId'] = $instanceId;
        
        // here is we call python 
        if($isUseOwn == 1) { 
            $domain = "http://167.86.89.241:82/".$link;
        }else{
            $domain = "https://api.chat-api.com/instance$instanceId/$link?token=$token";
        }

        // \Log::channel('chatapi')->debug('cUrl_url:' . $domain . "\nMessage: " . $message. "\nCUSTOMREQUEST: " . 'POST' ."\nPostFields: " . json_encode($array) . "\nFile:" . $file . "\n" . ' ['. json_encode($logDetail). '] ');

        $customerrequest_arr['CUSTOMREQUEST'] = 'POST';
        $message_arr['message'] = $message;
        $file_arr['file'] = $file;

        $log_data = [
            'Message_Data' => $message_arr,
            'Customer_request_data' => $customerrequest_arr,
            'PostFields' => $array,
            'file_data' => $file_arr,
            'logDetail_data' => $logDetail,
        ];

        $str_log = 'Message :: '.json_encode($message).' || Customer Request :: POST || Post Fields :: '.json_encode($array).' || File :: '.$file.' || Log Details :: '.json_encode($logDetail);

        \Log::channel('chatapi')->debug('cUrl_url:{"' . $domain . " } \nMessage: ".$str_log );

        // \Log::channel('chatapi')->debug('cUrl_url:{"' . $domain . " } \nMessage: ".json_encode($log_data) );

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $domain,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                // "token: $wa_token"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        // $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            // DON'T THROW EXCEPTION
            //throw new \Exception("cURL Error #:" . $err);
            \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") cURL Error for number " . $number . ":" . $err. ' ['. json_encode($logDetail). '] ');
            if($chatMessage) {
                $chatMessage->error_status = \App\ChatMessage::ERROR_STATUS_ERROR;
                $chatMessage->error_info = json_encode(["number" => $number, "error" => $err]);
                $chatMessage->save();
            }
            return false;
        } else {
            // Log curl response

            // \Log::channel('chatapi')->debug('cUrl:' . $response . "\nMessage: " . $message . "\nFile:" . $file . "\n" . ' ['. json_encode($logDetail). '] ');
            $customerrequest_arr['CUSTOMREQUEST'] = 'POST';
            $message_arr1['message'] = $message;
            $file_arr1['file'] = $file;

            $log_data_send = [
                'Message_Data' => $message_arr1,
                'file_data' => $file_arr1,
                'logDetail_data' => $logDetail,
            ];
    
            $str_log = 'Message :: '.json_encode($message).' || File :: '.$file.' || Log Details :: '.json_encode($logDetail);

            \Log::channel('chatapi')->debug('cUrl:' . $response . "\nMessage: ".$str_log );

            // \Log::channel('chatapi')->debug('cUrl:' . $response . "\nMessage: ".json_encode($log_data_send) );

            // Json decode response into result
            $result = json_decode($response, true);

            // throw new \Exception("Something was wrong with message: " . $response);
            if (!is_array($result) || array_key_exists('sent', $result) && !$result['sent']) {
                // DON'T THROW EXCEPTION
                //throw new \Exception("Something was wrong with message: " . $response);
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Something was wrong with the message for number " . $number . ": " . $response. ' ['. json_encode($logDetail). '] ');
                if($chatMessage) {
                    $chatMessage->error_status = \App\ChatMessage::ERROR_STATUS_ERROR;
                    $chatMessage->error_info = json_encode(["number" => $number, "error" => $response]);
                    $chatMessage->save();
                }
                return false;
            } else {
                // Log successful send
                if($chatMessage) {
                    $chatMessage->error_status = \App\ChatMessage::ERROR_STATUS_SUCCESS;
                    $chatMessage->save();
                }   
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Message was sent to number " . $number . ":" . $response. ' ['. json_encode($logDetail). '] ');
            }
        }

        return $result;
    }

    private function getWhatsAppNumberConfig($target)
    {
        $numbers = \Config::get("apiwha.api_keys");
        foreach ($numbers as $number) {
            if ($number['number'] == $target) {
                return $number;
            }
        }

        return $numbers[0];
    }

    private function formatChatDate($date)
    {
        return $date->format("Y-m-d h:iA");
    }

    private function modifyParamsWithMessage($params, $data)
    {
        if (filter_var($data['text'], FILTER_VALIDATE_URL)) {
            // you're good
            $path = $data['text'];
            $paths = explode("/", $path);
            $file = $paths[count($paths) - 1];
            $extension = explode(".", $file)[1];
            $fileName = uniqid(true) . "." . $extension;
            $contents = file_get_contents($path);
            if (file_put_contents(implode(DIRECTORY_SEPARATOR, array(\Config::get("apiwha.media_path"), $fileName)), $contents) == false) {
                return false;
            }
            $url = implode("/", array(\Config::get("app.url"), "apiwha", "media", $fileName));
            $params['media_url'] = $url;


            return $params;
        }
        $params['message'] = $data['text'];
        return $params;
    }

    public function updatestatus(Request $request)
    {
        $message = ChatMessage::find($request->get('id'));
        $message->status = $request->get('status');
        $message->save();

        if ($request->id && $request->status == 5) {
            ChatMessagesQuickData::updateOrCreate([
                'model' => "\App\Customer",
                'model_id' => $request->id
            ], [
                'last_unread_message' => '',
                'last_unread_message_at' => null,
                'last_unread_message_id' => null,
            ]);
        }

        return response('success');
    }

    public function fixMessageError(Request $request, $id)
    {
        $chat_message = ChatMessage::find($id);

        if ($customer = Customer::find($chat_message->customer_id)) {
            $customer->is_error_flagged = 0;
            $customer->save();

            $messages = ChatMessage::where('customer_id', $customer->id)->where('error_status', '!=', 0)->get();

            foreach ($messages as $message) {
                $message->error_status = 0;
                $message->save();
            }
        }

        return response('success');
    }

    public function resendMessage(Request $request, $id)
    {
        $chat_message = ChatMessage::find($id);
        if ($customer = Customer::find($chat_message->customer_id)) {
            // $params = [
            //    'number'       => NULL,
            //    'user_id'      => Auth::id(),
            //    'approved'     => 1,
            //    'status'       => 2,
            //    'customer_id'  => $customer->id,
            //    'message'      => $chat_message->message
            //  ];
            //
            // $additional_message = ChatMessage::create($params);

            if ($chat_message->message != '') {
                $this->sendWithThirdApi($customer->phone, $customer->whatsapp_number, $chat_message->message, null, $chat_message->id);
            }

            if ($chat_message->hasMedia(config('constants.attach_image_tag'))) {
                foreach ($chat_message->getMedia(config('constants.attach_image_tag')) as $image) {
                    $this->sendWithThirdApi($customer->phone, $customer->whatsapp_number, $chat_message->message, $image->getUrl(), $chat_message->id);
                }
            }

            $chat_message->update([
                'resent' => $chat_message->resent + 1
            ]);

            return response()->json([
                'resent' => $chat_message->resent
            ]);
        }

        if ($chat_message->erp_user != '' || $chat_message->contact_id != '') {
            $sender = User::find($chat_message->user_id);
            if ($chat_message->erp_user != '') {
                $receiver = User::find($chat_message->erp_user);
            } else {
                $receiver = Contact::find($chat_message->contact_id);
            }

            $phone = $receiver->phone;
            $whatsapp_number = ($sender) ? $sender->whatsapp_number : null;
            $sending_message = $chat_message->message;

            if (preg_match_all("/Resent ([\d]+) times/i", $sending_message, $match)) {
                $sending_message = preg_replace("/Resent ([\d]+) times/i", "Resent " . ($chat_message->resent + 1) . " times", $sending_message);
            } else {
                $sending_message = 'Resent ' . ($chat_message->resent + 1) . " times. " . $sending_message;
            }

            $params = [
                'user_id' => $chat_message->user_id,
                'number' => null,
                'task_id' => $chat_message->task_id,
                'erp_user' => $chat_message->erp_user,
                'contact_id' => $chat_message->contact_id,
                'message' => $sending_message,
                'resent' => $chat_message->resent + 1,
                'approved' => 1,
                'status' => 2
            ];

            $new_message = ChatMessage::create($params);

            if ($chat_message->hasMedia(config('constants.attach_image_tag'))) {
                foreach ($chat_message->getMedia(config('constants.attach_image_tag')) as $image) {
                    $new_message->attachMedia($image, config('constants.media_tags'));
                }
            }

            $this->sendWithThirdApi($phone, $whatsapp_number, $new_message->message, null, $new_message->id);

            if ($task = Task::find($chat_message->task_id)) {
                if (count($task->users) > 0) {
                    if ($task->assign_from == Auth::id()) {
                        foreach ($task->users as $key => $user) {
                            if ($key != 0) {
                                $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message']);
                            }
                        }
                    } else {
                        foreach ($task->users as $key => $user) {
                            if ($key != 0) {
                                if ($user->id != Auth::id()) {
                                    $this->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message']);
                                }
                            }
                        }
                    }
                }

                if (count($task->contacts) > 0) {
                    foreach ($task->contacts as $key => $contact) {
                        if ($key != 0) {
                            $this->sendWithThirdApi($contact->phone, $contact->whatsapp_number, $params['message']);
                        }
                    }
                }
            }

            if ($new_message->hasMedia(config('constants.attach_image_tag'))) {
                foreach ($new_message->getMedia(config('constants.attach_image_tag')) as $image) {
                    $this->sendWithThirdApi($phone, $whatsapp_number, null, $image->getUrl(), $new_message->id);
                }
            }

            $chat_message->update([
                'resent' => $chat_message->resent + 1
            ]);
        }

        if ($chat_message->vendor_id != "") {

            $vendor = \App\Vendor::find($chat_message->vendor_id);


            if ($vendor) {
                if ($chat_message->message != '') {
                    $this->sendWithThirdApi($vendor->phone, $vendor->whatsapp_number, $chat_message->message, null, $chat_message->id);
                }

                if ($chat_message->hasMedia(config('constants.attach_image_tag'))) {
                    foreach ($chat_message->getMedia(config('constants.attach_image_tag')) as $image) {
                        $this->sendWithThirdApi($vendor->phone, $vendor->whatsapp_number, $chat_message->message, $image->getUrl(), $chat_message->id);
                    }
                }

                $chat_message->update([
                    'resent' => $chat_message->resent + 1
                ]);

            }


        }

        if($chat_message->supplier_id != "")
        {
            $supplier = Supplier::find($chat_message->supplier_id);
         
            if ($supplier) {

                if ($chat_message->message != '') {
                    $this->sendWithThirdApi($supplier->phone, $supplier->whatsapp_number, $chat_message->message, null, $chat_message->id);
                }

                if ($chat_message->additional_data != '') {
                   $additional_data_arr =  json_decode($chat_message->additional_data);
                   $path = $additional_data_arr->attachment[0];
                   $subject = 'Product order';
                    $message = 'Please check below product order request';
                   if($path != '')
                   {
                        $emailClass = (new PurchaseExport($path, $subject, $message))->build();

                        $email             = Email::create([
                            'model_id'         => $supplier->id,
                            'model_type'       => Supplier::class,
                            'from'             => 'buying@amourint.com',
                            'to'               => $supplier->email,
                            'subject'          => $subject,
                            'message'          => $message,
                            'template'         => 'purchase-simple',
                            'additional_data'  => json_encode(['attachment' => [$path]]),
                            'status'           => 're-send',
                            'is_draft'         => 0,
                        ]);
            
                        \App\Jobs\SendEmail::dispatch($email);
                   }
                }

                $chat_message->update([
                    'resent' => $chat_message->resent + 1
                ]);

            }
        }

        return response()->json([
            'resent' => $chat_message->resent
        ]);
    }


    public function createGroup($task_id = null, $group_id = null, $number, $message = null, $whatsapp_number)
    {

        $encodedText = $message;

        if ($whatsapp_number == '919004780634') { // Indian
            $instanceId = "43281";
            $token = "yi841xjhrwyrwrc7";
        } elseif ($whatsapp_number == '971502609192') { // YM Dubai
            $instanceId = "62439";
            $token = "jdcqh3ladeuvwzp4";
        } else {
            if ($whatsapp_number == '971562744570') { // Solo 06
                $instanceId = '55202';
                $token = '42ndn0qg5om26vzf';
            } else {
                if ($whatsapp_number == '971547763482') { // 04
                    $instanceId = '55211';
                    $token = '3b92u5cbg215c718';
                } else { // James
//                    $instanceId = "43112";
//                    $token = "vbi9bpkoejv2lvc4";
                    $instanceId = "62439";
                    $token = "jdcqh3ladeuvwzp4";
                }
            }
        }


        if ($task_id != null) {
            $id = (string)$task_id;

            $array = [
                'groupName' => $id,
                'phones' => $number,

            ];
            $link = 'group';

        } else {
            $id = (string)$group_id;

            $array = [
                'groupId' => $id,
                'participantPhone' => $number,
            ];
            $link = 'addGroupParticipant';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.chat-api.com/instance$instanceId/$link?token=$token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                // "token: $wa_token"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        // $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $result = json_decode($response, true);

        if ($err) {
            // DON'T THROW EXCEPTION
            //throw new \Exception("cURL Error #:" . $err);
            \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") cURL Error for number " . $number . ":" . $err);
            return false;
        } else {
            $result = json_decode($response, true);
            // throw new \Exception("Something was wrong with message: " . $response);
            if (!is_array($result) || array_key_exists('sent', $result) && !$result['sent']) {
                // DON'T THROW EXCEPTION
                //throw new \Exception("Something was wrong with message: " . $response);
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Something was wrong with the message for number " . $response);
                return false;
            } else {
                // Log successful send
                \Log::channel('whatsapp')->debug("(file " . __FILE__ . " line " . __LINE__ . ") Message was sent to number " . $response);
            }
        }
        return $result;
    }

    public function saveProductFromSupplierIncomingImages($id, $imageUrl)
    {

        //FInd Supplier
        $supplier = Supplier::find($id);

        //get sku
        $lastQuickSellProduct = Product::select('sku')->where('sku', 'LIKE', '%QUICKSELL' . date('yz') . '%')->orderBy('id', 'desc')->first();

        try {
            if ($lastQuickSellProduct) {
                $number = str_ireplace('QUICKSELL', '', $lastQuickSellProduct->sku) + 1;
            } else {
                $number = date('yz') . sprintf('%02d', 1);
            }
        } catch (\Exception $e) {
            $number = 0;
        }


        //$brand = Brand::where('name', 'LIKE', '%QUICKSELL%')->first();


        $product = new Product;
        $product->name = 'QUICKSELL';
        $product->sku = 'QuickSell' . $number;
        $product->size = '';
        $product->brand = null;
        $product->color = '';
        $product->location = '';
        $product->category = '';
        $product->supplier = $supplier->supplier;
        $product->price = 0;
        $product->price_inr_special = 0;
        $product->stock = 1;
        $product->quick_product = 1;
        $product->is_pending = 0;
        $product->save();
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $imageUrl, $match);
        $imageUrl = $match[0][0];
        $jpg = \Image::make($imageUrl)->encode('jpg');
        $filename = substr($imageUrl, strrpos($imageUrl, '/'));
        $filename = str_replace("/", "", $filename);
        $media = MediaUploader::fromString($jpg)->useFilename($filename)->upload();
        $product->attachMedia($media, config('constants.media_tags'));

        return true;
    }

    public function delete(Request $request)
    {
        $messageId = $request->get("id", 0);

        if ($messageId) {
            $chatMessage = \App\ChatMessage::where("id", $messageId)->first();
            if ($chatMessage) {
                $chatMessage->delete();
                \App\SuggestedProductList::where('chat_message_id',$messageId)->delete();
            }
        }

        return response()->json(["code" => 200]);

    }
    private function createTask($data) {
        $default_user_id = \App\User::USER_ADMIN_ID;
		$data['assign_from'] = $default_user_id;
		$data['is_statutory'] = 0;
        $data['task_details'] =$data['task_description'];
        $data['task_subject'] =$data['task_subject'];
        $data['assign_to'] =$data['assigned_to'];

			$task = Task::create($data);

            if ($data['assign_to']) {
                $task->users()->attach([$data['assign_to'] => ['type' => User::class]]);
			}

            $message = "#" . $task->id . ". " . $task->task_subject . ". " . $task->task_details;

			$params = [
			 'number'       => NULL,
			 'user_id'      => $default_user_id,
			 'approved'     => 1,
			 'status'       => 2,
			 'task_id'			=> $task->id,
			 'message'      => $message
		    ];

            $user = User::find($data['assign_to']);
            app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message']);
            $chat_message = ChatMessage::create($params);
			ChatMessagesQuickData::updateOrCreate([
                'model' => \App\Task::class,
                'model_id' => $params['task_id']
                ], [
                'last_communicated_message' => @$params['message'],
                'last_communicated_message_at' => $chat_message->created_at,
                'last_communicated_message_id' => ($chat_message) ? $chat_message->id : null,
            ]);


            // $hubstaff_project_id = getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID');
            $hubstaff_project_id = config('env.HUBSTAFF_BULK_IMPORT_PROJECT_ID');

			  $assignedUser = HubstaffMember::where('user_id', $user->id)->first();	  
			  $hubstaffUserId = null;
			  if ($assignedUser) {
				  $hubstaffUserId = $assignedUser->hubstaff_user_id;
			  }
			  $taskSummery = substr($message, 0, 200);
			  
	  
			  $hubstaffTaskId = $this->createHubstaffTask(
				  $taskSummery,
				  $hubstaffUserId,
				  $hubstaff_project_id
			  );
	  
			  if($hubstaffTaskId) {
				  $task->hubstaff_task_id = $hubstaffTaskId;
				  $task->save();
			  }
			  if ($hubstaffUserId) {
				  $task = new HubstaffTask();
				  $task->hubstaff_task_id = $hubstaffTaskId;
				  $task->project_id = $hubstaff_project_id;
				  $task->hubstaff_project_id = $hubstaff_project_id;
				  $task->summary = $message;
				  $task->save();
              }
              return true;
    }
    private function createDevtask($data) {
        $default_user_id = \App\User::USER_ADMIN_ID;

		$data['created_by'] = $default_user_id;
        $data['task'] =$data['task_description'];
        $data['subject'] =$data['task_subject'];
        $data['assigned_to'] =$data['assigned_to'];
        $data['user_id'] =$data['assigned_to'];
        $data['priority'] = 0;
        $data['hubstaff_task_id'] = 0;
        $data['assigned_by'] = $default_user_id;
        $data['status'] = 'In Progress';
        // $data['hubstaff_project'] = getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID');
        $data['hubstaff_project'] = config('env.HUBSTAFF_BULK_IMPORT_PROJECT_ID');
        $task = DeveloperTask::create($data);

        // CREATE GITHUB REPOSITORY BRANCH
        $newBranchName = $this->createBranchOnGithub(
            $data['repository_id'],
            $task->id,
            $task->subject
        );

        // UPDATE TASK WITH BRANCH NAME
        if ($newBranchName) {
            $task->github_branch_name = $newBranchName;
            $task->save();
        }

        if (is_string($newBranchName)) {
            $message = $data['task'] . PHP_EOL . "A new branch " . $newBranchName . " has been created. Please pull the current code and run 'git checkout " . $newBranchName . "' to work in that branch.";
        } else {
            $message = $data['task'];
        }
        $message = $data['task'];
        $requestData = new Request();
        $requestData->setMethod('POST');
        $requestData->request->add(['issue_id' => $task->id, 'message' => $message, 'status' => 1]);

        app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'auto_task');

        $hubstaff_project_id = $data['hubstaff_project'];

        $assignedUser = HubstaffMember::where('user_id', $data['assigned_to'])->first();

        $hubstaffUserId = null;
        if ($assignedUser) {
            $hubstaffUserId = $assignedUser->hubstaff_user_id;
        }
        $summary = substr($data['task'], 0, 200);
        $taskSummery = '#DEVTASK-' . $task->id . ' => ' . $summary;

        $hubstaffTaskId = $this->createHubstaffTask(
            $taskSummery,
            $hubstaffUserId,
            $hubstaff_project_id
        );
        if($hubstaffTaskId) {
            $task->hubstaff_task_id = $hubstaffTaskId;
            $task->save();
        }
        if ($hubstaffUserId) {
            $task = new HubstaffTask();
            $task->hubstaff_task_id = $hubstaffTaskId;
            $task->project_id = $hubstaff_project_id;
            $task->hubstaff_project_id = $hubstaff_project_id;
            $task->summary = $data['task'];
            $task->save();
        }
        return true;
    }


    private function createHubstaffTask(string $taskSummary, ?int $hubstaffUserId, int $projectId, bool $shouldRetry = true)
    {
        $tokens = $this->getTokens();
        $url = 'https://api.hubstaff.com/v2/projects/' . $projectId . '/tasks';
        $httpClient = new GuzzleClient();
        try {

            $body = array(
                'summary' => $taskSummary
            );

            if ($hubstaffUserId) {
                $body['assignee_id'] = $hubstaffUserId;
            } else {
                // $body['assignee_id'] = getenv('HUBSTAFF_DEFAULT_ASSIGNEE_ID');
                $body['assignee_id'] = config('env.HUBSTAFF_DEFAULT_ASSIGNEE_ID');
            }
            $response = $httpClient->post(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token,
                        'Content-Type' => 'application/json'
                    ],

                    RequestOptions::BODY => json_encode($body)
                ]
            );
            $parsedResponse = json_decode($response->getBody()->getContents());
            return $parsedResponse->task->id;
        } catch (ClientException $e) {
        	if($e->getCode() == 401) {
        		$this->refreshTokens();
                if ($shouldRetry) {
                    return $this->createHubstaffTask(
                        $taskSummary,
                        $hubstaffUserId,
                        $projectId,
                        false
                    );
                }
        	}
        }
        return false;
    }
    
    private function createBranchOnGithub($repositoryId, $taskId, $taskTitle,  $branchName = 'master')
    {
        $newBranchName = 'DEVTASK-' . $taskId;

        // get the master branch SHA
        // https://api.github.com/repositories/:repoId/branches/master
        $url = 'https://api.github.com/repositories/' . $repositoryId . '/branches/' . $branchName;
        try {
            $response = $this->githubClient->get($url);
            $masterSha = json_decode($response->getBody()->getContents())->commit->sha;
        } catch (Exception $e) {
            return false;
        }

        // create a branch
        // https://api.github.com/repositories/:repo/git/refs
        $url = 'https://api.github.com/repositories/' . $repositoryId . '/git/refs';
        try {
            $this->githubClient->post(
                $url,
                [
                    RequestOptions::BODY => json_encode([
                        "ref" => "refs/heads/" . $newBranchName,
                        "sha" => $masterSha
                    ])
                ]
            );
            return $newBranchName;
        } catch (Exception $e) {

            if ($e instanceof ClientException && $e->getResponse()->getStatusCode() == 422) {
                // branch already exists
                return $newBranchName;
            }
            return false;
        }
    }

    public function autoCompleteMessages(Request $request){

        $data = AutoCompleteMessage::where('message', 'like', ''. $request->keyword . '%')->pluck('message')->toArray();
        return response()->json(['data' => $data]);
    }

}