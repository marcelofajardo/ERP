<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;  
use DB; 
use Auth;

class DBQueryController extends Controller
{
 

    public function index()
    {
        $user = Auth::user();
        $tables = DB::select('show tables');
        $table_array = [];
        foreach($tables as $tab){
            $table_array[] = array_values((array) $tab)[0];
        }
        return view('admin-menu.database-menu.db-query.index', compact('table_array', 'user'));

    }



    public function columns(Request $request)
    {
        $column_array = [];
        $columns = DB::select('DESCRIBE ' . array_keys($request->all())[0] . ';');
            foreach($columns as $col){
                $column_array[] = $col;
            }
        return response()->json([
            'status' => true,
            'data' => $column_array
        ]);
    }

    public function confirm(Request $request)
    {
        $sql_query = 'UPDATE ' . $request->table_name . ' SET ' ;

        $data = $request->all();
        $where_query_exist = 0;
        foreach($data as $key => $val){
            if(strpos($key, 'update_') !== false && in_array(str_replace('update_', '', $key), $request->columns)){
                $sql_query .= str_replace('update_', '', $key) . ' = "' . $val . '", ';
            } 
        }
        $sql_query .= ' WHERE ';
        $sql_query = str_replace(',  WHERE', ' WHERE', $sql_query);
        foreach($data as $key => $val){ 
            if(strpos($key, 'where_') !== false && $val !== null){
                $key = str_replace('where_', '', $key);
                $sql_query .= $where_query_exist ? ' AND ' : '';
                $sql_query .= $key . ' = ' . $request->criteriaColumnOperators["'".$key."'"] . ' "' . $val . '"';
                $where_query_exist = 1;
            }
        }
        !$where_query_exist ? $sql_query .= ' = 1 ;' : $sql_query .= ' ;'; 
        
        return response()->json([
            'status' => true,
            'sql' => $sql_query,
            'data' => $request->all(),
        ]);
    }


    public function deleteConfirm(Request $request)
    {
        $sql_query = 'DELETE from ' . $request->table_name;

        $data = $request->all();
        $where_query_exist = 0; 
        $sql_query .= ' WHERE ';
        $sql_query = str_replace(',  WHERE', ' WHERE', $sql_query);
        foreach($data as $key => $val){ 
            if(strpos($key, 'where_') !== false && $val !== null){
                $key = str_replace('where_', '', $key);
                $sql_query .= $where_query_exist ? ' AND ' : '';
                $sql_query .= $key . ' ' . $request->criteriaColumnOperators["'".$key."'"] . ' "' . $val . '"';
                $where_query_exist = 1;
            }
        }
        !$where_query_exist ? $sql_query .= '1 ;' : $sql_query .= ' ;'; 
        
        return response()->json([
            'status' => true,
            'sql' => $sql_query,
            'data' => $request->all(),
        ]);
    }
    public function update(Request $request)
    {
        try{
            $sql = DB::select($request->sql);
        }catch(\Exception $e){
            $error = $e;
        }

        return response()->json([
            'status' => isset($error) ? false : true,
            'error' => $error ?? '',
        ]);
    }
    public function delete(Request $request)
    {
        try{
            $sql = DB::select($request->sql);
        }catch(\Exception $e){
            $error = $e;
        }

        return response()->json([
            'status' => isset($error) ? false : true,
            'error' => $error ?? '',
        ]);
    }
}
