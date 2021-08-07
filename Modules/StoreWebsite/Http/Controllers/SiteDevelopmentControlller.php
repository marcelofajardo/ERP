<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\Role;
use App\Setting;
use App\SiteDevelopment;
use App\SiteDevelopmentCategory;
use App\StoreWebsite;
use App\User;
use App\SiteDevelopmentArtowrkHistory;
use App\DeveloperTask;
use App\Task;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class SiteDevelopmentController extends Controller
{
//
    public function index($id = null, Request $request)
    {

        //Getting Website Details
        $website = StoreWebsite::find($id);

        $categories = SiteDevelopmentCategory::orderBy('title', 'asc');
        if ($request->k != null) {
            $categories = $categories->where("title", "like", "%" . $request->k . "%");
        }

        $ignoredCategory = \App\SiteDevelopmentHiddenCategory::where("store_website_id", $id)->pluck("category_id")->toArray();

        if (request('status') == "ignored") {
            $categories = $categories->whereIn('id', $ignoredCategory);
        } else {
            $categories = $categories->whereNotIn('id', $ignoredCategory);
        }
        $categories = $categories->paginate(Setting::get('pagination'));

        //Getting Roles Developer
        $role = Role::where('name', 'LIKE', '%Developer%')->first();

        //User Roles with Developers
        $roles = DB::table('role_user')->select('user_id')->where('role_id', $role->id)->get();

        foreach ($roles as $role) {
            $userIDs[] = $role->user_id;
        }

        if (!isset($userIDs)) {
            $userIDs = [];
        }

        $allStatus = \App\SiteDevelopmentStatus::pluck("name", "id")->toArray();

//dd($allStatus);
        $statusCount = \App\SiteDevelopment::join("site_development_statuses as sds","sds.id","site_developments.status")
        ->where("site_developments.website_id",$id)
        ->groupBy("sds.id")
        ->select(["sds.name",\DB::raw("count(sds.id) as total")])
        ->orderBy("name","desc")
        ->get();

        $allUsers = User::select('id', 'name')->get();

        $users     = User::select('id', 'name')->whereIn('id', $userIDs)->get();

        if ($request->ajax() && $request->pagination == null) {
            return response()->json([
                'tbody' => view('storewebsite::site-development.partials.data', compact('categories', 'users', 'website', 'allStatus', 'ignoredCategory', 'statusCount','allUsers'))->render(),
                'links' => (string) $categories->render(),
            ], 200);
        }

        return view('storewebsite::site-development.index', compact('categories', 'users', 'website', 'allStatus', 'ignoredCategory','statusCount','allUsers'));
    }

    public function addCategory(Request $request)
    {
        if ($request->text) {

            //Cross Check if title is present
            $categoryCheck = SiteDevelopmentCategory::where('title', $request->text)->first();

            if (empty($categoryCheck)) {
                //Save the Category
                $develop        = new SiteDevelopmentCategory;
                $develop->title = $request->text;
                $develop->save();

                return response()->json(["code" => 200, "messages" => 'Category Saved Sucessfully']);

            } else {

                return response()->json(["code" => 500, "messages" => 'Category Already Exist']);
            }

        } else {
            return response()->json(["code" => 500, "messages" => 'Please Enter Text']);
        }
    }

    public function addSiteDevelopment(Request $request)
    {


        if ($request->site) {
            $site = SiteDevelopment::find($request->site);
        } else {
            $site = new SiteDevelopment;
        }

        if ($request->type == 'title') {
            $site->title = $request->text;
        }

        if ($request->type == 'description') {
            $site->description = $request->text;
        }

        if ($request->type == 'status') {
            $site->status = $request->text;
        }

        if ($request->type == 'developer') {
            $site->developer_id = $request->text;
        }

        if ($request->type == 'designer_id') {
            $site->designer_id = $request->text;
        }

        if ($request->type == 'html_designer') {
            $site->html_designer = $request->text;
        }

        if ($request->type == 'tester_id') {
            $site->tester_id = $request->text;
        }

        if ($request->type == 'artwork_status') {
            $old_artwork = $site->artwork_status;
            if(!$old_artwork || $old_artwork == '') {
                $old_artwork = 'Yes';
            }
            $new_artwork = $request->text;
            $site->artwork_status = $request->text;
        }

        $site->site_development_category_id = $request->category;
        $site->website_id                   = $request->websiteId;
        $site->save();
        $html='';
        if ($request->type == 'status') {
            $id = $site->id;
            $siteDev =  SiteDevelopment::where('id',$id)->first();
            $status = ($siteDev) ? $siteDev->status : 0; 
            if($status==3){
                $html .= "<i class='fa fa-ban save-status' data-text='4' data-site=".$siteDev->id." data-category=".$siteDev->site_development_category_id."  data-type='status' aria-hidden='true' style='color:red;'' title='Deactivate'></i>";
            }elseif($status==4 || $status==0 ){
                $html .= "<i class='fa fa-ban save-status' data-text='3' data-site=".$siteDev->id." data-category=".$siteDev->site_development_category_id."  data-type='status' aria-hidden='true' style='color:black;' title='Activate'></i>";
            }
        }
        if ($request->type == 'artwork_status') {
            $history = new SiteDevelopmentArtowrkHistory;
            $history->date = date('Y-m-d');
            $history->site_development_id = $site->id;
            $history->from_status = $old_artwork;
            $history->to_status = $new_artwork;
            $history->username = Auth::user()->name;
            $history->save();
        }

        return response()->json(["code" => 200, "messages" => 'Site Development Saved Sucessfully','html'=>$html]);

    }

    public function getArtworkHistory($site_id) {
        $site = SiteDevelopment::find($site_id);
        $histories = [];
        if($site) {
            $histories = SiteDevelopmentArtowrkHistory::where('site_development_id',$site->id)->get();
        }
        return response()->json(["code" => 200, "data" => $histories]);
    }

    public function editCategory(Request $request)
    {

        $category = SiteDevelopmentCategory::find($request->categoryId);
        if ($category) {
            $category->title = $request->category;
            $category->save();
        }

        return response()->json(["code" => 200, "messages" => 'Category Edited Sucessfully']);
    }

    public function disallowCategory(Request $request)
    {
        $category         = $request->category;
        $store_website_id = $request->store_website_id;

        if ($category != null && $store_website_id != null) {
            if ($request->status) {
                \App\SiteDevelopmentHiddenCategory::where('store_website_id', $request->store_website_id)->where('category_id', $request->category)->delete();
            } else {
                $siteDevHiddenCat = \App\SiteDevelopmentHiddenCategory::updateOrCreate(
                    ['store_website_id' => $request->store_website_id, 'category_id' => $request->category],
                    ['store_website_id' => $request->store_website_id, 'category_id' => $request->category]
                );
            }
            return response()->json(["code" => 200, "data" => [], "message" => "Data updated Sucessfully"]);
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Required field missing like store website or category"]);
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
        $site      = null;
        $documents = $request->input('document', []);
        if (!empty($documents)) {
            if ($request->id) {
                $site = SiteDevelopment::find($request->id);
            }

            if (!$site || $request->id == null) {
                $site                               = new SiteDevelopment;
                $site->title                        = "";
                $site->description                  = "";
                $site->website_id                   = $request->store_website_id;
                $site->site_development_category_id = $request->site_development_category_id;
                $site->save();
            }

            foreach ($request->input('document', []) as $file) {
                $path  = storage_path('tmp/uploads/' . $file);
                $media = MediaUploader::fromSource($path)
                    ->toDirectory('site-development/' . floor($site->id / config('constants.image_per_folder')))
                    ->upload();
                $site->attachMedia($media, config('constants.media_tags'));
            }

            return response()->json(["code" => 200, "data" => [], "message" => "Done!"]);
        } else {
            return response()->json(["code" => 500, "data" => [], "message" => "No documents for upload"]);
        }

    }

    public function listDocuments(Request $request, $id)
    {
        $site = SiteDevelopment::find($request->id);

        $userList = [];

        if ($site->developer) {
            $userList[$site->developer->id] = $site->developer->name;
        }

        if ($site->designer) {
            $userList[$site->designer->id] = $site->designer->name;
        }

        $userList = array_filter($userList);
        // create the select box design html here
        $usrSelectBox = "";
        if (!empty($userList)) {
            $usrSelectBox = (string) \Form::select("send_message_to", $userList, null, ["class" => "form-control send-message-to-id"]);
        }

        $records = [];
        if ($site) {
            if ($site->hasMedia(config('constants.media_tags'))) {
                foreach ($site->getMedia(config('constants.media_tags')) as $media) {
                    $records[] = [
                        "id"        => $media->id,
                        'url'       => $media->getUrl(),
                        'site_id'   => $site->id,
                        'user_list' => $usrSelectBox,
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

    public function sendDocument(Request $request)
    {
        if ($request->id != null && $request->site_id != null && $request->user_id != null) {
            $media        = \Plank\Mediable\Media::find($request->id);
            $user         = \App\User::find($request->user_id);
            if ($user) {
                if ($media) {
                    \App\ChatMessage::sendWithChatApi(
                        $user->phone,
                        null,
                        "Please find attached file",
                        $media->getUrl()
                    );
                    return response()->json(["code" => 200, "message" => "Document send succesfully"]);
                }
            }else{
                return response()->json(["code" => 200, "message" => "User or site is not available"]);
            }
        }

        return response()->json(["code" => 200, "message" => "Sorry required fields is missing like id, siteid , userid"]);
    }

    public function remarks(Request $request, $id)
    {
        $response = \App\StoreDevelopmentRemark::join("users as u","u.id","store_development_remarks.user_id")->where("store_development_id",$id)
        ->select(["store_development_remarks.*",\DB::raw("u.name as created_by")])
        ->orderBy("store_development_remarks.created_at","desc")
        ->get();
        return response()->json(["code" => 200 , "data" => $response, 'site_id' => $id]);
    }

    public function saveRemarks(Request $request, $id)
    {
        \App\StoreDevelopmentRemark::create([
            "remarks" => $request->remark,
            "store_development_id" => $id,
            "user_id" => \Auth::user()->id,
        ]);

        $response = \App\StoreDevelopmentRemark::join("users as u","u.id","store_development_remarks.user_id")->where("store_development_id",$id)
        ->select(["store_development_remarks.*",\DB::raw("u.name as created_by")])
        ->orderBy("store_development_remarks.remarks","asc")
        ->get();
        return response()->json(["code" => 200 , "data" => $response]);

    }

    public function previewImage($site_id) {
        $site = SiteDevelopment::find($site_id);
        $records = [];
            if ($site) {
                // $userList = [];

                // if ($site->developer_id) {
                //     $userList[$site->publisher->id] = $site->publisher->name;
                // }

                // if ($site->designer_id) {
                //     $userList[$site->creator->id] = $site->creator->name;
                // }
                // if ($site->designer_id) {
                //     $userList[$site->creator->id] = $site->creator->name;
                // }
                // $userList = array_filter($userList);

                
                    if ($site->hasMedia(config('constants.media_tags'))) {
                        foreach ($site->getMedia(config('constants.media_tags')) as $media) {
                            $records[] = [
                                "id"        => $media->id,
                                'url'       => $media->getUrl(),
                                'site_id'   => $site->id
                            ];
                        }
                    }
            }
        $title = 'Preview images';
        return response()->json(["code" => 200 , "data" => $records, 'title' => $title]);
        // return view('content-management.preview-website-images', compact('title','records'));
    }

    public function latestRemarks($id) {

        $remarks = DB::select(DB::raw('select * from (SELECT max(store_development_remarks.id) as remark_id,remarks,site_development_categories.title,store_development_remarks.created_at,site_development_categories.id as category_id, users.name as username,
        store_development_remarks.store_development_id,site_developments.id as site_id,store_development_remarks.user_id, site_developments.title as sd_title, sw.website as sw_website
        FROM `store_development_remarks` inner join site_developments on site_developments.id = store_development_remarks.store_development_id inner join site_development_categories on site_development_categories.id = site_developments.site_development_category_id 
        left join store_websites as sw on sw.id = site_developments.website_id
        join users on users.id = store_development_remarks.user_id
        where site_developments.website_id = '.$id.' group by store_development_id) as latest join store_development_remarks on store_development_remarks.id = latest.remark_id order by title asc'));

        // $remarks = \App\StoreDevelopmentRemark::join('site_developments','site_developments.id','store_development_remarks.store_development_id')
        // ->join('site_development_categories','site_development_categories.id','site_developments.site_development_category_id')
        // ->orderBy('store_development_remarks.created_at','DESC')
        // ->groupBy('site_developments.site_development_category_id')
        // ->select('store_development_remarks.*','site_development_categories.title')->get();

        // $response = \App\StoreDevelopmentRemark::join("users as u","u.id","store_development_remarks.user_id")->where("store_development_id",$id)
        // ->select(["store_development_remarks.*",\DB::raw("u.name as created_by")])
        // ->orderBy("store_development_remarks.created_at","desc")
        // ->get();
        return response()->json(["code" => 200 , "data" => $remarks]);
    }

    public function allartworkHistory($website_id) {
            $histories = \App\SiteDevelopment::
            join("site_development_artowrk_histories","site_development_artowrk_histories.site_development_id","site_developments.id")
            ->join("site_development_categories","site_development_categories.id","site_developments.site_development_category_id")
            ->where('site_developments.website_id', $website_id)
            ->select("site_development_artowrk_histories.*",'site_development_categories.title')
            ->get();
        $title = 'Multi site artwork histories';
        return response()->json(["code" => 200 , "data" => $histories]);
    }
    public function taskCount($site_developement_id) {
        $taskStatistics['Devtask'] = DeveloperTask::where('site_developement_id',$site_developement_id)->where('status','!=','Done')->select();

        $query = DeveloperTask::join('users','users.id','developer_tasks.assigned_to')->where('site_developement_id',$site_developement_id)->where('status','!=','Done')->select('developer_tasks.id','developer_tasks.task as subject','developer_tasks.status','users.name as assigned_to_name');
        $query = $query->addSelect(DB::raw("'Devtask' as task_type,'developer_task' as message_type"));
        $taskStatistics = $query->get();
        //print_r($taskStatistics);
        $othertask = Task::where('site_developement_id',$site_developement_id)->whereNull('is_completed')->select(); 
        $query1 = Task::join('users','users.id','tasks.assign_to')->where('site_developement_id',$site_developement_id)->whereNull('is_completed')->select('tasks.id','tasks.task_subject as subject','tasks.assign_status','users.name as assigned_to_name');
        $query1 = $query1->addSelect(DB::raw("'Othertask' as task_type,'task' as message_type"));
        $othertaskStatistics = $query1->get();
        $merged = $othertaskStatistics->merge($taskStatistics);
        //print_r($merged);
        return response()->json(["code" => 200, "taskStatistics" => $merged]);

    }
    public function deleteDevTask(Request $request){

        $id   = $request->input( 'id' );
        if($request->tasktype=='Devtask'){
            $task = DeveloperTask::find( $id );
        }elseif($request->tasktype=='Othertask'){
            $task = Task::find( $id );
        }
		
		if($task) {
			$task->delete();
		}

		if ($request->ajax()) {
			return response()->json(["code" => 200]);
		}

	}
}
