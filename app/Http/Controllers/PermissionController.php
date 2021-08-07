<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        if($request->term){
            $query = $query->where('route', 'LIKE','%'.$request->term.'%');
        }

        
        $users = User::where('is_active',1)->get();

        $permissions =  $query->orderBy('id', 'DESC')->paginate(25)->appends(request()->except(['page']));

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('permissions.partials.list-permission', compact('permissions'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$permissions->render(),
                'count' => $permissions->total(),
            ], 200);
        }

        return view('permissions.index',compact('users','permissions'))->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
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
            'route' => 'required|unique:roles,name',
            
        ]);
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->route = $request->route;
        $permission->save();
      

        return redirect()->back()
                         ->with('success','Permission created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permissions = Permission::find($id);
        return view('permissions.show',compact('permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissions = Permission::find($id);
        return view('permissions.edit',compact('permissions'));
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
            'route' => 'required',
            
        ]);


        $permission = Permission::find($id);
        $permission->name = $request->input('name');
        $permission->route = $request->input('route');
        $permission->save();
        
        return redirect()->route('permissions.index')
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
        $permission = Permission::find($id);
        $permission->delete();
        return redirect()->route('permissions.index')
                         ->with('success','Role deleted successfully');
    }

    public function users(Request $request)
    {
        $users = User::where('is_active',1)->orderBy('name','asc')->get();
        $permissions = Permission::orderBy('name','asc')->get();
        return view('permissions.users',compact('users','permissions'))->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * @SWG\Get(
     *   path="/users/updatePermission",
     *   tags={"Permission"},
     *   summary="update permission",
     *   operationId="update-permission",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function  updatePermission(Request $request){
       $user_id =  $request->user_id;
       $permission_id = $request->permission_id;
       $is_Active = $request->is_active;
        $user = User::findorfail($user_id);
        //ADD PERMISSION
        if($is_Active == 0){
            $user->permissions()->attach($permission_id);
            $message = "Permission added Successfully";
        }
        //REMOVE PERMISSION
        if($is_Active == 1){
            $user->permissions()->detach($permission_id);
            $message = "Permission removed Successfully";
        }

        $data = [
            'success' => true,
            'message'=> $message
        ] ;
        return response()->json($data);


    }

    
    
}
