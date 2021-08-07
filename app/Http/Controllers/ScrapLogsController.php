<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use \Carbon\Carbon;
use Response;
use Illuminate\Support\Facades\DB;

class ScrapLogsController extends Controller
{
    public function index(Request $Request) 
    {	
		$name = "";
		$servers = \App\Scraper::select('server_id')->whereNotNull('server_id')->groupBy('server_id')->get();
		return view('scrap-logs.index',compact('name','servers'));
    }

	public function filter($searchVal, $dateVal, Request $request) 
    {
    	$serverArray = [];
    	$servers = \App\Scraper::select('server_id')->whereNotNull('server_id')->groupBy('server_id')->get();
    	if ($request->server_id !== null) {
			$servers = \App\Scraper::select('server_id')->where('server_id',$request->server_id)->groupBy('server_id')->get();
		}
    	foreach ($servers as $server) {
    		$serverArray[] = $server['server_id'];
    	}
		
		$file_list = [];
    	$searchVal = $searchVal != "null" ? $searchVal : "";
    	$dateVal = $dateVal != "null" ? $dateVal : "";
		$file_list = [];
		// $files = File::allFiles(env('SCRAP_LOGS_FOLDER'));
		$files = File::allFiles(config('env.SCRAP_LOGS_FOLDER'));

		$date = $dateVal;

        $lines = [];
        $log_status= '';
		$status_lists = DB::table('scrapper_log_status')->get();
		
        foreach ($files as $key => $val) {
			
            $day_of_file = explode('-', $val->getFilename());
			$day_of_file = str_replace('.log', '', $day_of_file);

            if( ( (end($day_of_file) == $date) || (end($day_of_file) == '0'.$date) ) && (str_contains($val->getFilename(), $searchVal) || empty($searchVal))) {
				
				if (!in_array($val->getRelativepath(), $serverArray)) {
					continue;
				}

				// $file_path_new = env('SCRAP_LOGS_FOLDER')."/".$val->getRelativepath()."/".$val->getFilename();
				$file_path_new = config('env.SCRAP_LOGS_FOLDER')."/".$val->getRelativepath()."/".$val->getFilename();

				$file = file($file_path_new);
				
                $log_msg = "";
				for ($i = max(0, count($file)-3); $i < count($file); $i++) {
				  $log_msg.=$file[$i];
				}

				$file_path_info = pathinfo($val->getFilename());
				$file_name_str = $file_path_info['filename'];
				$file_name_ss = $val->getFilename();

                $lines[] = "=============== $file_name_ss log started from here ===============";
                
                for ($i = max(0, count($file)-10); $i < count($file); $i++) {
                  $lines[] = $file[$i];
                }

                $lines[] = "=============== $file_name_ss log ended from here ===============";
                if($log_msg != "") {
	                foreach ($status_lists as $key => $value) {
	                	if (stripos(strtolower($log_msg), $value->text) !== false){
	                		$log_status = $value->status;
	                	}
	                }
	            }
				if($log_msg == "") {
					$log_msg = "Log data not found.";	
				}
				
                array_push($file_list, array(
						"filename" => $file_name_ss,
	        			"foldername" => $val->getRelativepath(),
	        			"log_msg"=>$log_msg,
	        			"status"=>$log_status,
	        			"scraper_id"=>$file_name_str
	    			)
	    		);
			}
		}

        //config
        if(strtolower(request('download')) == "yes") {
            $nameF = "scraper-log-temp-file.txt";
            $namefile = storage_path()."/logs/".$nameF;
            $content = implode("\n",$lines);

            //save file
            $file = fopen($namefile, "w") or die("Unable to open file!");
            fwrite($file, $content);
            fclose($file);

            //header download
            header("Content-Disposition: attachment; filename=\"" . $nameF . "\"");
            header("Content-Type: application/force-download");
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header("Content-Type: text/plain");

            echo $content;
            die;
        }



		return  response()->json(["file_list" => $file_list]);
    }
    public function filtertosavelogdb() 
    {
    	$file_list = [];
    	$searchVal = "";
    	$dateVal = "";
		$file_list = [];
		// $files = File::allFiles(env('SCRAP_LOGS_FOLDER'));
		$files = File::allFiles(config('env.SCRAP_LOGS_FOLDER'));

		$date = empty($dateVal )? Carbon::now()->format('d') : sprintf("%02d", $dateVal);
		if($date == 01) 
		{
			$date = 32;
		}

		foreach ($files as $key => $val) {
			$day_of_file = explode('-', $val->getFilename());
			if(str_contains(end($day_of_file), sprintf("%02d", $date-1)) && (str_contains($val->getFilename(), $searchVal) || empty($searchVal))) {
				// $file_path_new = env('SCRAP_LOGS_FOLDER')."/".$val->getRelativepath()."/".$val->getFilename();
				$file_path_new = config('env.SCRAP_LOGS_FOLDER')."/".$val->getRelativepath()."/".$val->getFilename();

				$file = file($file_path_new);
				$log_msg = "";
				for ($i = max(0, count($file)-3); $i < count($file); $i++) {
				  $log_msg.=$file[$i];
				}
				if($log_msg == "")
				{
					$log_msg = "Log data not found.";	
				}
				$file_path_info = pathinfo($val->getFilename());
				

				$search_scraper = substr($file_path_info['filename'], 0, -3);
				$search_scraper = str_replace("-", "_", $search_scraper);	
				$scrapers_info = DB::table('scrapers')
					->select('id')
					->where('scraper_name', 'like', $search_scraper)
					->get(); 
				
				if(count($scrapers_info) > 0)
				{
					$scrap_logs_info = DB::table('scrap_logs')
					->select('id','scraper_id')
					->where('scraper_id', '=', $scrapers_info[0]->id)
					->get();
					$scrapers_id = $scrapers_info[0]->id;
				}
				else
				{
					$scrapers_id = 0;
				}
					
				if(count($scrap_logs_info) == 0)
				{
					$file_list_data = array(
	    				"scraper_id"=>$scrapers_id,
	    				"folder_name"=>$val->getRelativepath(),
	    				"file_name"=>$val->getFilename(),
	    				"log_messages"=>$log_msg,
	    				"created_date"=>date("Y-m-d H:i:s"),
	    				"updated_date"=>date("Y-m-d H:i:s")
		    		); 
		    		DB::table('scrap_logs')->insert($file_list_data);
				}
			}
		}

		
		//return  response()->json(["file_list" => $file_list]);
    }
    public function fetchlog()
    {
    	$file_list = [];
    	$scrap_logs_info = DB::table('scrap_logs')
		->select('*')
		->get();
		foreach ($scrap_logs_info as $row_log) {
			array_push($file_list, array(
					"filename" => $row_log->file_name,
	    			"foldername" => $row_log->folder_name,
	    			"log_msg"=>$row_log->log_messages,
	    			"scraper_id"=>$row_log->scraper_id
				)
			);
		}
		return  response()->json(["file_list" => $file_list]);
    }

    public function fileView($filename, $foldername) {
		// $path = env('SCRAP_LOGS_FOLDER') . '/' . $foldername . '/' . $filename;
		$path = config('env.SCRAP_LOGS_FOLDER') . '/' . $foldername . '/' . $filename;
    	return response()->file($path);
    }
    
    public function indexByName($name) {
    	$name =  strtolower(str_replace(' ', '', $name));
    	return view('scrap-logs.index',compact('name'));
    }

    public function databaseLog(Request $request){
    	$search = '';

    	$namefile = env('DATABASE_LOGS_FILE');

    	if(!empty($namefile)){
	    	$lines = @file($namefile);
	    	if($lines){
	    		$output = array();
				for($i = count($lines) -1; $i >= 0; $i--){
					$output[] = $lines[$i];
				}
		    	if($request->search){
		    		$search = $request->search;
		    		$result = array_filter($output, function ($item) use ($search) {
					    if (stripos($item, $search) !== false) {
					        return true;
					    }
					    return false;
					});
		    		$output = $result;
		    	}
		    	return view('scrap-logs.database-log', compact('output','search'));
	    	}
	    	return 'File not found!';
		}
    	return 'File not found!';
    }
    public function store(Request $request){
    	
    	$data = array(
            'text' => strtolower($request->errortext),
            'status' => strtolower($request->errorstatus),
        );

        DB::table('scrapper_log_status')->insert($data);
        return redirect()->back()->with('success','New status created successfully.');
    }
}
