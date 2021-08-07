<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use DB;


class RoleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function __construct()
	{
		
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$query = Role::query();

		if($request->term){
			$query = $query->where('name', 'LIKE','%'.$request->term.'%');
		}

		$roles = $query->orderBy('id','DESC')->paginate(25)->appends(request()->except(['page']));

		if ($request->ajax()) {
            return response()->json([
                'tbody' => view('roles.partials.list-roles', compact('roles'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$roles->render(),
                'count' => $roles->total(),
            ], 200);
        }

		return view('roles.index',compact('roles'))
			->with('i', ($request->input('page', 1) - 1) * 10);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$permission = Permission::get();
		return view('roles.create',compact('permission'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|unique:roles,name',
			'permission' => 'required',
		]);
		$role = new Role();
		$role->name = $request->name;
		$role->save();
		$role_id = $role->id;

		$role->permissions()->sync($request->input('permission'));
	
		return redirect()->route('roles.index')
		                 ->with('success','Role created successfully');
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$role = Role::find($id);
		
		return view('roles.show',compact('role'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$role = Role::find($id);
		$permission = Permission::get();
		
		return view('roles.edit',compact('role','permission'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
			$this->validate($request, [
			'name' => 'required',
			'permission' => 'required',
		]);


		$role = Role::find($id);
		$role->name = $request->input('name');
		$role->save();
		
		$role->permissions()->sync($request->input('permission'));
		
		return redirect()->route('roles.index')
		                 ->with('success','Role updated successfully');
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		Role::delete($id);
		return redirect()->route('roles.index')
		                 ->with('success','Role deleted successfully');
	}

	public function unAuthorized()
	{
		return view('errors.401');
	}
}