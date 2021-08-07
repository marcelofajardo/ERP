@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    <style>
        .btn-secondary{
            color: #757575;
            border: 1px solid #ddd;
            background-color: #fff;
        }
    </style>
    <div class="row m-0 p-0">
        <div class="col-lg-12 margin-tb p-0">
            <h2 class="page-heading">Cash Flow
            <div class="pull-left">
              {{-- <form action="/order/" method="GET">
                  <div class="form-group">
                      <div class="row">
                          <div class="col-md-12">
                              <input name="term" type="text" class="form-control"
                                     value="{{ isset($term) ? $term : '' }}"
                                     placeholder="Search">
                          </div>
                          <div class="col-md-4">
                              <button hidden type="submit" class="btn btn-primary">Submit</button>
                          </div>
                      </div>
                  </div>
              </form> --}}
            </div>
            <div class="pull-right">
              <button type="button" class="btn btn-secondary mr-2" data-toggle="modal" data-target="#cashCreateModal">+</button>
            </div>
            </h2>

            <div class="form-group mb-3 ml-3">
                <input style="border: 1px solid #ddd;height:30px;border-radius: 4px; padding: 0 5px;" type="text" placeholder="Enter name" name="filter" id="filter_cash_flow" value="">
                <button class="btn"><img src="/images/filter.png" style="width:16px"></button>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

   <div >
{{--       <div id="exTab2" class=" mt-3">--}}
{{--           <ul class="nav nav-tabs">--}}
{{--               <li class="active">--}}
{{--                   <a href="#manual_tab" data-toggle="tab">Manual Entries</a>--}}
{{--               </li>--}}
{{--               <li>--}}
{{--                   <a href="#order_tab" data-toggle="tab">Orders</a>--}}
{{--               </li>--}}
{{--               <li>--}}
{{--                   <a href="#purchase_tab" data-toggle="tab">Purchases</a>--}}
{{--               </li>--}}
{{--               <li>--}}
{{--                   <a href="#voucher_tab" data-toggle="tab">Convenience Vouchers</a>--}}
{{--               </li>--}}
{{--               </ul>--}}
{{--       </div>--}}

           <div class="tab-pane active " id="manual_tab" style="margin: 0 10px">
               <div class="table-responsive ">
                   <table class="table table-bordered">
                       <thead>
                       <tr>
                           <th>Module</th>
                           <th>Type</th>
                           <th>Date</th>
                           <th>Description</th>
                           <th>Amount</th>
                           <th>Expected</th>
                           <th>Actual</th>
                           <th>Type</th>
                           <th>Actions</th>
                       </tr>
                       </thead>

                       <tbody>
                       @foreach ($cash_flows as $cash_flow)
                           <tr>
                               <td><a href="{{ route('order.show',[$cash_flow->cash_flow_able_id]) }}" title="View {{ class_basename($cash_flow->cashFlowAble) }} Detail" target="_blank">{{ optional($cash_flow->cashFlowAble)->order_id }}</a><br>{{ class_basename($cash_flow->cashFlowAble) }}</td>
                               <td></td>
                               <td class="small">{{ date('Y-d-m', strtotime($cash_flow->date)) }}</td>

                               <td>
                                   {{ $cash_flow->description }}
                                   @if ($cash_flow->files)
                                       <ul>
                                           @foreach ($cash_flow->files as $file)
                                               <li><a href="{{ route('cashflow.download', $file->id) }}" class="btn-link">{{ $file->filename }}</a></li>
                                           @endforeach
                                       </ul>
                                   @endif
                               </td>
                               <td>@if($cash_flow->amount > 0)$@endif{{ $cash_flow->amount }}</td>
                               <td>{{ $cash_flow->expected }}</td>
                               <td>{{ number_format($cash_flow->actual, 2) }}</td>
                               <td>{{ ucwords($cash_flow->type) }}</td>
                               <td>
                                   {!! Form::open(['method' => 'DELETE','route' => ['cashflow.destroy', $cash_flow->id],'style'=>'display:inline']) !!}
                                   <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                                   {!! Form::close() !!}
                               </td>
                           </tr>
                       @endforeach
                       </tbody>
                   </table>
               </div>

               {!! $cash_flows->appends(Request::except('page'))->links() !!}
           </div>

{{--           <div class="tab-pane mt-3" id="order_tab">--}}
{{--               <div class="table-responsive mt-3">--}}
{{--                   <table class="table table-bordered">--}}
{{--                       <thead>--}}
{{--                       <tr>--}}
{{--                           <th>ID</th>--}}
{{--                           <th>Date</th>--}}
{{--                           <th>Order Products</th>--}}
{{--                           <th>Order Balance</th>--}}
{{--                           <th>Purchase</th>--}}
{{--                           <th>Profit</th>--}}
{{--                           <th>Actions</th>--}}
{{--                       </tr>--}}
{{--                       </thead>--}}

{{--                       <tbody>--}}
{{--                       @foreach ($orders as $order)--}}
{{--                           <tr>--}}
{{--                               <td><a href="{{ route('order.show', $order->id) }}" target="_blank">{{ $order->id }}</a></td>--}}
{{--                               <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d-m') }}</td>--}}
{{--                               <td>--}}
{{--                                   @if ($order->order_product)--}}
{{--                                       <ul>--}}
{{--                                           @foreach ($order->order_product as $order_product)--}}
{{--                                               <li>{{ $order_product->sku }} - {{ $order_product->product_price }}</li>--}}
{{--                                           @endforeach--}}
{{--                                       </ul>--}}
{{--                                   @endif--}}
{{--                               </td>--}}
{{--                               <td>@if($order->balance_amount > 0)$@endif{{ $order->balance_amount }}</td>--}}
{{--                               <td>--}}
{{--                                   @if ($order->order_product)--}}
{{--                                       @php $purchase_price = 0; @endphp--}}

{{--                                       <ul>--}}
{{--                                           @foreach ($order->order_product as $order_product)--}}
{{--                                               @if ($order_product->product)--}}
{{--                                                   @if ($order_product->product->purchases)--}}
{{--                                                       @foreach ($order_product->product->purchases as $purchase)--}}
{{--                                                           <li>{{ $order_product->product->price }}</li>--}}

{{--                                                           @php $purchase_price += $order_product->product->price; @endphp--}}
{{--                                                       @endforeach--}}
{{--                                                   @endif--}}
{{--                                               @endif--}}
{{--                                           @endforeach--}}
{{--                                       </ul>--}}
{{--                                   @endif--}}
{{--                               </td>--}}
{{--                               <td>--}}
{{--                                   @if( $order->balance_amount - $purchase_price  > 0)$@endif{{ $order->balance_amount - $purchase_price }}--}}
{{--                               </td>--}}
{{--                               <td>--}}
{{--                                   {!! Form::open(['method' => 'DELETE','route' => ['cashflow.destroy', $cash_flow->id],'style'=>'display:inline']) !!}--}}
{{--                                   <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>--}}
{{--                                   {!! Form::close() !!}--}}
{{--                               </td>--}}
{{--                           </tr>--}}
{{--                       @endforeach--}}
{{--                       </tbody>--}}
{{--                   </table>--}}
{{--               </div>--}}

{{--               {!! $orders->appends(Request::except('order-page'))->links() !!}--}}
{{--           </div>--}}

{{--           <div class="tab-pane mt-3" id="purchase_tab">--}}
{{--               <div class="table-responsive mt-3">--}}
{{--                   <table class="table table-bordered">--}}
{{--                       <thead>--}}
{{--                       <tr>--}}
{{--                           <th>ID</th>--}}
{{--                           <th>Date</th>--}}
{{--                           <th>Amount</th>--}}
{{--                           <th>Actions</th>--}}
{{--                       </tr>--}}
{{--                       </thead>--}}

{{--                       <tbody>--}}
{{--                       @foreach ($purchases as $purchase)--}}
{{--                           <tr>--}}
{{--                               <td><a href="{{ route('purchase.show', $purchase->id) }}" target="_blank">{{ $purchase->id }}</a></td>--}}
{{--                               <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m H:i') }}</td>--}}
{{--                               <td>--}}
{{--                                   <ul>--}}
{{--                                       @foreach ($purchase->products as $product)--}}
{{--                                           <li>{{ $product->price }}</li>--}}
{{--                                       @endforeach--}}
{{--                                   </ul>--}}
{{--                               </td>--}}
{{--                               <td>--}}
{{--                                   {!! Form::open(['method' => 'DELETE','route' => ['cashflow.destroy', $cash_flow->id],'style'=>'display:inline']) !!}--}}
{{--                                   <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>--}}
{{--                                   {!! Form::close() !!}--}}
{{--                               </td>--}}
{{--                           </tr>--}}
{{--                       @endforeach--}}
{{--                       </tbody>--}}
{{--                   </table>--}}
{{--               </div>--}}

{{--               {!! $purchases->appends(Request::except('purchase-page'))->links() !!}--}}
{{--           </div>--}}

{{--           <div class="tab-pane mt-3" id="voucher_tab">--}}
{{--               <div class="table-responsive mt-3">--}}
{{--                   <table class="table table-bordered">--}}
{{--                       <thead>--}}
{{--                       <tr>--}}
{{--                           <th>User</th>--}}
{{--                           <th>Date</th>--}}
{{--                           <th>Description</th>--}}
{{--                           <th>Amount</th>--}}
{{--                           <th>Paid</th>--}}
{{--                           <th>Credit</th>--}}
{{--                           <th>Actions</th>--}}
{{--                       </tr>--}}
{{--                       </thead>--}}

{{--                       <tbody>--}}
{{--                       @foreach ($vouchers as $voucher)--}}
{{--                           <tr>--}}
{{--                               <td>{{ $voucher->user->name }}</td>--}}
{{--                               <td>{{ \Carbon\Carbon::parse($voucher->date)->format('d-m') }}</td>--}}
{{--                               <td>{{ $voucher->description }}</td>--}}
{{--                               <td>{{ $voucher->amount }}</td>--}}
{{--                               <td>{{ $voucher->paid }}</td>--}}
{{--                               <td>{{ ($voucher->amount - $voucher->paid) * -1 }}</td>--}}
{{--                               <td>--}}
{{--                                   {!! Form::open(['method' => 'DELETE','route' => ['cashflow.destroy', $cash_flow->id],'style'=>'display:inline']) !!}--}}
{{--                                   <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>--}}
{{--                                   {!! Form::close() !!}--}}
{{--                               </td>--}}
{{--                           </tr>--}}
{{--                       @endforeach--}}
{{--                       </tbody>--}}
{{--                   </table>--}}
{{--               </div>--}}

{{--               {!! $vouchers->appends(Request::except('voucher-page'))->links() !!}--}}
{{--           </div>--}}
   </div>

    <div id="cashCreateModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <form action="{{ route('cashflow.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-header">
              <h4 class="modal-title">Store a Record</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              {{-- <div class="form-group">
                <select class="selectpicker form-control" data-live-search="true" data-size="15" name="user_id" title="Choose a User" required>
                    @foreach ($users as $user)
                      <option data-tokens="{{ $user->name }} {{ $user->email }}" value="{{ $user->id }}"  {{ $user->id == old('user_id') ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('user_id'))
                  <div class="alert alert-danger">{{$errors->first('user_id')}}</div>
                @endif
              </div> --}}

              <div class="form-group">
                <strong>Description:</strong>
                <textarea name="description" class="form-control" rows="8" cols="80">{{ old('description') }}</textarea>

                @if ($errors->has('description'))
                  <div class="alert alert-danger">{{$errors->first('description')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Date:</strong>
                <div class='input-group date' id='date-datetime'>
                  <input type='text' class="form-control" name="date" value="{{ date('Y-m-d H:i') }}" required />

                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>

                @if ($errors->has('date'))
                  <div class="alert alert-danger">{{$errors->first('date')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Amount:</strong>
                <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" required>

                @if ($errors->has('amount'))
                  <div class="alert alert-danger">{{$errors->first('amount')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Type:</strong>
                <select class="form-control" name="type" required id="cashflow_type">
                  <option value="received" {{ 'received' == old('type') ? 'selected' : '' }}>Received</option>
                  <option value="paid" {{ 'paid' == old('type') ? 'selected' : '' }}>Paid</option>
                </select>

                @if ($errors->has('type'))
                  <div class="alert alert-danger">{{$errors->first('type')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Category:</strong>
                <select class="form-control" name="cash_flow_category_id" id="cashflow_category">
                  <option value="">Select Category</option>
                  @foreach ($categories['received'] as $id => $category)
                    <option value="{{ $id }}" {{ $id == old('cash_flow_category_id') ? 'selected' : '' }}>{{ $category }}</option>
                  @endforeach
                </select>

                @if ($errors->has('cash_flow_category_id'))
                  <div class="alert alert-danger">{{$errors->first('cash_flow_category_id')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Files:</strong>
                <input type="file" name="file[]" class="form-control" value="" multiple>

                @if ($errors->has('file'))
                  <div class="alert alert-danger">{{$errors->first('file')}}</div>
                @endif
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

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#date-datetime').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
      });
    });

    $('#cashflow_type').on('change', function() {
      var type = $(this).val();
      var categories = {!! json_encode($categories) !!};

      $('#cashflow_category').empty();

      $('#cashflow_category').append($('<option>', {
        value: '',
        text: 'Select Category'
      }));

      Object.keys(categories[type]).forEach(function(category_id) {
        $('#cashflow_category').append($('<option>', {
          value: category_id,
          text: categories[type][category_id]
        }));
      });
    });
  </script>
@endsection
