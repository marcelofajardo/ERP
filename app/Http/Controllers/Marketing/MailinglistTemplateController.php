<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\MailinglistTemplate;
use App\MailinglistTemplateCategory;
use App\StoreWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Qoraiche\MailEclipse\MailEclipse;
use View;

class MailinglistTemplateController extends Controller
{
    public function index()
    {

        $mailings = MailinglistTemplate::with('category', 'storeWebsite')->paginate(20);

        // get first all mail class
       /* $mailEclipse = mailEclipse::getMailables();
        $rLstMails   = [];
        if (!empty($mailEclipse)) {
            foreach ($mailEclipse as $lms) {
                $rLstMails[$lms["namespace"]] = $lms["name"];
            }
        }*/

        // get all templates for mail
        $mailEclipseTpl = mailEclipse::getTemplates();
        $rViewMail      = [];
        if (!empty($mailEclipseTpl)) {
            foreach ($mailEclipseTpl as $mTpl) {
                $v             = mailEclipse::$view_namespace . '::templates.' . $mTpl->template_slug;
                $rViewMail[$v] = $mTpl->template_name . " [" . $mTpl->template_description . "]";
            }
        }

        $MailingListCategory = MailinglistTemplateCategory::select('id', 'title as name')
            ->get()
            ->pluck('name','id')
            ->toArray();

        $storeWebSites = StoreWebsite::select('id', 'title')
            ->get()
            ->pluck('title','id')
            ->toArray();

        return view("marketing.mailinglist.templates.index", compact('mailings', 'rViewMail', 'MailingListCategory', 'storeWebSites'));

    }
    public function ajax(Request $request)
    {

        $query = MailinglistTemplate::query();

        if ($request->term) {
            $query->where('name', 'LIKE', '%' . $request->term . '%')
                ->orWhere('mail_class', 'LIKE', '%' . $request->term . '%')
                ->orWhere('mail_tpl', 'LIKE', '%' . $request->term . '%');
        }
        if ($request->date) {
            $query->where('created_at', 'LIKE', '%' . $request->date . '%');
        }
        $query = $query->get();

        return response()->json([
            'mailings' => view('partials.mailing-template.list', [
                'mailings' => $query,
            ])->render(),
        ]);

    }
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'name'       => 'required|string',
            //'mail_class' => 'required|string',
            'mail_tpl'   => 'required|string',
            //'image_count' => 'required|numeric',
            //'text_count' => 'required|numeric',
            //'image' => 'required|image',
            /*       'file' => 'required|image',*/
            'category' => 'nullable|numeric',
            'store_website' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        // now start to check that view file is created or not
        // if created than check same name
        // if same nae then update
        // else assign new name

        // $mailFile = mailEclipse::getMailable("namespace", $data['mail_class'])->first();
        
        $id  = $request->get("id");
        if($id > 0) {
           $mailing_item = MailinglistTemplate::where("id",$id)->first();
        }

        // check if there is mailing item
        if(empty($mailing_item)) {
           $mailing_item = new MailinglistTemplate();
        }

        $mailing_item->name        = $data['name'];
        $mailing_item->subject        = $data['subject'];
        $mailing_item->static_template        = $data['static_template'];

        // if($mailFile) {
        //     $mailing_item->mail_class  = isset($data['mail_class']) ? $data['mail_class'] : null;
        //     if($mailFile["data"]->view != $data['mail_tpl']) {
        //         if (View::exists($data['mail_tpl'])) {
        //             $viewPath    = View($data['mail_tpl'])->getPath();
        //             $viewContent = "this->view('".$mailFile["data"]->view;
        //             $replaceContent = "this->view('".$data['mail_tpl'];
        //             $contents = file_get_contents($mailFile["path_name"]);
        //             $newContents = str_replace($viewContent, $replaceContent, $contents);
        //             file_put_contents($mailFile["path_name"], $newContents);
        //         }
        //     }
        // }

        $mailing_item->mail_tpl    = isset($data['mail_tpl']) ? $data['mail_tpl'] : null;
        $mailing_item->image_count = isset($data['image_count']) ? $data['image_count'] : 0;
        $mailing_item->text_count  = isset($data['text_count']) ? $data['text_count'] : 0;

        $mailing_item->category_id  = $request->category;
        $mailing_item->store_website_id  = $request->store_website;

        $mailing_item->save();

        // this is related to image upload

        $path = "mailinglist/email-templates/" . $mailing_item->id;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filename = date('U') . str_random(10);

        if (!empty($_FILES['image'])) {
            $ext  = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $path = $path . "/" . $filename . "." . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
                $mailing_item->example_image = $path;
                $mailing_item->save();
            }
        }

        return response()->json([
            'item' => view('partials.mailing-template.store', [
                'item' => $mailing_item,
            ])->render(),
        ]);
    }

    public function delete(Request $request, $id)
    {
        $mltemplate = MailinglistTemplate::where("id", $id)->first();
        // check mailing list template
        if ($mltemplate) {
            $mltemplate->delete();
        }

        return response()->json(["code" => 200, "data" => [], "message" => "Mailing list template deleted successfully"]);
    }

}

/*
$table->increments('id');
$table->string("name");
$table->unsignedInteger("image_count");
$table->unsignedInteger("text_count");
$table->text("example_image");
$table->timestamps();*/
