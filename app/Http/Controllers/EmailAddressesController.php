<?php

namespace App\Http\Controllers;

use App\EmailAddress;
use App\EmailRunHistories;
use App\StoreWebsite;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Exports\EmailFailedReport;
use Maatwebsite\Excel\Facades\Excel;

class EmailAddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = EmailAddress::query();
        
        $query->select('email_addresses.*', DB::raw('(SELECT is_success FROM email_run_histories WHERE email_address_id = email_addresses.id Order by id DESC LIMIT 1) as is_success'));

        $columns = ['from_name', 'from_address', 'driver', 'host', 'port', 'encryption'];

        if ($request->keyword) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $request->keyword . '%');
            }
        }

        $emailAddress = $query->paginate();
        $allStores    = StoreWebsite::all();
        $allDriver    = EmailAddress::pluck('driver')->unique();
        $allPort      = EmailAddress::pluck('port')->unique();
        $allEncryption= EmailAddress::pluck('encryption')->unique();

        //dd($allDriver);

        return view('email-addresses.index', [
            'emailAddress' => $emailAddress,
            'allStores'    => $allStores,
            'allDriver'    => $allDriver,
            'allPort'      => $allPort,
            'allEncryption'=> $allEncryption,
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'from_name'      => 'required|string|max:255',
            'from_address'   => 'required|string|max:255',
            'driver'         => 'required|string|max:255',
            'host'           => 'required|string|max:255',
            'port'           => 'required|string|max:255',
            'encryption'     => 'required|string|max:255',
            'username'       => 'required|string|max:255',
            'password'       => 'required|string|max:255',
            'recovery_phone' => 'required|string|max:255',
            'recovery_email' => 'required|string|max:255',
        ]);

        $data = $request->except('_token');

        EmailAddress::create($data);

        return redirect()->route('email-addresses.index')->withSuccess('You have successfully saved a Email Address!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $this->validate($request, [
            'from_name'      => 'required|string|max:255',
            'from_address'   => 'required|string|max:255',
            'driver'         => 'required|string|max:255',
            'host'           => 'required|string|max:255',
            'port'           => 'required|string|max:255',
            'encryption'     => 'required|string|max:255',
            'username'       => 'required|string|max:255',
            'password'       => 'required|string|max:255',
            'recovery_phone' => 'required|string|max:255',
            'recovery_email' => 'required|string|max:255',
        ]);

        $data = $request->except('_token');

        EmailAddress::find($id)->update($data);

        return redirect()->back()->withSuccess('You have successfully updated a Email Address!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $emailAddress = EmailAddress::find($id);

        $emailAddress->delete();

        return redirect()->route('email-addresses.index')->withSuccess('You have successfully deleted a Email Address');
    }

    public function getEmailAddressHistory(Request $request)
    {
        $EmailHistory = EmailRunHistories::where('email_run_histories.email_address_id', $request->id)
            ->whereDate('email_run_histories.created_at', Carbon::today())
            ->join('email_addresses', 'email_addresses.id', 'email_run_histories.email_address_id')
            ->select(['email_run_histories.*', 'email_addresses.from_name'])
            ->latest()
            ->get();

        $history = '';
        if (sizeof($EmailHistory) > 0) {
            foreach ($EmailHistory as $runHistory) {
                $status  = ($runHistory->is_success == 0) ? "Failed" : "Success";
                $message = empty($runHistory->message) ? "-" : $runHistory->message;
                $history .= '<tr>
                <td>' . $runHistory->id . '</td>
                <td>' . $runHistory->from_name . '</td>
                <td>' . $status . '</td>
                <td>' . $message . '</td>
                <td>' . $runHistory->created_at->format('Y-m-d H:i:s') . '</td>
                </tr>';
            }
        } else {
            $history .= '<tr>
                    <td colspan="5">
                        No Result Found
                    </td>
                </tr>';
        }

        return response()->json(['data' => $history]);
    }


    public function getRelatedAccount(Request $request)
    {
        $adsAccounts  = \App\GoogleAdsAccount::where("account_name", $request->id)->get();
        $translations = \App\googleTraslationSettings::where("email", $request->id)->get();
        $analytics    = \App\StoreWebsiteAnalytic::where("email", $request->id)->get();

        $accounts = [];

        if (!$adsAccounts->isEmpty()) {
            foreach ($adsAccounts as $adsAccount) {
                $accounts[] = [
                    "name"          => $adsAccount->account_name,
                    "email"         => $adsAccount->account_name,
                    "last_error"    => $adsAccount->last_error,
                    "last_error_at" => $adsAccount->last_error_at,
                    "credential"    => $adsAccount->config_file_path,
                    'store_website' => $adsAccount->store_websites,
                    'status'        => $adsAccount->status,
                    'type'          => "Google Ads Account",
                ];
            }
        }

        if (!$translations->isEmpty()) {
            foreach ($translations as $translation) {
                $accounts[] = [
                    "name"          => $translation->email,
                    "email"         => $translation->email,
                    "last_error"    => $translation->last_note,
                    "last_error_at" => $translation->last_error_at,
                    "credential"    => $translation->account_json,
                    'store_website' => "N/A",
                    'status'        => $translation->status,
                    'type'          => "Google Translation",
                ];
            }
        }

        if (!$analytics->isEmpty()) {
            foreach ($analytics as $analytic) {
                $accounts[] = [
                    "name"          => $analytic->email,
                    "email"         => $analytic->email,
                    "last_error"    => $analytic->last_error,
                    "last_error_at" => $analytic->last_error_at,
                    "credential"    => $analytic->account_id . " - " . $analytic->view_id,
                    'store_website' => $analytic->website,
                    'status'        => "N/A",
                    'type'          => "Google Analytics",
                ];
            }
        }

        return view("email-addresses.partials.task", compact('accounts'));

    }

    public function getErrorEmailHistory(Request $request)
    {
        $histories = EmailAddress::whereHas('history_last_message',function($query){
                $query->where('is_success', 0);
            })
            ->with('history_last_message')
            ->get();
        
        $history = '';
        
        if($histories) {
            foreach ($histories as $row) {
                $status  = ($row->history_last_message->is_success == 0) ? "Failed" : "Success";
                $message = $row->history_last_message->message??'-';
                $history .= '<tr>
                <td>' . $row->history_last_message->id . '</td>
                <td>' . $row->from_name . '</td>
                <td>' . $status . '</td>
                <td>' . $message . '</td>
                <td>' . $row->history_last_message->created_at->format('Y-m-d H:i:s') . '</td>
                </tr>';
            }
        } else {
            $history .= '<tr>
                    <td colspan="5">
                        No Result Found
                    </td>
                </tr>';
        }

        return response()->json(['data' => $history]);
    }

    public function downloadFailedHistory(Request $request){

        
        $histories = EmailAddress::whereHas('history_last_message',function($query){
                $query->where('is_success', 0);
            })
            ->with('history_last_message')
            ->get();

        $recordsArr = []; 
        foreach($histories as $row){
            $recordsArr[] = [
                'id'         => $row->history_last_message->id,
                'from_name'  => $row->from_name,
                'status'     => ($row->history_last_message->is_success == 0) ? "Failed" : "Success",
                'message'    => $row->history_last_message->message??'-',
                'created_at' => $row->history_last_message->created_at->format('Y-m-d H:i:s'),
            ];
        }
        $filename = 'Report-Email-failed'.'.csv';
        return Excel::download(new EmailFailedReport($recordsArr),$filename);
    }
}
