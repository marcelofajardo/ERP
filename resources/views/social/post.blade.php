@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2 class="ml-4">Page Posting <h2>
            </div>
        </div>
    </div>


    @if ($message = Session::get('message'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif

    <div class="container-fluid"">
        <div class="row">
            <div class="col-md-8">
                <form action="{{route('social.post.page.create')}}" method="post" enctype="multipart/form-data" style="border:1px solid #dedede;padding:4%;border-radius: 5px">
                   @csrf

                   <div class="form-group">
                    <label>Picture <small class="text-danger">* You can select multiple images only </small></label>
                    <input type="file" multiple  name="source[]" class="form-control-file">
                    @if ($errors->has('source.*'))
                    <p class="text-danger">{{$errors->first('source.*')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>Video</label>
                    <input type="file"  name="video" class="form-control-file">
                    @if ($errors->has('video'))
                    <p class="text-danger">{{$errors->first('video')}}</p>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="">Message</label>
                    <input type="text" name="message" class="form-control" placeholder="Type your message">
                    @if ($errors->has('message'))
                    <p class="text-danger">{{$errors->first('message')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea name="description" class="form-control" cols="30" rows="5"></textarea>
                </div>

                <div class="form-group">
                    <label for="">Post on
                     <small class="text-danger">
                     * Can be Scheduled too </small>
                 </label>
                 <input  type="date"  name="date" class="form-control">
             </div>

             <button type="submit" class="btn btn-info">Post Now</button>
         </form>
     </div>
     <div class="col-md-4"></div>
 </div>

</div>





@endsection