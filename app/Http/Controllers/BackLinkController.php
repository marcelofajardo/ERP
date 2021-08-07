<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Response;
use Storage;
use DB;
use App\BackLinking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class BackLinkController extends Controller
{
     /**
     * Get Broken Links Details
     * Function for display
     * 
     * @return json response
     */
    public function displayBackLinkDetails() 
    {
        if (!empty($_GET['title'])) {
            $title = $_GET['title'];
            $details = BackLinking::where('title', $title)->paginate(50)->setPath('');
            $pagination = $details->appends(
                array(
                    'title' => Input::get('title'),
                )
            );
        } else {
            $details = BackLinking::paginate(50);
        }
            $titles = BackLinking::select('title')->pluck('title')->toArray();
            return View('back-linking.index',
            compact('details', 'titles')
        );
        // $json_file = Storage::disk('local')->get('/files/article.json');
        // $details = json_decode($json_file, true);
        // if ($json_file) {
        //     foreach($details as $detail) {
        //         DB::table('back_linkings')->insert(
        //             [
        //                 "title" => $detail['title'], 
        //                 "description" => $detail['Description'],
        //                 "url" => $detail['url'],
        //                 "created_at" => Carbon::now(), "updated_at" => Carbon::now()
        //             ]
        //         );
        //     }
        //     return View('back-linking.index',
        //         compact('domains', 'rankings', 'details')
        //     );
        // } else {
        //     abort('File Not Found');
        // }
    }

    /**
     * Update title
     * 
     * @return json response
     */
    public function updateTitle(Request $request) {
        $back_linking = BackLinking::findOrFail($request['id']);
        $back_linking->title = $request['title'];
        $back_linking->save();
        return response()->json([
            'type' => 'success',
            'message' => 'Title Updated'
        ]);
    }

    /**
     * Updated Title
     * Function for display
     * 
     * @return json response
     */
    public function updateDesc(Request $request) {
        $back_linking = BackLinking::findOrFail($request['id']);
        $back_linking->description = $request['desc'];
        $back_linking->save();
        return response()->json([
            'type' => 'success',
            'message' => 'Title Updated'
        ]);
    }

    /**
     * Updated Title
     * Function for display
     * 
     * @return json response
     */
    public function updateURL(Request $request) {
        $back_linking = BackLinking::findOrFail($request['id']);
        $back_linking->url = $request['url'];
        $back_linking->save();
        return response()->json([
            'type' => 'success',
            'message' => 'Title Updated'
        ]);
    }
}
