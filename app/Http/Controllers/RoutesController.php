<?php
namespace App\Http\Controllers;

use App\Routes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers;
use DB;
use Session;
use Artisan;


class RoutesController extends Controller
{
	function __construct()
	{
		//
	}


	/**
	 * List out all the register routes
	 * $param String $request
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$query = Routes::query();
		if($request->id){
			$query = $query->where('id', $request->id);
		}
		if($request->search){
			$request->search = preg_replace('/[\s]+/', '/', $request->search);
			$query = $query->where('url', 'LIKE','%'.$request->search.'%')->orWhere('page_title', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('page_description', 'LIKE', '%'.$request->search.'%');
		}
		$routesData = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
		return view('routes.index', compact('routesData'))
			->with('i', ($request->input('page', 1) - 1) * 5);
			
	}

	
	/**
	 * Sync the registered routes in DB.
	 * It skip if any route entry is already exist
	 * $param String $request
	 * @return \Illuminate\Http\Response
	 */
	public function sync(Request $request)
	{
		Artisan::call('routes:sync');
		Session::flash('message', 'Data Sync Completed!'); 
		return redirect()->back();
	}
	
	/**
	 * Get all the register route
	 * $param String $method
	 * @return \Illuminate\Http\Response
	 */
	private function getRoutesByMethod($method){
		$routes = \Route::getRoutes()->getRoutesByMethod();
		return $routes[$method];
	}
	
	
	/**
	 * Sync the registered routes in DB.
	 * It skip if any route entry is already exist
	 * $param String $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$routes = Routes::find($id);
		if($request->post('page_title') && $request->post('page_description'))
		{
			$updateData = array('page_title'=>$request->post('page_title'), 'page_description'=>$request->post('page_description'));
			Routes::whereId($id)->update($updateData);
			Session::flash('message', 'Data Updated Successfully'); 
			return redirect()->route('routes.update', [$id]);
		}

		if($request->post('status'))
		{
			$updateData = array('status'=>$request->post('status'));
			Routes::whereId($id)->update($updateData);
			return response()->json(["code" => 200 , "data" => [], "message" => "Status updated successfully"]);
		}
		return view ('routes.update',compact('routes'));
		//return redirect('routes.update');
		
	}


	
}
