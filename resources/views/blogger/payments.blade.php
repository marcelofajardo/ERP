 @extends('layouts.app')

@section('title', 'Blogger Info')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('large_content')

    <div class="row">
      <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Payment History - <a href="{{route('blogger.show',$blogger->id)}}" title="Blogger Details">{{ $blogger->name }}</a></h2>
        <div class="pull-left">
          <form class="form-inline" action="{{ route('blogger.index') }}" method="GET">
            <div class="form-group">
              <input name="term" type="text" class="form-control"
                     value="{{ isset($term) ? $term : '' }}"
                     placeholder="Search">
            </div>

            {{-- <div class="form-group ml-3">
              <select class="form-control" name="type">
                <option value="">Select Type</option>
                ndr<option value="has_error" {{ isset($type) && $type == 'has_error' ? 'selected' : '' }}>Has Error</option>
              </select>
            </div> --}}

              <div class="form-group">
                  <input type="checkbox" name="with_archived" id="with_archived" {{ Request::get('with_archived')=='on'? 'checked' : '' }}>
                  <label for="with_archived">Archived</label>
              </div>

            <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
          </form>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#paymentFormModal">+</button>
        </div>
      </div>
    </div>

    @include('partials.flash_messages')
    
    <div class="table-responsive mt-3">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="5%">ID</th>
            <th width="5%">Currency</th>
            <th width="10%">Payment Date</th>
            <th width="10%">Amount</th>
            <th width="10%">Status</th>
            <th width="10%">Paid Date</th>
            <th width="10%">Paid Amount</th>
            <th width="10%">Detail</th>
            <th width="10%">Action</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($payments as $payment)
            <tr>
              <td>{{ $payment->id }}</td>
                <td>{{$currencies[$payment->currency]??'N/A'}}</td>
                <td>{{$payment->payment_date}}</td>
                <td>{{$payment->payable_amount}}</td>
                <td>{{$payment->status ? 'Paid' : 'Pending'}}</td>
                <td>{{$payment->paid_date}}</td>
                <td>{{$payment->paid_amount}}</td>
              <td class="expand-row table-hover-cell" style="word-break: break-all;">
                <span class="td-mini-container">
                  {{ strlen($payment->description) > 10 ? substr($payment->description, 0, 10) : $payment->description }}
                </span>

                <span class="td-full-container hidden">
                  {{ $payment->description }}
                </span>
              </td>
              <td>
                <div class="d-flex">
                  <button type="button" class="btn btn-image edit-blogger" data-toggle="modal" data-target="#paymentShowModal" data-payment="{{ json_encode($payment) }}" title="View Payment Detail" data-currency="{{ $currencies[$payment->currency]??'N/A' }}"><img src="/images/view.png" /></button>
                    <button type="button" class="btn btn-image edit-blogger" data-toggle="modal" data-target="#paymentFormModal" data-payment="{{ json_encode($payment) }}" title="Edit Payment Detail"><img src="/images/edit.png" /></button>
                  {!! Form::open(['method' => 'DELETE','route' => ['blogger.payments.destroy', $blogger->id,$payment->id],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn btn-image" title="Delete Payment detail"><img src="/images/delete.png" /></button>
                  {!! Form::close() !!}
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {!! $payments->appends(Request::except('page'))->links() !!}

    <div id="paymentFormModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('blogger.payments.store', $blogger->id) }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Add Payment</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- currency -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('currency')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                     {!! Form::label('currency', 'Currency', ['class' => 'form-control-label']) !!}
                                    {!! Form::select('currency', $currencies, null, ['class'=>'form-control  '.($errors->has('currency')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'placeholder'=>'Choose Currency','required']) !!}
                                        @if($errors->has('currency'))
                                <div class="form-control-feedback">{{$errors->first('currency')}}</div>
                                            @endif
                                </div>
                            </div>
                            <!-- payment_date -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('payment_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('payment_date', 'Payment date', ['class' => 'form-control-label']) !!}
                                    {!! Form::date('payment_date', null, ['class'=>'form-control '.($errors->has('payment_date')?'form-control-danger':(count($errors->all())>0?' form-control-success':'')),'required']) !!}
                                    @if($errors->has('payment_date'))
                                        <div class="form-control-feedback">{{$errors->first('payment_date')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- payable_amount -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('payable_amount')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('payable_amount', 'Payable Amount', ['class' => 'form-control-label']) !!}
                                    {!! Form::number('payable_amount', null, ['class'=>'form-control '.($errors->has('payable_amount')?'form-control-danger':(count($errors->all())>0?' form-control-success':'')),'required']) !!}
                                    @if($errors->has('payable_amount'))
                                        <div class="form-control-feedback">{{$errors->first('payable_amount')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- paid_date -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('paid_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('paid_date', 'Paid Date', ['class' => 'form-control-label']) !!}
                                    {!! Form::date('paid_date', null, ['class'=>'form-control '.($errors->has('paid_date')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                    @if($errors->has('paid_date'))
                                        <div class="form-control-feedback">{{$errors->first('paid_date')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- paid_amount -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('paid_amount')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('paid_amount', 'Paid Amount', ['class' => 'form-control-label']) !!}
                                    {!! Form::number('paid_amount', null, ['class'=>'form-control '.($errors->has('paid_amount')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                    @if($errors->has('paid_amount'))
                                        <div class="form-control-feedback">{{$errors->first('paid_amount')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- description -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('description')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('description', 'Description', ['class' => 'form-control-label']) !!}
                                    {!! Form::textarea('description', null, ['class'=>'form-control '.($errors->has('description')?'form-control-danger':(count($errors->all())>0?' form-control-success':'')),'rows'=>4]) !!}
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

    <div id="paymentShowModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Payment Detail</h4>
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
      $('#paymentShowModal').on('show.bs.modal', function (event) {
          var modal = $(this)
          var button = $(event.relatedTarget)
          var payment = button.data('payment')
          var status = payment.status ? 'Paid' : 'Pending';
          var currency = button.data('currency');
          var html = '<div class="row">' +
              '<div class="col-12">Currency : '+currency+'</div>' +
              '<div class="col-6">Payment Date : '+payment.payment_date+'</div>' +
              '<div class="col-6">Amount : '+payment.payable_amount+'</div>' +
              '<div class="col-6">Status: '+status+'</div>' +
              '<div class="col-6">Paid Date: '+payment.paid_date+'</div>' +
              '<div class="col-6">Paid Amount: '+payment.paid_amount+'</div>' +
              '<div class="col-6">Description: <p>'+payment.description+'</p> </div>' +
              '</div>'
          modal.find('.modal-body').html(html);
      })

      $('#paymentFormModal').on('show.bs.modal', function (event) {
          var modal = $(this)
          var button = $(event.relatedTarget)
          var payment = button.data('payment')
          if (payment != undefined) {
              var url = "{{ url('blogger') }}/" + payment.blogger_id+'/payments/'+payment.id;
              modal.find('form').attr('action', url);
              var method = '<input type="hidden" name="_method" value="PUT">'
              modal.find('form').append(method)
              modal.find('input[name="_method"]').val('PUT');
              modal.find('#payment_date').val(payment.payment_date)
              modal.find('#payable_amount').val(payment.payable_amount)
              modal.find('#paid_date').val(payment.paid_date)
              modal.find('#paid_amount').val(payment.paid_amount)
              modal.find('#description').val(payment.description)
              modal.find('#currency option[value="' + payment.currency + '"]').attr('selected', 'true')
              modal.find('button[type="submit"]').html('Update')
              modal.find('.modal-title').html('Update Blogger Payment')
          } else {
              var url = "{{ route('blogger.payments.store', $blogger->id) }}";
              modal.find('form').attr('action', url);
              modal.find('form').trigger('reset');
              modal.find('button[type="submit"]').html('Add')
              modal.find('.modal-title').html('Store Blogger Payment')
              modal.find('input[name="_method"]').remove()
          }
      })
  </script>
@endsection
