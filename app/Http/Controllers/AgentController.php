<?php

namespace App\Http\Controllers;

use App\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        'model_id'        => 'required|numeric',
        'model_type'      => 'required|string',
        'name'            => 'required|string||max:255',
        'phone'           => 'sometimes|nullable|numeric',
        'whatsapp_number' => 'sometimes|nullable|numeric',
        'address'         => 'sometimes|nullable|string',
        'email'           => 'sometimes|nullable|email',
      ]);

      $data = $request->except('_token');

      Agent::create($data);

      if ($request->model_type == 'App\Supplier') {
        return redirect()->route('supplier.show', $request->model_id)->withSuccess('You have successfully added an agent!');
      } else if ($request->model_type == 'App\Vendor') {
        return redirect()->route('vendors.show', $request->model_id)->withSuccess('You have successfully added an agent!');
      } else if ($request->model_type == 'App\Old') {
        return redirect()->route('old.show', $request->model_id)->withSuccess('You have successfully added an agent!');
      }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
         'name'             => 'required|string||max:255',
         'phone'            => 'sometimes|nullable|numeric',
         'whatsapp_number'  => 'sometimes|nullable|numeric',
         'address'          => 'sometimes|nullable|string',
         'email'            => 'sometimes|nullable|email',
       ]);

       $data = $request->except('_token');

       $agent = Agent::find($id);
       $agent->update($data);

       if ($agent->model_type == 'App\Supplier') {
         return redirect()->back()->withSuccess('You have successfully updated an agent!');
         // return redirect()->route('supplier.index')->withSuccess('You have successfully updated an agent!');
       } else if ($agent->model_type == 'App\Vendor') {
         return redirect()->back()->withSuccess('You have successfully updated an agent!');
       }
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Agent::find($id)->delete();

      return redirect()->back()->withSuccess('You have successfully deleted and agent!');
    }
}
