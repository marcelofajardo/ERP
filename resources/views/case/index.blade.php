@extends('layouts.app')

@section('title', 'case Info')

@section('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Case Info</h2>
            <div class="pull-left">
                <form class="form-inline" action="{{ route('case.index') }}" method="GET">
                    <div class="form-group">
                        <input name="term" type="text" class="form-control"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="Search">
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="with_archived"
                               id="with_archived" {{ Request::get('with_archived')=='on'? 'checked' : '' }}>
                        <label for="with_archived">Archived</label>
                    </div>

                    <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Filter</button>
                </form>
            </div>
            <div class="pull-right">
                <a href="{{route('lawyer.index')}}?create=true" type="button" class="btn btn-secondary">Add Lawyer</a>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#caseFormModal"
                        title="Add new Case"><i class="fa fa-plus"></i> Create New
                </button>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="5%">
                    <a href="{{route('case.index')}}{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=lawyer{{ ($orderby == 'ASC') ? '&orderby=DESC' : '' }}">Lawyer</a>
                </th>
                <th width="5%">Case no</th>
                <th width="5%">Detail</th>
                <th width="5%">Phone</th>
                <th width="30%">Send</th>
                <th width="20%">Communication</th>
                <th width="5%">Status</th>
                <th>Resource/Laws</th>
                <th>Last Date</th>
                <th>Next Date</th>
                <th>Cost/Hearing</th>
                <th width="5%">Action</th>
            </tr>
            </thead>

            <tbody>
            @forelse ($cases as $case)
                <tr>
                    <td>{{ $case->id }}</td>
                    <td class="small">
                        <a href="{{route('lawyer.show',$case->lawyer_id) }}" title="View Lawyer Details"
                           target="_blank">
                            <span class="td-mini-container">
                          {{ strlen(optional($case->lawyer)->name) > 10 ? substr(optional($case->lawyer)->name, 0, 10) : optional($case->lawyer)->name }}
                        </span>
                            <span class="td-full-container hidden">
                        {{ optional($case->lawyer)->name }}
                        </span>
                        </a>
                    </td>
                    <td class="small">{{$case->case_number}}</td>
                    <td class="expand-row table-hover-cell" style="word-break: break-all;">
                        <span class="td-mini-container">
                          {{ strlen($case->court_detail) > 10 ? substr($case->court_detail, 0, 10) : $case->court_detail }}
                        </span>
                        <span class="td-full-container hidden">
                        {{ $case->court_detail }}
                        </span>
                    </td>
                    <td class="small">{{ $case->phone }}</td>
                    <td>
                        <div class="d-flex">
                            <input type="text" class="form-control quick-message-field" name="message"
                                   placeholder="Message" value="">
                            <button class="btn btn-sm btn-image send-message" data-caseid="{{ $case->id }}"
                                    data-lawyer-id="{{ $case->lawyer_id }}"><img
                                        src="/images/filled-sent.png"/></button>
                        </div>
                    </td>
                    <td class="expand-row table-hover-cell {{ $case->chat_message->count() && $case->chat_message->first()->status == 0 ? 'text-danger' : '' }}"
                        style="word-break: break-all;">
                        @if($case->chat_message->first())
                            <span class="td-mini-container">
                          {{ strlen($case->chat_message->first()->message) > 20 ? substr($case->chat_message->first()->message, 0, 20) . '...' : $case->chat_message->first()->message }}
                        </span>
                            <span class="td-full-container hidden">
                            {{ $case->chat_message->first()->message }}
                        </span>
                        @endif
                    </td>
                    <td class="small">{{ $statuses[$case->status] }}</td>
                    <td class="small">
                        <span class="td-mini-container">
                          {{ strlen($case->resource) > 10 ? substr($case->resource, 0, 10) : $case->resource }}
                        </span>
                        <span class="td-full-container hidden">
                        {{ $case->resource }}
                        </span>
                    </td>
                    <td>{{ $case->last_date }}</td>
                    <td>{{ $case->next_date }}</td>
                    <td>
                        <a href="#" data-toggle="modal" data-target="#costsModal"
                           data-case-id="{{ $case->id }}">View</a>
                    </td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('case.show', $case->id) }}" class="btn btn-image" href=""><img
                                        src="/images/view.png"/></a>
                            <button type="button" class="btn btn-image edit-case" data-toggle="modal"
                                    data-target="#caseFormModal" data-case="{{ json_encode($case) }}"><img
                                        src="/images/edit.png"/></button>
                            <a href="{{route('case.receivable', $case->id)}}" class="btn btn-sm" title="Case Receivables" target="_blank"><i class="fa fa-money"></i> </a>
                            {!! Form::open(['method' => 'DELETE','route' => ['case.destroy', $case->id],'style'=>'display:inline']) !!}
                            <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <th colspan="13" class="text-center text-danger">No Case/s Found.</th>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {!! $cases->appends(Request::except('page'))->links() !!}
    @include('case.partials.case-form-modals')
    @include('case.partials.cost-modals')
@endsection

@section('scripts')
    <script>
        $('#caseFormModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var legal_case = button.data('case')
            if (legal_case != undefined) {
                var url = "{{ url('case') }}/" + legal_case.id;
                modal.find('form').attr('action', url);
                var method = '<input type="hidden" name="_method" value="PUT">'
                modal.find('form').append(method)
                modal.find('input[name="_method"]').val('PUT');
                modal.find('#case_number').val(legal_case.case_number)
                modal.find('#for_against').val(legal_case.for_against)
                modal.find('#court_detail').html(legal_case.court_detail)
                modal.find('#phone').val(legal_case.phone)
                modal.find('#resource').val(legal_case.resource)
                modal.find('#last_date').val(legal_case.last_date)
                modal.find('#next_date').val(legal_case.next_date)
                modal.find('#lawyer_id option[value="' + legal_case.lawyer_id + '"]').attr('selected', 'true')
                modal.find('#status option[value="' + legal_case.status + '"]').attr('selected', 'true')
                modal.find('button[type="submit"]').html('Update')
                modal.find('.modal-title').html('Update a Case')
            } else {
                var url = "{{ route('case.store') }}";
                modal.find('form').attr('action', url);
                modal.find('form').trigger('reset');
                modal.find('button[type="submit"]').html('Add')
                modal.find('.modal-title').html('Store a Case')
                modal.find('input[name="_method"]').remove()
            }
        })

        $('#costsModal').on('hidden.bs.modal', function (event) {
            $(".case_costs").html(' ')
        });
        $('#costsModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var case_id = button.data('case-id')
            modal.find('.add-cost-button').attr('data-case-id', case_id)
            $('#add_payment').css('display', 'none')
            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('case-costs') }}' + '/' + case_id,
            }).done(response => {
                var html = '';
                $.each(response, function (index, value) {
                    var paid_date = '';
                    var amount_paid = '';
                    if (value.paid_date != null && value.paid_date != undefined) {
                        paid_date = value.paid_date
                    }
                    if (value.amount_paid != null && value.amount_paid != undefined) {
                        amount_paid = value.amount_paid
                    }
                    html += " <tr> " +
                        "<td>" + value.billed_date + "</td>" +
                        "<td>" + value.amount + "</td>" +
                        "<td><input type='date' name='paid_date' class='form-control' id='cost-paid-date' value='" + paid_date + "'/></td>" +
                        "<td><input type='number' name='amount_paid' class='form-control' id='cost-paid-amount' value='" + parseFloat(amount_paid) + "'/></td>" +
                        "<td><a href='#' data-cost-id='" + value.id + "' class='update-cost'><i class='fa fa-upload'></i></a></td>" +
                        "</tr>";
                });
                $(".case_costs").html(html);
            });


        })
        $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });

        $('.add-cost-button').on('click', function () {
            var case_id = $(this).attr('data-case-id')
            var billed_date = $('#billed_date').val();
            var amount = $('#amount').val();
            var paid_date = $('#paid_date').val();
            var amount_paid = $('#amount_paid').val();

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('case.cost.post') }}',
                data: {
                    case_id: case_id,
                    billed_date: billed_date,
                    amount: amount,
                    paid_date: paid_date,
                    amount_paid: amount_paid,
                },
            }).done(response => {
                $('#billed_date').val('');
                $('#amount').val('');
                $('#paid_date').val('');
                $('#amount_paid').val('');

                var paid_date = '';
                var amount_paid = '';
                if (response.paid_date != null && response.paid_date != undefined) {
                    paid_date = response.paid_date
                }
                if (response.amount_paid != null && response.amount_paid != undefined) {
                    amount_paid = response.amount_paid
                }
                var html = ' <tr> ' +
                    '<td>' + response.billed_date + '</td>' +
                    '<td>' + response.amount + '</td>' +
                    "<td><input type='date' name='paid_date' class='form-control' id='cost-paid-date' value='" + paid_date + "'/></td>" +
                    "<td><input type='number' name='amount_paid' class='form-control' id='cost-paid-amount' value='" + parseFloat(amount_paid) + "'/></td>" +
                    "<td><a href='#' data-cost-id='" + response.id + "' class='update-cost'><i class='fa fa-upload'></i></a></td>" +
                    '</tr>';

                $(".case_costs").append(html);
            }).fail(function (response) {
                console.log(response);
                alert('Could not store payment detail');
            });
        });

        $(document).on('click', '.update-cost', function () {
            var cost_id = $(this).attr('data-cost-id');
            var paid_date = $(this).closest('tr').find('#cost-paid-date').val();
            var amount_paid = $(this).closest('tr').find('#cost-paid-amount').val();

            $.ajax({
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('case-costs/update') }}/' + cost_id,
                data: {
                    paid_date: paid_date,
                    amount_paid: amount_paid,
                },
            }).done(response => {
                alert('Payment Detail Updated')
            }).fail(function (response) {
                console.log(response);

                alert('Could not store payment detail');
            });
        });

        $(document).on('click', '.send-message', function () {
            var thiss = $(this);
            var data = new FormData();
            var case_id = $(this).data('caseid');
            var lawyer_id = $(this).data('lawyer-id');
            var message = $(this).siblings('input').val();

            data.append("case_id", case_id);
            data.append("lawyer_id", lawyer_id);
            data.append("message", message);
            data.append("status", 1);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: '/whatsapp/sendMessage/case',
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
                        $(thiss).siblings('input').val('');

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
        $('#show-form').on('click', function () {
            $('#add_payment').css('display', 'block')
        })
    </script>
@endsection
