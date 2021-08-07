@extends('layouts.app')


@section('content')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

<div class="row">
  <div class="col-lg-10 margin-tb">
      <h2>Image</h2>
  </div>

  <div class="col-lg-2 mt-4">
    <a href="{{ route('image.grid') }}" class="btn btn-secondary">Back</a>
  </div>
</div>

@if ($message = Session::get('success'))
  <div class="alert alert-success">
    {{ $message }}
  </div>
@endif

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <strong>Brand:</strong> {{ $image->brand ? $brands[$image->brand] : '' }}
    </div>

    <div class="form-group">
      <strong>Category:</strong> {{ $categories_array[$image->category] }}
    </div>

    <div class="form-group">
      <strong>Price:</strong> {{ $image->price }}
    </div>

    @if ($image->status == 2 && $image->publish_date)
      <div class="form-group">
        <strong>Publish Date:</strong> {{ isset($image->publish_date) ? $image->publish_date : date('Y-m-d H:i') }}
      </div>
    @endif

    @if (isset($image->approved_user))
      <div class="form-group">
        <strong>Approved:</strong> by {{ App\User::find($image->approved_user)->name}} on {{ Carbon\Carbon::parse($image->approved_date)->format('d-m') }}
      </div>
    @endif

    <div class="form-group">
      <strong>Tags</strong> <br>
      @foreach ($image->tags as $tag)
        <span class="label label-default">{{ $tag->tag }}</span>
      @endforeach
    </div>

  </div>

  <div class="col-md-6">
    <img src="{{ $image->filename ? (asset('uploads/social-media') . '/' . $image->filename) : ($image->getMedia(config('constants.media_tags'))->first() ? $image->getMedia(config('constants.media_tags'))->first()->getUrl() : '') }}" class="img-responsive" alt="">
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
  $('#publish-date').datetimepicker({
    format: 'YYYY-MM-DD HH:mm'
  });

  $('#multi_category').select2({
      placeholder: 'Category',
      disabled: 'readonly'
  });
</script>

@endsection
