<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\Http\Controllers\Controller;
use App\StoreWebsite;
use App\Website;
use App\WebsiteStore;
use App\WebsiteStoreView;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Website | Store Website";

        $storeWebsites = StoreWebsite::all()->pluck("website", "id");
        $countries     = \App\SimplyDutyCountry::pluck('country_name', 'country_code')->toArray();

        return view('storewebsite::website.index', [
            'title'         => $title,
            'storeWebsites' => $storeWebsites,
            'countries'     => $countries,
        ]);
    }

    public function records(Request $request)
    {
        $websites = Website::leftJoin('store_websites as sw', 'sw.id', 'websites.store_website_id');

        // Check for keyword search
        if ($request->keyword != null) {
            $websites = $websites->where(function ($q) use ($request) {
                $q->where("websites.name", "like", "%" . $request->keyword . "%")
                    ->orWhere("websites.code", "like", "%" . $request->keyword . "%");
            });
        }

        if ($request->store_website_id != null) {
            $websites = $websites->where("websites.store_website_id", $request->store_website_id);
        }

        if ($request->is_finished != null) {
            $websites = $websites->where("websites.is_finished", $request->is_finished);
        }

        $websites = $websites->select(["websites.*", "sw.website as store_website_name"])->orderBy('websites.name', "asc")->paginate();

        $items = $websites->items();

        foreach ($items as $k => &$item) {
            if (!empty($item->countries) && $item->countries != "null") {
                $item->countires_str = implode(",", json_decode($item->countries));
            } else {
                $item->countires_str = "";
            }
        }

        return response()->json(["code" => 200, "data" => $items, "total" => $websites->total(), "pagination" => (string) $websites->render()]);
    }

    public function store(Request $request)
    {
        $post      = $request->all();
        $validator = Validator::make($post, [
            'name'             => 'required',
            'code'             => 'required',
            'store_website_id' => 'required',
        ]);

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

        $id = $request->get("id", 0);

        $records = Website::find($id);

        if (!$records) {
            $records = new Website;
        }

        if (!empty($request->countries) && $request->countries != "null") {
            $records->countries = json_encode($request->countries);
        }
        $post['code'] = replace_dash($post['code']);

        $records->fill($post);

        // if records has been save then call a request to push
        if ($records->save()) {

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
        $website = Website::where("id", $id)->first();

        if ($website) {
            if (!empty($website->countries) && $website->countries != "null") {
                $website->countries = json_decode($website->countries);
            } else {
                $website->countries = [];
            }

            $countries = \App\SimplyDutyCountry::pluck('country_name', 'country_code')->toArray();

            $form = (string) \Form::select('countries[]', $countries, $website->countries, ["class" => "form-control select-2", "multiple" => true]);

            return response()->json(["code" => 200, "data" => $website, 'form' => $form]);
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
        $website = Website::where("id", $id)->first();

        if ($website) {
            $stores = $website->stores;
            if (!$stores->isEmpty()) {
                foreach ($stores as $store) {
                    $storeViews = $store->storeView;
                    if (!$storeViews->isEmpty()) {
                        foreach ($storeViews as $storeView) {
                            $storeView->delete();
                        }
                    }
                    $store->delete();
                }
            }

            $website->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

    public function push(Request $request, $id)
    {
        $website = Website::where("id", $id)->first();

        if ($website) {

            \App\Jobs\PushWebsiteToMagento::dispatch($website)->onQueue('mageone');
            return response()->json(["code" => 200, 'message' => "Website send for push"]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

    public function createDefaultStores(Request $request)
    {
        $storeWebsiteId = $request->store_website_id;

        if (!empty($storeWebsiteId)) {

            // check if there is country code set if yes then
            $codes = $request->get("country_codes");
            if (!empty($codes)) {
                $codes     = explode(",", $codes);
                $countries = \App\SimplyDutyCountry::whereIn('country_code', $codes)->pluck('country_name', 'country_code')->toArray();
            } else {
                $countries = \App\SimplyDutyCountry::pluck('country_name', 'country_code')->toArray();
            }

            if (!empty($countries)) {
                foreach ($countries as $k => $c) {
                    $website = Website::where('countries', 'like', '%"' . $k . '"%')->where('store_website_id', $storeWebsiteId)->first();
                    if (!$website) {
                        $website                   = new Website;
                        $website->name             = $c;
                        $website->code             = replace_dash($k);
                        $website->countries        = json_encode([$k]);
                        $website->store_website_id = $storeWebsiteId;
                        if ($website->save()) {
                            $websiteStore             = new WebsiteStore;
                            $websiteStore->name       = $c . " Store";
                            $websiteStore->code       = replace_dash($k) . "_store";
                            $websiteStore->website_id = $website->id;
                            $websiteStore->save();
                            if ($websiteStore->save()) {
                                $websiteStoreView                   = new WebsiteStoreView;
                                $websiteStoreView->name             = "English";
                                $websiteStoreView->code             = replace_dash(strtolower($k . "_en"));
                                $websiteStoreView->website_store_id = $websiteStore->id;
                                $websiteStoreView->save();
                            }
                        }
                    }
                }

                return response()->json(["code" => 200, "data" => $website, "message" => "Request created successfully"]);
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Your request couldn't meet criteria , website can not be created"]);

    }

    public function moveStores(Request $request)
    {
        $storeWebsiteId = $request->store_website_id;
        $ids            = $request->ids;
        $groupName      = $request->group_name;

        if (!empty($storeWebsiteId) && !empty($ids)) {

            $countries = [];
            $websites  = Website::whereIn('id', $ids)->get();

            if (!$websites->isEmpty()) {
                foreach ($websites as $website) {
                    $ct = json_decode($website->countries, true);
                    if (!empty($ct)) {
                        foreach ($ct as $c) {
                            $countries[] = $c;
                        }
                    }
                    $stores = $website->stores;
                    if (!$stores->isEmpty()) {
                        foreach ($stores as $s) {
                            $storeViews = $s->storeView;
                            if (!$storeViews->isEmpty()) {
                                foreach ($storeViews as $key => $m) {
                                    $m->delete();
                                }
                            }
                            $s->delete();
                        }
                    }

                    $website->delete();
                }
            }

            // need to create a group based on given countires
            $slug                      = preg_replace('/\s+/', '_', strtolower($groupName));
            $website                   = new Website;
            $website->name             = $groupName;
            $website->code             = replace_dash($slug);
            $website->countries        = json_encode($countries);
            $website->store_website_id = $storeWebsiteId;
            if ($website->save()) {
                $websiteStore             = new WebsiteStore;
                $websiteStore->name       = $groupName;
                $websiteStore->code       = replace_dash($slug) . "_store";
                $websiteStore->website_id = $website->id;
                $websiteStore->save();
                if ($websiteStore->save()) {
                    $websiteStoreView                   = new WebsiteStoreView;
                    $websiteStoreView->name             = "English";
                    $websiteStoreView->code             = replace_dash(strtolower($slug . "_en"));
                    $websiteStoreView->website_store_id = $websiteStore->id;
                    $websiteStoreView->save();
                }

                return response()->json(["code" => 200, "data" => $website, "message" => "Request created successfully"]);
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Your request couldn't meet criteria , website can not be created"]);

    }

    public function copyStores(Request $request)
    {
        $storeWebsiteId = $request->store_website_id;
        $copyID         = $request->copy_id;

        if (!empty($copyID)) {

            $cWebsite = Website::find($copyID);

            if ($cWebsite->store_website_id != $storeWebsiteId) {
                if ($cWebsite) {

                    $website                   = new Website;
                    $website->name             = $cWebsite->name;
                    $website->code             = replace_dash($cWebsite->code);
                    $website->countries        = $cWebsite->countries;
                    $website->store_website_id = $storeWebsiteId;

                    if ($website->save()) {
                        // star to push into store
                        $cStores = $cWebsite->stores;
                        if (!$cStores->isEmpty()) {
                            foreach ($cStores as $cStore) {
                                $store             = new WebsiteStore;
                                $store->name       = $cStore->name;
                                $store->code       = replace_dash($cStore->code);
                                $store->website_id = $website->id;
                                if ($store->save()) {
                                    $cStoreViews = $cStore->storeView;
                                    if (!$cStoreViews->isEmpty()) {
                                        foreach ($cStoreViews as $cStoreView) {
                                            $storeView                   = new WebsiteStoreView;
                                            $storeView->name             = $cStoreView->name;
                                            $storeView->code             = replace_dash($cStoreView->code);
                                            $storeView->website_store_id = $store->id;
                                            $storeView->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                return response()->json(["code" => 200, "data" => [], "message" => "Copied has been finished successfully"]);
            } else {
                return response()->json(["code" => 500, "data" => [], "error" => "Copy Store Website and current store website can not be same"]);
            }

        }

        return response()->json(["code" => 500, "data" => [], "error" => "Copy field or Store Website id is not selected"]);

    }

    public function changeStatus(Request $request)
    {
        $id    = $request->get("id");
        $value = $request->get("value");

        if (!empty($id)) {
            $website = Website::find($id);
            if ($website) {
                $website->is_finished = ($value == 1) ? 1 : 0;
                $website->save();
            }
        }

        return response()->json(["code" => 200, "data" => [], "message" => "Status updated successfully"]);

    }

    public function changePriceOvveride(Request $request)
    {
        $id    = $request->get("id");
        $value = $request->get("value");

        $website = null;

        if (!empty($id)) {
            $website = Website::find($id);
            if ($website) {
                $website->is_price_ovveride = $value;
                $website->save();
            }
        }

        return response()->json(["code" => 200, "data" => $website, "message" => "Price ovveride updated successfully"]);

    }

    public function copyWebsites(Request $request)
    {
        $storeWebsiteId = $request->store_website_id;
        $ids            = $request->ids;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $cWebsite = Website::find($id);

                if ($cWebsite->store_website_id != $storeWebsiteId) {
                    if ($cWebsite) {

                        $website                   = new Website;
                        $website->name             = $cWebsite->name;
                        $website->code             = replace_dash($cWebsite->code);
                        $website->countries        = $cWebsite->countries;
                        $website->store_website_id = $storeWebsiteId;

                        if ($website->save()) {
                            // star to push into store
                            $cStores = $cWebsite->stores;
                            if (!$cStores->isEmpty()) {
                                foreach ($cStores as $cStore) {
                                    $store             = new WebsiteStore;
                                    $store->name       = $cStore->name;
                                    $store->code       = replace_dash($cStore->code);
                                    $store->website_id = $website->id;
                                    if ($store->save()) {
                                        $cStoreViews = $cStore->storeView;
                                        if (!$cStoreViews->isEmpty()) {
                                            foreach ($cStoreViews as $cStoreView) {
                                                $storeView                   = new WebsiteStoreView;
                                                $storeView->name             = $cStoreView->name;
                                                $storeView->code             = replace_dash($cStoreView->code);
                                                $storeView->website_store_id = $store->id;
                                                $storeView->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }

            return response()->json(["code" => 200, "data" => [], "message" => "Copied has been finished successfully"]);
        }
        return response()->json(["code" => 500, "data" => [], "error" => "Copy field or Store Website id is not selected"]);
    }

    public function pushStores(Request $request, $id)
    {
        $allWebsites = Website::where("store_website_id", $id)->get();

        if (!$allWebsites->isEmpty()) {
            foreach ($allWebsites as $website) {
                \App\Jobs\PushWebsiteToMagento::dispatch($website)->onQueue('mageone');
            }
        }

        return response()->json(["code" => 200, "data" => [], "message" => "All Requested pushed successfully"]);
    }

    public function copyWebsiteStructure(Request $request, $id)
    {
        $storeWebsiteId = $id;
        $copyStoreWebsiteID  = $request->to_store_website_id;

        if (!empty($copyStoreWebsiteID)) {
            $allWebsites = Website::where("store_website_id", $storeWebsiteId)->get();
            if(!$allWebsites->isEmpty()) {
                foreach ($allWebsites as $key => $cWebsite) {
                    $isExist = Website::where("code", replace_dash($cWebsite->code))->where('store_website_id',$copyStoreWebsiteID)->first();
                    if($isExist) {
                        continue;
                    }

                    $website                   = new Website;
                    $website->name             = $cWebsite->name;
                    $website->code             = replace_dash($cWebsite->code);
                    $website->countries        = $cWebsite->countries;
                    $website->store_website_id = $copyStoreWebsiteID;

                    if ($website->save()) {
                        // star to push into store
                        $cStores = $cWebsite->stores;
                        if (!$cStores->isEmpty()) {
                            foreach ($cStores as $cStore) {
                                $store             = new WebsiteStore;
                                $store->name       = $cStore->name;
                                $store->code       = replace_dash($cStore->code);
                                $store->website_id = $website->id;
                                if ($store->save()) {
                                    $cStoreViews = $cStore->storeView;
                                    if (!$cStoreViews->isEmpty()) {
                                        foreach ($cStoreViews as $cStoreView) {
                                            $storeView                   = new WebsiteStoreView;
                                            $storeView->name             = $cStoreView->name;
                                            $storeView->code             = replace_dash($cStoreView->code);
                                            $storeView->website_store_id = $store->id;
                                            $storeView->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return response()->json(["code" => 200, "data" => [], "message" => "Copied has been finished successfully"]);
        }
        return response()->json(["code" => 500, "data" => [], "error" => "Copy field or Store Website id is not selected"]);
    }
}
