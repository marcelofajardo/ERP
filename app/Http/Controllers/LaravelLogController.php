<?php

namespace App\Http\Controllers;

use App\LaravelLog;
use App\Setting;
use App\User;
use App\LogKeyword;
use File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;
use App\ChatMessage;
use Illuminate\Support\Carbon;

class LaravelLogController extends Controller
{
    public $channel_filter = [];
    public function index(Request $request)
    {
        if ($request->filename || $request->log || $request->log_created || $request->created || $request->updated || $request->orderCreated || $request->orderUpdated || $request->modulename || $request->controllername || $request->action) {

            $query = LaravelLog::query();

            if (request('filename') != null) {
                $query->where('filename', 'LIKE', "%{$request->filename}%");
            }

            if (request('log') != null) {
                $query->where('log', 'LIKE', "%{$request->log}%");
            }

            if (request('modulename') != null) {
                $query->where('module_name', request('modulename'));
            }

            if (request('controllername') != null) {
                $query->where('controller_name', request('controllername'));
            }

            if (request('action') != null) {
                $query->where('action', request('action'));
            }

            if (request('log_created') != null) {
                $query->whereDate('log_created', request('log_created'));
            }

            if (request('created') != null) {
                $query->whereDate('created_at', request('created'));
            }

            if (request('updated') != null) {
                $query->whereDate('updated_at', request('updated'));
            }

            if (request('orderCreated') != null) {
                if (request('orderCreated') == 0) {
                    $query->orderby('created_at', 'asc');
                } else {
                    $query->orderby('created_at', 'desc');
                }
            }

            if (request('orderUpdated') != null) {
                if (request('orderUpdated') == 0) {
                    $query->orderby('updated_at', 'asc');
                } else {
                    $query->orderby('updated_at', 'desc');
                }
            }

            if (request('orderCreated') == null && request('orderUpdated') == null) {
                $query->orderby('log_created', 'desc');
            }

            $paginate = (Setting::get('pagination') * 10);
            $logs     = $query->paginate($paginate)->appends(request()->except(['page']));
        } else {

            $paginate = (Setting::get('pagination') * 10);
            $logs     = LaravelLog::orderby('updated_at', 'desc')->paginate($paginate);

        }

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('logging.partials.laraveldata', compact('logs'))->render(),
                'links' => (string) $logs->render(),
                'count' => $logs->total(),
            ], 200);
        }

        return view('logging.laravellog', compact('logs'));
    }

    public function liveLogsSingle(Request $request)
    {
        
        $filename = '/laravel-' . now()->format('Y-m-d') . '.log';
        //$filename = '/laravel-2020-09-10.log';
        $path         = storage_path('logs');
        $fullPath     = $path . $filename;
        $errSelection = [];
        try {
            $content = File::get($fullPath);
            preg_match_all("/\[(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})\](.*)/", $content, $match);
            $errorTypeArr       = ['ERROR', 'INFO', 'WARNING'];
            $errorTypeSeparated = implode('|', $errorTypeArr);

            $defaultSearchTerm = 'ERROR';
            if ($request->get('type')) {
                $defaultSearchTerm = $request->get('type');
            }

            foreach ($match[0] as $value) {
                foreach ($errorTypeArr as $errType) {
                    if (preg_match("/" . $errType . "/", $value)) {
                        $errSelection[] = $errType;

                        break;
                    }
                }
                if ($request->get('search') && $request->get('search') != '') {
                    //if(preg_match("/".$request->get('search')."/", $value) && preg_match("/".$defaultSearchTerm."/", $value)) {
                    if (strpos(strtolower($value), strtolower($request->get('search'))) !== false && preg_match("/" . $defaultSearchTerm . "/", $value)) {
                        $str   = $value;
                        $temp1 = explode(".", $str);
                        $temp2 = explode(" ", $temp1[0]);
                        $type  = $temp2[2];
                        array_push($this->channel_filter, $type);

                        $errors[] = $value . "===" . str_replace('/', '', $filename);
                    }
                } else {
                    if (preg_match("/" . $defaultSearchTerm . "/", $value)) {
                        $str   = $value;
                        $temp1 = explode(".", $str);
                        $temp2 = explode(" ", $temp1[0]);
                        $type  = $temp2[2];
                        array_push($this->channel_filter, $type);

                        $errors[] = $value . "===" . str_replace('/', '', $filename);
                    }
                }

            }
            //if(isset($_GET['channel']) && $_GET['channel'] == "local"){
            $errors = array_reverse($errors);
            //}
        } catch (\Exception $e) {
            $errors = [];

        }

        $other_channel_data = $this->getDirContents($path);
        foreach ($other_channel_data as $other) {
            array_push($errors, $other);
        }
        $allErrorTypes = array_values(array_unique($errSelection));

        $users       = User::all();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $perPage = Setting::get('pagination');

        $final = $key = [];
        if (isset($request->channel)) {
            session(['channel' => $request->channel]);
        }
        foreach ($errors as $key => $error) {

            $str   = $error;
            $temp1 = explode(".", $str);
            $temp2 = explode(" ", $temp1[0]);
            $type  = $temp2[2];
            $if_available = false;
            if (stripos(strtolower($request->msg), $temp1[1]) !== false){
                array_push($final, $temp2[0].$temp2[1]);
            }
        }
        return $final;
    }
    public function liveLogs(Request $request)
    {
        $filename = '/laravel-' . now()->format('Y-m-d') . '.log';
        //$filename = '/laravel-2020-09-10.log';
        $path         = storage_path('logs');
        $fullPath     = $path . $filename;
        $errSelection = [];
        try {
            $content = File::get($fullPath);
            preg_match_all("/\[(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})\](.*)/", $content, $match);
            $errorTypeArr       = ['ERROR', 'INFO', 'WARNING'];
            $errorTypeSeparated = implode('|', $errorTypeArr);

            $defaultSearchTerm = 'ERROR';
            if ($request->get('type')) {
                $defaultSearchTerm = $request->get('type');
            }

            foreach ($match[0] as $value) {
                foreach ($errorTypeArr as $errType) {
                    if (preg_match("/" . $errType . "/", $value)) {
                        $errSelection[] = $errType;

                        break;
                    }
                }
                if ($request->get('search') && $request->get('search') != '') {
                    //if(preg_match("/".$request->get('search')."/", $value) && preg_match("/".$defaultSearchTerm."/", $value)) {
                    if (strpos(strtolower($value), strtolower($request->get('search'))) !== false && preg_match("/" . $defaultSearchTerm . "/", $value)) {
                        $str   = $value;
                        $temp1 = explode(".", $str);
                        $temp2 = explode(" ", $temp1[0]);
                        $type  = $temp2[2];
                        array_push($this->channel_filter, $type);

                        $errors[] = $value . "===" . str_replace('/', '', $filename);
                    }
                } else {
                    if (preg_match("/" . $defaultSearchTerm . "/", $value)) {
                        $str   = $value;
                        $temp1 = explode(".", $str);
                        $temp2 = explode(" ", $temp1[0]);
                        $type  = $temp2[2];
                        array_push($this->channel_filter, $type);

                        $errors[] = $value . "===" . str_replace('/', '', $filename);
                    }
                }

            }
            //if(isset($_GET['channel']) && $_GET['channel'] == "local"){
            $errors = array_reverse($errors);
            //}
        } catch (\Exception $e) {
            $errors = [];

        }

        /*$other_channel_data = $this->getDirContents($path);
        foreach ($other_channel_data as $other) {
            array_push($errors, $other);
        }*/
        $allErrorTypes = array_values(array_unique($errSelection));

        $users       = User::all();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $perPage = Setting::get('pagination');

        $final = $key = [];
        if (isset($request->channel)) {
            session(['channel' => $request->channel]);
        }
        foreach ($errors as $key => $error) {

            $str   = $error;
            $temp1 = explode(".", $str);
            $temp2 = explode(" ", $temp1[0]);
            $type  = $temp2[2];
            
            $if_available = false;
            if (isset($request->channel) && $request->channel == $type) {
                foreach ($final as $value) {
                    if (stripos(strtolower($value), $temp1[1]) !== false) $if_available = true;
                }
                if($if_available){
                    continue;
                }else{
                    array_push($final, $error);
                }
            }

            if (!isset($request->channel)) {
                
                foreach ($final as $value) {
                    if (stripos(strtolower($value), $temp1[1]) !== false) $if_available = true;
                }
                if($if_available){
                    continue;
                }else{
                    array_push($final, $error);
                }
                
            }
        }
        //     dd($final);
        //    exit;

        $errors       = [];
        $errors       = array_unique($final);
        $currentItems = array_slice($errors, $perPage * ($currentPage - 1), $perPage);
        //dd($currentItems);

        $logs = new LengthAwarePaginator($currentItems, count($errors), $perPage, $currentPage, [
            'path'  => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);
        //dd($errors);

        //$this->channel_filter;
        $filter_channel = [];
        foreach ($this->channel_filter as $ch) {
            if (!in_array($ch, $filter_channel)) {
                array_push($filter_channel, $ch);
            }
        }
        $logKeywords = LogKeyword::all();
        $ChatMessages = ChatMessage::where('message_application_id',10001)->orderBy('created_at','DESC')->whereDate('created_at', '>', Carbon::now()->subDays(30))->get();


        return view('logging.livelaravellog', ['logs' => $logs, 'filename' => str_replace('/', '', $filename), 'errSelection' => $allErrorTypes, 'users' => $users, 'filter_channel' => $filter_channel, 'logKeywords' => $logKeywords,'ChatMessages'=>$ChatMessages]);

    }

    public function LogKeyword(Request $request)
    {
        if($request->title){
            //creating message
            $params = [
                'text'              => $request->title,
            ];
            $logKeyword = LogKeyword::create($params);
            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'errors',
        ]);
    }
    /**
     * to get relelated records for scraper
     *
     *
     */

    public function scraperLiveLogs()
    {
        $filename = '/scraper-' . now()->format('Y-m-d') . '.log';
        $path     = storage_path('logs') . DIRECTORY_SEPARATOR . "scraper";
        $fullPath = $path . $filename;
        $errors   = self::getErrors($fullPath);

        $currentPage  = LengthAwarePaginator::resolveCurrentPage();
        $perPage      = Setting::get('pagination');
        $currentItems = array_slice($errors, $perPage * ($currentPage - 1), $perPage);

        $logs = new LengthAwarePaginator($currentItems, count($errors), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return view('logging.scraperlog', ['logs' => $logs, 'filename' => str_replace('/', '', $filename)]);

    }

    public function assign(Request $request)
    {
        if ($request->get('issue') && $request->get('assign_to')) {
            $error       = html_entity_decode($request->get('issue'), ENT_QUOTES, 'UTF-8');
            $issueName   = substr($error, 0, 150);
            $requestData = new Request();
            $requestData->setMethod('POST');
            $requestData->request->add([
                'priority'    => 1,
                'issue'       => $error,
                'status'      => 'Planned',
                'module'      => 'Cron',
                'subject'     => $issueName . "...",
                'assigned_to' => $request->get('assign_to'),
            ]);

            app('App\Http\Controllers\DevelopmentController')->issueStore($requestData, 'issue');

            return redirect()->route('logging.live.logs');
        }

        return back()->with('error', '"issue" or "assign_to" not found in request.');
    }

    public static function getErrors($fullPath)
    {
        $errors = [];

        try {
            $content = File::get($fullPath);
            preg_match_all("/\[(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})\](.*)/", $content, $match);
            foreach ($match[0] as $value) {
                $errors[] = str_replace("##!!##", "", $value);
            }
            $errors = array_reverse($errors);
        } catch (\Exception $e) {
            $errors = [];
        }

        return $errors;

    }

    public function liveLogDownloads()
    {
        $filename = '/laravel-' . now()->format('Y-m-d') . '.log';

        $path     = storage_path('logs');
        $fullPath = $path . $filename;
        return response()->download($fullPath, str_replace('/', '', $filename));
    }

    public function liveMagentoDownloads()
    {
        $filename = '/list-magento-' . now()->format('Y-m-d') . '.log';

        $path     = storage_path('logs');
        $fullPath = $path . $filename;
        return response()->download($fullPath, str_replace('/', '', $filename));
    }

    public function saveNewLogData(Request $request)
    {

        $url             = $request->url;
        $message         = $request->message;
        $website         = $request->website;
        $module_name     = $request->module_name;
        if(!empty($request->modulename)) {
            $module_name = $request->modulename;
        }

        $controller_name = $request->controller_name;
        if(!empty($request->controller)) {
            $controller_name = $request->controller;
        }

        $action          = $request->action;

        if ($url == '') {
            $message = $this->generate_erp_response("laravel.log.failed", 0, $default = 'URL is required', request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message], 400);
        }
        if ($message == '') {
            $message = $this->generate_erp_response("laravel.log.failed", 0, $default = 'Message is required', request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message], 400);
        }
        if ($module_name == '') {
            $message = $this->generate_erp_response("laravel.log.failed", 0, $default = 'Module name is required', request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message], 400);
        }
        if ($controller_name == '') {
            $message = $this->generate_erp_response("laravel.log.failed", 0, $default = 'Controller name is required', request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message], 400);
        }
        if ($action == '') {
            $message = $this->generate_erp_response("laravel.log.failed", 0, $default = 'action is required', request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message], 400);
        }
        $laravelLog                  = new LaravelLog();
        $laravelLog->filename        = $url;
        $laravelLog->log             = $message;
        $laravelLog->website         = $website;
        $laravelLog->module_name     = $module_name;
        $laravelLog->controller_name = $controller_name;
        $laravelLog->action          = $action;
        $laravelLog->save();
        $message = $this->generate_erp_response("laravel.log.success", 0, $default = 'Log data Saved', request('lang_code'));
        return response()->json(['status' => 'success', 'message' => $message], 200);
    }

    public function getDirContents($dir, $results = array())
    {
        $directories   = glob($dir . '/*', GLOB_ONLYDIR);
        $allErrorTypes = [];
        $final_result  = [];
        foreach ($directories as $dir) {

            if ($handle = opendir($dir)) {

                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
                        $current_date = explode('-', date('Y-m-d'));
                        $temp         = explode('-', $entry);
                        $errors       = [];
                        $errSelection = [];
                        if (!isset($temp[1]) || !isset($temp[2])) {
                            continue;
                        }
                        if ($current_date[0] == $temp[1] && $current_date[1] == $temp[2] && $current_date[2] == str_replace('.log', '', $temp[3])) {

                            $fullPath = $dir . "/" . $entry;
                            $content  = File::get($fullPath);
                            preg_match_all("/\[(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})\](.*)/", $content, $match);
                            $errorTypeArr       = ['ERROR', 'INFO', 'WARNING'];
                            $errorTypeSeparated = implode('|', $errorTypeArr);

                            $defaultSearchTerm = 'ERROR';
                            if (isset($_GET['type'])) {
                                $defaultSearchTerm = $_GET['type'];
                            }

                            foreach ($match[0] as $value) {
                                foreach ($errorTypeArr as $errType) {
                                    if (preg_match("/" . $errType . "/", $value)) {
                                        $errSelection[] = $errType;
                                        break;
                                    }
                                }
                                if (preg_match("/" . $defaultSearchTerm . "/", $value)) {
                                    $str   = $value;
                                    $temp1 = explode(".", $str);
                                    $temp2 = explode(" ", $temp1[0]);
                                    $type  = $temp2[2];
                                    array_push($this->channel_filter, $type);
                                    $errors[] = $value . "===" . str_replace('/', '', $entry);
                                }
                            }
                            $errors          = array_reverse($errors);
                            $allErrorTypes[] = array_values(array_unique($errSelection));
                            foreach ($errors as $er) {
                                array_push($final_result, $er);
                            }
                            //$final_result[] = $errors;
                        }
                    }
                }
                closedir($handle);
            }
        }

        return $final_result;
    }

    public function apiLogs(Request $request)
    {
        $logs = new \App\LogRequest;

        //echo '<pre>';print_r($request->all());

        if ($request->id) {
            $logs = $logs->where('id', $request->id);
        }

        if ($request->ip) {
            $logs = $logs->where('ip', 'like', $request->ip . '%');
        }

        if ($request->method) {
            $logs = $logs->where('method', 'like', $request->method . '%');
        }

        if ($request->status) {
            $logs = $logs->where('status_code', 'like', $request->status . '%');
        }
        if ($request->url) {
            $logs = $logs->where('url', 'like', '%' . $request->url . '%');
        }

        if ($request->created_at) {
            $logs = $logs->whereDate('created_at', \Carbon\Carbon::createFromFormat('Y/m/d', $request->created_at)->format('Y-m-d'));
        }

        $count = $logs->count();

        $logs = $logs->orderBy("id", "desc")->paginate(Setting::get('pagination'));
        $status_codes = \App\LogRequest::distinct()->get(['status_code']);

        if ($request->ajax()) {
            //$request->render('logging.partials.apilogdata',compact('logs'));
            $html = view('logging.partials.apilogdata', compact('logs'))->render();

            if (count($logs)) {
                return array('status' => 1, 'html' => $html, 'count' => $count, 'logs' => $logs);
            } else {
                return array('status' => 0, 'html' => '<tr id="noresult_tr"><td colspan="7">No More Records</td></tr>');
            }
        }
        return view('logging.apilog', compact('logs', 'count','status_codes'));
    }

    public function generateReport(Request $request)
    {   

        $logsGroupWise = \App\LogRequest::query();

        if($request->keyword != "") {
            $keyword = $request->keyword;
            $logsGroupWise = $logsGroupWise->where(function($q) use($keyword) {
                $q->orWhere("request","like","%".$keyword."%")->orWhere("response","like","%".$keyword."%");
            });
        }

        if($request->for_date != "") {
            $forDate = $request->for_date;
            $logsGroupWise = $logsGroupWise->whereDate('created_at',">=",$forDate);
        }


        if($request->report_type == "time_wise") {
            //$logsGroupWise = $logsGroupWise->groupBy('time_taken');
            $logsGroupWise = $logsGroupWise->where('time_taken', '>', 5);
            $logsGroupWise = $logsGroupWise->whereNotNull('time_taken');
            $logsGroupWise = $logsGroupWise->orderByRaw('CONVERT(time_taken, SIGNED) desc');
            $logsGroupWise = $logsGroupWise->select(["*", \DB::raw('1 as total_request')])->get();
        }else{
            $logsGroupWise = $logsGroupWise->where('status_code', '!=', 200);
            $logsGroupWise = $logsGroupWise->groupBy('url');
            $logsGroupWise = $logsGroupWise->orderBy('total_request','desc');
            $logsGroupWise = $logsGroupWise->select(["*", \DB::raw('count(*) as total_request')])->get();
        }

        


        return view('logging.partials.generate-report', compact('logsGroupWise'));

    }

}