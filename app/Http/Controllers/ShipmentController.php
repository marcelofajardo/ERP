<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Email;
use App\Library\DHL\CreatePickupRequest;
use App\Library\DHL\CreateShipmentRequest;
use App\MailinglistTemplate;
use App\Mails\Manual\ShipmentEmail;
use App\Order;
use App\Waybill;
use App\waybillTrackHistories;
use App\CashFlow;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;
use Validator;
use App\Mail\InvoicePaymentMail;

class ShipmentController extends Controller
{
    protected $wayBill, $emails;
    public function __construct(Waybill $wayBill, Email $emails)
    {
        $this->wayBill = $wayBill;
        $this->emails  = $emails;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $waybills = $this->wayBill->leftJoin("orders as o", "o.id", "waybills.order_id")
        ->leftJoin("waybill_invoices as wi", "wi.shipment_number", "waybills.awb");

        if ($request->get('awb')) {
            $waybills->where('waybills.awb', '=', $request->get('awb'));
        }

        if ($request->get('destination')) {
            $waybills->where('waybills.destination', 'like', '%' . $request->get('destination') . '%');
        }

        if ($request->get('order_id')) {
            $waybills->where('o.id', $request->get('order_id'));
        }

        if ($request->get('consignee')) {
            $customer_name = Customer::where('name', 'like', '%' . $request->get('consignee') . '%')->select('id')->get()->toArray();
            $ids           = [];
            foreach ($customer_name as $cus) {
                array_push($ids, $cus['id']);
            }
            $waybills->whereIn('waybills.customer_id', $ids);
        }

        $waybills = $waybills->groupBy("waybills.awb");

        $waybills = $waybills->orderBy('waybills.id', 'desc')->select(
            [
                "waybills.*",
                "wi.due_date",
                "wi.invoice_currency",
                "wi.invoice_amount",
                "wi.invoice_number"
            ]
        )->with('order', 'order.customer', 'customer', 'waybill_track_histories');

        $waybills_array = $waybills->paginate(20);
        $customers      = Customer::all();

        $fromdatadefault = array(
            "street"       => config("dhl.shipper.street"),
            "city"         => config("dhl.shipper.city"),
            "postal_code"  => config("dhl.shipper.postal_code"),
            "country_code" => config("dhl.shipper.country_code"),
            "person_name"  => config("dhl.shipper.person_name"),
            "company_name" => config("dhl.shipper.company_name"),
            "phone"        => config("dhl.shipper.phone"),
        );

        $mailinglist_templates = MailinglistTemplate::groupBy('name')->get();

        return view('shipment.index', ['waybills_array' => $waybills_array,
            'customers'                                     => $customers, 'template_names'           => $mailinglist_templates,
            'countries'                                     => config('countries'), 'fromdatadefault' => $fromdatadefault,
        ]);
    }

    /**
     * Send an email to dhl
     */
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|min:3|max:255',
            'message' => 'required',
            'to.*'    => 'required|email',
            'cc.*'    => 'nullable|email',
            'bcc.*'   => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fromEmail = 'buying@amourint.com';
        $fromName  = "buying";

        $file_paths = [];

        if ($request->hasFile('file')) {
            $path = "shipment/" . $request->order_id;
            foreach ($request->file('file') as $file) {
                $filename = $file->getClientOriginalName();

                $file->storeAs($path, $filename, 'files');

                $file_paths[] = "$path/$filename";
            }
        }

        $cc     = $bcc     = [];
        $emails = $request->to;

        if ($request->has('cc')) {
            $cc = array_values(array_filter($request->cc));
        }
        if ($request->has('bcc')) {
            $bcc = array_values(array_filter($request->bcc));
        }

        $to = array_shift($emails);
        $cc = array_merge($emails, $cc);

        $mail = Mail::to($to);

        if ($cc) {
            $mail->cc($cc);
        }
        if ($bcc) {
            $mail->bcc($bcc);
        }

        // return $mail;
        $mail->send(new ShipmentEmail($request->subject, $request->message, $file_paths, ["from" => $fromEmail]));

        $params = [
            'model_id'        => $request->order_id,
            'model_type'      => Order::class,
            'from'            => $fromEmail,
            'to'              => implode(',', $request->to),
            'seen'            => 1,
            'subject'         => $request->subject,
            'message'         => $request->message,
            'template'        => $request->template,
            'additional_data' => json_encode(['attachment' => $file_paths]),
            'cc'              => ($cc) ? implode(',', $cc) : null,
            'bcc'             => ($bcc) ? implode(',', $bcc) : null,
        ];

        $this->emails::create($params);

        return redirect()->route('shipment.index')->withSuccess('You have successfully sent an email!');

    }

    /**
     * View communication email sent
     */
    public function viewSentEmail(Request $request)
    {
        $emails = $this->emails->where('model_type', Order::class)
            ->where('model_id', $request->order_id)->orderBy('id', 'desc')->get();

        return view('shipment.partial.load_sent_email_data', ['emails' => $emails])->render();
    }

    public function showCustomerDetails($id)
    {
        try {
            $customer_details = Customer::where('id', '=', $id)->firstOrFail();
            return new JsonResponse(['status' => 1, 'message' => 'Customer detail found', 'data' => $customer_details]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 0, 'message' => 'No data found']);
        }
    }

    public function generateShipment(Request $request)
    {
        $inputs    = $request->all();
        $validator = Validator::make($inputs, [
            //'customer_id' => 'required|numeric',
            'from_customer_id'       => 'required',
            'from_customer_city'     => 'required|string',
            'from_customer_country'  => 'required|string',
            'from_customer_phone'    => 'required|numeric',
            'from_customer_address1' => 'required|string|min:1|max:40',
            'customer_id'            => 'required',
            'customer_city'          => 'required|string',
            'customer_country'       => 'required|string',
            'customer_phone'         => 'required|numeric',
            'customer_address1'      => 'required|string|min:1|max:40',
            'actual_weight'          => 'required|numeric',
            'box_length'             => 'required|numeric',
            'box_width'              => 'required|numeric',
            'box_height'             => 'required|numeric',
            'amount'                 => 'required|numeric',
            'currency'               => 'required',
            'pickup_time'            => 'required',
            'service_type'           => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ]);
        }

        try {

            //get customer details
            if ($request->from_customer_id > 0) {
                $from_customer      = Customer::where('id', $request->from_customer_id)->first();
                $from_customer_id   = $request->from_customer_id;
                $from_customer_name = $from_customer->name;
            } else {
                $from_customer_id   = null;
                $from_customer_name = $request->from_customer_id;
            }

            $rateReq = new CreateShipmentRequest("soap");
            /* $rateReq->setShipper([
            "street"         => config("dhl.shipper.street"),
            "city"             => config("dhl.shipper.city"),
            "postal_code"     => config("dhl.shipper.postal_code"),
            "country_code"    => config("dhl.shipper.country_code"),
            "person_name"     => config("dhl.shipper.person_name"),
            "company_name"     => "Solo Luxury",
            "phone"         => config("dhl.shipper.phone")
            ]); */

            $rateReq->setShipper([
                "street"       => $request->from_customer_address1,
                "city"         => $request->from_customer_city,
                "postal_code"  => $request->from_customer_pincode,
                "country_code" => $request->from_customer_country,
                "person_name"  => $from_customer_name,
                "company_name" => $request->company_name,
                "phone"        => $request->from_customer_phone,
            ]);

            if ($request->customer_id > 0) {
                $customer      = Customer::where('id', $request->customer_id)->first();
                $customer_id   = $request->customer_id;
                $customer_name = $customer->name;
            } else {
                $customer_id   = null;
                $customer_name = $request->customer_id;
            }

            $rateReq->setRecipient([
                "street"       => $request->customer_address1,
                "city"         => $request->customer_city,
                "postal_code"  => $request->customer_pincode,
                "country_code" => $request->customer_country,
                "person_name"  => $customer_name,
                "company_name" => $customer_name,
                "phone"        => $request->customer_phone,
                "email"        => $request->get('customer_email'),
            ]);

            $rateReq->setShippingTime(gmdate("Y-m-d\TH:i:s", strtotime($request->pickup_time)) . " GMT+05:30");

            if (isset($request->duty_mode) && $request->duty_mode != null) {
                $rateReq->setPaymentInfo($request->duty_mode);
            }

            $rateReq->setDeclaredValue($request->amount);
            $rateReq->setCurrency($request->currency);
            $rateReq->setPackages([
                [
                    "weight" => (float) $request->actual_weight,
                    "length" => $request->box_length,
                    "width"  => $request->box_width,
                    "height" => $request->box_height,
                    "note"   => "N/A",
                ],
            ]);

            $phone = !empty($request->customer_phone) ? $request->customer_phone : '';
            $rateReq->setMobile($phone);
            $rateReq->setServiceType($request->service_type);
            $response = $rateReq->call();

            if (!$response->hasError()) {
                $receipt = $response->getReceipt();
                if (!empty($receipt["label_format"])) {
                    if (strtolower($receipt["label_format"]) == "pdf") {
                        Storage::disk('files')->put('waybills/' . $receipt["tracking_number"] . '_package_slip.pdf', $bin = base64_decode($receipt["label_image"], true));
                        $waybill                   = new Waybill;
                        $waybill->order_id         = null;
                        $waybill->customer_id      = $request->customer_id;
                        $waybill->awb              = $receipt["tracking_number"];
                        $waybill->box_width        = $request->box_width;
                        $waybill->box_height       = $request->box_height;
                        $waybill->box_length       = $request->box_length;
                        $waybill->actual_weight    = (float) $request->get("actual_weight");
                        $volume_weight             = $request->box_width * $request->box_height * $request->box_length / 5000;
                        $waybill->volume_weight    = (float) $volume_weight;
                        $waybill->cost_of_shipment = $request->amount . ' ' . $request->currency;
                        $waybill->duty_cost        = null; #TODO after discussing
                        $waybill->package_slip     = $receipt["tracking_number"] . '_package_slip.pdf';
                        $waybill->pickup_date      = $request->pickup_time;
                        //newly added
                        $waybill->from_customer_id        = $from_customer_id;
                        $waybill->from_customer_name      = $from_customer_name;
                        $waybill->from_city               = $request->from_customer_city;
                        $waybill->from_country_code       = $request->from_customer_country;
                        $waybill->from_customer_phone     = $request->from_customer_phone;
                        $waybill->from_customer_address_1 = $request->from_customer_address1;
                        $waybill->from_customer_address_2 = $request->from_customer_address2;
                        $waybill->from_customer_pincode   = $request->from_customer_pincode;
                        $waybill->from_company_name       = $request->from_company_name;
                        $waybill->to_customer_id          = $customer_id;
                        $waybill->to_customer_name        = $customer_name;
                        $waybill->to_city                 = $request->customer_city;
                        $waybill->to_country_code         = $request->customer_country;
                        $waybill->to_customer_phone       = $request->customer_phone;
                        $waybill->to_customer_address_1   = $request->customer_address1;
                        $waybill->to_customer_address_2   = $request->customer_address2;
                        $waybill->to_customer_pincode     = $request->customer_pincode;
                        $waybill->to_company_name         = $request->company_name;
                        $waybill->save();
                    }
                }
                return response()->json([
                    'success' => true,
                ]);
            } else {
                return response()->json([
                    'success'      => false,
                    'globalErrors' => $response->getErrorMessage(),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success'      => false,
                'globalErrors' => $e->getMessage(),
            ]);
        }
    }

    public function getShipmentByName($name)
    {
        $all_templates = MailinglistTemplate::where('name', '=', $name)->get();
        return new JsonResponse(['status' => 1, 'data' => $all_templates]);
    }

    public function viewWaybillTrackHistory(Request $request)
    {
        $tracks = waybillTrackHistories::where('waybill_id', $request->waybill_id)
            ->orderBy('id', 'desc')->get();

        return view('shipment.partial.load_waybill_track_histories', ['tracks' => $tracks])->render();

    }

    public function createPickupRequest(Request $request)
    {
        try {
            //get customer details
            $waybill = Waybill::where(['id' => $request->waybill_id])->with('order', 'order.customer')->first();
            $rateReq = new CreatePickupRequest("soap");
            $rateReq->setShipper([
                "street"       => config("dhl.shipper.street"),
                "city"         => config("dhl.shipper.city"),
                "postal_code"  => config("dhl.shipper.postal_code"),
                "country_code" => config("dhl.shipper.country_code"),
                "person_name"  => config("dhl.shipper.person_name"),
                "company_name" => "Solo Luxury",
                "phone"        => config("dhl.shipper.phone"),
                "email"        => config("dhl.shipper.email"),
                "mobile"       => config("dhl.shipper.mobile"),
            ]);
            $rateReq->setRecipient([
                "street"       => $waybill->order->customer->address,
                "city"         => $waybill->order->customer->city,
                "postal_code"  => $waybill->order->customer->pincode,
                "country_code" => $waybill->order->customer->country,
                "person_name"  => $waybill->order->customer->name,
                "company_name" => $waybill->order->customer->name,
                "phone"        => $waybill->order->customer->phone,
                "email"        => $waybill->order->customer->email,
                "mobile"       => $waybill->order->customer->phone,
            ]);

            $rateReq->setPickupTimestamp(gmdate("Y-m-d\TH:i:s", strtotime($request->pickup_time)) . " GMT+05:30");
            $rateReq->setPickupLocationCloseTime(gmdate("H:i", strtotime($request->location_close_time)));
            $rateReq->setPickupLocation($request->pickup_location);
            $rateReq->setSpecialPickupInstruction($request->special_pickup_instruction);
            $rateReq->setPackages([
                [
                    "weight" => (float) $waybill->actual_weight,
                    "length" => $waybill->box_length,
                    "width"  => $waybill->box_width,
                    "height" => $waybill->box_height,
                    "note"   => "N/A",
                ],
            ]);

            $phone = !empty($waybill->order->customer->phone) ? $waybill->order->customer->phone : '';
            $rateReq->setMobile($phone);
            $rateReq->setServiceType($request->service_type);
            $response = $rateReq->call();
            if (!$response->hasError()) {
                $receipt = $response->getReceipt();
                if (!empty($receipt["message"])) {
                    Waybill::where('id', $request->waybill_id)->update(['createPickupRequest' => 1]);
                }
                return response()->json([
                    'success' => true,
                ]);
            } else {
                return response()->json([
                    'success'      => false,
                    'globalErrors' => $response->getErrorMessage(),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success'      => false,
                'globalErrors' => $e->getMessage(),
            ]);
        }
    }

    public function saveBoxSize(Request $request)
    {
        $id = $request->get("shipment_id");

        if ($id > 0) {

            $waybill = \App\Waybill::find($id);

            if ($waybill) {
                $waybill->fill($request->all());
                $waybill->save();
            }

            return response()->json(["code" => 200, "data" => [
                "box_length"    => $waybill->box_length,
                "box_width"     => $waybill->box_width,
                "box_height"    => $waybill->box_height,
                "actual_weight" => $waybill->actual_weight,
                "volume_weight" => $waybill->volume_weight,
                "id"            => $waybill->id,
            ], "message" => "Request update successfully"]);
        }

        return response()->json(["code" => 500, "error" => "Waybill id is wrong"]);

    }


    public function editShipment(Request $request, $id)
    {
        $wayBill = \App\Waybill::Find($id);

        // if way bill found then start to insert data
        if($wayBill) {

            $view  = (string)view('shipment.partial.edit-modal',compact('wayBill'));

            return response()->json(["code" => 200, "data" => ["html" => $view]]);
        }

    }

    public function saveShipment(Request $request, $id)
    {
        $wayBill = \App\Waybill::Find($id);
        // if way bill found then start to insert data
        if($wayBill) {
            $wayBill->fill($request->all());
            if($wayBill->save()) {
                return response()->json(["code" => 200, "data" => "Record stored successfully"]);
            }
        }

        return response()->json(["code" => 500, "data" => "Record stored successfully"]);

    }

    public function getPaymentInfo(Request $request){
        $wayBill = \App\Waybill::leftJoin("waybill_invoices", "waybill_invoices.shipment_number", "waybills.awb")
            ->select('waybills.id','waybill_invoices.invoice_number','waybills.awb','waybills.cost_of_shipment')
            ->where('waybills.id',$request->waybill_id)->groupBy('waybills.id')->first();

        // if way bill found then start to insert data
        if($wayBill) {
            $view  = (string)view('shipment.partial.payment-model',compact('wayBill'));

            return response()->json(["code" => 200, "data" => $view]);
        }
        else
        {
            return response()->json(["code" => 500, "message" => "Shipment not found."]);
        }
    }


    public function savePaymentInfo(Request $request){
        try {
            $wayBill = \App\Waybill::leftJoin("waybill_invoices", "waybill_invoices.shipment_number", "waybills.awb")
                ->select('waybills.id','waybill_invoices.id as invoice_id','waybills.cost_of_shipment')
                ->where('waybills.id',$request->waybill_id)->groupBy('waybills.id')->first();

            if ($wayBill) {
                $wayBill->paid_date = now();
                $wayBill->payment_mode = $request->payment_mode;
                $wayBill->save();

                $cash_flow = new CashFlow();
                $cash_flow->fill([
                    'user_id' => auth()->user()->id,
                    'amount' => $wayBill->cost_of_shipment,
                    'actual' => $wayBill->cost_of_shipment,
                    'date'                => $wayBill->paid_date,
                    'type'                => 'paid',
                    'description'         => 'Waybill invoice details',
                    'cash_flow_able_id'   => $wayBill->invoice_id,
                    'cash_flow_able_type' => \App\Waybillinvoice::class,
                    'updated_by' => auth()->user()->id,
                ])->save();
                Mail::to('billing-query.ae@dhl.com')->send(new InvoicePaymentMail($wayBill));
            }
            return response()->json(["code" => 200, "message" => "Payment updated successfully."]);
        } catch(Exception $e){
            return response()->json(["code" => 500, "message" => "Something went wrong, please try after sometimes."]);
        }
    }

    
}
