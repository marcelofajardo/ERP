@extends('layouts.app')
@section('favicon' , 'vendor-payments.png')
@section('title', 'Vendor payments')

@section("styles")
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
  <style type="text/css">
    .preview-category input.form-control {
      width: auto;
    }

    #loading-image {
              position: fixed;
              top: 50%;
              left: 50%;
              margin: -50px 0px 0px -50px;
          }

      .dis-none {
              display: none;
          }
      .pd-5 {
        padding: 3px;
      }
      .toggle.btn {
        min-height:25px;
      }
      .toggle-group .btn {
        padding: 2px 12px;
      }
      .latest-remarks-list-view tr td {
        padding:3px !important;
      }
  </style>

@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb mb-3">
            <h2 class="page-heading">Vendor payments</h2>

            <div class="pull-right">
              @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM'))
                <a class="btn btn-secondary manual-payment-btn" href="#">Manual payment</a>
                <a class="btn btn-secondary paid-selected-payment-btn" href="javascript:;">Paid Selected</a>
              @endif
              <a class="btn btn-secondary manual-request-btn" href="javascript:void(0);">Manual request</a>
              <!-- <a class="btn btn-secondary" href="{{ route('voucher.create') }}">+</a> -->
            </div>
        </div>
    </div>
    @include('partials.flash_messages')
    <div class="row mb-3">
      <div class="col-sm-12">
        <form action="{{ route('voucher.index') }}" method="GET" class="form-inline align-items-start" id="searchForm">
          <div class="row full-width" style="width: 100%;">
            @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM'))
              <div class="col-md-4 col-sm-12">
              <select class="form-control select-multiple" name="user_id" id="user-select">
                  <option value="">Select User</option>
                  @foreach($users as $key => $user)
                    <option value="{{ $user->id }}" {{($selectedUser == $user->id) ? 'selected' : ''}}>{{ $user->name }}</option>
                  @endforeach
                </select>
              </div>
            @endif

            <div class="col-sm-12 col-md-4">
              <div class="form-group mr-3">
                <input type="text" name="range_start" value="{{ request('range_start') }}" hidden/>
                <input type="text" name="range_end" value="{{ request('range_end') }}" hidden/>
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="fa fa-calendar"></i>&nbsp;
                  <span></span> <i class="fa fa-caret-down"></i>
                </div>
              </div>
            </div>

            <div class="col-md-2 col-sm-3">
              <select class="form-control " name="limit" id="limit">
                  <option value="">--Select Page--</option>
                  <option {{ request("limit") == "24"  ? 'selected' : ''}} value="24">24</option>
                  <option {{ request("limit") == "50"  ? 'selected' : ''}} value="50">50</option>
                  <option {{ request("limit") == "100"  ? 'selected' : ''}} value="100">100</option>
                  <option {{ request("limit") == "all"  ? 'selected' : ''}} value="all">All</option>
                </select>
              </div>

              <div class="col-sm-12 col-md-4">
                <label>Due Date</label>
                <div class="form-group mr-3">
                  <input type="text" name="range_due_start" value="{{ request('range_due_start') }}" hidden/>
                  <input type="text" name="range_due_end" value="{{ request('range_due_end') }}" hidden/>
                  <div id="reportrange_duedate" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                  </div>
                </div>
              </div>

            <div class="col-md-2"><button type="submit" class="btn btn-image"><img src="/images/search.png" /></button></div>
          </div>
        </form>
      </div>
    </div>



    <div class="table-responsive">
        <table class="table table-bordered">
        <tr>
          <th width="2%">-</th>
          <th width="5%">User</th>
          <th width="5%">Date</th>
          <th width="20%">Details</th>
          <th width="5%">Category</th>
          <th width="5%">Time Spent</th>
          <th width="7%">Amount</th>
          <th width="7%">Currency</th>
          <th width="5%">Amount Paid</th>
          <th width="7%">Balance</th>
          <th width="11%">Due Date</th>
          <th width="15%">Communication</th>
          <th width="20%" colspan="2" class="text-center">Action</th>
        </tr>
          @php
            $totalRateEstimate = 0;
            $totalPaid = 0;
            $totalBalance = 0;
          @endphp
          @foreach ($tasks as $task)
            <tr>
              <td><input type="checkbox" class="paid-all-payment" name="paid_all[]" value="{{$task->id}}"></td>
              <td>
                @if(isset($task->user)) {{  $task->user->name }} @endif
              </td>
              <td>{{ \Carbon\Carbon::parse($task->date)->format('d-m') }}</td>
              <td>{{ str_limit($task->details, $limit = 100, $end = '...') }}</td>
              <td>@if($task->task_id) Task #{{$task->task_id}} @elseif($task->developer_task_id) Devtask #{{$task->developer_task_id}} @else Manual @endif </td>
              <td>{{ $task->estimate_minutes }}</td>
              <td>{{ $task->rate_estimated }}</td>
              <td>{{ $task->currency }}</td>
              <td>{{ $task->paid_amount }}</td>
              <td>{{ $task->balance }}</td>
              <td>{{ $task->billing_due_date }}</td>
              <td>
                <div class="row">
                    <div class="col-md-12 form-inline cls_remove_rightpadding">
                          <textarea rows="1" class="form-control quick-message-field cls_quick_message" id="messageid_{{ $task->id }}" name="message" placeholder="Message"></textarea>
                          <button class="btn btn-sm btn-image send-message1" data-payment-receipt-id="{{ $task->id }}"><img src="/images/filled-sent.png"/></button>
                          <button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" data-object="payment-receipts" data-id="{{$task->id}}" data-load-type="text" data-all="1" title="Load messages"><img src="{{asset('images/chat.png')}}" alt=""></button>
                    </div>
                </div>
                <div class="row cls_mesg_box">
                  <div class="col-md-12">
                      <div class="col-md-12 expand-row" style="padding: 3px;">
                      @if(isset($task->chat_messages[0]))
                          <span class="td-mini-container message-chat-txt" id="message-chat-txt-{{ $task->id }}">
                          {{ strlen($task->chat_messages[0]->message) > 30 ? substr($task->chat_messages[0]->message, 0, 30) . '...' : $task->chat_messages[0]->message }}
                          </span>
                          <span class="td-full-container hidden" id="message-chat-fulltxt-{{ $task->id }}">
                            {{ $task->chat_messages[0]->message }}
                          </span>
                      @endif
                      </div>
                  </div>
              </div>
              </td>
              @php
                $totalRateEstimate += is_numeric(str_replace(",","",$task->rate_estimated)) ? str_replace(",","",$task->rate_estimated) : 0;
                $totalPaid += is_numeric(str_replace(",","",$task->paid_amount)) ? str_replace(",","",$task->paid_amount) : 0;
                $totalBalance += is_numeric(str_replace(",","",$task->balance)) ? str_replace(",","",$task->balance) : 0;
              @endphp
              <td>
                @if (Auth::user()->hasRole('Admin'))
                  <a data-toggle="tooltip" title="Create Payment" class="btn btn-secondary create-payment" data-id="{{$task->id}}">+</a>
                @endif
                <button type="button" data-toggle="tooltip" title="Upload File" data-payment-receipt-id="{{$task->id}}" class="btn btn-file-upload pd-5">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                </button>
                <button type="button" data-payment-receipt-id="{{$task->id}}" data-toggle="tooltip" title="List of Files" class="btn btn-file-list pd-5">
                    <i class="fa fa-list" aria-hidden="true"></i>
                </button>
                <button type="button" data-payment-receipt-id="{{$task->id}}" data-toggle="tooltip" title="Payment" class="btn btn-payment-list pd-5">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                </button>
                <?php /* ?>
                <button type="button" data-site-id="@if($site){{ $site->id }}@endif" data-site-category-id="{{ $category->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-store-development-remark pd-5">
                    <i class="fa fa-comment" aria-hidden="true"></i>
                </button-->
                <?php */ ?>                
              </td>
            </tr>
          @endforeach
          <tr>
            <td colspan="6" width="10%" style="text-align: right;"><b>TOTAL Amount : {{$totalRateEstimate}}</b></td>
            <td colspan="2" width="10%" style="text-align: right;"><b>TOTAL Amount Paid : {{$totalPaid}}</b></td>
            <td colspan="2" width="20%"><b>TOTAL Balance : {{$totalBalance}}</b></td>
            <td></td>
            <td></td>
          </tr>
      </table>
      {{$tasks->links()}}
    </div>



    <div id="paymentModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" id="payment-content">
                
            </div>
        </div>
    </div>

    <div id="rejectVoucherModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="#" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <!-- reject_reason -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('reject_reason')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('reject_reason', 'Reason', ['class' => 'form-control-label']) !!}
                                {!! Form::textarea('reject_reason', null, ['class'=>'form-control  '.($errors->has('reject_reason')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'required','rows'=>3]) !!}
                                    @if($errors->has('reject_reason'))
                            <div class="form-control-feedback">{{$errors->first('reject_reason')}}</div>
                                        @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Reject Voucher</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <div id="manualPayments" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="" method="POST" >
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Manual payment receipt</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">


                     
                        <div class="col-md-12 col-lg-12 @if($errors->has('reject_reason')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('user_id', 'User', ['class' => 'form-control-label']) !!}
                                <select class="form-control select-multiple" name="user_id" id="user-select" required>
                                  <option value="">Select User</option>
                                  @foreach($users as $key => $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                  @endforeach
                                </select>
                                    @if($errors->has('user_id'))
                                      <div class="form-control-feedback">{{$errors->first('user_id')}}</div>
                                    @endif
                            </div>


                            <div class="form-group">
                                {!! Form::label('billing_start_date', 'Billing start date', ['class' => 'form-control-label']) !!}
                                {!! Form::date('billing_start_date', null, ['class'=>'form-control  '.($errors->has('billing_start_date')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'required']) !!}
                                    @if($errors->has('billing_start_date'))
                                      <div class="form-control-feedback">{{$errors->first('billing_start_date')}}</div>
                                    @endif
                            </div>

                            <div class="form-group">
                                {!! Form::label('billing_end_date', 'Billing end date', ['class' => 'form-control-label']) !!}
                                {!! Form::date('billing_end_date', null, ['class'=>'form-control  '.($errors->has('billing_end_date')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'required']) !!}
                                    @if($errors->has('billing_end_date'))
                                      <div class="form-control-feedback">{{$errors->first('billing_end_date')}}</div>
                                    @endif
                            </div>



                            <div class="form-group">
                                {!! Form::label('worked_minutes', 'Time spent (In minutes)', ['class' => 'form-control-label']) !!}
                                {!! Form::number('worked_minutes', null, ['class'=>'form-control  '.($errors->has('worked_minutes')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                    @if($errors->has('worked_minutes'))
                                      <div class="form-control-feedback">{{$errors->first('worked_minutes')}}</div>
                                    @endif
                            </div>

                            <div class="form-group">
                                {!! Form::label('rate_estimated', 'Amount', ['class' => 'form-control-label']) !!}
                                {!! Form::number('rate_estimated', null, ['class'=>'form-control  '.($errors->has('rate_estimated')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'required']) !!}
                                    @if($errors->has('rate_estimated'))
                                      <div class="form-control-feedback">{{$errors->first('rate_estimated')}}</div>
                                    @endif
                            </div>

                            <div class="form-group">
                                {!! Form::label('remarks', 'Remarks', ['class' => 'form-control-label']) !!}
                                {!! Form::textarea('remarks', null, ['class'=>'form-control  '.($errors->has('remarks')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'rows'=>3]) !!}
                                    @if($errors->has('remarks'))
                                      <div class="form-control-feedback">{{$errors->first('remarks')}}</div>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div id="pay-selected-payment" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
            </div>
        </div>
    </div>

    <div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                    <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
                    <input type="hidden" id="chat_obj_type" name="chat_obj_type">
                    <input type="hidden" id="chat_obj_id" name="chat_obj_id">
                    <button type="submit" class="btn btn-default downloadChatMessages">Download</button>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
    </div>

    <div id="file-upload-area-section" class="modal fade" role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
             <form action="{{ route("voucher.upload-documents") }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="hidden-payment-receipt-id" value="">
                <div class="modal-header">
                    <h4 class="modal-title">Upload File(s)</h4>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                @csrf
                <div class="form-group">
                    <label for="document">Documents</label>
                    <div class="needsclick dropzone" id="document-dropzone">

                    </div>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-save-documents">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
        </form>
          </div>
      </div>
  </div>

  <div id="file-upload-area-list" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-body">
            <table class="table table-bordered">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th width="45%">Link</th>
                <th width="25%">Send To</th>
                <th width="25%">Action</th>
              </tr>
            </thead>
            <tbody class="display-document-list">
            </tbody>
        </table>
      </div>
           <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
  <script type="text/javascript">

    $('.assign-to.select2').select2({
      width: "100%"
    });

    var uploadedDocumentMap = {}
    Dropzone.options.documentDropzone = {
      url: '{{ route("voucher.upload-documents") }}',
      maxFilesize: 20, // MB
      addRemoveLinks: true,
      headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      success: function (file, response) {
          $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
          uploadedDocumentMap[file.name] = response.name
      },
      removedfile: function (file) {
          file.previewElement.remove()
          var name = ''
          if (typeof file.file_name !== 'undefined') {
            name = file.file_name
          } else {
            name = uploadedDocumentMap[file.name]
          }
          $('form').find('input[name="document[]"][value="' + name + '"]').remove()
      },
      init: function () {

      }
  }
    // $(document).ready(function() {
    //    $(".select-multiple").multiselect({
    //     enableFiltering: true,
    //    });
    // });

  $(document).on("click",".btn-save-documents",function(e){
    e.preventDefault();
    var $this = $(this);
    var formData = new FormData($this.closest("form")[0]);
    $.ajax({
      url: '/voucher/save-documents',
      type: 'POST',
      headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        dataType:"json",
      data: $this.closest("form").serialize(),
      beforeSend: function() {
        $("#loading-image").show();
            }
    }).done(function (data) {
      $("#loading-image").hide();
      toastr["success"]("Document uploaded successfully");
      location.reload();
    }).fail(function (jqXHR, ajaxOptions, thrownError) {      
      toastr["error"](jqXHR.responseJSON.message);
      $("#loading-image").hide();
    });
  });

    $('.select-multiple').select2({width: '100%'});

    /*let r_s = '';
    let r_e = '{{ date('y-m-d') }}';

    let start = r_s ? moment(r_s,'YYYY-MM-DD') : moment().subtract(6, 'days');
    let end =   r_e ? moment(r_e,'YYYY-MM-DD') : moment();

    jQuery('input[name="range_start"]').val(start.format('YYYY-MM-DD'));
    jQuery('input[name="range_end"]').val(end.format('YYYY-MM-DD'));*/

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        maxYear: 1,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    var sStart = jQuery('input[name="range_start"]').val();
    var sEnd = jQuery('input[name="range_end"]').val();

    if(sStart != "") {
      cb(moment(sStart,'YYYY-MM-DD'), moment(sEnd,'YYYY-MM-DD'));
    }

    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {

        jQuery('input[name="range_start"]').val(picker.startDate.format('YYYY-MM-DD'));
        jQuery('input[name="range_end"]').val(picker.endDate.format('YYYY-MM-DD'));

    });


    function cbc(start, end) {
        $('#reportrange_duedate span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    
    $('#reportrange_duedate').daterangepicker({
        maxYear: 1,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cbc);

    var selectStart = jQuery('input[name="range_due_start"]').val();
    var selectEnd = jQuery('input[name="range_due_end"]').val();

    if(selectStart != "") {
      cbc(moment(selectStart,'YYYY-MM-DD'), moment(selectEnd,'YYYY-MM-DD'));
    }


    $('#reportrange_duedate').on('apply.daterangepicker', function(ev, picker) {
       alert(picker.startDate.format('YYYY-MM-DD'));

        jQuery('input[name="range_due_start"]').val(picker.startDate.format('YYYY-MM-DD'));
        jQuery('input[name="range_due_end"]').val(picker.endDate.format('YYYY-MM-DD'));

    });


    

    $(document).on('click', '.expand-row', function() {
      var selection = window.getSelection();
      if (selection.toString().length === 0) {
        $(this).find('.td-mini-container').toggleClass('hidden');
        $(this).find('.td-full-container').toggleClass('hidden');
      }
    });
    $('#rejectVoucherModal').on('show.bs.modal', function (event) {
        var modal = $(this)
        var button = $(event.relatedTarget)
        var voucher = button.data('voucher')
        var url = "{{ url('voucher') }}/" + voucher.id + '/reject';
        modal.find('form').attr('action', url);
    })
    

    $(document).on('click', '.create-payment', function(e) {
      e.preventDefault();
      var thiss = $(this);
      var type = 'GET';
        $.ajax({
          url: '/voucher/payment/'+thiss.data('id'),
          type: type,
          beforeSend: function() {
            $("#loading-image").show();
          }
        }).done( function(response) {
          $("#loading-image").hide();
          $('#paymentModal').modal('show');
          $('#payment-content').html(response);
          $(".currency-select2").select2({width: '100%',tags:true});
          $(".payment-method-select2").select2({width: '100%',tags:true});
        }).fail(function(errObj) {
          $("#loading-image").hide();
        });
    });


    $(document).on("click",".btn-file-upload",function() {
      var $this = $(this);
      $("#file-upload-area-section").modal("show");
       $("#hidden-payment-receipt-id").val($this.data("payment-receipt-id"));
      // $("#hidden-site-id").val($this.data("site-id"));
      // $("#hidden-site-category-id").val($this.data("site-category-id"));
    });

    $(document).on("click",".btn-file-list",function(e) {
        e.preventDefault();
        var $this = $(this);
        var id = $(this).data("payment-receipt-id");
        $.ajax({
          url: '/voucher/'+id+'/list-documents',
          type: 'GET',
          headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            dataType:"json",
          beforeSend: function() {
            $("#loading-image").show();
                }
        }).done(function (response) {
          $("#loading-image").hide();
          var html = "";
          $.each(response.data,function(k,v){
            html += "<tr>";
              html += "<td>"+v.id+"</td>";
              html += "<td>"+v.url+"</td>";
              html += "<td><div class='form-row'>"+v.user_list+"</div></td>";
              html += '<td><a class="btn-secondary" href="'+v.url+'" data-site-id="'+v.site_id+'" target="__blank"><i class="fa fa-download" aria-hidden="true"></i></a>&nbsp;<a class="btn-secondary link-delete-document" data-payment-receipt-id="'+v.payment_receipt_id+'" data-id='+v.id+' href="_blank"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
            html += "</tr>";
          });
          $(".display-document-list").html(html);
          $("#file-upload-area-list").modal("show");
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
          toastr["error"]("Oops,something went wrong");
          $("#loading-image").hide();
        });
      });

    $(document).on("click",".link-delete-document",function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        var $this = $(this);
        if(confirm("Are you sure you want to delete records ?")) {
          $.ajax({
            url: '/voucher/delete-document',
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
              },
              dataType:"json",
            data: { id : id},
            beforeSend: function() {
              $("#loading-image").show();
                  }
          }).done(function (data) {
            $("#loading-image").hide();
            toastr["success"]("Document deleted successfully");
            $this.closest("tr").remove();
          }).fail(function (jqXHR, ajaxOptions, thrownError) {
            toastr["error"]("Oops,something went wrong");
            $("#loading-image").hide();
          });
        }
      });

    $(document).on("click",".paid-selected-payment-btn",function(e) {
          e.preventDefault();
          var ids = [];
          $(".paid-all-payment").each(function(k, v) {
              if($(v).is(":checked")) {
                 ids.push($(v).val());
              }
          });
          $.ajax({
            url: '/voucher/paid-selected-payment',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            data: { ids : ids},
            beforeSend: function() {
              $("#loading-image").show();
            }
          }).done(function (data) {
            $("#loading-image").hide();
            $("#pay-selected-payment").find(".modal-content").html(data);
            $("#pay-selected-payment").modal("show");
            $("#pay-selected-payment").find(".currency-select2").select2({width: '100%',tags:true});
            $("#pay-selected-payment").find(".payment-method-select2").select2({width: '100%',tags:true});
          }).fail(function (jqXHR, ajaxOptions, thrownError) {
            toastr["error"]("Oops,something went wrong");
            $("#loading-image").hide();
          });
    });

    $(document).on("click",".btn-payment-list",function(e) {
          e.preventDefault();
          var $this = $(this);
          $.ajax({
            url: '/voucher/paid-selected-payment-list',
            type: 'GET',
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            data: { payment_receipt_id : $this.data("payment-receipt-id")},
            beforeSend: function() {
              $("#loading-image").show();
            }
          }).done(function (data) {
            $("#loading-image").hide();
            $("#pay-selected-payment").find(".modal-content").html(data);
            $("#pay-selected-payment").modal("show");
            $("#pay-selected-payment").find(".currency-select2").select2({width: '100%',tags:true});
            $("#pay-selected-payment").find(".payment-method-select2").select2({width: '100%',tags:true});
          }).fail(function (jqXHR, ajaxOptions, thrownError) {
            toastr["error"]("Oops,something went wrong");
            $("#loading-image").hide();
          });
    });


    

    $(document).on("submit","#vendor-payment-receipt-form",function(e) {
        e.preventDefault();
        var $this = $(this);
          $.ajax({
            url: $this.attr("action"),
            type: $this.attr("method"),
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            data: $this.serialize(),
            dataType:"json",
            beforeSend: function() {
              $("#loading-image").show();
            }
          }).done(function (data) {
            $("#loading-image").hide();
            toastr["success"](data.message);
          }).fail(function (jqXHR, ajaxOptions, thrownError) {
            toastr["error"]("Oops,something went wrong");
            $("#loading-image").hide();
          });
    });

    $(document).on('click', '.send-message1', function () {
        var thiss = $(this);
        var data = new FormData();
        var payment_receipt_id = $(this).data('payment-receipt-id');

        var message = $("#messageid_"+payment_receipt_id).val();
        data.append("payment_receipt_id", payment_receipt_id);
        data.append("message", message);
        data.append("status", 1);

        if (message.length > 0) {
            if (!$(thiss).is(':disabled')) {
                $.ajax({
                    url: BASE_URL+'/whatsapp/sendMessage/payment-receipts',
                    type: 'POST',
                    "dataType": 'json',           // what to expect back from the PHP script, if anything
                    "cache": false,
                    "contentType": false,
                    "processData": false,
                    "data": data,
                    beforeSend: function () {
                        $(thiss).attr('disabled', true);
                    }
                }).done(function (response) {
                    //thiss.closest('tr').find('.message-chat-txt').html(thiss.siblings('textarea').val());
                    if(message.length > 30)
                    {
                        var res_msg = message.substr(0, 27)+"..."; 
                        $("#message-chat-txt-"+payment_receipt_id).html(res_msg);
                        $("#message-chat-fulltxt-"+payment_receipt_id).html(message);    
                    }else{
                        $("#message-chat-txt-"+payment_receipt_id).html(message); 
                        $("#message-chat-fulltxt-"+payment_receipt_id).html(message);      
                    }

                    $("#messageid_"+payment_receipt_id).val('');
                    $(thiss).attr('disabled', false);
                }).fail(function (errObj) {
                    $(thiss).attr('disabled', false);
                    alert("Could not send message");
                    console.log(errObj);
                });
            }
        } else {
            alert('Please enter a message first');
        }
    });

    

    // $(document).on('click', '.submit-manual-receipt', function(e) {
    //   e.preventDefault();
    //   var form = $(this).closest("form");
    //   var thiss = $(this);
    //   var type = 'POST';
    //     $.ajax({
    //       url: '/voucher/payment-request',
    //       type: type,
    //       dataType: 'json',
    //       data: form.serialize(),
    //       beforeSend: function() {
    //         $(thiss).text('Loading');
    //       }
    //     }).done( function(response) {
    //       // $(thiss).closest('tr').removeClass('row-highlight');
    //       // $(thiss).prev('span').text('Approved');
    //       // $(thiss).remove();
    //     }).fail(function(errObj) {
    //       alert("Could not change status");
    //     });
    // });

  </script>
@endsection
