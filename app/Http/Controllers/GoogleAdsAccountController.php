<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;
use stdClass;

class GoogleAdsAccountController extends Controller
{
    // show campaigns in main page
    public function index(Request $request)
    {
        /* $oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile(storage_path('adsapi_php.ini'))
            ->build();

        $session = (new AdWordsSessionBuilder())
            ->fromFile(storage_path('adsapi_php.ini'))
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

        $adWordsServices = new AdWordsServices();

        $campInfo = $this->getCampaigns($adWordsServices, $session); */
        $query=\App\GoogleAdsAccount::query();
        if($request->website){
			$query = $query->where('store_websites', $request->website);
		}
		if($request->accountname){
			$query = $query->where('account_name', 'LIKE','%'.$request->accountname.'%');
		}

        $googleadsaccount = $query->orderby('id','desc')->paginate(25)->appends(request()->except(['page']));
		if ($request->ajax()) {
            return response()->json([
                'tbody' => view('googleadsaccounts.partials.list-adsaccount', compact('googleadsaccount'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$googleadsaccount->render(),
                'count' => $googleadsaccount->total(),
            ], 200);
        }

        $store_website=\App\StoreWebsite::all();
        $totalentries = $googleadsaccount->count();
        return view('googleadsaccounts.index', ['googleadsaccount' => $googleadsaccount, 'totalentries' => $totalentries,'store_website'=>$store_website]);
    }
    
    public function createGoogleAdsAccountPage()
    {
        $store_website=\App\StoreWebsite::all();
        return view('googleadsaccounts.create',['store_website'=>$store_website]);
    }

    public function createGoogleAdsAccount(Request $request)
    {
        //create account
        $this->validate($request, [
            'account_name' => 'required',
            'store_websites' => 'required',
            'config_file_path' => 'required',
            'status' => 'required',
        ]);

        $accountArray = array(
            'account_name' => $request->account_name,
            'store_websites' => $request->store_websites,
            'notes' => $request->notes,
            'status' => $request->status,
        );
        $googleadsAc = \App\GoogleAdsAccount::create($accountArray);
        $account_id = $googleadsAc->id;
        if($request->file('config_file_path')){
            $uploadfile = MediaUploader::fromSource($request->file('config_file_path'))
                ->toDestination('adsapi', $account_id)
                ->upload();
            $getfilename = $uploadfile->filename . '.' . $uploadfile->extension;
            $googleadsAc->config_file_path = $getfilename;
            $googleadsAc->save();
        }
        return redirect()->to('/google-campaigns/ads-account')->with('actSuccess', 'GoogleAdwords account details added successfully');
    }

    public function editeGoogleAdsAccountPage($id)
    {
        $store_website=\App\StoreWebsite::all();
        $googleAdsAc=\App\GoogleAdsAccount::find($id);
        return view('googleadsaccounts.update',['account'=>$googleAdsAc,'store_website'=>$store_website]);
    }

    public function updateGoogleAdsAccount(Request $request)
    {
        $account_id = $request->account_id;
        //update account
        $this->validate($request, [
            'account_name' => 'required',
            'store_websites' => 'required',
            'status' => 'required',
        ]);

        $accountArray = array(
            'account_name' => $request->account_name,
            'store_websites' => $request->store_websites,
            'notes' => $request->notes,
            'status' => $request->status,
        );
        $googleadsAcQuery = New \App\GoogleAdsAccount;
        $googleadsAc=$googleadsAcQuery->find($account_id);
        if($request->file('config_file_path')){
            //find old one
            if(isset($googleadsAc->config_file_path) && $googleadsAc->config_file_path!="" && \Storage::disk('adsapi')->exists($account_id.'/'.$googleadsAc->config_file_path)){
                \Storage::disk('adsapi')->delete($account_id.'/'.$googleadsAc->config_file_path);
            }
            $uploadfile = MediaUploader::fromSource($request->file('config_file_path'))
                ->toDestination('adsapi', $account_id)
                ->upload();
            $getfilename = $uploadfile->filename . '.' . $uploadfile->extension;
            $accountArray['config_file_path'] = $getfilename;
        }
        $googleadsAc->fill($accountArray);
        $googleadsAc->save();
        return redirect()->to('/google-campaigns/ads-account')->with('actSuccess', 'GoogleAdwords account details added successfully');
    }
}
