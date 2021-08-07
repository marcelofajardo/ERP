<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\GoogleTranslate;
use App\Http\Controllers\Controller;
use App\Language;
use App\StoreWebsite;
use App\StoreWebsitePage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use seo2websites\MagentoHelper\MagentoHelper;
use Spatie\Activitylog\Models\Activity;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Pages | Store Website";

        $storeWebsites = StoreWebsite::all()->pluck("website", "id");
        $pages         = StoreWebsitePage::join("store_websites as  sw", "sw.id", "store_website_pages.store_website_id")
            ->select([\DB::raw("concat(store_website_pages.title,'-',sw.title) as page_name"), "store_website_pages.id"])
            ->pluck('page_name', 'id');

        $languages = Language::pluck('locale', 'code')->toArray(); //

        $languagesList = Language::pluck('name', 'name')->toArray(); //

        return view('storewebsite::page.index', [
            'title'         => $title,
            'storeWebsites' => $storeWebsites,
            'pages'         => $pages,
            'languages'     => $languages,
            'languagesList' => $languagesList,
        ]);
    }

    public function records(Request $request)
    {
        $pages = StoreWebsitePage::leftJoin('store_websites as sw', 'sw.id', 'store_website_pages.store_website_id');

        // Check for keyword search
        if ($request->keyword != null) {
            $pages = $pages->where(function ($q) use ($request) {
                $q->where("store_website_pages.title", "like", "%" . $request->keyword . "%")
                    ->orWhere("store_website_pages.content", "like", "%" . $request->keyword . "%");
            });
        }

        if ($request->language != null) {
            $pages = $pages->where("store_website_pages.language", $request->language);
        }

        if ($request->store_website_id != null) {
            $pages = $pages->where("store_website_pages.store_website_id", $request->store_website_id);
        }

        $pages = $pages->orderBy("store_website_pages.id","desc")->select(["store_website_pages.*", "sw.website as store_website_name"])->paginate();

        $items = $pages->items();

        $recItems = [];
        foreach($items as $item) {
            $attributes = $item->getAttributes();
            $attributes['stores_small'] = strlen($attributes['stores']) > 15 ? substr($attributes['stores'],0,15) : $attributes['stores'];
            $attributes['stores'] = $attributes['stores'];
            $recItems[] = $attributes;
        }


        return response()->json(["code" => 200, "data" => $recItems, "total" => $pages->total(),
            "pagination"                    => (string) $pages->links(),
        ]);
    }

    public function store(Request $request)
    {
        $post = $request->all();
        $id   = $request->get("id", 0);

        $params = [
            'title'    => 'required',
            'content'  => 'required',
            'language' => 'required',
            //'stores'           => 'required',
            //'store_website_id' => 'required',
        ];

        if (empty($id)) {
            $params['stores']           = 'required';
            $params['store_website_id'] = 'required';
        }

        $validator = Validator::make($post, $params);

        if ($validator->fails()) {
            $outputString = "";
            $messages     = $validator->errors()->getMessages();
            foreach ($messages as $k => $errr) {
                foreach ($errr as $er) {
                    $outputString .= "$k : " . $er . "<br>";
                }
            }
            return response()->json(["code" => 500, "error" => $outputString]);
        }

        $records = StoreWebsitePage::find($id);

        if (!$records) {
            $records = new StoreWebsitePage;
        }

        if (empty($id)) {
            $post["stores"]  = implode(",", $post["stores"]);
            $string          = str_replace(' ', '-', strtolower($post["title"])); // Replaces all spaces with hyphens.
            $string          = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
            $post["url_key"] = $string;
        }

        if (!empty($post['stores_str'])) {
            $post['stores'] = $post['stores_str'];
        }

        $records->fill($post);

        // if records has been save then call a request to push
        if ($records->save()) {
            //Logging activity
            if($id == 0) {
                activity()->causedBy(auth()->user())->performedOn($records)->log('page created');
            } else {
                activity()->causedBy(auth()->user())->performedOn($records)->log('page updated');
            }
            
        }

        return response()->json(["code" => 200, "data" => $records]);
    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $page = StoreWebsitePage::where("id", $id)->first();

        if ($page) {
            return response()->json(["code" => 200, "data" => $page]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $page = StoreWebsitePage::where("id", $id)->first();

        if ($page) {
            $page->delete();
            activity()->causedBy(auth()->user())->performedOn($page)->log('page deleted');
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

    public function push(Request $request, $id)
    {
        $page = StoreWebsitePage::where("id", $id)->first();

        if ($page) {
            \App\Jobs\PushPageToMagento::dispatch($page)->onQueue('magetwo');
            return response()->json(["code" => 200, 'message' => "Website send for push"]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

    public function pull(Request $request, $id)
    {
        $page = StoreWebsitePage::where("id", $id)->first();

        if ($page) {
            $website = $page->storeWebsite;
            $data = MagentoHelper::pullWebsitePage($website);
            if (!empty($data)) {
                foreach ($data as $key => $d) {
                    if($page->platform_id == $d->id) {
                        activity()->causedBy(auth()->user())->performedOn($page)->log('page pulled');
                        $page->title            = isset($d->title) ? $d->title : "";
                        $page->url_key          = isset($d->identifier) ? $d->identifier : "";
                        $page->layout           = isset($d->page_layout) ? $d->page_layout : "";
                        $page->meta_title       = isset($d->meta_title) ? $d->meta_title : "";
                        $page->meta_keywords    = isset($d->meta_keywords) ? $d->meta_keywords : "";
                        $page->meta_description = isset($d->meta_description) ? $d->meta_description : "";
                        $page->content_heading  = isset($d->content_heading) ? $d->content_heading : "";
                        $page->content          = isset($d->content) ? $d->content : "";
                        $page->created_at       = isset($d->creation_time) ? $d->creation_time : "";
                        $page->updated_at       = isset($d->update_time) ? $d->update_time : "";
                        $page->save();                        
                    }
                }
            }
            return response()->json(["code" => 200, 'message' => "Website send for pull"]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

    public function getStores(Request $request, $id)
    {
        $stores = \App\StoreWebsite::join("websites as w", "w.store_website_id", "store_websites.id")
            ->join("website_stores as ws", "ws.website_id", "w.id")
            ->join("website_store_views as wsv", "wsv.website_store_id", "ws.id")
            ->where("w.store_website_id", $id)
            ->select("wsv.*")
            ->get();

        return response()->json(["code" => 200, "stores" => $stores]);
    }

    public function loadPage(Request $request, $id)
    {
        $page = \App\StoreWebsitePage::find($id);

        if ($page) {

            $language = $request->language;

            // do by lanuage
            if (!empty($language)) {

                $translateDescription = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                    new GoogleTranslate,
                    $language,
                    [$page->content]
                );

                return response()->json(["code" => 200, "content" => !empty($translateDescription) ? $translateDescription : $page->content]);

            } else {
                return response()->json(["code" => 200, "content" => $page->content, "meta_title" => $page->meta_title, "meta_keyword" => $page->meta_keywords, "meta_desc" => $page->meta_description]);
            }
        }

    }

    public function copyTo(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'page'    => 'required',
            'to_page' => 'different:page',
        ]);

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $page = StoreWebsitePage::where("id", $request->page )->first();

        if ($page) {

            if( ! empty( $request->to_page ) || ! empty( $request->site_urls ) ){
                $updateData = [];

                if($request->cttitle == 'true'){
                    $updateData['meta_title'] = $page->meta_title;
                }
                if($request->ctkeyword  == 'true'){
                    $updateData['meta_keywords'] = $page->meta_keywords;
                }
                if($request->ctdesc  == 'true'){
                    $updateData['meta_description'] = $page->meta_description;
                }
                if( $updateData ){
                    if( $request->to_page ){
                        StoreWebsitePage::where("id", $request->to_page )->update( $updateData );
                    }
                    if( $request->site_urls == 'true' ){
                        StoreWebsitePage::where("url_key", $page->url_key )->update( $updateData );
                    }
                    return response()->json(["code" => 200, "success" => 'Success']);
                }
            }
        }
        return response()->json(["code" => 200, "error" => 'Page not found']);
    }

    public function pageHistory(Request $request, $page)
    {
        $histories = \App\StoreWebsitePageHistory::where('store_website_page_id', $page)->latest()->get();

        foreach ($histories as $h => $history) {
            # code...
            $history->user;
        }

        return response()->json(["code" => 200, "data" => $histories]);
    }

    public function pageActivities(Request $request, $page) {
        $activities = Activity::with('causer')->where('subject_id', $page)->latest()->get();
        return response()->json(["code" => 200, "data" => $activities]);
    }

    public function translateForOtherLanguage(Request $request, $id)
    {
        $page = \App\StoreWebsitePage::find($id);

        $errorMessage = [];

        if ($page) {
            // find the language all active and then check that record page is exist or not
            $languages = \App\Language::where("status", 1)->get();
            foreach ($languages as $l) {
                if (strtolower($page->language) != strtolower($l->name)) {
                    $pageExist = \App\StoreWebsitePage::where("url_key", $page->url_key)->where("store_website_id", $page->store_website_id)->where("language", $l->name)->first();
                    if (!$pageExist) {

                        $newPage = new \App\StoreWebsitePage;

                        $title = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                            new GoogleTranslate,
                            $l->locale,
                            [$page->title]
                        );

                        $metaTitle = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                            new GoogleTranslate,
                            $l->locale,
                            [$page->meta_title]
                        );

                        $metaKeywords = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                            new GoogleTranslate,
                            $l->locale,
                            [$page->meta_keywords]
                        );

                        $metaDescription = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                            new GoogleTranslate,
                            $l->locale,
                            [$page->meta_description]
                        );

                        $contentHeading = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                            new GoogleTranslate,
                            $l->locale,
                            [$page->content_heading]
                        );

                        $content = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                            new GoogleTranslate,
                            $l->locale,
                            [$page->content]
                        );

                        // assign the stores  column
                        $fetchStores = \App\WebsiteStoreView::where('website_store_views.name', $l->name)
                            ->join("website_stores as ws", "ws.id", "website_store_views.website_store_id")
                            ->join("websites as w", "w.id", "ws.website_id")
                            ->where("w.store_website_id", $page->store_website_id)
                            ->select("website_store_views.*")
                            ->get();


                        $stores = [];
                        if (!$fetchStores->isEmpty()) {
                            foreach ($fetchStores as $fetchStore) {
                                $stores[] = $fetchStore->code;
                            }
                        }

                        $newPage->title            = !empty($title) ? $title : $page->title;
                        $newPage->meta_title       = !empty($metaTitle) ? $metaTitle : $page->meta_title;
                        $newPage->meta_keywords    = !empty($metaKeywords) ? $metaKeywords : $page->meta_keywords;
                        $newPage->meta_description = !empty($metaDescription) ? $metaDescription : $page->meta_description;
                        $newPage->content_heading  = !empty($contentHeading) ? $contentHeading : $page->content_heading;
                        $newPage->content          = !empty($content) ? $content : $page->content;
                        $newPage->layout           = $page->layout;
                        $newPage->url_key          = $page->url_key;
                        $newPage->active           = $page->active;
                        $newPage->stores           = implode(",", $stores);
                        $newPage->store_website_id = $page->store_website_id;
                        $newPage->language         = $l->name;
                        $newPage->copy_page_id     = $page->id;
                        $newPage->save();

                        activity()->causedBy(auth()->user())->performedOn($page)->log('page translated to ' . $l->name);
                        activity()->causedBy(auth()->user())->performedOn($newPage)->log('Parent Page Title:' . $newPage->title . ' Page URL Key:'. $newPage->url_key);
                    }else{
                        $errorMessage[] = "Page not pushed because of page already copied to {$pageExist->url_key} for {$l->name}";
                    }
                }
            }

            return response()->json(["code" => 200, "data" => [], "message" => "Records copied succesfully","errorMessage" => implode("<br>",$errorMessage)]);
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Page does not exist"]);

    }

    public function pushWebsiteInLive (Request $request, $id)
    {
        $pages = \App\StoreWebsitePage::where("store_website_id",$id)->get();
        activity()->causedBy(auth()->user())->log('pages pushed');
        if(!$pages->isEmpty()) {
            foreach($pages as $page) {
                \App\Jobs\PushPageToMagento::dispatch($page)->onQueue('magetwo');
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Page does not exist"]);

    }

    public function pullWebsiteInLive (Request $request, $id)
    {   
        $website = \App\StoreWebsite::where("id", $id)->where("api_token", "!=", "")->where('remote_software', '2')->where("website_source", "magento")->first();
        if ($website) {
            activity()->causedBy(auth()->user())->log('pages pulled');
            $data = MagentoHelper::pullWebsitePage($website);
            if (!empty($data)) {
                foreach ($data as $key => $d) {
                    $pages = \App\StoreWebsitePage::where("store_website_id", $website->id)->where('platform_id', $d->id)->first();
                    if (!$pages) {
                        $pages = new \App\StoreWebsitePage;
                    }

                    $pages->store_website_id = $website->id;
                    $pages->platform_id      = $d->id;
                    $pages->title            = isset($d->title) ? $d->title : "";
                    $pages->url_key          = isset($d->identifier) ? $d->identifier : "";
                    $pages->layout           = isset($d->page_layout) ? $d->page_layout : "";
                    $pages->meta_title       = isset($d->meta_title) ? $d->meta_title : "";
                    $pages->meta_keywords    = isset($d->meta_keywords) ? $d->meta_keywords : "";
                    $pages->meta_description = isset($d->meta_description) ? $d->meta_description : "";
                    $pages->content_heading  = isset($d->content_heading) ? $d->content_heading : "";
                    $pages->content          = isset($d->content) ? $d->content : "";
                    $pages->created_at       = isset($d->creation_time) ? $d->creation_time : "";
                    $pages->updated_at       = isset($d->update_time) ? $d->update_time : "";

                    $pages->save();

                }
            }
            return response()->json(["code" => 200, "data" => [], "message" => "Website pages pulled successfully!"]);
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Page does not exist"]);

    }

    public function histories(Request $request)
    {
        $title = "Page History";

        $storeWebsites = \App\StoreWebsite::pluck("title", "id")->toArray();    

        $records = StoreWebsitePage::join("store_website_page_histories as swh","swh.store_website_page_id","store_website_pages.id")
        ->join("store_websites as sw","sw.id","store_website_pages.store_website_id")
        ->select(["swh.*","sw.id as store_website_id","sw.title as store_website_name","store_website_pages.url_key"])
        ->orderBy("swh.id","desc");

        if($request->store_website_id != null) {
            $records =  $records->where("store_website_id",$request->store_website_id);
        }

        if($request->keyword != null) {
            $records =  $records->where(function($q) use($request) {
                return $q->where("store_website_pages.url_key","like","%".$request->keyword."%");
            });
        }


        $records =  $records->paginate();

        return view("storewebsite::page.histories",compact('records','title','storeWebsites'));
    }

    public function store_platform_id() {
        $page = StoreWebsitePage::find(request()->page_id);
        $old = $page->platform_id;
        $page->platform_id = request()->platform_id;
        if($page->save()) {
            activity()->causedBy(auth()->user())->performedOn($page)->log('page platform id updated from ' .$old. ' to '. request()->platform_id);
            return "success";
        } else {
            return "failed";
        }
    }

}
