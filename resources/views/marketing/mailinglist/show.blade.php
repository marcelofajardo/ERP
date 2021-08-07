@extends('layouts.app')

@section('styles')

    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }

        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .show_select {
            display: none;
        }
    </style>
@endsection


@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <h2 class="page-heading">Maililng list {{$list->name}} ({{$customers->total()}})</h2>
                    <div class="pull-left">
                        <form action="{{route('mailingList.single', ['remoteId' => $list->remote_id, 'store_id' => $list->website_id])}}" method="GET">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input name="term" type="text" class="form-control global ui-autocomplete-input"
                                               value="" placeholder="Email" id="term" autocomplete="off">
                                    </div>
                                    <div class="col-md-5">
                                        <select class="form-control customer_type ui-autocomplete-input ui-autocomplete-loading"
                                                id="total" name="total" autocomplete="off">
                                            <option value="0">Select Customer Type</option>
                                            <option value="1">Enabled Customer</option>
                                            <option value="2">Pending Customer For Enable</option>
                                            <option value="3">DND Customer</option>
                                            <option value="4">Customer With Leads</option>
                                            <option value="5">Customer With Offers</option>
                                            <option value="6">Enabled Customer (Missing Number)</option>
                                            <option value="7">Message Send Failed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-image"><img src="/images/filter.png">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div class="pull-right">
                        <button type="button" class="btn btn-secondary" id="totalCustomer">Total Customers
                            : {{$customers->total()}}</button>
                        <button type="button" class="btn btn-secondary" id="selectDND">DND Customers
                            : {{ $countDNDCustomers }}</button>
                        <br>
                        <div style="margin-top: 7px;">
                            <button type="button" class="btn btn-secondary" id="select">Select</button>
                            <button type="button" class="btn btn-secondary" id="enable">Enable</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <div class="table-responsive mt-3">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        {{$customers->links()}}
        <table class="table table-bordered" id="passwords-table">
            <thead>
            <tr>
                <th class="show_select">Select All</th>
                <th style="">Customer ID</th>
                <th style="">Customer Name</th>
                <th style="">Email</th>
                <th style="">Source</th>
                <th>
                    <select class="form-control search" id="dnd">
                        <option>Select DND Users</option>
                    </select>
                </th>
                <th style="">Manual Approval</th>
                <th>Remarks</th>
            </thead>
            <tbody>
            <tr>
                <th class="show_select"><input type="checkbox" class="form-control" id="select_all"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>
                </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            @foreach($customers as $value)
                <tr>
                    <td class="show_select"><input type="checkbox" name="select" class="form-control checkbox_select"
                                                   value="{{$value->id}}" data-id="{{$id}}" data-email="{{$value['email']}}"></td>
                    <td>{{$value["id"]}}</td>
                    <td>{{$value["name"]}}</td>
                    <td>{{$value["email"]}}</td>
                    <td>
                        <select class="form-control update_source" data-href="{!! route('mailingList.customer.source',$value['id']) !!}">
                            <option>Select Source Users</option>
                            @foreach(App\Customer::ListSource() as $key => $val)
                                <option value="{!! $key !!}" {!! $value["source"] == $key ?  'selected' : '' !!}>{!! $val !!}</option>
                            @endforeach
                        </select></td>
                    <td>
                        <label class="switch" style="margin: 0px">
                            @if($value->do_not_disturb == 1)
                                <input type="hidden" value="0" id="checkbox_value_dnd">
                                <input type="checkbox" class="checkbox" checked value="{{ $value->id }}"
                                       onclick="disableDND({{ $value->id }})">
                            @else
                                <input type="hidden" value="1" id="checkbox_value_dnd">
                                <input type="checkbox" class="checkbox" value="{{ $value->id }}"
                                       onclick="enableDND({{ $value->id }})">
                            @endif
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <label class="switch" style="margin: 0px">
                            @if(in_array($value['id'], $contacts))
                                <input type="hidden" id="checkbox_value_dnd">
                                <input type="checkbox" class="checkbox" checked value="{{ $value->id }}"
                                       onclick="disable({{$id}},'{{$value['email']}}')">
                            @else
                                <input type="hidden" id="checkbox_value_dnd">
                                <input type="checkbox" class="checkbox" value="{{ $value->id }}"
                                       onclick="enable('{{$id}}','{{$value['email']}}')" id="marketing{{ $value->id }}">
                            @endif
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <button type="button" class="btn btn-image make-remarks d-inline" data-toggle="modal"
                                data-target="#makeRemarksModal" data-id="{{$value->id}}">
                            <img src="/images/remark.png" style="cursor: default; width: 16px;">
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{$customers->links()}}
    </div>
    <div id="makeRemarksModal" class="modal fade" role="dialog" style="display: none;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Remarks</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div id="remarks-list">
                        <p><br> <small> </small></p>
                    </div>

                    <form class="add-remarks"></form>
                    <input type="hidden" name="id" value="1" class="id">
                    <div class="form-group">
                        <textarea rows="2" name="remark" class="form-control remark"
                                  placeholder="Start the Remark"></textarea>
                    </div>
                    <button type="button" class="btn btn-secondary btn-block mt-2" id="addRemarksButton">Add</button>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection


@section('scripts')

    <script !src="">
        $("#select").click(function () {
            $(".show_select").toggle();
        });
        $("#enable").click(function () {
            val = $('input[name="select"]:checked');
            if (val.length == 0) {
                alert('Please Select Customer');
            } else {
                $('input[name="select"]:checked').each(function () {
                    id = this.value;
                    email = $(this).attr('data-email');
                    id_v = $(this).attr('data-id');
                    $.ajax({
                        url: "{{ route('mailinglist.add.manual') }}",
                        dataType: "json",
                        data: {
                            id: id_v,
                            email: email,
                        },
                        beforeSend: function () {
                            $("#loading-image").show();
                            $("#marketing" + id).prop('checked', true);
                        },
                    }).done(function (data) {
                        $("#loading-image").hide();
                    }).fail(function (jqXHR, ajaxOptions, thrownError) {
                        alert('No response from server');
                    });
                });
                // alert('Customer Updated');
            }
        });

        $(".update_source").change(function () {
                var _this = $(this);
                $.ajax({
                    url: jQuery(_this).data('href'),
                    dataType: "json",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        source: jQuery(_this).val(),
                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                }).done(function (data) {
                    $("#loading-image").hide();
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
        });
        //select all checkboxes
        $("#select_all").change(function () {  //"select all" change
            $(".checkbox_select").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
        });

        //".checkbox" change
        $('.checkbox_select').change(function () {
            //uncheck "select all", if one of the listed checkbox item is unchecked
            if (false == $(this).prop("checked")) { //if this item is unchecked
                $("#select_all").prop('checked', false); //change "select all" checked status to false
            }
            //check "select all" if all checkbox items are checked
            if ($('.checkbox_select:checked').length == $('.checkbox_select').length) {
                $("#select_all").prop('checked', true);
            }
        });

            function disable(id, email) {
                $.ajax({
                    type: 'GET',
                    url: '/marketing/mailinglist/delete/' + id + '/' + email,
                    success: function (data) {
                        if (data.status == 'error') {
                            alert('Something went wrong');
                        } else {
                            alert('Customer removed');
                        }
                    },
                    error: function (data) {
                        alert('Something went wrong');
                    }
                });
            }

            function enable(id, email) {
                $.ajax({
                    type: 'GET',
                    url: '/marketing/mailinglist/add/' + id + '/' + email,
                    success: function (data) {
                        if (data.status == 'error') {
                            alert('Something went wrong');
                        } else {
                            alert('Customer Added');
                        }
                    },
                    error: function (data) {
                        alert('Something went wrong');
                    }
                });
            }

            function enableDND(id) {
                method = $('#checkbox_value_dnd').val();
                if (method == 1) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('broadcast.add.dnd') }}',
                        data: {
                            id: id,
                            type: 1,
                        }, success: function (data) {
                            console.log(data);
                            if (data.status == 'error') {
                                // alert('Something went wrong');
                            } else {
                                $('#checkbox_value_dnd').val('0');
                                alert('Customer Added to DND');

                            }

                        },
                        error: function (data) {
                            alert('Something went wrong');
                        }
                    });

                } else {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('broadcast.add.dnd') }}',
                        data: {
                            id: id,
                            type: 0,
                        }, success: function (data) {
                            console.log(data);
                            if (data.status == 'error') {
                                //    alert('Something went wrong');
                            } else {
                                $('#checkbox_value_dnd').val('1');
                                alert('Customer Removed From DND');

                            }
                        },
                        error: function (data) {
                            alert('Something went wrong');
                        }
                    });

                }
            }

            $('#addRemarksButton').on('click', function () {
                var id = $('.id').val();
                var remark = $('.remark').val();
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('mailingList.addRemark') }}',
                    data: {
                        id: id,
                        remark: remark,
                    },
                }).done(response => {
                    $('.add-remarks').find('textarea[name="remark"]').val('');

                    var html = ' <p> ' + remark + ' <br> <small>By You updated on ' + moment().format('DD-M H:mm') + ' </small></p>';

                    $("#makeRemarksModal").find('#remarks-list').append(html);
                }).fail(function (response) {
                    console.log(response);

                    alert('Could not fetch remarks');
                });
            });
            $(document).on('click', '.make-remarks', function (e) {
                e.preventDefault();

                var id = $(this).data('id');
                $('.id').val(id);
                $.ajax({
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('mailingList.gets.remark') }}',
                    data: {
                        id: id,
                    },
                }).done(response => {
                    var html = '';

                    $.each(response, function (index, value) {
                        html += ' <p> ' + value.text + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></p>';
                        html + "<hr>";
                    });
                    $("#makeRemarksModal").find('#remarks-list').html(html);
                });
        });
    </script>
@endsection