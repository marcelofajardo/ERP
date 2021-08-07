<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StoreWebsiteAnalytic;
use App\StoreWebsite;
use Illuminate\Support\Facades\Validator;
use Storage;
use File;

class StoreWebsiteAnalyticsController extends Controller
{

    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     session()->flash('active_tab','blogger_list_tab');
        //     return $next($request);
        // });
    }

    public function index()
    {
        try {
            $storeWebsiteAnalyticsData = StoreWebsiteAnalytic::all();
            return view('store-website-analytics.index', compact('storeWebsiteAnalyticsData'));
        } catch (Exception $e) {
            \Log::error('Account page ::'. $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        if($request->post()){
            $rules = [
                'website' => 'required',
                'account_id' => 'required',
                'view_id' => 'required',
                'store_website_id' => 'required|integer',
            ];

            //validation for googles service account json file for google analytics
            if (!$request->id) {
                $rules['google_service_account_json'] = 'required|file';
            }else{
                //$rules['google_service_account_json'] = 'file|mimetypes:application/json';
            }

            $messages = [
               'website' => 'Website field is required.',
               'account_id' => 'Account Id field is required.',
               'view_id' => 'View Id field is required.',
               'store_website_id' => 'Store Id field is required.',
               'store_website_id' => 'Store Id value must be a number.',
               'google_service_account_json' => 'Please Upload Valid Google Service Account Json File.',
            ];

            $validation = validator(
               $request->all(),
               $rules,
               $messages
            );
            //If validation fail send back the Input with errors
            if($validation->fails()) {
                //withInput keep the users info
                return redirect()->back()->withErrors($validation)->withInput();
            } else {

                //file upload code for googles service account json file for google analytics
                $filename = '';
                if ($request->hasFile('google_service_account_json')) {
                    $GoogleServiceAccountJsonFile = $request->file('google_service_account_json');
                    $extension = $GoogleServiceAccountJsonFile->getClientOriginalExtension();
                    $filename = $request->view_id.$GoogleServiceAccountJsonFile->getFilename().'.'.$extension;
                    // file will be uploaded to resources/assets/analytics_files
                    Storage::disk('analytics_files')->put($filename, File::get($GoogleServiceAccountJsonFile));
                }

                if($request->id){
                    $updatedData = $request->all();
                    unset($updatedData['_token']);
                    // save uploaded googles service account json file name
                    $updatedData['google_service_account_json'] = $filename;
                    StoreWebsiteAnalytic::whereId($request->id)->update($updatedData);
                    return redirect()->to('/store-website-analytics/index')->with('success','Store Website Analytics updated successfully.');
                }else{
                    $insertData = $request->all();
                    // save uploaded googles service account json file name
                    $insertData['google_service_account_json'] = $filename;
                    StoreWebsiteAnalytic::create($insertData);
                    return redirect()->to('/store-website-analytics/index')->with('success','Store Website Analytics saved successfully.');
                }
            }
        }else{
            $storeWebsites = StoreWebsite::where('deleted_at',null)->get();
            return view('store-website-analytics.create',compact('storeWebsites'));
        }
    }

    public function edit($id = null)
    {
        $storeWebsiteAnalyticData = StoreWebsiteAnalytic::whereId($id)->first();
        $storeWebsites = StoreWebsite::where('deleted_at',null)->get();
        return view('store-website-analytics.edit',compact('storeWebsiteAnalyticData','storeWebsites'));

    }

    public function delete($id = null)
    {
        StoreWebsiteAnalytic::whereId($id)->delete();
        return redirect()->to('/store-website-analytics/index')->with('success','Record deleted successfully.');
    }

    public function report($id = null) 
    {
        $reports = \App\ErpLog::where('model',\App\StoreWebsiteAnalytic::class)->orderBy("id","desc")->where("model_id",$id)->get();
        return view("store-website-analytics.reports",compact('reports'));
    }

}
