@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('content')

  <div class="row">
        <div class="col-lg-12">
          @include('partials.flash_messages')
        </div>
        <div class="col-lg-12 margin-tb">

          <h2 class="page-heading">Account: {{ $account->first_name }} {{ $account->last_name }}</h2>
          <p>Send messages to Instagram Users!</p>

          <form id="bulkMessages">
            @csrf
            <div class="form-group">
              <label for="senders">Senders</label>
              <select name="senders[]" id="senders" multiple class="form-control" style="height: 250px;">
                @foreach($accounts as $acc)
                  <option {{ $acc->id===$account->id ? 'selected' : '' }} value="{{ $acc->id }}">{{ $acc->first_name  }}({{$acc->last_name}})</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="receipts">Receipts (Separated by SPACE)</label>
              <input type="text" name="receipts" id="receipts" class="form-control" value="{{old('receipts')}}">
            </div>
            <div class="form-group">
              <label for="text_message">Message</label>
              <input class="form-control" type="text" name="text_message" id="text_message">
            </div>
            <div class="form-group">
              <h3>PROGRESS: <span id="progress">0</span> OF <span id="totalReceipts">X</span></h3>
              <h3>STATUS: <span id="status">NOT STARTED</span></h3>
            </div>
            <div class="form-group">
              <button class="btn btn-default">Send Bulk Message</button>
            </div>
          </form>

        </div>
    </div>


@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#bulkMessages').submit(function(e) {
        var data = $('#receipts').val();
        var count = data.split(' ');
        var progress = 0;
        e.preventDefault();
        var senders = $('#senders').val();
        senders.forEach(function(id) {
          count.forEach(function (username) {
            setTimeout($.ajax({
              url: '{{action('AccountController@sendMessage', '')}}/' + id,
              type: 'POST',
              data: {
                '_token': '{{ @csrf_token() }}',
                'username': username,
                'message': $("#text_message").val()
              },
              success: function(response) {
                console.log(response);
                progress  = progress + 1;
                $('#progress').html(progress);
              },
              error: function(error) {

              },
              beforeSend: function() {
                $('#totalReceipts').html(senders.length * count.length);
                $('#progress').html(progress);
                if (senders.length * count.length == progress) {
                  $('#totalReceipts').html('X');
                  $('#progress').html(0);
                  alert("Message sent successfully!");
                }
              }
            }), 1000);
          })
        });
      });
    })
  </script>
@endsection
