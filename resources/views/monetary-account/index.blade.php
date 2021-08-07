@extends('layouts.app')

@section('title', 'Account Info')

@section('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Account History</h2>
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12 border">
                    <div class="pull-right">
                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                                data-target="#accountFormModal"
                                title="Add new Monetary Account Product"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Sr. no</th>
                                <th>Currency</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($accounts as $account)
                                <tr>
                                    <td>{{ $account->id }}</td>
                                    <td>
                                        {{ $currencies[$account->currency]??'N/A' }}
                                        <br>
                                        <small>Type: {{ucfirst($account->type)}}</small>
                                    </td>
                                    <td>{{$account->date}}</td>
                                    <td>{{$account->amount}}</td>
                                    <td class="small expand-row table-hover-cell">
                                                <span class="td-mini-container">
                                              {{ strlen($account->short_note) > 40 ? substr($account->short_note, 0, 40) : $account->short_note }}
                                            </span>
                                        <span class="td-full-container hidden">
                                                {{ $account->short_note }}
                                                </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <button class="btn btn-image"
                                                    data-toggle="modal"
                                                    data-target="#accountShowModal"
                                                    data-account="{{ json_encode($account) }}"
                                                    data-currency="{{ $currencies[$account->currency]??'N/A' }}">
                                                <img
                                                        src="/images/view.png"/></button>
                                            <button type="button" class="btn btn-image"
                                                    data-toggle="modal"
                                                    data-target="#accountFormModal"
                                                    data-account="{{ json_encode($account) }}"
                                                    data-currency="{{ $currencies[$account->currency]??'N/A' }}"><img
                                                        src="/images/edit.png"/></button>
                                            {!! Form::open(['method' => 'DELETE','route' => ['monetary-account.destroy', $account->id],'style'=>'display:inline']) !!}
                                            <button type="submit" class="btn btn-image"><img
                                                        src="/images/delete.png"/></button>
                                            {!! Form::close() !!}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="15" class="text-center text-danger">No Account History Found.</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')


    {!! $accounts->appends(Request::except('page'))->links() !!}

    <div id="accountFormModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('monetary-account.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Add Capital</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- currency -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('currency')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('currency', 'Currency', ['class' => 'form-control-label']) !!}
                                    {!! Form::select('currency', $currencies, null, ['class'=>'form-control  '.($errors->has('currency')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                    @if($errors->has('currency'))
                                        <div class="form-control-feedback">{{$errors->first('currency')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- short_note -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('short_note')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('short_note', 'Note', ['class' => 'form-control-label']) !!}
                                    {!! Form::textarea('short_note', null, ['class'=>'form-control '.($errors->has('short_note')?'form-control-danger':(count($errors->all())>0?' form-control-success':'')),'rows'=>3]) !!}
                                    @if($errors->has('short_note'))
                                        <div class="form-control-feedback">{{$errors->first('short_note')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- date -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('date', 'Date', ['class' => 'form-control-label']) !!}
                                    {!! Form::date('date', null, ['class'=>'form-control '.($errors->has('date')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                    @if($errors->has('date'))
                                        <div class="form-control-feedback">{{$errors->first('date')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- amount -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('amount')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('amount', 'Amount', ['class' => 'form-control-label']) !!}
                                    {!! Form::number('amount', null, ['class'=>'form-control '.($errors->has('amount')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                    @if($errors->has('amount'))
                                        <div class="form-control-feedback">{{$errors->first('amount')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- type -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('type')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('type', 'Type', ['class' => 'form-control-label']) !!}
                                    {!! Form::select('type', $account_types, null, ['class'=>'form-control '.($errors->has('type')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                    @if($errors->has('type'))
                                        <div class="form-control-feedback">{{$errors->first('type')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- description -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('description')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('description', 'Description', ['class' => 'form-control-label']) !!}
                                    {!! Form::textarea('description', null, ['class'=>'form-control  '.($errors->has('description')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'rows'=>5]) !!}
                                    @if($errors->has('description'))
                                        <div class="form-control-feedback">{{$errors->first('description')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Add</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div id="accountShowModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $('#accountShowModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var account = button.data('account')
            var currency = button.data('currency');
            var html = '<div class="row">' +
                '<div class="col-6">Currency : ' + currency + '</div>' +
                '<div class="col-6">Type: ' + account.type + '</div>' +
                '<div class="col-6">Date : ' + account.date + '</div>' +
                '<div class="col-6">Amount : ' + account.amount + '</div>' +
                '<div class="col-6">Note: ' + account.short_note + '</div>' +
                '<div class="col-12">Description: <p>' + account.description + '</p> </div>' +
                '</div>'
            modal.find('.modal-body').html(html);
        })

        $('#accountFormModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var account = button.data('account')
            if (account != undefined) {
                var url = "{{ url('monetary-account') }}/" + account.id;
                modal.find('form').attr('action', url);
                var method = '<input type="hidden" name="_method" value="PUT">'
                modal.find('form').append(method)
                modal.find('input[name="_method"]').val('PUT');
                modal.find('#date').val(account.date)
                modal.find('#amount').val(account.amount)
                modal.find('#short_note').val(account.short_note)
                modal.find('#type').val(account.type)
                modal.find('#description').val(account.description)
                modal.find('#currency option[value="' + account.currency + '"]').attr('selected', 'true')
                modal.find('#type option[value="' + account.type + '"]').attr('selected', 'true')
                modal.find('button[type="submit"]').html('Update')
                modal.find('.modal-title').html('Update Monetary Capital')
            } else {
                var url = "{{ route('monetary-account.store') }}";
                modal.find('form').attr('action', url);
                modal.find('form').trigger('reset');
                modal.find('button[type="submit"]').html('Add')
                modal.find('.modal-title').html('Store Monetary Capital')
                modal.find('input[name="_method"]').remove()
            }
        })
    </script>
@endsection