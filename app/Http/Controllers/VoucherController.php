<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\Currency;
use App\DeveloperTask;
use App\Events\VoucherApproved;
use App\Payment;
use App\PaymentMethod;
use App\PaymentReceipt;
use App\Task;
use App\Team;
use App\User;
use App\Voucher;
use App\VoucherCategory;
use Auth;
use Illuminate\Http\Request;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class VoucherController extends Controller
{
    public function __construct()
    {
        //$this->middleware('permission:voucher');
    }

    public function index(Request $request)
    {
        // dd($request->all());
        // $start = $request->range_start ? $request->range_start : Carbon::now()->startOfWeek();
        // $end = $request->range_end ? $request->range_end : Carbon::now()->endOfWeek();
        $start         = $request->range_start ? $request->range_start : date("Y-m-d", strtotime('monday this week'));
        $end           = $request->range_end ? $request->range_end : date("Y-m-d", strtotime('saturday this week'));
        $selectedUser  = $request->user_id ? $request->user_id : null;
        $tasks         = PaymentReceipt::where('status', 'Pending');
        $teammembers   = Team::where(['teams.user_id' => Auth::user()->id])->join('team_user', 'team_user.team_id', '=', 'teams.id')->select(['team_user.user_id'])->get()->toArray();
        $teammembers[] = Auth::user()->id;
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM')) {

            if ($request->user_id != null && $request->user_id != "") {
                $tasks = $tasks->where('user_id', $request->user_id)->where('date', '>=', $start)->where('date', '<=', $end);
            } else {
                $tasks = $tasks->where('date', '>=', $start)->where('date', '<=', $end);
            }
        } elseif (count($teammembers) > 0) {

            $tasks = $tasks->whereIn('user_id', $teammembers)->where('date', '>=', $start)->where('date', '<=', $end);
        } else {

            $tasks = $tasks->where('user_id', Auth::id())->where('date', '>=', $start)->where('date', '<=', $end);
        }

        if($request->range_due_start) {
            $tasks = $tasks->whereDate('billing_due_date', '>=', $request->range_due_start);
        }

        if($request->range_due_end) {
            $tasks = $tasks->whereDate('billing_due_date', '<=', $request->range_due_end);
        }

        $limit = request('limit');
        if (!empty($limit)) {
            if ($limit == "all") {
                $limit = $tasks->count();
            }
        }

        $tasks = $tasks->orderBy('id', 'desc')->paginate($limit)->appends(request()->except('page'));
        foreach ($tasks as $task) {
            $task->user;

            $totalPaid = Payment::where('payment_receipt_id', $task->id)->sum('amount');
            if ($totalPaid) {
                $task->paid_amount = number_format($totalPaid, 2);
                $task->balance     = $task->rate_estimated - $totalPaid;
                $task->balance     = number_format($task->balance, 2);
            } else {
                $task->paid_amount = 0;
                $task->balance     = $task->rate_estimated;
                $task->balance     = number_format($task->balance, 2);
            }
            // $task->assignedUser;
            if ($task->task_id) {
                $task->taskdetails      = Task::find($task->task_id);
                $task->estimate_minutes = 0;
                if ($task->taskdetails) {
                    $task->details = $task->taskdetails->task_details;
                    if (!$task->worked_minutes) {
                        $task->estimate_minutes = $task->taskdetails->approximate;
                    }
                }
            } else if ($task->developer_task_id) {
                $task->taskdetails      = DeveloperTask::find($task->developer_task_id);
                $task->estimate_minutes = 0;
                if ($task->taskdetails) {
                    $task->details = $task->taskdetails->task;
                    if (!$task->worked_minutes) {
                        $task->estimate_minutes = $task->taskdetails->estimate_minutes;
                    }
                }
            } else {
                $task->details          = $task->remarks;
                $task->estimate_minutes = $task->worked_minutes;
            }
        }

        // $vouchers = $vouchers->orderBy('date', 'DESC')->get();
        // dd($vouchers);
        //
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // $perPage = Setting::get('pagination');
        // $currentItems = array_slice($vouchers, $perPage * ($currentPage - 1), $perPage);
        //
        // $vouchers = new LengthAwarePaginator($currentItems, count($vouchers), $perPage, $currentPage, [
        //     'path'    => LengthAwarePaginator::resolveCurrentPath()
        // ]);
        //
        // dd($vouchers);
        // paginate(Setting::get('pagination'));
        // $users_array = Helpers::getUserArray(User::all());
        $users = User::all();
        return view('vouchers.index', [
            'tasks'        => $tasks,
            'users'        => $users,
            'user'         => $request->user,
            'selectedUser' => $selectedUser,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $voucher_categories          = VoucherCategory::where('parent_id', 0)->get();
        $voucher_categories_dropdown = VoucherCategory::attr(['name' => 'category_id', 'class' => 'form-control', 'placeholder' => 'Select a Category'])
            ->renderAsDropdown();

        return view('vouchers.create', [
            'voucher_categories'          => $voucher_categories,
            'voucher_categories_dropdown' => $voucher_categories_dropdown,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|min:3',
            'travel_type' => 'sometimes|nullable|string',
            'amount'      => 'sometimes|nullable|numeric',
            'paid'        => 'sometimes|nullable|numeric',
            'date'        => 'required|date',
        ]);

        $data            = $request->except('_token');
        $data['user_id'] = Auth::id();

        $voucher = Voucher::create($data);
        //create chat message
        $params = [
            'number'     => null,
            'user_id'    => Auth::id(),
            'voucher_id' => $voucher->id,
            'message'    => $voucher->description . ' ' . $voucher->amount,
        ];
        $message = ChatMessage::create($params);

        //TODO send message to admin yogesh for approval

        //TODO listen for whatsapp messages, identify the keywords and update the approval status accordingly
        if ($request->ajax()) {
            return response()->json(['id' => $voucher->id]);
        }

        return redirect()->route('voucher.index')->with('success', 'You have successfully created cash voucher');
    }

    public function storeCategory(Request $request)
    {
        $this->validate($request, [
            'title' => 'required_without:subcategory',
        ]);

        if ($request->title != '') {
            VoucherCategory::create(['title' => $request->title]);
        }

        if ($request->parent_id != '' && $request->subcategory != '') {
            VoucherCategory::create(['title' => $request->subcategory, 'parent_id' => $request->parent_id]);
        }

        return redirect()->back()->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $voucher                     = Voucher::find($id);
        $voucher_categories          = VoucherCategory::where('parent_id', 0)->get();
        $voucher_categories_dropdown = VoucherCategory::attr(['name' => 'category_id', 'class' => 'form-control', 'placeholder' => 'Select a Category'])
            ->selected($voucher->category_id)
            ->renderAsDropdown();

        return view('vouchers.edit', [
            'voucher'                     => $voucher,
            'voucher_categories'          => $voucher_categories,
            'voucher_categories_dropdown' => $voucher_categories_dropdown,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->type == "partial") {
            $this->validate($request, [
                'travel_type' => 'sometimes|nullable|string',
                'amount'      => 'sometimes|nullable|numeric',
            ]);
        } else {
            $this->validate($request, [
                'description' => 'required|min:3',
                'travel_type' => 'sometimes|nullable|string',
                'amount'      => 'sometimes|nullable|numeric',
                'paid'        => 'sometimes|nullable|numeric',
                'date'        => 'required|date',
            ]);
        }

        $data = $request->except('_token');

        Voucher::find($id)->update($data);

        if ($request->type == "partial") {
            return redirect()->back()->with('success', 'You have successfully updated cash voucher');
        }

        return redirect()->route('voucher.index')->with('success', 'You have successfully updated cash voucher');
    }

    public function approve(Request $request, $id)
    {
        $voucher = Voucher::find($id);

        //
        /*if ($voucher->approved == 1) {
        $voucher->approved = 2;
        } else {
        $voucher->approved = 1;
        }*/

        $voucher->approved = 2;

        $voucher->save();
        event(new VoucherApproved($voucher));
        //TODO send message to user via whatsapp notifying that the voucher request has been approved.
        return redirect()->route('voucher.index')->withSuccess('Voucher Approved.');
    }

    public function reject(Request $request, $id)
    {
        $voucher = Voucher::find($id);

        $voucher->reject_reason = $request->get('reject_reason');
        $voucher->reject_count += 1;

        $voucher->save();
        //TODO send message to user via whatsapp notifying that the voucher request has been rejected.
        return redirect()->route('voucher.index')->withSuccess('You have successfully updated the voucher!');
    }

    public function resubmit(Request $request, $id)
    {
        $voucher           = Voucher::find($id);
        $voucher->approved = 1;
        $voucher->resubmit_count += 1;

        $voucher->save();

        return redirect()->route('voucher.index')->withSuccess('You have successfully resubmitted the voucher!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Voucher::find($id)->delete();

        return redirect()->route('voucher.index')->with('success', 'You have successfully deleted a cash voucher');
    }

    public function userSearch()
    {
        $term   = request()->get("q", null);
        $search = User::where('name', 'LIKE', "%" . $term . "%")
            ->orWhere('email', 'LIKE', "%" . $term . "%")->get();
        return response()->json($search);
    }

    public function createPaymentRequest(Request $request)
    {
        $this->validate($request, [
            'user_id'  => 'required',
            'date'     => 'required',
            'amount'   => 'required',
            'currency' => 'required',
        ]);

        $input                   = $request->except('_token');
        $input['status']         = 'Pending';
        $input['rate_estimated'] = $input['amount'];
        PaymentReceipt::create($input);
        //create entry in table cash_flows
        \DB::table('cash_flows')->insert(
            [
                'user_id'             => $request->input('user_id'),
                'description'         => 'Vendor paid',
                'date'                => $request->input('date'),
                'amount'              => $request->input('amount'),
                'type'                => 'paid',
                'cash_flow_able_type' => 'App\PaymentReceipt',

            ]
        );
        return redirect()->back()->with('success', 'Successfully created');
    }

    public function paymentRequest()
    {
        $users = User::all();
        return view("vouchers.payment-request", compact('users'));
    }

    public function viewPaymentModal($id)
    {
        $task = PaymentReceipt::find($id);
        if ($task->user_id) {
            $task->userName = User::find($task->user_id)->name;
        }
        $paymentMethods = PaymentMethod::all();
        $currencies     = Currency::get();
        return view("vouchers.payment-modal", compact('task', 'paymentMethods', 'currencies'));
    }

    public function submitPayment($id, Request $request)
    {
        $this->validate($request, [
            'date'              => 'required',
            'amount'            => 'required',
            'currency'          => 'required',
            'payment_method_id' => 'required',
        ]);
        $preceipt = PaymentReceipt::find($id);

        if (!$preceipt) {
            return redirect()->back()->with('warning', 'Payment receipt not found');
        }
        $totalPaid = Payment::where('payment_receipt_id', $preceipt->id)->sum('amount');
        $newTotal  = $totalPaid + $request->amount;

        if ($newTotal > $preceipt->rate_estimated) {
            return redirect()->back()->with('warning', 'Amount can not be greater than receipt amount');
        }

        $input = $request->except('_token');

        if (!is_numeric($input['payment_method_id'])) {
            $paymentMethod = PaymentMethod::where("name", $input['payment_method_id'])->first();
            if (!$paymentMethod) {
                $paymentMethod = PaymentMethod::create([
                    "name" => $input['payment_method_id'],
                ]);
                $input['payment_method_id'] = $paymentMethod->id;
            } else {
                $input['payment_method_id'] = $paymentMethod->id;
            }
        }

        $payment_method              = PaymentMethod::find($input['payment_method_id']);
        $input['payment_receipt_id'] = $preceipt->id;
        $message['message']          = "Admin has given the payment of Payment Receipt #" . $preceipt->id . " and amount " . $request->amount . " " . $request->currency . " through " . $payment_method->name . " \n Note: " . $request->note;
        $message['user_id']          = $request->user_id;
        $message['status']           = 1;

        Payment::create($input);
        $request1 = new \Illuminate\Http\Request();
        $request1->replace($message);

        $sendMessage = app('App\Http\Controllers\WhatsAppController')->sendMessage($request1, 'user');
        $cashData    = [
            'user_id'             => $request->user_id,
            'description'         => 'Vendor paid',
            'date'                => $request->input('date'),
            'amount'              => $newTotal,
            'type'                => 'paid',
            'cash_flow_able_type' => 'App\PaymentReceipt',
            'created_at'          => date("Y-m-d H:i:s"),
            'updated_by'          => \Auth::user()->id,

        ];
        if ($newTotal >= $preceipt->rate_estimated) {
            $preceipt->update(['status' => 'Done']);
            $cashdata['order_status'] = 'Done';
            $cashdata['status']       = 1;
        }
        //create entry in table cash_flows
        \DB::table('cash_flows')->insert($cashData);
        //created
        return redirect()->back()->with('success', 'Successfully submitted');
    }

    public function uploadDocuments(Request $request)
    {
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function saveDocuments(Request $request)
    {

        $documents = $request->input('document', []);
        if (!empty($documents)) {
            $receipt = PaymentReceipt::find($request->id);

            foreach ($request->input('document', []) as $file) {
                $path  = storage_path('tmp/uploads/' . $file);
                $media = MediaUploader::fromSource($path)
                    ->toDirectory('voucher/' . floor($request->id / config('constants.image_per_folder')))
                    ->upload();
                $receipt->attachMedia($media, config('constants.media_tags'));
            }

            return response()->json(["code" => 200, "data" => [], "message" => "Done!"]);
        } else {
            return response()->json(["code" => 500, "data" => [], "message" => "No documents for upload"]);
        }

    }

    public function listDocuments(Request $request, $id)
    {
        $receipt = PaymentReceipt::find($request->id);

        $userList = [];

        // $userList = array_filter($userList);
        // // create the select box design html here
        // $usrSelectBox = "";
        // if (!empty($userList)) {
        //     $usrSelectBox = (string) \Form::select("send_message_to", $userList, null, ["class" => "form-control send-message-to-id"]);
        // }

        $records = [];
        if ($receipt) {
            if ($receipt->hasMedia(config('constants.media_tags'))) {
                foreach ($receipt->getMedia(config('constants.media_tags')) as $media) {
                    $records[] = [
                        "id"                 => $media->id,
                        'url'                => $media->getUrl(),
                        'payment_receipt_id' => $request->id,
                    ];
                }
            }
        }

        return response()->json(["code" => 200, "data" => $records]);
    }

    public function deleteDocument(Request $request)
    {
        if ($request->id != null) {
            $media = \Plank\Mediable\Media::find($request->id);
            if ($media) {
                $media->delete();
                return response()->json(["code" => 200, "message" => "Document delete succesfully"]);
            }
        }

        return response()->json(["code" => 500, "message" => "No document found"]);
    }

    public function viewManualPaymentModal()
    {
        $users          = User::all();
        $paymentMethods = PaymentMethod::all();
        $currencies     = Currency::get();
        return view("vouchers.manual-payment-modal", compact('users', 'paymentMethods', 'currencies'));
    }

    public function manualPaymentSubmit(Request $request)
    {
        $this->validate($request, [
            'date'              => 'required',
            'user_id'           => 'required',
            'amount'            => 'required',
            'currency'          => 'required',
            'payment_method_id' => 'required',
        ]);
        $input = $request->except('_token');

        $input['status']         = 'Pending';
        $input['rate_estimated'] = $input['amount'];
        $input['remarks']        = $input['note'];
        $paymentReceipt          = PaymentReceipt::create($input);

        $input['payment_receipt_id'] = $paymentReceipt->id;

        if (!is_numeric($input['payment_method_id'])) {
            $paymentMethod = PaymentMethod::where("name", $input['payment_method_id'])->first();
            if (!$paymentMethod) {
                $paymentMethod = PaymentMethod::create([
                    "name" => $input['payment_method_id'],
                ]);
                $input['payment_method_id'] = $paymentMethod->id;
            } else {
                $input['payment_method_id'] = $paymentMethod->id;
            }
        }

        Payment::create($input);
        $cashData = [
            'user_id'             => $request->user_id,
            'description'         => 'Vendor paid',
            'date'                => $request->date,
            'amount'              => $request->amount,
            'type'                => 'paid',
            'cash_flow_able_type' => 'App\PaymentReceipt',
        ];
        //create entry in table cash_flows
        \DB::table('cash_flows')->insert($cashData);
        return redirect()->back()->with('success', 'Successfully submitted');
    }

    public function paidSelected(Request $request)
    {
        $ids            = !empty($request->ids) ? $request->ids : [0];
        $paymentReceipt = \App\PaymentReceipt::whereIn("id", $ids)->get();

        $paymentMethods = PaymentMethod::all();
        $currencies     = Currency::get();

        return view("vouchers.partials.modal-payment-receipt-paid", compact('paymentReceipt', 'currencies', 'paymentMethods'));
    }

    public function paidSelectedPaymentList(Request $request)
    {
        $payments = \App\Payment::where("payment_receipt_id", $request->payment_receipt_id)->get();
        return view("vouchers.partials.payment-receipt-list", compact('payments'));
    }

    public function payMultiple(Request $request)
    {
        $this->validate($request, [
            'date'              => 'required',
            'amount.*'          => 'required',
            'currency'          => 'required',
            'payment_method_id' => 'required',
        ]);

        $input = $request->except('_token');

        if (!is_numeric($input['payment_method_id'])) {
            $paymentMethod = PaymentMethod::where("name", $input['payment_method_id'])->first();
            if (!$paymentMethod) {
                $paymentMethod = PaymentMethod::create([
                    "name" => $input['payment_method_id'],
                ]);
                $input['payment_method_id'] = $paymentMethod->id;
            } else {
                $input['payment_method_id'] = $paymentMethod->id;
            }
        }

        $payment_method = PaymentMethod::find($input['payment_method_id']);

        if (!empty($request->amount)) {
            foreach ($request->amount as $k => $amount) {
                $preceipt = PaymentReceipt::find($k);
                if ($preceipt) {
                    $totalPaid = Payment::where('payment_receipt_id', $preceipt->id)->sum('amount');
                    $newTotal  = $totalPaid + $amount;

                    $input['payment_receipt_id'] = $preceipt->id;
                    $input['amount']             = $amount;
                    $message['message']          = "Admin has given the payment of Payment Receipt #" . $preceipt->id . " and amount " . $amount . " " . $request->currency . " through " . $payment_method->name . " \n Note: " . $request->note;
                    $message['user_id']          = $preceipt->user_id;
                    $message['status']           = 1;

                    Payment::create($input);
                    $request1 = new \Illuminate\Http\Request();
                    $request1->replace($message);

                    $sendMessage = app('App\Http\Controllers\WhatsAppController')->sendMessage($request1, 'user');
                    $cashData    = [
                        'user_id'             => $preceipt->user_id,
                        'description'         => 'Vendor paid',
                        'date'                => $request->input('date'),
                        'amount'              => $newTotal,
                        'type'                => 'paid',
                        'cash_flow_able_type' => 'App\PaymentReceipt',
                        'created_at'          => date("Y-m-d H:i:s"),
                        'updated_by'          => \Auth::user()->id,
                    ];
                    if ($newTotal >= $preceipt->rate_estimated) {
                        $preceipt->update(['status' => 'Done']);
                        $cashdata['order_status'] = 'Done';
                        $cashdata['status']       = 1;
                    }
                    //create entry in table cash_flows
                    \DB::table('cash_flows')->insert($cashData);
                }

            }
        }

        return response()->json(["code" => 200, "message" => "Payment paid successfully"]);
    }
}
