<?php

namespace App\Http\Controllers;

use App\Complaint;
use App\ComplaintThread;
use App\StatusChange;
use Auth;
use Storage;
use Illuminate\Http\Request;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
    //    $this->middleware('permission:review-view');
    }

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
        'customer_id'     => 'sometimes|nullable|integer',
        'platform'        => 'sometimes|nullable|string',
        'complaint'       => 'required|string|min:3',
        'thread.*'        => 'sometimes|nullable|string',
        'account_id.*'    => 'sometimes|nullable|numeric',
        'link'            => 'sometimes|nullable|url',
        'where'           => 'sometimes|nullable|string',
        'username'        => 'sometimes|nullable|string',
        'name'            => 'sometimes|nullable|string',
        'plan_of_action'  => 'sometimes|nullable|string',
        'date'            => 'required|date'
      ]);

      $data = $request->except('_token');

      $complaint = Complaint::create($data);

      if ($request->thread[0] != null) {
        foreach ($request->thread as $key => $thread) {
          ComplaintThread::create([
            'complaint_id' => $complaint->id,
            'account_id'   => array_key_exists($key, $request->account_id) ? $request->account_id[$key] : '',
            'thread'       => $thread
          ]);
        }
      }

      if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
          $media = MediaUploader::fromSource($image)->toDirectory('reviews-images')->upload();
          $complaint->attachMedia($media,config('constants.media_tags'));
        }
      }

      return redirect()->route('review.index')->withSuccess('You have successfully added complaint');
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
        'customer_id'     => 'sometimes|nullable|integer',
        'platform'        => 'sometimes|nullable|string',
        'complaint'       => 'required|string|min:3',
        'thread.*'        => 'sometimes|nullable|string',
        'account_id.*'    => 'sometimes|nullable|numeric',
        'link'            => 'sometimes|nullable|url',
        'where'           => 'sometimes|nullable|string',
        'username'        => 'sometimes|nullable|string',
        'name'            => 'sometimes|nullable|string',
        'plan_of_action'  => 'sometimes|nullable|string',
        'date'            => 'required|date'
      ]);

      $data = $request->except('_token');

      $complaint = Complaint::find($id);
      $complaint->update($data);

      if ($request->thread[0] != null) {
        $complaint->threads()->delete();

        foreach ($request->thread as $key => $thread) {
          ComplaintThread::create([
            'complaint_id' => $complaint->id,
            'account_id'   => array_key_exists($key, $request->account_id) ? $request->account_id[$key] : '',
            'thread'       => $thread
          ]);
        }
      }

      if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
          $media = MediaUploader::fromSource($image)->toDirectory('reviews-images')->upload();
          $complaint->attachMedia($media,config('constants.media_tags'));
        }
      }

      return redirect()->route('review.index')->withSuccess('You have successfully updated complaint');
    }

    public function updateStatus(Request $request, $id)
    {
      $complaint = Complaint::find($id);

      StatusChange::create([
        'model_id'    => $complaint->id,
        'model_type'  => Complaint::class,
        'user_id'     => Auth::id(),
        'from_status' => $complaint->status,
        'to_status'   => $request->status
      ]);

      $complaint->status = $request->status;
      $complaint->save();

      return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $complaint = Complaint::find($id);
      $complaint->threads()->delete();
      $complaint->internal_messages()->delete();
      $complaint->plan_messages()->delete();
      $complaint->remarks()->delete();
      if ($complaint->hasMedia(config('constants.media_tags'))) {
        foreach ($complaint->getMedia(config('constants.media_tags')) as $image) {
          // dd(public_path() . '/' . $image->getDiskPath());
          Storage::delete($image->getDiskPath());
        }

        $complaint->detachMediaTags(config('constants.media_tags'));
      }

      $complaint->delete();

      return redirect()->route('review.index')->withSuccess('You have successfully deleted complaint');
    }
}
