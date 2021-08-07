@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')



    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">
              Broadcast Grid ({{ $broadcasts->total() }})
            </h2>
            
            <div class="pull-left">
                            <form action="{{ route('broadcast.index') }}" method="GET">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                <input type="hidden" name="reportrange" id="custom">
                                                <i class="fa fa-calendar"></i>&nbsp;
                                                <span></span> <i class="fa fa-caret-down"></i>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-1">
                                         <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
                                     </div>
                                      <div class="col-md-1">
                                         <button type="button" class="btn btn-image"><a href="/broadcast"><img src="/images/resend2.png"/></a></button>
                                     </div>
                                     
                                 </div>
                             </div>
                         </form>
                     </div>
            <div class="pull-right">
                <!-- <a href="{{ route('customer.whatsapp.stop.all') }}" class="btn btn-secondary">STOP ALL</a> -->
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#sendAllModal">Create Broadcast</button>
            </div>         

             
              
            </div>
        </div>
    

    @include('partials.flash_messages')
 

    <div class="table-responsive mt-3">
        <table class="table table-bordered" id="customers-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Broadcast Number</th>
                <th>Frequency</th>
                <th>Message</th>
                <th>Image</th>
                <th>Start Date and Time</th>
                <th>End Date and Time</th>
                <th>Pending</th>
                <th>Recieved</th>
                <th>Failed</th>
                <th>Total</th>
                
                
            </tr>
            <tr>
                <!-- <th class="show_select"><input type="checkbox" class="form-control" id="select_all"></th>
                <th></th>
                <th><input type="text" class="search form-control" id="name"></th>
                <th><input type="text" class="search form-control" id="number"></th>
                <th><select class="form-control search" id="dnd">
                        <option>Select DND Users</option>
                        <option value="0">Active Users</option>
                        <option value="1">DND Users</option>
                    </select></th>
                <th>
                    <select class="form-control">
                        <option>Asked Price</option>
                        <option>Communication Done Removed</option>
                        <option>Due to not delivered</option>
                        <option>Manual Reject</option>
                    </select>
                </th>
                <th>
                    <select class="form-control search" id="manual">
                        <option value="">Select Manual</option>
                        <option value="1">Active</option>
                        <option value="0">All</option>
                    </select>
                </th>
                <th></th>
                <th></th>
                <th><input type="text" class="search form-control" id="broadcast"></th>
                <th><select class="form-control search" id="whats_number">
                        <option value="">Select Option</option>
                       
                    </select></th>
                <th><input type="text" class="search form-control" id="remark"></th> -->
            </tr>
            </thead>

            <tbody>
                
            {!! $broadcasts->render() !!}
            
            @include('customers.partials.broadcast-list')
            </tbody>
        </table>
        {!! $broadcasts->render() !!}
    </div>
    @include('customers.partials.modal-upload-images')
    @include('customers.partials.modal-send-to-all')

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" type="text/javascript"></script>
  <script type="text/javascript">
    
    $(document).ready(function() {
      $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    });

    $(function() {

                var start = moment().subtract(29, 'days');
                var end = moment();

                function cb(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#custom').val(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
                }

                $('#reportrange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                     'Today': [moment(), moment()],
                     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                     'This Month': [moment().startOf('month'), moment().endOf('month')],
                     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                 }
             }, cb)
                cb(start, end);
             });

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
