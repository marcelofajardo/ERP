@extends('layouts.app')


@section('content')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">

<div class="row">
  <div class="col-12 margin-tb mb-3">
    <h2 class="page-heading">Approved Images</h2>

    <form action="{{ route('image.grid.approved') }}" method="GET" class="form-inline align-items-start">
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

      <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
      <a href="{{url()->current()}}" class="btn btn-image"><img src="/images/clear-filters.png"/></a>
    </form>

     @if(auth()->user()->checkPermission('social-create'))
      <div class="pull-right">
        {{-- <a href class="btn btn-secondary" data-toggle="modal" data-target="#imageModal">Upload Templates</a> --}}
        <a href class="btn btn-secondary" id="toggleButton" data-toggle="modal" data-target="#imageModal" style="display: none;">Upload Templates</a>
        <a href class="btn btn-secondary select-image">Upload Templates</a>
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
              <input type="hidden" name="status" value="2">
              <input type="hidden" name="image_id" value="">

              <div class="modal-body">
                <div class="form-group">
                     <input type="file" name="images[]" required />
                     @if ($errors->has('images'))
                         <div class="alert alert-danger">{{$errors->first('images')}}</div>
                     @endif
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
    @endif
  </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  {{ $message }}
</div>
@endif

@if ($errors->any())
  <div class="alert alert-danger">
      <strong>Whoops!</strong> There were some problems with your input.<br><br>
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif

<div class="row">
  @foreach ($images as $image)
  <div class="col-md-3 col-xs-6 text-center mb-5">
    <img src="{{ $image->filename ? (asset('uploads/social-media') . '/' . $image->filename) : ($image->getMedia(config('constants.media_tags'))->first() ? $image->getMedia(config('constants.media_tags'))->first()->getUrl() : '') }}" class="img-responsive grid-image" alt="" />
    <input type="checkbox" class="form-control image-selection" value="{{ $image->id }}" style="display: none;">
    <a class="btn btn-image" href="{{ route('image.grid.show',$image->id) }}"><img src="/images/view.png" /></a>

    @if(auth()->user()->checkPermission('social-create'))
      <a class="btn btn-image" href="{{ route('image.grid.edit',$image->id) }}"><img src="/images/edit.png" /></a>

      {!! Form::open(['method' => 'DELETE','route' => ['image.grid.delete', $image->id],'style'=>'display:inline']) !!}
        <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
      {!! Form::close() !!}
    @endif

    <a href="{{ route('image.grid.download', $image->id) }}" class="btn-link">Download</a>

    @if (isset($image->approved_user))
      <span>Approved by {{ App\User::find($image->approved_user)->name}} on {{ Carbon\Carbon::parse($image->approved_date)->format('d-m') }}</span>
    @endif
  </div>
  @endforeach
</div>

{!! $images->appends(Request::except('page'))->links() !!}

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
       $(".select-multiple").select2();

       $('.select-image').on('click', function(e) {
         e.preventDefault();

         $('.image-selection').show();
       });

       $(document).on('click', '.image-selection', function() {
         $('.image-selection').prop('checked', false);

         $(this).prop('checked', true);
         $('input[name="image_id"]').val($(this).val());
         // jQuery.noConflict();
         $('#imageModal').modal('toggle')
       });
    });
  </script>
@endsection
