<?php

namespace App\Http\Controllers;

use App\Email;
use App\MailinglistTemplate;
use App\Mails\Manual\PurchaseEmail;
use Illuminate\Http\Request;
use Mail;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function sendCommonEmail(request $request)
    {
        $this->validate($request, [
            'subject' => 'required|min:3|max:255',
            'message' => 'required',
            'cc.*'    => 'nullable|email',
            'bcc.*'   => 'nullable|email',
            'sendto'  => 'required',
        ]);

        $fromEmail = 'buying@amourint.com';
        $fromName  = "buying";

        if ($request->from_mail) {
            $mail = \App\EmailAddress::where('id', $request->from_mail)->first();
            if ($mail) {
                $fromEmail = $mail->from_address;
                $fromName  = $mail->from_name;
                $config    = config("mail");
                unset($config['sendmail']);
                $configExtra = array(
                    'driver'     => $mail->driver,
                    'host'       => $mail->host,
                    'port'       => $mail->port,
                    'from'       => [
                        'address' => $mail->from_address,
                        'name'    => $mail->from_name,
                    ],
                    'encryption' => $mail->encryption,
                    'username'   => $mail->username,
                    'password'   => $mail->password,
                );
                \Config::set('mail', array_merge($config, $configExtra));
                (new \Illuminate\Mail\MailServiceProvider(app()))->register();
            }
        }

        /* if ($request->vendor_ids) {
        $vendor_ids = explode(',', $request->vendor_ids);
        $vendors = Vendor::whereIn('id', $vendor_ids)->get();
        }

        if ($request->vendors) {
        $vendors = Vendor::where('id', $request->vendors)->get();
        } else {
        if ($request->not_received != 'on' && $request->received != 'on') {
        return redirect()->route('vendors.index')->withErrors(['Please select vendors']);
        }
        }

        if ($request->not_received == 'on') {
        $vendors = Vendor::doesnthave('emails')->where(function ($query) {
        $query->whereNotNull('email');
        })->get();
        }

        if ($request->received == 'on') {
        $vendors = Vendor::whereDoesntHave('emails', function ($query) {
        $query->where('type', 'incoming');
        })->where(function ($query) {
        $query->orWhereNotNull('email');
        })->where('has_error', 0)->get();
        } */

        $file_paths = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $filename = $file->getClientOriginalName();

                $file->storeAs("documents", $filename, 'files');

                $file_paths[] = "documents/$filename";
            }
        }

        $cc = $bcc = [];
        if ($request->has('cc')) {
            $cc = array_values(array_filter($request->cc));
        }
        if ($request->has('bcc')) {
            $bcc = array_values(array_filter($request->bcc));
        }

        $emailClass = (new PurchaseEmail($request->subject, $request->message, $file_paths, ["from" => $fromEmail]))->build();

        $params = [
            'model_id'        => $request->id,
            'from'            => $fromEmail,
            'seen'            => 1,
            'to'              => $request->sendto,
            'subject'         => $request->subject,
            'message'         => $emailClass->render(),
            'template'        => 'simple',
            'additional_data' => json_encode(['attachment' => $file_paths]),
            'cc'              => $cc ?: null,
            'bcc'             => $bcc ?: null,
        ];
        if ($request->object) {
            if ($request->object == 'vendor') {
                $params['model_type'] = "Vendor::class";
            } elseif ($request->object == 'user') {
                $params['model_type'] = "User::class";
            } elseif ($request->object == 'supplier') {
                $params['model_type'] = "Supplier::class";
            } elseif ($request->object == 'customer') {
                $params['model_type'] = "Customer::class";
            } elseif ($request->object == 'order') {
                $params['model_type'] = "Order::class";
            }
        }

        $email = Email::create($params);

        \App\Jobs\SendEmail::dispatch($email);

        return redirect()->back()->withSuccess('You have successfully sent email!');

    }
    public function getMailTemplate(request $request)
    {
        if (isset($request->mailtemplateid)) {
            $data            = MailinglistTemplate::select('static_template', 'subject')->where('id', $request->mailtemplateid)->first();
            $static_template = $data->static_template;
            $subject         = $data->subject;
            if (!$static_template) {return response()->json(['error' => 'unable to get template', 'success' => false]);}
            return response()->json(['template' => $static_template, 'subject' => $subject, 'success' => true]);
        }
        return response()->json(['error' => 'unable to get template', 'success' => false]);
    }
}
