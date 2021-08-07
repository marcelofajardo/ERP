<?php

namespace App\Http\Controllers;

use App\DigitalMarketingPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DigitalMarketingPlatformFile;
use App\DigitalMarketingSolutionFile;
use App\Email;

class DigitalMarketingController extends Controller
{
    public function index(Request $request)
    {
        $title  = "Social-Digital Marketing";
        $status = \App\DigitalMarketingPlatform::STATUS;
        $records = \App\DigitalMarketingPlatform::get();

        return view("digital-marketing.index", compact('records', 'title', 'status'));
    }

    public function records(Request $request)
    {
        $records = \App\DigitalMarketingPlatform::query();

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("platform", "LIKE", "%$keyword%")
                    ->orWhere("description", "LIKE", "%$keyword%");
            });
        }

        $records = $records->get();

        foreach ($records as &$rec) {
            $rec->status_name = isset(\App\DigitalMarketingPlatform::STATUS[$rec->status]) ? \App\DigitalMarketingPlatform::STATUS[$rec->status] : $rec->status;
            $rec->components_list = implode(",",$rec->components->pluck("name")->toArray());
        }

        return response()->json(["code" => 200, "data" => $records, "total" => count($records)]);
    }

    public function save(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'platform'    => 'required',
            'description' => 'required',
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

        $records = DigitalMarketingPlatform::find($id);

        if (!$records) {
            $records = new DigitalMarketingPlatform;
        }

        $records->fill($post);
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);

    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $digitalMarketing = DigitalMarketingPlatform::where("id", $id)->first();

        if ($digitalMarketing) {
            return response()->json(["code" => 200, "data" => $digitalMarketing]);
        }

        return response()->json(["code" => 500, "error" => "Wrong digital marketing id!"]);
    }

    public function saveImages(Request $request)
    {
        $files = $request->file('file');
        $fileNameArray = array();
        foreach($files as $key=>$file){
           //echo $file->getClientOriginalName();
           $fileName = time().$key.'.'.$file->extension();
           $fileNameArray[] = $fileName;
           //echo $request->id;
           if($request->type == "marketing"){
            $createFile = DigitalMarketingPlatformFile::create(['digital_marketing_platform_id'=>$request->id,'file_name'=>$fileName,"user_id"=>\Auth::id()]);
           }else{
            $createFile = DigitalMarketingSolutionFile::create(['digital_marketing_solution_id'=>$request->id,'file_name'=>$fileName,"user_id"=>\Auth::id()]);
           }
           
           $file->move(public_path('digital_marketing'), $fileName);
        }
        return response()->json(["code" => 200, "msg" => "files uploaded successfully","data"=>$fileNameArray]);
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $digitalMarketing = DigitalMarketingPlatform::where("id", $id)->first();

        if ($digitalMarketing) {

            foreach ($digitalMarketing->solutions as $solution) {
                $solution->attributes()->delete();
                $solution->delete();
            }

            $digitalMarketing->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong digital marketing id!"]);
    }

    public function solution(Request $request, $id)
    {
        $title = "Social-Digital Marketing Solution";
        $status = \App\DigitalMarketingPlatform::STATUS;
        return view("digital-marketing.solution.index", compact('title', 'status', 'id'));

    }

    public function solutionRecords(Request $request, $id)
    {

        $records = \App\DigitalMarketingSolution::where("digital_marketing_platform_id", $id);

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("provider", "LIKE", "%$keyword%")
                    ->orWhere("website", "LIKE", "%$keyword%")
                    ->orWhere("contact", "LIKE", "%$keyword%");
            });
        }

        $records = $records->get();

        $filledUsp = [];
        foreach ($records as $record) {
            $attributes = $record->attributes;
            if (!$attributes->isEmpty()) {
                foreach ($attributes as $attribute) {
                    $filledUsp[$record->id][$attribute->key] = $attribute->value;
                }
            }
        }

        $usps = \App\DigitalMarketingUsp::where("digital_marketing_platform_id", $id)->get();

        return response()->json([
            "code"      => 200,
            "data"      => $records,
            "total"     => count($records),
            "usps"      => $usps,
            "filledUsp" => $filledUsp,
        ]);

    }

    public function solutionSave(Request $request, $id)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'provider' => 'required',
            'website'  => 'required',
            'contact'  => 'required',
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

        $solutionId = $request->get("solution_id", 0);

        $records = \App\DigitalMarketingSolution::where("digital_marketing_platform_id", $id)->where("id", $solutionId)->first();

        if (!$records) {
            $records = new \App\DigitalMarketingSolution;
        }

        $records->fill($post);
        $records->digital_marketing_platform_id = $id;
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);

    }

    public function solutionEdit(Request $request, $id, $solutionId)
    {
        $record = \App\DigitalMarketingSolution::where("digital_marketing_platform_id", $id)->where("id", $solutionId)->first();

        if ($record) {
            return response()->json(["code" => 200, "data" => $record]);
        }

        return response()->json(["code" => 500, "error" => "Wrong digital marketing solution id!"]);

    }

    public function solutionDelete(Request $request, $id, $solutionId)
    {
        $record = \App\DigitalMarketingSolution::where("digital_marketing_platform_id", $id)->where("id", $solutionId)->first();

        if ($record) {
            // check all attributes
            $record->attributes()->delete();
            $record->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong digital marketing id!"]);
    }

    public function solutionCreateUsp(Request $request, $id)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'name' => 'required',
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

        $solutionId = $request->get("solution_id", 0);

        $records = \App\DigitalMarketingUsp::where("digital_marketing_platform_id", $id)->where("id", $solutionId)->first();

        if (!$records) {
            $records = new \App\DigitalMarketingUsp;
        }

        $records->fill($post);
        $records->digital_marketing_platform_id = $id;
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);
    }

    public function solutionSaveUsp(Request $request, $id, $solutionId)
    {

        $attributes = $request->get("usps");
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                if (!empty($value)) {
                    \App\DigitalMarketingSolutionAttribute::updateOrCreate([
                        'digital_marketing_solution_id' => $solutionId, "key" => $key,
                    ], [
                        'digital_marketing_solution_id' => $solutionId, "key" => $key, "value" => $value,
                    ]);
                }
            }
        }

        return response()->json(["code" => 200, "data" => []]);

    }

    public function research(Request $request, $id, $solutionId)
    {
        $title = "Social-Digital Marketing Solution Research";

        $priority = \App\DigitalMarketingSolutionResearch::PRIORITY;

        return view("digital-marketing.solution.research.index", compact('title', 'status', 'id', 'solutionId', 'priority'));
    }

    public function researchRecords(Request $request, $id, $solutionId)
    {

        $records = \App\DigitalMarketingSolutionResearch::where("digital_marketing_solution_id", $solutionId);

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("subject", "LIKE", "%$keyword%")
                    ->orWhere("description", "LIKE", "%$keyword%")
                    ->orWhere("remarks", "LIKE", "%$keyword%");
            });
        }

        $records = $records->get();

        foreach ($records as &$rec) {
            $rec->priority = isset(\App\DigitalMarketingSolutionResearch::PRIORITY[$rec->priority]) ? \App\DigitalMarketingSolutionResearch::PRIORITY[$rec->priority] : $rec->priority;
        }

        return response()->json([
            "code"  => 200,
            "data"  => $records,
            "total" => count($records),
        ]);

    }

    public function researchSave(Request $request, $id, $solutionId)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'subject'     => 'required',
            //'description' => 'required',
            //'remarks'     => 'required',
            'priority'    => 'required',
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

        $researchId = $request->get("research_id", 0);

        $records = \App\DigitalMarketingSolutionResearch::where("digital_marketing_solution_id", $solutionId)->where("id", $researchId)->first();

        if (!$records) {
            $records = new \App\DigitalMarketingSolutionResearch;
        }

        $records->fill($post);
        $records->digital_marketing_solution_id = $solutionId;
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);
    }

    public function researchEdit(Request $request, $id, $solutionId, $researchId)
    {
        $record = \App\DigitalMarketingSolutionResearch::where("digital_marketing_solution_id", $solutionId)->where("id", $researchId)->first();

        if ($record) {
            return response()->json(["code" => 200, "data" => $record]);
        }

        return response()->json(["code" => 500, "error" => "Wrong digital marketing solution research id!"]);

    }


    public function researchDelete(Request $request, $id, $solutionId, $researchId)
    {
        $record = \App\DigitalMarketingSolutionResearch::where("digital_marketing_solution_id", $solutionId)->where("id", $researchId)->first();

        if ($record) {
            $record->delete();
            return response()->json(["code" => 200, "data" => []]);
        }

        return response()->json(["code" => 500, "error" => "Wrong digital marketing solution research id!"]);

    }

    public function components(Request $request , $id) 
    {
        $records = [];
        $records["id"] = $id;
        $records["components"] = \App\DigitalMarketingPlatformComponent::where("digital_marketing_platform_id",$id)->get()->pluck("name")->toArray();
        
        return response()->json(["code" => 200, "data" => $records]);
    }

    public function files(Request $request , $id) 
    {
        $records = [];
        $records["id"] = $id;
        $records["components"] = \App\DigitalMarketingPlatformFile::where("digital_marketing_platform_id",$id)->get()->transform(function($files){
            // $files->downloadUrl = env("APP_URL")."/digital_marketing/".$files->file_name;
            $files->downloadUrl = config('env.APP_URL')."/digital_marketing/".$files->file_name;
            $files->user = \App\User::find($files->user_id)->name;
            return $files;
        });
        
        return response()->json(["code" => 200, "data" => $records]);
    }

    public function filesSolution(Request $request , $id) 
    {
        $records = [];
        $records["id"] = $id;
        $records["components"] = \App\DigitalMarketingSolutionFile::where("digital_marketing_solution_id",$id)->get()->transform(function($files){
            // $files->downloadUrl = env("APP_URL")."/digital_marketing/".$files->file_name;
            $files->downloadUrl = config('env.APP_URL')."/digital_marketing/".$files->file_name;
            $files->user = \App\User::find($files->user_id)->name;
            return $files;
        });
        
        return response()->json(["code" => 200, "data" => $records]);
    }

    public function componentStore(Request $request , $id) 
    {
        \App\DigitalMarketingPlatformComponent::where("digital_marketing_platform_id",$id)->delete();
        
        $components = $request->get("components");
        if(!empty($components)) {
            foreach($components as $component) {
                \App\DigitalMarketingPlatformComponent::create([
                    "digital_marketing_platform_id" => $id,
                    "name" => $component
                ]);
            }
        }

        return response()->json(["code" => 200, "data" => []]);
    }
    public function getEmails(Request $request){
        if($request->id){
            $emails = Email::where('digital_platfirm',$request->id)->get();
            return $emails;
        }
        return response()->json(["code" => 500, "data" => '']);   
    }

}
