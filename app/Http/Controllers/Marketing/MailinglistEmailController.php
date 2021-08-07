<?php

namespace App\Http\Controllers\Marketing;

use App\GmailData;
use App\Image;
use App\Mailinglist;
use App\MailinglistEmail;
use App\MailinglistTemplate;
use App\MailingTemplateFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use qoraiche\mailEclipse\mailEclipse;
use View;

class MailinglistEmailController extends Controller
{
    public function index () {

        $audience = Mailinglist::all();
        $templates = MailinglistTemplate::all();
        $images = Image::all();
        $images_gmail = GmailData::all();
        $mailings = MailinglistEmail::with('audience','template')->orderBy('created_at','desc')->get();

        return view('marketing.mailinglist.sending-email.index',compact('audience', 'templates','images','images_gmail','mailings'));
    }

    public function ajaxIndex (Request $request) {
        $data = $request->all();
        $content = null;

        $mtemplate = MailinglistTemplate::find($request->id);
        if(!empty($mtemplate)) {
            $content  = @(string)view($mtemplate->mail_tpl);
        }

        /*$template_html = MailingTemplateFile::where('mailing_id',$request->id)->where("path", "like", "%index.html%")->first();
        if($template_html){
            $content = file_get_contents($template_html->path);
        }*/

        return response()->json([ 'template_html' => $content]);
    }

    public function store (Request $request) {
        $data = $request->all();


       $validator = Validator::make($request->all(), [
            'template_id' => 'required',
            'scheduled_date' => 'required',
            'mailinglist_id' => 'required',
            'subject' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->getMessageBag()->toArray()]);
        }
        //getting mailing list 

        $mailing_item = new MailinglistEmail();
        $mailing_item->mailinglist_id = $data['mailinglist_id'];
        $mailing_item->template_id = $data['template_id'] ;
        $mailing_item->html = $data['html'];
        $mailing_item->subject = $data['subject'];
        $mailing_item->scheduled_date =$data['scheduled_date'];
        $mailing_item->html = $data['html'];

        $list = Mailinglist::find($data['mailinglist_id']);

        if($list->service){
            if($list->service && isset($list->service->name) ){
            if($list->service->name == 'AcelleMail'){
                
                $curl = curl_init();

                curl_setopt_array($curl, array(
                //   CURLOPT_URL => "http://165.232.42.174/api/v1/campaign/create/".$list->remote_id."?api_token=".getenv('ACELLE_MAIL_API_TOKEN'),
                CURLOPT_URL => "http://165.232.42.174/api/v1/campaign/create/".$list->remote_id."?api_token=".config('env.ACELLE_MAIL_API_TOKEN'),
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => array('name' => $mailing_item->subject,'subject' => $mailing_item->subject , 'run_at' => $mailing_item->scheduled_date , 'template_content' => $mailing_item->html),
                ));

                $response = curl_exec($curl);   
                $response = json_decode($response);
                
                if(!empty($response->campaign)){
                    $mailing_item->api_template_id = $response->campaign;
                }
            }else{
                if(!empty($data['html'])){
                    $curl = curl_init();
                    $data = [
                        "sender" => array(
                            'name' => 'Luxury Unlimited',
                            'id' => 1,
                        ),
                        "htmlContent" => $this->utf8ize($mailing_item->html),
                        "templateName" =>  $mailing_item->subject,
                        'subject'=> $mailing_item->subject
                    ];
                    
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.sendinblue.com/v3/smtp/templates",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($data),
                        CURLOPT_HTTPHEADER => array(
                            // "api-key: ".getenv('SEND_IN_BLUE_API'),
                            "api-key: ".config('env.SEND_IN_BLUE_API'),
                            "Content-Type: application/json"
                        ),
                    ));
                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    if(!empty($response->id)){
                        $mailing_item->api_template_id = $response->id;
                    }
                    curl_close($curl);
                }
            }
        }
        

        
        }
        $mailing_item->save();

        return response()->json([
            'item' => view('partials.mailing-template.template',[
                'item' => $mailing_item
            ])->render(),
        ]);
    }

    public function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    public function show (Request $request) {
/*        dd($request->id);*/

        $data = MailinglistEmail::where("id", $request->id)->first();
        return response()->json([
            'html'=>$data
        ]);

    }

    public function duplicate (Request $request) {
        /*        dd($request->id);*/


        $data = MailinglistEmail::where("id", $request->id)->first();


        return response()->json([
            'html'=>$data
        ]);

    }


    public function getStats(Request $request)
    {
       dd($request);
    }
}
