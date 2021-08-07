<?php

namespace App\Http\Controllers\Marketing;

use App\Customer;
use App\CustomerMarketingPlatform;
use App\Mailinglist;
use App\EmailLead;
use App\Imports\EmailLeadImport;
use App\MailinglistEmail;
use App\MailingRemark;
use App\Service;
use App\LeadList;
use App\StoreWebsite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use DB;


class EmailLeadsController extends Controller
{
	/**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
		$query = EmailLead::query();
		
		if($request->email){
			$query = $query->where('email', 'LIKE','%'.$request->email.'%');
		}
		if($request->source){
			$query = $query->where('source', 'LIKE','%'.$request->source.'%');
		}
		
        $emailLeads = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
		$mailingList = Mailinglist::all();
        return view('marketing.emailleads.index', compact('emailLeads', 'mailingList') );
    }

	
	/**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assignList(Request $request)
    {
		
		$leadIdArray = explode(",",$request->lead_id);  //comma separated
		$listIdArray = $request->list_id;  //in_array
		
		$batchArray = array();
		$i = 0;
		foreach($leadIdArray as $leadId)
		{
			
			foreach($listIdArray as $listId)
			{
				$batchArray[$i]['erp_lead_id'] = $leadId;
				$batchArray[$i]['list_id'] = $listId;
				$i++;
			}
		}
		if(!empty($batchArray))
		{
			LeadList::insert($batchArray);
			return redirect('emailleads')->with('flash_type', 'alert-success')->with('message', 'List updated with erp lead');
		}else{
			return redirect('emailleads')->with('flash_type', 'alert-danger')->with('message', 'An error occurred, please try again');
		}
	}

	
	public function import(Request $request)
	{
		(new EmailLeadImport)->queue($request->file('file'));
        return redirect('emailleads')->with('flash_type', 'alert-info')->with('message', 'Email Leads are importing in queue, existing records will be skipped.');
	}
	
	
	public function export()
	{
		$filename = 'email-lead-export-sample.xlsx';
		$path = public_path('sample-email-lead/'.$filename);
		
		return response()->download($path, $filename, [
			'Content-Type' => 'application/vnd.ms-excel',
			'Content-Disposition' => 'inline; filename="' . $filename . '"'
		]);
	}
	
	public function show($id)
	{
		if($id)
		{
			$leadData = DB::table('email_leads')
			->join('lead_lists', 'lead_lists.erp_lead_id', '=', 'email_leads.id')
			->join('mailinglists', 'mailinglists.id', '=', 'lead_lists.list_id')
			->select('email_leads.*','lead_lists.id as lead_list_id', 'mailinglists.name', 'mailinglists.id as mailinglist_id')
			->where('email_leads.id',$id)
			->get();
		   
			$emailLeadData = EmailLead::find($id);
		   
			return view('marketing.emailleads.show', compact('leadData','emailLeadData'));
		}
	}
	
	public function unsubscribe($lead_id, $lead_list_id)
	{	
		$data = EmailLead::find($lead_id);
		$curl3 = curl_init();
		curl_setopt_array($curl3, array(
			CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/".$data->email,
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

		$res = LeadList::destroy($lead_list_id);
		if($res)
		{
			return redirect('emailleads')->with('flash_type', 'alert-success')->with('message', 'List has been unsubscribed.');
		}else{
			return redirect('emailleads')->with('flash_type', 'alert-danger')->with('message', 'An error occurred, please try again');
		}
	}
	
}