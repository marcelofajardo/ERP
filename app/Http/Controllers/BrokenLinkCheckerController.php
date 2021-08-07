<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Response;
use Storage;
use DB;
use App\BackLinkChecker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class BrokenLinkCheckerController extends Controller
{

    /**
     * @SWG\Get(
     *   path="/broken-link-details",
     *   tags={"Scraper"},
     *   summary="Get broken link details",
     *   operationId="scraper-get-broken-link-details",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
     *
     */
    /**
     * Get Broken Links Details
     * Function for API
     * 
     * @return json response
     */
    public function getBrokenLinkDetails() 
    {
        $json_file = Storage::disk('local')->get('/files/broken-link-checker.json');
        if ($json_file) {
            $info = json_decode($json_file, true);
            $json['type'] = 'success';
            $json['message']  = 'Data Received Successfully';
            return Response::json($json, 200);
        } else {
            $json['type']     = 'error';
            $json['message']  = 'File Not Found';
            return Response::json($json, 203);
        }
    }

     /**
     * Get Broken Links Details
     * Function for display
     * 
     * @return json response
     */
    public function displayBrokenLinkDetails() 
    {   
        if (!empty($_GET['domain'])) {
            $domain = $_GET['domain'];
            $details = BackLinkChecker::where('domains', $domain)->paginate(100)->setPath('');
            $pagination = $details->appends(
                array(
                    'domain' => Input::get('domain'),
                )
            );
        } else if (!empty($_GET['ranking'])) {
            $ranking = $_GET['ranking'];
            $details = BackLinkChecker::where('rank', $ranking)->paginate(100)->setPath('');
            $pagination = $details->appends(
                array(
                    'ranking' => Input::get('ranking'),
                )
            );
        } else {
            $details = BackLinkChecker::paginate(100);
        }
            $domains = BackLinkChecker::select('domains')->pluck('domains')->toArray();
            $rankings = BackLinkChecker::select('rank')->pluck('rank')->toArray();
        return View('broken-link-checker.index',
            compact('details', 'domains', 'rankings')
        );
        // $json_file = Storage::disk('local')->get('/files/broken-link-checker.json');
        // if ($json_file) {
        //     $details = json_decode($json_file, true);
        //     foreach($details as $detail) {
        //         $results = $detail['results'];
        //         foreach($results as $result){
        //             // DB::table('back_link_checkers')->insert(
        //             //     [
        //             //         'domains' => $result['domain'], 'links' => $result['link'],
        //             //         'link_type' => $result['link_type'], 
        //             //         'review_numbers' => $result['num_reviews'], 'rank' => $result['rank'], 
        //             //         'rating' => $result['rating'], 'serp_id' => $result['serp_id'], 
        //             //         'snippet' => $result['snippet'], 'title' => $result['title'], 
        //             //         'visible_link' => $result['visible_link'],
        //             //         "created_at" => Carbon::now(), "updated_at" => Carbon::now()
        //             //     ]
        //             // );
        //             $domains[] = $result['domain'];
        //             $rankings[] = $result['rank'];
        //         }
        //     }
        //     if (!empty($request['domain'])){
        //         if (in_array($request['domain'], $domains)) {
        //             $final_results[] = '';
        //         } 
        //         dd($final_results);
        //     }
        //     return View('broken-link-checker.index',
        //         compact('domains', 'rankings', 'details')
        //     );
        // } else {
        //     abort('File Not Found');
        // }
    }

    /**
     * Get Broken Links Details
     * Function for display
     * 
     * @return json response
     */
    public function updateDomain(Request $request) {
        $checker = BackLinkChecker::findOrFail($request['id']);
        $checker->domains = $request['domain_name'];
        $checker->save();
        return response()->json([
            'type' => 'success',
            'message' => 'Domain Updated'
        ]);
    }

    /**
     * Updated Title
     * Function for display
     * 
     * @return json response
     */
    public function updateTitle(Request $request) {
        $checker = BackLinkChecker::findOrFail($request['id']);
        $checker->title = $request['title'];
        $checker->save();
        return response()->json([
            'type' => 'success',
            'message' => 'Title Updated'
        ]);
    }
}
