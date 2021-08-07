<?php

namespace Modules\WebMessage\Http\Controllers;

use App\ChatMessage;
use App\Customer;
use App\Supplier;
use App\Vendor;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class WebMessageController extends Controller
{

    const LAST_MESSAGE_LIMIT_DAY = 5;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        $jsonUser = [
            "id"         => 0,
            "name"       => \Auth()->user()->name,
            "number"     => \Auth()->user()->phone,
            "is_admin"   => \Auth::user()->hasRole('Admin'),
            "is_hod_crm" => \Auth::user()->hasRole('HOD of CRM'),
            "pic"        => "https://via.placeholder.com/400x400",
        ];

        // customer list need to display first
        // show last customer message sent
        // on click based show the customer message
        $customerList = $this->getLastConversationGroup();
        $jsonCustomer = $customerList["jsonCustomer"];
        $jsonMessage  = $customerList["jsonMessage"];

        // add the auto reply message here
        $jsonAutoSuggest = \App\AutoReply::get()->pluck(["reply"])->toArray();

        return view('webmessage::index', compact('customers', 'jsonCustomer', 'jsonMessage', 'jsonUser', 'jsonAutoSuggest'));
    }

    public function getLastConversationGroup($page = 1)
    {

        $blockedUser = \App\BlockWebMessageList::all();
        $autoChat    = ChatMessage::EXECLUDE_AUTO_CHAT;

        $excludeSuppliers = [];
        $excludeVendors   = [];
        $excludeCustomers = [];

        if (!empty($blockedUser)) {
            foreach ($blockedUser as $user) {
                if ($user->object_type == Customer::class) {
                    $excludeCustomers[] = $user->object_id;
                }
                if ($user->object_type == Vendor::class) {
                    $excludeVendors[] = $user->object_id;
                }
                if ($user->object_type == Supplier::class) {
                    $excludeSuppliers[] = $user->object_id;
                }
            }
        }

        $customerList = \DB::table("chat_messages")
            ->where(function ($q) {
                $q->whereNull("group_id")->orWhere("group_id", 0);
            })
            ->whereNotIn('customer_id', $excludeCustomers)
            ->whereNotIn("status", $autoChat)
            ->whereDate('created_at', '>', Carbon::now()->subDays(self::LAST_MESSAGE_LIMIT_DAY))
            ->groupBy("customer_id")
            ->select(["customer_id", \DB::raw("max(id) as last_chat_id")])
            ->havingRaw("customer_id is not null")
            ->orderBy("last_chat_id", "desc")
            ->get();

        // need to setup list as per the the customer, supplier, vendor etc
        $vendorList = \DB::table("chat_messages")
            ->where(function ($q) {
                $q->whereNull("group_id")->orWhere("group_id", 0);
            })
            ->whereNotIn('vendor_id', $excludeVendors)
            ->whereNotIn("status", $autoChat)
            ->whereDate('created_at', '>', Carbon::now()->subDays(self::LAST_MESSAGE_LIMIT_DAY))
            ->groupBy("vendor_id")
            ->select(["vendor_id", \DB::raw("max(id) as last_chat_id")])
            ->havingRaw("vendor_id is not null")
            ->orderBy("last_chat_id", "desc")
            ->get();

        // need to setup list as per the the customer, supplier, vendor etc
        $supplierList = \DB::table("chat_messages")
            ->where(function ($q) {
                $q->whereNull("group_id")->orWhere("group_id", 0);
            })
            ->whereNotIn('supplier_id', $excludeSuppliers)
            ->whereNotIn("status", $autoChat)
            ->whereDate('created_at', '>', Carbon::now()->subDays(self::LAST_MESSAGE_LIMIT_DAY))
            ->groupBy("supplier_id")
            ->select(["supplier_id", \DB::raw("max(id) as last_chat_id")])
            ->havingRaw("supplier_id is not null")
            ->orderBy("last_chat_id", "desc")
            ->get();

        $customers = [];

        $customerIds    = [];
        $lastMessageIds = [];
        if (!empty($customerList)) {
            foreach ($customerList as $customer) {
                $customerIds[]    = $customer->customer_id;
                $lastMessageIds[] = $customer->last_chat_id;
            }
        }

        // get customer info
        $customerInfo = Customer::getInfoByIds(
            $customerIds,
            ["id", "name", "gender", "email", "phone", "whatsapp_number", "broadcast_number", "created_at"],
            true
        );

        $vendors   = [];
        $vendorIds = [];
        if (!empty($vendorList)) {
            foreach ($vendorList as $vendor) {
                $vendorIds[]      = $vendor->vendor_id;
                $lastMessageIds[] = $vendor->last_chat_id;
            }
        }

        // get vendor info
        $vendorInfo = Vendor::getInfoByIds(
            $vendorIds,
            ["id", "name", "email", "phone", "whatsapp_number", "default_phone", "created_at"]
        );

        $suppliers   = [];
        $supplierIds = [];
        if (!empty($supplierList)) {
            foreach ($supplierList as $supplier) {
                $supplierIds[]    = $supplier->supplier_id;
                $lastMessageIds[] = $supplier->last_chat_id;
            }
        }

        // get supplier info
        $supplierInfo = Supplier::getInfoByIds(
            $supplierIds,
            ["id", "supplier", "email", "phone", "whatsapp_number", "default_phone", "created_at"]
        );

        // get last message list
        $messageInfo = ChatMessage::getInfoByIds(
            $lastMessageIds,
            ["id", "number", "message", "media_url", "customer_id", "vendor_id", "supplier_id", "is_chatbot", "status", "approved", "created_at"],
            true
        );

        // check last message has any media images
        $lastImages = ChatMessage::getGroupImagesByIds(
            $lastMessageIds,
            true
        );

        // setup the customer information
        $jsonCustomer = [];
        if (!empty($customerInfo)) {
            foreach ($customerInfo as $customer) {
                $id                                     = "c_" . $customer["id"];
                $customers["c_" . $id]["customer_info"] = $customer;

                // json customer setup
                $jsonCustomer[] = [
                    "id"       => $id,
                    "name"     => $customer["name"],
                    "number"   => $customer["phone"],
                    "pic"      => "https://via.placeholder.com/400x400",
                    "lastSeen" => $customer["created_at"],
                    "real_id"  => $customer["id"],
                    "type"     => "customer",
                ];
            }
        }

        // setup the vendor for information
        if (!empty($vendorInfo)) {
            foreach ($vendorInfo as $vendor) {
                $id                                   = $vendor["id"];
                $vendors["v_" . $id]["customer_info"] = $vendor;

                // json customer setup
                $jsonCustomer[] = [
                    "id"       => "v_" . $id,
                    "name"     => $vendor["name"],
                    "number"   => $vendor["phone"],
                    "pic"      => "https://via.placeholder.com/400x400",
                    "lastSeen" => (string) $vendor["created_at"],
                    "real_id"  => $vendor["id"],
                    "type"     => "vendor",
                ];
            }
        }

        // setup the supplier for information
        if (!empty($supplierInfo)) {
            foreach ($supplierInfo as $supplier) {
                $id                                   = $supplier["id"];
                $vendors["s_" . $id]["customer_info"] = $supplier;

                // json customer setup
                $jsonCustomer[] = [
                    "id"       => "s_" . $id,
                    "name"     => $supplier["supplier"],
                    "number"   => $supplier["phone"],
                    "pic"      => "https://via.placeholder.com/400x400",
                    "lastSeen" => (string) $supplier["created_at"],
                    "real_id"  => $supplier["id"],
                    "type"     => "supplier",
                ];
            }
        }

        // setup the last message inforation
        $lastMessage    = [];
        $jsonMessageArr = [];
        if (!empty($messageInfo)) {
            foreach ($messageInfo as $message) {

                if ($message["customer_id"] > 0) {
                    $id = "c_" . $message["customer_id"];
                } else if ($message["vendor_id"] > 0) {
                    $id = "v_" . $message["vendor_id"];
                } else if ($message["supplier_id"] > 0) {
                    $id = "s_" . $message["supplier_id"];
                }

                $customers[$id]["last_message_info"]               = $message;
                $customers[$id]["last_message_info"]["has_images"] = false;
                $lastMessage[$message["id"]]                       = $id;

                $jsonMessageArr[$message["id"]] = [
                    "id"          => $message["id"],
                    "sender"      => 0,
                    "body"        => $message["message"],
                    "time"        => date("M d, Y H:i:s", strtotime($message["created_at"])),
                    "status"      => $message["status"],
                    "approved"    => $message["approved"],
                    "recvId"      => $id,
                    "recvIsGroup" => false,
                    "isSender"    => is_null($message["number"]) ? true : false,
                    "has_media"   => false,
                ];
            }
        }

        // last images
        if (!empty($lastImages)) {
            foreach ($lastImages as $lastImg) {
                $jsonMessageArr[$lastImg->mediable_id]["has_media"] = true;
            }
        }

        $jsonMessage = [];
        if (!empty($jsonMessageArr)) {
            foreach ($jsonMessageArr as $key => $arr) {
                $jsonMessage[] = $arr;
            }
        }

        return ["jsonMessage" => $jsonMessage, 'jsonCustomer' => $jsonCustomer];
    }

    public function messageList(Request $request, $id)
    {
        $params            = $request->all();
        list($object, $id) = explode("_", $id);

        if ($object == "c") {
            $field    = "customer_id";
            $customer = Customer::find($id);
        } elseif ($object == "v") {
            $field    = "vendor_id";
            $customer = Vendor::find($id);
        } elseif ($object == "s") {
            $field    = "supplier_id";
            $customer = Supplier::find($id);
        }

        $jsonMessage = [];

        if (!empty($customer)) {
            $messageInfo = ChatMessage::getInfoByObjectIds(
                $field,
                [$customer->id],
                ["id", "number", "message", "media_url", "customer_id", "vendor_id", "supplier_id", "is_chatbot", "status", "approved", "created_at"],
                $params,
                true
            );

            $messageIds = [];
            if (!empty($messageInfo)) {
                foreach ($messageInfo as $message) {
                    $messageIds[]                = $message["id"];
                    $jsonMessage[$message["id"]] = [
                        "id"          => $message["id"],
                        "sender"      => 0,
                        "body"        => is_null($message["message"]) ? "" : $message["message"],
                        "time"        => date("M d, Y H:i:s", strtotime($message["created_at"])),
                        "status"      => $message["status"],
                        "approved"    => $message["approved"],
                        "recvId"      => $message[$field],
                        "recvIsGroup" => false,
                        "isSender"    => is_null($message["number"]) || $message["number"] != $customer->phone ? false : true,
                        "isLast"      => false,
                    ];
                }
                $jsonMessage[$message["id"]]["isLast"] = true;
            }

            // check last message has any media images
            $lastImages = ChatMessage::getGroupImagesByIds(
                $messageIds,
                true
            );

            $allMediaIds = [];
            if (!empty($lastImages)) {
                foreach ($lastImages as $lastImg) {
                    $cMedia = explode(",", $lastImg->image_ids);
                    if (!empty($cMedia)) {
                        $allMediaIds = array_merge($allMediaIds, $cMedia);
                    }
                }
            }

            $allMedias = \Plank\Mediable\Media::whereIn("id", $allMediaIds)->get();
            $urls      = [];
            if (!$allMedias->isEmpty()) {
                foreach ($allMedias as $aMedias) {
                    $urls[$aMedias->id] = [
                        "url"  => $aMedias->getUrl(),
                        "type" => $aMedias->extension,
                    ];
                }
            }

            // get the product id for the dependent media ids
            $mediables = \DB::table("mediables")->whereIn("media_id", $allMediaIds)->where("mediable_type", "App\\Product")->get();
            if (!empty($mediables)) {
                foreach ($mediables as $mdb) {
                    $urls[$mdb->media_id]["product_id"] = $mdb->mediable_id;
                }
            }

            // last images
            if (!empty($lastImages)) {
                foreach ($lastImages as $lastImg) {
                    $jsonMessage[$lastImg->mediable_id]["has_media"] = true;
                    $mediaId                                         = explode(",", $lastImg->image_ids);
                    if (!empty($mediaId)) {
                        foreach ($mediaId as $mi) {
                            if (isset($urls[$mi])) {
                                $jsonMessage[$lastImg->mediable_id]["media"][] = $urls[$mi];
                            }
                        }
                    }
                }
            }
        }

        $m = [];
        foreach ($jsonMessage as $jMsg) {
            $m[] = $jMsg;
        }

        return response()->json(["code" => 200, "msgs" => $m]);
    }

    public function send(Request $request)
    {
        $params    = $request->all();
        $pureValue = self::getObject($params["recvId"]);

        $case = $pureValue["object"];
        switch ($case) {
            case "customer":
                $params = [
                    "customer_id" => $pureValue["real_id"],
                    "message"     => $request->get("body", ""),
                    "status"      => 1,
                ];
                $request = new Request;
                $request->setMethod('POST');
                $request->request->add($params);
                return app('App\Http\Controllers\WhatsAppController')->sendMessage($request, 'customer', true);
                break;
            case "vendor":
                $params = [
                    "vendor_id" => $pureValue["real_id"],
                    "message"   => $request->get("body", ""),
                    "status"    => 1,
                ];
                $request = new Request;
                $request->setMethod('POST');
                $request->request->add($params);
                return app('App\Http\Controllers\WhatsAppController')->sendMessage($request, 'vendor', true);
                break;
            case "supplier":
                $params = [
                    "supplier_id" => $pureValue["real_id"],
                    "message"     => $request->get("body", ""),
                    "status"      => 1,
                ];
                $request = new Request;
                $request->setMethod('POST');
                $request->request->add($params);
                return app('App\Http\Controllers\WhatsAppController')->sendMessage($request, 'supplier', true);
                break;
            default:
                # code...
                break;
        }

        return response()->json(["code" => 200, "data" => []]);

    }

    public function status(Request $request)
    {
        $params       = $request->all();
        $customerList = $this->getLastConversationGroup();

        // setup the customer information
        $jsonCustomer    = $customerList['jsonCustomer'];
        $mainJsonMessage = $customerList["jsonMessage"];

        $ac = $request->get("ac");
        if (!empty($ac)) {
            $pureValue = self::getObject($request->get("ac"));
            if ($pureValue["object"] == "customer") {
                $customer = Customer::find($pureValue["real_id"]);
            } elseif ($pureValue["object"] == "vendor") {
                $customer = Vendor::find($pureValue["real_id"]);
            } elseif ($pureValue["object"] == "supplier") {
                $customer = Supplier::find($pureValue["real_id"]);
            }
        }

        $jsonMessage = [];

        if (!empty($customer) && !empty($pureValue["field"])) {
            $messageInfo = ChatMessage::getInfoByObjectIds(
                $pureValue["field"],
                array($customer->id),
                ["id", "number", "message", "media_url", "customer_id", "is_chatbot", "status", "approved", "created_at"],
                $params,
                true
            );

            $messageIds = [];
            if (!empty($messageInfo)) {
                foreach ($messageInfo as $message) {
                    $messageIds[]                = $message["id"];
                    
                    if ($message["customer_id"] > 0) {
                        $id = "c_" . $message["customer_id"];
                    } else if ($message["vendor_id"] > 0) {
                        $id = "v_" . $message["vendor_id"];
                    } else if ($message["supplier_id"] > 0) {
                        $id = "s_" . $message["supplier_id"];
                    }

                    $jsonMessage[$message["id"]] = [
                        "id"          => $message["id"],
                        "sender"      => 0,
                        "body"        => is_null($message["message"]) ? "" : $message["message"],
                        "time"        => date("M d, Y H:i:s", strtotime($message["created_at"])),
                        "status"      => $message["status"],
                        "approved"    => $message["approved"],
                        "recvId"      => $id,
                        "recvIsGroup" => false,
                        "isSender"    => is_null($message["number"]) || $message["number"] != $customer->phone ? false : true,
                        "isLast"      => false,
                    ];
                }
                $jsonMessage[$message["id"]]["isLast"] = true;
            }

            // check last message has any media images
            $lastImages = ChatMessage::getGroupImagesByIds(
                $messageIds,
                true
            );

            $allMediaIds = [];
            if (!empty($lastImages)) {
                foreach ($lastImages as $lastImg) {
                    $cMedia = explode(",", $lastImg->image_ids);
                    if (!empty($cMedia)) {
                        $allMediaIds = array_merge($allMediaIds, $cMedia);
                    }
                }
            }

            $allMedias = \Plank\Mediable\Media::whereIn("id", $allMediaIds)->get();
            $urls      = [];
            if (!$allMedias->isEmpty()) {
                foreach ($allMedias as $aMedias) {
                    $urls[$aMedias->id] = [
                        "url"  => $aMedias->getUrl(),
                        "type" => $aMedias->extension,
                    ];
                }
            }

            // get the product id for the dependent media ids
            $mediables = \DB::table("mediables")->whereIn("media_id", $allMediaIds)->where("mediable_type", "App\\Product")->get();
            if (!empty($mediables)) {
                foreach ($mediables as $mdb) {
                    $urls[$mdb->media_id]["product_id"] = $mdb->mediable_id;
                }
            }

            // last images
            if (!empty($lastImages)) {
                foreach ($lastImages as $lastImg) {
                    $jsonMessage[$lastImg->mediable_id]["has_media"] = true;
                    $mediaId                                         = explode(",", $lastImg->image_ids);
                    if (!empty($mediaId)) {
                        foreach ($mediaId as $mi) {
                            if (isset($urls[$mi])) {
                                $jsonMessage[$lastImg->mediable_id]["media"][] = $urls[$mi];
                            }
                        }
                    }
                }
            }
        }

        $m = [];
        foreach ($jsonMessage as $jMsg) {
            $m[] = $jMsg;
        }

        return response()->json(["code" => 200, "data" => ['jsonCustomer' => $jsonCustomer, 'jsonMessage' => $mainJsonMessage, 'msgs' => $m]]);

    }

    public function action(Request $request)
    {
        $params      = $request->all();
        $chatMessage = ChatMessage::where("id", $params["id"])->first();

        if (!empty($params["case"])) {
            switch ($params["case"]) {
                case 'delete':
                    $message = $chatMessage->delete();
                    return response()->json(["code" => 200, "data" => [], "message" => "Message removed successfully"]);
                    break;
                case 'send_dimension':

                    $requestData = new Request();
                    $requestData->setMethod('POST');
                    $requestData->request->add([
                        'customer_id'      => $chatMessage->customer_id,
                        'selected_product' => [$params["p"]],
                        'dimension'        => true,
                        'auto_approve'     => true,
                    ]);

                    $res = app('App\Http\Controllers\LeadsController')->sendPrices($requestData, new GuzzleClient);
                    return response()->json(["code" => 200, "data" => [], "message" => "Dimension send successfully"]);

                    break;
                case 'send_detail':

                    $requestData = new Request();
                    $requestData->setMethod('POST');
                    $requestData->request->add([
                        'customer_id'      => $chatMessage->customer_id,
                        'selected_product' => [$params["p"]],
                        'detailed'         => true,
                        'auto_approve'     => true,
                    ]);

                    $res = app('App\Http\Controllers\LeadsController')->sendPrices($requestData, new GuzzleClient);
                    return response()->json(["code" => 200, "data" => [], "message" => "Detail send successfully"]);

                    break;
                case 'create_lead':
                    break;
                case 'create_order':
                    break;
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Oops, Something went wrong or required field missing"]);
    }

    public static function getObject($id)
    {
        list($o, $i) = explode("_", $id);

        $field  = "";
        $object = "";
        $class  = "";

        if ($o == "c") {
            $field  = "customer_id";
            $object = "customer";
            $class  = Customer::class;
        } else if ($o == "v") {
            $field  = "vendor_id";
            $object = "vendor";
            $class  = Vendor::class;
        } elseif ($o == "s") {
            $field  = "supplier_id";
            $object = "supplier";
            $class  = Supplier::class;
        }

        return ["field" => $field, "object" => $object, "real_id" => $i, "class" => $class];

    }

    public function userAction(Request $request)
    {
        $params    = $request->all();
        $pureValue = self::getObject($params["id"]);

        if (!empty($pureValue["object"]) && !empty($pureValue["real_id"])) {
            switch ($params["case"]) {
                case 'block':
                    $bml = \App\BlockWebMessageList::updateOrCreate(
                        ['object_id' => $pureValue["real_id"], 'object_type' => $pureValue["class"]],
                        ['object_id' => $pureValue["real_id"], 'object_type' => $pureValue["class"]]
                    );
                    break;
            }
        }

        return response()->json(["code" => 200, "data" => [], "message" => "User added successfully in blocked list"]);

    }

}
