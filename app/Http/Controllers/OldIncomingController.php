<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OldIncoming;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use View;
use App\Issue;

class OldIncomingController extends Controller
{
    /**
     * Defining scope of variable
     *
     * @access protected
     *
     * @var    array $oldIncoming
     */
    protected $oldincoming;

    /**
     * Create a new controller instance.
     *
     * @param mixed $oldincoming get oldincoming model
     *
     * @return void
     */
    public function __construct(OldIncoming $oldincoming)
    {
        $this->oldincoming = $oldincoming;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $issues = new Issue;
        if (!empty($_GET['sr_no'])) {
            $sr_no = $_GET['sr_no'];
            $old_incomings = $this->oldincoming::where('serial_no', $sr_no)->paginate(10)->setPath('');
            $pagination = $old_incomings->appends(
                array(
                    'sr_no' => Input::get('sr_no'),
                )
            );
        } else if (!empty($_GET['status'])) {
            $status = $_GET['status'];
            $old_incomings = $this->oldincoming::where('status', $status)->paginate(5)->setPath('');
            $pagination = $old_incomings->appends(
                array(
                    'status' => Input::get('status'),
                )
            );
        } else {
            $old_incomings = $this->oldincoming->paginate(10);
        }
        $issues = $issues->orderBy('created_at', 'DESC')->with('communications')->get();
        $status = $this->oldincoming->getStatus();
        return view('old-incomings.index', compact('status', 'old_incomings', 'issues'));
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
        $this->oldincoming->saveRecord($request);
        Session::flash('success', 'Record Created');
        return Redirect::back();
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
    public function edit($serial_no)
    {        
        $old_incoming = $this->oldincoming::where('serial_no', $serial_no)->first();
        $status = $this->oldincoming->getStatus();
        return view('old-incomings.edit', compact('status', 'old_incoming'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $serial_no)
    {
        $this->oldincoming->updateRecord($request, $serial_no);
        Session::flash('success', 'Record Updated');
        return redirect('old-incomings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
