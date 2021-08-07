@extends('layouts.app')

@section('title', 'Settings')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.css">
@endsection

@section('content')

    <div class="row">
      <div class="col-12">
        <h2 class="page-heading">Settings</h2>
      </div>
    </div>

    @include('partials.flash_messages')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">


            <form method="post" action="{{ route('settings.store') }}" class="form-horizontal" role="form">
                {!! csrf_field() !!}

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-flash"></i>
                        <strong></strong>
                    </div>

                    <div class="panel-body">
                        {{--<div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="form-group">
                                    <strong> Euro to inr conversion:</strong>
                                    <input type="text" class="form-control" name="euro_to_inr" placeholder="Eur to inr" value="{{ old('euro_to_inr') ? old('euro_to_inr') : $euro_to_inr }}"/>
                                    @if ($errors->has('euro_to_inr'))
                                        <div class="alert alert-danger">{{$errors->first('euro_to_inr')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="form-group">
                                    <strong> Special Price Discount (%):</strong>
                                    <input type="number" class="form-control" name="special_price_discount" placeholder="Special Price Discount " value="{{ old('special_price_discount') ? old('special_price_discount') : $special_price_discount }}"/>
                                    @if ($errors->has('special_price_discount'))
                                        <div class="alert alert-danger">{{$errors->first('special_price_discount')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>--}}
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                              <div class="d-flex justify-content-between">
                                <div class="form-group">
                                  <strong>Start Time</strong>
                                  <div class='input-group date' id='start-time'>
                                    <input type='text' class="form-control input-sm" name="start_time" value="{{ $start_time }}" required />

                                    <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                  </div>

                                  @if ($errors->has('start_time'))
                                    <div class="alert alert-danger">{{$errors->first('start_time')}}</div>
                                  @endif
                                </div>

                                <div class="form-group ml-3">
                                  <strong>End Time</strong>
                                  <div class='input-group date' id='end-time'>
                                    <input type='text' class="form-control input-sm" name="end_time" value="{{ $end_time }}" required />

                                    <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                  </div>

                                  @if ($errors->has('end_time'))
                                    <div class="alert alert-danger">{{$errors->first('end_time')}}</div>
                                  @endif
                                </div>
                              </div>

                                <div class="form-group">
                                    <strong> Pagination:</strong>
                                    <input type="number" class="form-control" name="pagination" placeholder="Number of products per pages" value="{{ old('pagination') ? old('pagination') : $pagination }}"/>
                                    @if ($errors->has('pagination'))
                                        <div class="alert alert-danger">{{$errors->first('pagination')}}</div>
                                    @endif
                                </div>

                                <hr>

                                <div class="form-group">
                                    <input type="checkbox" name="disable_twilio" id="disable_twilio" {{ $disable_twilio ? 'checked' : '' }} />
                                    <label for="disable_twilio">Disable Twilio:</label>
                                    @if ($errors->has('disable_twilio'))
                                        <div class="alert alert-danger">{{$errors->first('disable_twilio')}}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" name="incoming_calls_yogesh" id="incoming_calls_yogesh" {{ $incoming_calls_yogesh ? 'checked' : '' }} />
                                    <label for="incoming_calls_yogesh">Incoming Calls for Yogesh:</label>
                                    @if ($errors->has('incoming_calls_yogesh'))
                                        <div class="alert alert-danger">{{$errors->first('incoming_calls_yogesh')}}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" name="incoming_calls_andy" id="incoming_calls_andy" {{ $incoming_calls_andy ? 'checked' : '' }} />
                                    <label for="incoming_calls_andy">Incoming Calls for Andy:</label>
                                    @if ($errors->has('incoming_calls_andy'))
                                        <div class="alert alert-danger">{{$errors->first('incoming_calls_andy')}}</div>
                                    @endif
                                </div>

                                <hr>

                                <div class="form-group">
                                    <strong>User for Image Shortcut:</strong>
                                    <select class="selectpicker form-control" data-live-search="true" data-size="15" name="image_shortcut" title="Choose a User" required>
                                        @foreach ($users_array as $index => $user)
                                            <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}" {{ $index == $image_shortcut ? 'selected' : '' }}>{{ $user }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('image_shortcut'))
                                        <div class="alert alert-danger">{{$errors->first('image_shortcut')}}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <strong>User for Price Shortcut:</strong>
                                    <select class="selectpicker form-control" data-live-search="true" data-size="15" name="price_shortcut" title="Choose a User" required>
                                        @foreach ($users_array as $index => $user)
                                            <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}" {{ $index == $price_shortcut ? 'selected' : '' }}>{{ $user }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('price_shortcut'))
                                        <div class="alert alert-danger">{{$errors->first('price_shortcut')}}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <strong>User for Call Shortcut:</strong>
                                    <select class="selectpicker form-control" data-live-search="true" data-size="15" name="call_shortcut" title="Choose a User" required>
                                        @foreach ($users_array as $index => $user)
                                            <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}" {{ $index == $call_shortcut ? 'selected' : '' }}>{{ $user }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('call_shortcut'))
                                        <div class="alert alert-danger">{{$errors->first('call_shortcut')}}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <strong>User for Attach Image Physically Shortcut:</strong>
                                    <select class="selectpicker form-control" data-live-search="true" data-size="15" name="screenshot_shortcut" title="Choose a User" required>
                                        @foreach ($users_array as $index => $user)
                                            <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}" {{ $index == $screenshot_shortcut ? 'selected' : '' }}>{{ $user }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('screenshot_shortcut'))
                                        <div class="alert alert-danger">{{$errors->first('screenshot_shortcut')}}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <strong>User for Give Details Shortcut:</strong>
                                    <select class="selectpicker form-control" data-live-search="true" data-size="15" name="details_shortcut" title="Choose a User" required>
                                        @foreach ($users_array as $index => $user)
                                            <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}" {{ $index == $details_shortcut ? 'selected' : '' }}>{{ $user }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('details_shortcut'))
                                        <div class="alert alert-danger">{{$errors->first('details_shortcut')}}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <strong>User for Check Purchase Shortcut:</strong>
                                    <select class="selectpicker form-control" data-live-search="true" data-size="15" name="purchase_shortcut" title="Choose a User" required>
                                        @foreach ($users_array as $index => $user)
                                            <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}" {{ $index == $purchase_shortcut ? 'selected' : '' }}>{{ $user }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('details_shortcut'))
                                        <div class="alert alert-danger">{{$errors->first('details_shortcut')}}</div>
                                    @endif
                                </div>

                                <hr>

                                <div class="form-group">
                                  <strong>Consignor Name:</strong>
                                  <input type="text" class="form-control" name="consignor_name" value="{{ $consignor_name }}" required>

                                  @if ($errors->has('consignor_name'))
                                    <div class="alert alert-danger">{{$errors->first('consignor_name')}}</div>
                                  @endif
                                </div>

                                <div class="form-group">
                                  <strong>Consignor Address:</strong>
                                  <input type="text" class="form-control" name="consignor_address" value="{{ $consignor_address }}" required>

                                  @if ($errors->has('consignor_address'))
                                    <div class="alert alert-danger">{{$errors->first('consignor_address')}}</div>
                                  @endif
                                </div>

                                <div class="form-group">
                                  <strong>Consignor City:</strong>
                                  <input type="text" class="form-control" name="consignor_city" value="{{ $consignor_city }}" required>

                                  @if ($errors->has('consignor_city'))
                                    <div class="alert alert-danger">{{$errors->first('consignor_city')}}</div>
                                  @endif
                                </div>

                                <div class="form-group">
                                  <strong>Consignor Country:</strong>
                                  <input type="text" class="form-control" name="consignor_country" value="{{ $consignor_country }}" required>

                                  @if ($errors->has('consignor_country'))
                                    <div class="alert alert-danger">{{$errors->first('consignor_country')}}</div>
                                  @endif
                                </div>

                                <div class="form-group">
                                  <strong>Consignor Phone:</strong>
                                  <input type="text" class="form-control" name="consignor_phone" value="{{ $consignor_phone }}" required>

                                  @if ($errors->has('consignor_phone'))
                                    <div class="alert alert-danger">{{$errors->first('consignor_phone')}}</div>
                                  @endif
                                </div>

                                <hr>

                                <div class="form-group">
                                  <input type="checkbox" name="forward_messages" id="forward_messages" {{ $forward_messages ? 'checked' : '' }} />
                                  <label for="forward_messages">Forward Messages to Personal:</label>
                                  @if ($errors->has('forward_messages'))
                                    <div class="alert alert-danger">{{$errors->first('forward_messages')}}</div>
                                  @endif
                                </div>

                                <div class="form-group">
                                  <strong>Select Users to Forward to</strong>
                                  <select class="form-control select-multiple" name="forward_users[]" multiple>
                                    @foreach ($users_array as $index => $user)
                                      <option value="{{ $index }}" {{ isset($forward_users) && in_array($index, $forward_users) ? 'selected' : '' }}>{{ $user }}</option>
                                    @endforeach
                                  </select>
                                </div>

                                <div class="form-group">
                                  <strong>Start Date:</strong>
                                  <div class='input-group date' id='forward-start-date'>
                                    <input type='text' class="form-control" name="forward_start_date" value="{{ $forward_start_date }}" />

                                    <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                  </div>

                                  @if ($errors->has('forward_start_date'))
                                      <div class="alert alert-danger">{{$errors->first('forward_start_date')}}</div>
                                  @endif
                                </div>

                                <div class="form-group">
                                  <strong>End Date:</strong>
                                  <div class='input-group date' id='forward-end-date'>
                                    <input type='text' class="form-control" name="forward_end_date" value="{{ $forward_end_date }}" />

                                    <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                  </div>

                                  @if ($errors->has('forward_end_date'))
                                      <div class="alert alert-danger">{{$errors->first('forward_end_date')}}</div>
                                  @endif
                                </div>

                                <h4>Welcome Message</h4>
                                {!! $welcome_message !!}
                                <div class="form-group">
                                  <input name="welcome_message" type="text" value="{!! $welcome_message !!}" class="jqte-test">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row m-b-md">
                    <div class="col-md-12">
                        <button class="btn-secondary btn">
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.js"></script>
  <script type="text/javascript">
    $('#add-more-fields').on('click', function() {
      var count = $(this).data('count') + 1;
      $(this).data('count', count);
      var fields = $('<div class="form-row"><div class="form-group col"><strong>Number:</strong><input type="number" class="form-control" name="number[]" value="" required></div><div class="form-group col ml-3"><strong>API Key:</strong><input type="text" class="form-control" name="key[]" value="" required><input type="radio" name="default" value="' + count + '"></div><div class="col-xs-12"><button type="button" class="btn btn-image remove-fields"><img src="/images/delete.png" /></button></div></div>');

      $('#apiwha-container').append(fields);

      console.log($(this).data('count'));
    });

    $(document).on('click', '.remove-fields', function() {
      $(this).closest('.form-row').remove();
      var count = $('#add-more-fields').data('count');
      $('#add-more-fields').data('count', count - 1);
      console.log($('#add-more-fields').data('count'));
    });

    var api_keys = {!! json_encode($api_keys) !!};

    Object.keys(api_keys).forEach(function(index) {
      if (index == 0) {
        $('#first_number').val(api_keys[index].number);
        $('#first_key').val(api_keys[index].key);

        if (api_keys[index].default == 1) {
          $('#first_default').prop('checked', true);
        }
      } else {
        var checked = api_keys[index].default == 1 ? 'checked' : '';
        var count = parseInt(index, 10) + 1;
        var fields = $('<div class="form-row"><div class="form-group col"><strong>Number:</strong><input type="number" class="form-control" name="number[]" value="' + api_keys[index].number + '" required></div><div class="form-group col ml-3"><strong>API Key:</strong><input type="text" class="form-control" name="key[]" value="' + api_keys[index].key + '" required><input type="radio" name="default" value="' + count + '" ' + checked + '></div><div class="col-xs-12"><button type="button" class="btn btn-image remove-fields"><img src="/images/delete.png" /></button></div></div>');

        $('#apiwha-container').append(fields);
      }
    });

    $(document).ready(function() {
       $(".select-multiple").multiselect();

       $('#forward-start-date, #forward-end-date').datetimepicker({
         format: 'YYYY-MM-DD HH:mm'
       });

       $('#start-time, #end-time').datetimepicker({
         format: 'HH:mm'
       });
    });

    $('.jqte-test').jqte();
  
  // settings of status
  var jqteStatus = true;
  $(".status").click(function()
  {
    jqteStatus = jqteStatus ? false : true;
    $('.jqte-test').jqte({"status" : jqteStatus})
  });


  </script>
@endsection
