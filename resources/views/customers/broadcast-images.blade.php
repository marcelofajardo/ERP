@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
@endsection

@section('content')

<div class="row">
  <div class="col-12 margin-tb mb-3">
    <h2 class="page-heading">Broadcast Images</h2>

    {{-- <form action="{{ route('image.grid.approved') }}" method="GET" class="form-inline align-items-start">
      <div class="form-group mr-3 mb-3">
        {!! $category_selection !!}
      </div>

      <div class="form-group mr-3">
        <select class="form-control select-multiple" name="brand[]" multiple>
          <optgroup label="Brands">
            @foreach ($brands as $key => $name)
              <option value="{{ $key }}" {{ isset($brand) && $brand == $key ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </optgroup>
        </select>
      </div>

      <div class="form-group mr-3">
        <strong class="mr-3">Price</strong>
        <input type="text" name="price" data-provide="slider" data-slider-min="0" data-slider-max="10000000" data-slider-step="10" data-slider-value="[{{ isset($price) ? $price[0] : '0' }},{{ isset($price) ? $price[1] : '10000000' }}]"/>
      </div>

      <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
    </form> --}}

    <div class="pull-right">
      <!-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#sendAllModal">Create Broadcast</button> -->
      <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#uploadImagesModal">Upload Images</button>
    </div>

    @include('customers.partials.modal-upload-images')
    @include('customers.partials.modal-send-to-all')

  </div>
</div>

@include('partials.flash_messages')

<div class="row">
  @foreach ($broadcast_images as $image)
  <div class="col-md-3 col-xs-6 text-center mb-5">
    <img src="{{ $image->hasMedia(config('constants.media_tags')) ? $image->getMedia(config('constants.media_tags'))->first()->getUrl() : '#no-image' }}" class="img-responsive grid-image" alt="" onclick="sendBroadCastAll({{ $image->id }})" />

    @if ($image->products)
      <span class="badge">Linked</span>
    @else
      {{-- <a href="{{ route('attachImages', ['broadcast-images', $image->id]) }}" class="btn-link">Link</a> --}}
      <a href="{{ route('attachProducts', ['broadcast-images', $image->id]) }}" class="btn-link">Link Products</a>
    @endif

    <input type="checkbox" class="form-control image-selection hidden" value="{{ $image->id }}">
    {{-- <a class="btn btn-image" href="{{ route('image.grid.show',$image->id) }}"><img src="/images/view.png" /></a> --}}

    {{-- @can ('social-create') --}}
      {{-- <a class="btn btn-image" href="{{ route('image.grid.edit',$image->id) }}"><img src="/images/edit.png" /></a> --}}

    {!! Form::open(['method' => 'DELETE','route' => ['broadcast.images.delete', $image->id],'style'=>'display:inline']) !!}
      <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
    {!! Form::close() !!}
    {{-- @endcan --}}

    {{-- <a href="{{ route('image.grid.download', $image->id) }}" class="btn-link">Download</a>

    @if (isset($image->approved_user))
      <span>Approved by {{ App\User::find($image->approved_user)->name}} on {{ Carbon\Carbon::parse($image->approved_date)->format('d-m') }}</span>
    @endif --}}
  </div>
  @endforeach
</div>

{!! $broadcast_images->appends(Request::except('page'))->links() !!}

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script type="text/javascript">
    var images_selection = [];

    $(document).on('click', '.link-images-button', function() {
      $('.image-selection').removeClass('hidden');

      $('#sendAllModal').find('.close').click();
    });

    $(document).on('click', '.image-selection', function() {
      var id = $(this).val();

      if ($(this).prop('checked') == true) {
        images_selection.push(id);
      } else {
        var index = images_selection.indexOf(id);
        images_selection.splice(index, 1);
      }

      $('#linked_images').val(JSON.stringify(images_selection));
      console.log(images_selection);
    });

    function sendBroadCastAll(id){
      $('#broadcast_image').val(id);
      $('#sendAllModal').modal('show');
    }

    $( "#platform" ).change(function() {
      platform = $( "#platform option:selected" ).text().toLowerCase();;
      if(platform == 'instagram'){
          $('.gender').hide();
          $('.shoe-size').hide();
          $('.clothing-size').hide();
          $('.select-group').hide();
          $('#message').text('Please Enter Instagram Link');
      }else if(platform == 'facebook'){
          $('.gender').hide();
          $('.shoe-size').hide();
          $('.clothing-size').hide();
          $('.select-group').hide();
      }else{
          $('.gender').show();
          $('.shoe-size').show();
          $('.clothing-size').show();
          $('.select-group').show();
      }
  }); 
  </script>
@endsection
