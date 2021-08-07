@extends('layouts.app')


@section('content')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">

<div class="row">
  <div class="col-12 margin-tb mb-3">
    <h2 class="page-heading">LifeStyle Image Grid</h2>

    <form action="{{ route('image.grid') }}" method="GET" class="form-inline align-items-start">
      <div class="form-group mr-3 mb-3">
        {!! $category_selection !!}
      </div>

      <div class="form-group mr-3">
        <select class="form-control select-multiple" name="brand[]" multiple data-placeholder="Brands...">
            @foreach ($brands as $key => $name)
              <option value="{{ $key }}" {{ isset($brand) && $brand == $key ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
      </div>

      <div class="form-group mr-3">
        <strong class="mr-3">Price</strong>
        <input type="text" name="price" data-provide="slider" data-slider-min="0" data-slider-max="10000000" data-slider-step="10" data-slider-value="[{{ isset($price) ? $price[0] : '0' }},{{ isset($price) ? $price[1] : '10000000' }}]"/>
      </div>

      <button type="submit" class="btn btn-image"><img src="{{asset('/images/filter.png')}}" /></button>
      <a href="{{url()->current()}}" class="btn btn-image"><img src="{{asset('/images/clear-filters.png')}}"/></a>
    </form>

    {{-- <strong>Sort By</strong>
    <a href="{{ route('image.grid') . '?sortby=asc' }}" class="btn-link">ASC</a>
     |
    <a href="{{ route('image.grid') . '?sortby=desc' }}" class="btn-link">DESC</a> --}}
  {{-- </div>
  <div class="col-lg-2 mt-4"> --}}
  @if(auth()->user()->checkPermission('social-create'))
    <div class="pull-right btn-group">
      <a href="{{ route('attachImages', ['images']) }}" class="btn btn-secondary">Attach Images</a>
      <a href class="btn btn-secondary" data-toggle="modal" data-target="#imageModal">Upload</a>
      <a href class="btn btn-secondary" data-toggle="modal" data-target="#queueModal">Image Queue</a>
    </div>

    <div id="imageModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Upload Images</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form action="{{ route('image.grid.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="status" value="1">

            <div class="modal-body">
              <div class="form-group">
                   <input type="file" name="images[]" multiple required />
                   @if ($errors->has('images'))
                       <div class="alert alert-danger">{{$errors->first('images')}}</div>
                   @endif
              </div>

              <div class="form-check">
                <input class="" type="radio" name="lifestyle" id="exampleRadios1" value="0" checked>
                <label class="form-check-label" for="exampleRadios1">
                  Default
                </label>
              </div>

              <div class="form-check">
                <input class="" type="radio" name="lifestyle" id="exampleRadios2" value="1">
                <label class="form-check-label" for="exampleRadios2">
                  Lifestyle
                </label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-secondary">Upload</button>
            </div>
          </form>
        </div>

      </div>
    </div>

    <!---   Image Queue Modal  !-->
    <div id="queueModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Image Queue</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form action="{{ route('image.queue') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <div class="form-group">
                <label>Search Term</label>
                <input type="text" name="search_term" class="form-control" required />
                @if ($errors->has('search_term'))
                    <div class="alert alert-danger">{{$errors->first('search_term')}}</div>
                @endif
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-secondary">Save</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  @endif
  </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  {{ $message }}
</div>
@endif

<div class="row">
  @foreach ($images as $image)
  <div class="col-md-3 col-xs-6 text-center mb-5">
    <img src="{{ $image->filename ? (asset('uploads/social-media') . '/' . $image->filename) : ($image->getMedia(config('constants.media_tags'))->first() ? $image->getMedia(config('constants.media_tags'))->first()->getUrl() : '') }}" class="img-responsive grid-image" alt="" />

    <a class="btn btn-image" href="{{ route('image.grid.show',$image->id) }}"><img src="{{asset('/images/view.png')}}" /></a>
    @if(auth()->user()->checkPermission('social-create'))
      <a class="btn btn-image" href="{{ route('image.grid.edit',$image->id) }}"><img src="{{asset('/images/edit.png')}}" /></a>

      {!! Form::open(['method' => 'DELETE','route' => ['image.grid.delete', $image->id],'style'=>'display:inline']) !!}
        <button type="submit" class="btn btn-image"><img src="{{asset('/images/delete.png')}}" /></button>
      {!! Form::close() !!}
    @endif

    @if (isset($image->approved_user))
      <span>Approved by {{ App\User::find($image->approved_user)->name}} on {{ Carbon\Carbon::parse($image->approved_date)->format('d-m') }}</span>
    @else
      @if(auth()->user()->checkPermission('social-create'))
        {{-- <form action="{{ route('image.grid.approveImage', $image->id) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-xs btn-secondary">Approve</button>
        </form> --}}
        <button type="button" class="btn btn-xs btn-secondary approve-image" data-id="{{ $image->id }}">Approve</button>
      @endif
    @endif
  </div>
  @endforeach
</div>

{!! $images->appends(Request::except('page'))->links() !!}

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
     $(".select-multiple").select2();
  });

  $(document).on('click', '.approve-image', function() {
    var id = $(this).data('id');
    var thiss = $(this);

    $.ajax({
      type: "POST",
      url: "{{ url('images/grid') }}/" + id + "/approveImage",
      data: {
        _token: "{{ csrf_token() }}"
      },
      beforeSend: function() {
        $(thiss).text('Approving');
      }
    }).done(function() {
      $(thiss).parent('div').remove();
    }).fail(function(response) {
      console.log(response);
      alert('Error while approving image');
    });
  });
</script>
@if (Session::has('errors'))
    <script>
        toastr["error"]("{{ $errors->first() }}", "Message")
    </script>
@endif
@if (Session::has('success'))
    <script>
        toastr["success"]("{{Session::get('success')}}", "Message")
    </script>
@endif
@endsection
