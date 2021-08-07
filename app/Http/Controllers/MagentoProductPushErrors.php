<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\Http\Controllers\WhatsAppController;
use App\StoreWebsite;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

use App\ProductPushErrorLog;
use App\Exports\MagentoProductCommonError;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class MagentoProductPushErrors extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $title = "List | Magento Log Errors";

        return view('magento-product-error.index', compact('title'));
    }

    public function records(Request $request)
    {
        $keyword = $request->get("keyword");

        $records = ProductPushErrorLog::with('store_website')
            ->where('response_status','error');

        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("message", "LIKE", "%$keyword%");
            });
        }


        $records = $records->latest()->paginate(50);

        $recorsArray = [];

        foreach ($records as $row) {

            $recorsArray[] = [
                'product_id'      => $row->product_id,
                'updated_at'      => $row->created_at->format('d-m-y H:i:s'),
                'store_website'   => $row->store_website->title,
                'message'         => str_limit($row->message, 30, 
                    '<a data-logid='.$row->id.' class="message_load">...</a>'),
                'request_data'    => str_limit($row->request_data, 30, 
                    '<a data-logid='.$row->id.' class="request_data_load">...</a>'),
                'response_data'   => str_limit($row->response_data, 30, 
                    '<a data-logid='.$row->id.' class="response_data_load">...</a>'),
                'response_status' => $row->response_status,
            ];
        }    

        return response()->json([
            "code"       => 200,
            "data"       => $recorsArray,
            "pagination" => (string) $records->links(),
            "total"      => $records->total(),
            "page"       => $records->currentPage(),
        ]);
        
    }

    public function getLoadDataValue(Request $request)
    {
        
        $records = ProductPushErrorLog::where('id',$request->id)->first();

        $fulltextvalue = $records[$request->field];
        
        return response()->json(["code" => 200, "data" => $fulltextvalue]);
    }

    public function groupErrorMessage(Request $request){

        $records = ProductPushErrorLog::where('response_status','error')
            ->whereDate('created_at',Carbon::now()->format('Y-m-d'))
            ->latest('count')
            ->groupBy('message')
            ->select(\DB::raw('*,COUNT(message) AS count'))
            ->get();

        $recordsArr = []; 
        foreach($records as $row){

            $recordsArr[] = [
                'count' => $row->count,
                'message' => $row->message,

            ];
        }

        // echo "<pre>";
        // print_r($recordsArr);
        // exit;

        $filename = 'Today Report Magento Errors.csv';
        return Excel::download(new MagentoProductCommonError($recordsArr),$filename);
    }
}
