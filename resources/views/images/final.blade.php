@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
@endsection

@section('content')

<div class="row">
  <div class="col-12 margin-tb mb-3">
    <h2 class="page-heading">Final Approval</h2>

    <form action="{{ route('image.grid.final.approval') }}" method="GET" class="form-inline align-items-start">
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
    </form>
  </div>

  @if(auth()->user()->checkPermission('social-create'))
    <div class="col-12 mb-3">
      <div class="pull-right">
        <a href class="btn btn-secondary select-image">Select Images</a>
        <a href class="btn btn-secondary" data-toggle="modal" data-target="#imageModal">Create a Set</a>
      </div>
    </div>

    <div id="imageModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Create a Set</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form action="{{ route('image.grid.set') }}" method="POST" id="setForm">
            @csrf
            <input type="hidden" name="image_id" value="" id="image_ids">

            <div class="modal-body">
              <div class="form-group">
                <strong>Publish Date:</strong>
                <div class='input-group date' id='publish-date'>
                  <input type='text' class="form-control" name="publish_date" value="{{ date('Y-m-d H:i') }}" required />

                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>

                @if ($errors->has('publish_date'))
                    <div class="alert alert-danger">{{$errors->first('publish_date')}}</div>
                @endif
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-secondary" id="setFormSubmit">Create a Set</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  @endif
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  {{ $message }}
</div>
@endif

<div id="exTab2" class="container">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#1" data-toggle="tab">Images</a>
    </li>
    <li><a href="#2" data-toggle="tab">Calendar</a></li>
    <li><a href="#3" data-toggle="tab">Statistics</a></li>
  </ul>
</div>

<div class="tab-content ">
  <div class="tab-pane active mt-3" id="1">
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
  </div>

  <div class="tab-pane mt-3" id="2">
    <div class="row">
      <div class="col-12">
        <div id="calendar"></div>
      </div>
    </div>
  </div>

  <div class="tab-pane mt-3" id="3">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Date</th>
                <th>Brand Counts</th>
                <th>Category Counts</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($stats_brand as $date => $stat)
                <tr>
                  <td>{{ $date }}</td>
                  <td>
                    <ul>
                      @foreach ($stat as $brand_id => $datas)
                        <li>{{ $brands[$brand_id] }} ({{ count($datas) }})</li>
                      @endforeach
                    </ul>
                  </td>
                  <td>
                    <ul>
                      @foreach ($stats_category[$date] as $category_id => $data)
                        <li>{{ $categories_array[$category_id] }} ({{ count($data) }})</li>
                      @endforeach
                    </ul>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="calendarModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{ route('image.grid.set.download') }}" method="POST" id="downloadForm">
        @csrf
        <input type="hidden" name="images" value="" id="download_images_field">
        <div class="modal-body">
          <div class="form-group" id="image_container">

          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-secondary" id="downloadImages">Download All</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>

  </div>
</div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script type="text/javascript">
    $('#publish-date').datetimepicker({
      format: 'YYYY-MM-DD HH:mm'
    });

    $(document).ready(function() {
       $(".select-multiple").multiselect();
       $('#calendar').fullCalendar({
         editable: true,
         header: {
           right: "month,agendaWeek,agendaDay, today prev,next",
         },
          events: [
            @foreach ($image_sets as $time => $set)
              {
                title: 'Post',
                start: "{{ $time }}",
                image_names: [
                  @foreach ($set as $image)
                    {
                      "id": {{ $image->id }},
                      'name': "{{ asset('uploads/social-media') . '/' . $image->filename }}",
                    },
                  @endforeach
                ]
              },
            @endforeach
          ],
          eventClick: function(calEvent, jsEvent, view) {
            $('#image_container').empty();

            var download_images = [];
            var image = '<div class="row">';
            calEvent.image_names.forEach(function(img) {
              image += '<div class="col-md-4"><img src="' + img.name + '" class="img-responsive" /></div>';
              download_images.push(img.id);
            });
            image += '</div>';

            $('#image_container').append($(image));
            $('#download_images_field').val(JSON.stringify(download_images));

            // jQuery.noConflict();
            $('#calendarModal').modal('toggle');
          },
          eventRender: function(event, eventElement) {
            if (event.image_names) {
              event.image_names.forEach(function(image) {
                eventElement.find("div.fc-content").prepend("<img src='" + image.name +"' width='50' height='50'>");
              });
            }
          },
          eventDrop: function(event, delta, revertFunc) {
            $.ajax({
              type: "POST",
              url: "{{ route('image.grid.update.schedule') }}",
              data: {
                _token: "{{ csrf_token() }}",
                images: event.image_names,
                date: event.start.format('Y-MM-DD H:mm')
              }
            }).done(function(response) {

            }).fail(function(response) {
              alert('Could not update schedule');
              console.log(response);
            });
          }
        });
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
      }).done(function(response) {
        var users_array = {!! json_encode(\App\Helpers::getUserArray(\App\User::all())) !!};
        var span = $('<span>Approved by ' + users_array[response.user] + ' on ' + moment(response.date).format('DD-MM') + '</span>');

        $(thiss).parent('div').append(span);
        $(thiss).remove();
      }).fail(function(response) {
        console.log(response);
        alert('Error while approving image');
      });
    });

    var images_array = [];
    $('.select-image').on('click', function(e) {
      e.preventDefault();

      $('.image-selection').show();
    });

    $(document).on('click', '.image-selection', function() {
      images_array.push($(this).val());
    });

    $('#setFormSubmit').on('click', function(e) {
      e.preventDefault();

      if (images_array.length == 0) {
        alert('Please select first some images!');
        return;
      }

      $('#image_ids').val(JSON.stringify(images_array));
      $('#setForm').submit();
    });
  </script>
@endsection
