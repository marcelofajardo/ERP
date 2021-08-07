<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProjectFileManager;
use DB;

class ProjectFileManagerController extends Controller
{
    //
	public $folderLimit = array('public'=>200);
	public $dumpData = [];
	public $updateData = [];
	public $count = 0;
	
	
	
	public function index(Request $request)
	{
		$query = ProjectFileManager::query();
		if($request->search){
			$query = $query->where('name', 'LIKE','%'.$request->search.'%')->orWhere('parent', 'LIKE', '%'.$request->search.'%');
		}
		$projectDirectoryData = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
		return view('project_directory_manager.index', compact('projectDirectoryData'))
			->with('i', ($request->input('page', 1) - 1) * 5);
		
	}
	
	public function update(Request $request)
	{
		if($request->post('id') && $request->post('size'))
		{
			$directoryData = ProjectFileManager::find($request->post('id'));
			$directoryData->notification_at = $request->post('size');
			$directoryData->save();
			echo "Size Updated Successfully";
		}else{
			echo "Incomplete Request";
		}
	}
	
	
	//Cron Funciton called from ProjectDirectory Console Command to dump all folders in Db
	public function listTree()
	{
		$directory = base_path();
		\Log::info("PROJECT_MANAGER => started to scan file directory");
		$this->listFolderFiles($directory);
		
		ProjectFileManager::insert($this->dumpData);
		
		foreach($this->updateData as $key => $value)
		{
			DB::table('project_file_managers')
                ->where('id', $value['id'])
                ->update(['size' => $value['size']]);
		}
		exit;
	}
	
	public function listFolderFiles($dir)
	{
		//for replace base path
		$basePath = base_path();
		foreach(new \DirectoryIterator($dir) as $fileInfo) {
			if (!$fileInfo->isDot()) {
				
				if ($fileInfo->isDir()) {
					 $exePath = [".git","vendor"];
					 $yes = false;
					 foreach($exePath as $exe) {
						 if(stripos($fileInfo->getPathname(),$exe) !== false) {
						 	$yes = true;
						 }
					 }

					 if($yes) {
					 	continue;
					 }

					//\Log::info("PROJECT_MANAGER => started to scan file directory ".$fileInfo->getPathname());
					$batchPathReplace = str_replace($basePath,'',$fileInfo->getPathname());
					$parentPath = str_replace($fileInfo->getFilename(),'',$batchPathReplace);
					$parentPath = str_replace('\\','/',$parentPath);
					
					$size = $this->folderSize($fileInfo->getPathname());
					
					$data = DB::table('project_file_managers')->where('name', $fileInfo->getFilename())->where('parent', $parentPath)->first();
					
					if(empty($data))
					{
						$this->dumpData[$this->count]['name'] = $fileInfo->getFilename();
						$this->dumpData[$this->count]['project_name'] = 'erp';
						$this->dumpData[$this->count]['size'] = $size;
						$this->dumpData[$this->count]['parent'] = $parentPath;
						$this->dumpData[$this->count]['created_at'] = date('Y-m-d H:i:s');
						
					}else{
						$this->updateData[$data->id]['id'] = $data->id;
						$this->updateData[$data->id]['size'] = $size;
						$sizeInMB = number_format($size / 1048576, 0);
						if(isset($data->notification_at) && $sizeInMB > $data->notification_at)
						{
							
							$requestData = new Request(); 
							$requestData->setMethod('POST'); 
							$requestData->request->add([ 'priority' => 1, 'issue' => "Error With folder size {$fileInfo->getFilename()} which is more then {$sizeInMB} and expected size is {$data->notification_at}", 'status' => "Planned", 'module' => "{$sizeInMB}", 'subject' => "Error With folder size {$fileInfo->getFilename()}", 'assigned_to' => 6 ]); 
							app('App\Http\Controllers\DevelopmentController')->issueStore($requestData, 'issue');
						}
					}
					$this->count++;
					self::listFolderFiles($fileInfo->getPathname());
				}
			}
		}
	}
	
	
	public function folderSize($dir)
	{
		$size = 0;

		foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
			$size += is_file($each) ? filesize($each) : self::folderSize($each);
		}

		return $size;
	}
	
}
